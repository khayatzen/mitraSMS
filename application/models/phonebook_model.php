<?php
/**
 * Kalkun
 * An open source web based SMS Management
 *
 * @package		Kalkun
 * @author		Kalkun Dev Team
 * @license		http://kalkun.sourceforge.net/license.php
 * @link		http://kalkun.sourceforge.net
 */

// ------------------------------------------------------------------------

/**
 * Phonebook_model Class
 *
 * Handle all phonebook database activity 
 *
 * @package		Kalkun
 * @subpackage	Phonebook
 * @category	Models
 */
class Phonebook_model extends Model {
    
	var $limit_village = 7;
	/**
	 * Constructor
	 *
	 * @access	public
	 */	
	function Phonebook_model()
	{
		parent::Model();
	}

	// --------------------------------------------------------------------
	
	/**
	 * Get Phonebook
	 *
	 * @access	public   		 
	 * @param	mixed $param
	 * @return	object
	 */	
	function get_phonebook($param)
	{
	   
	   if( isset($param['id_user']) && !empty($param['id_user']) ) $user_id = $param['id_user'];
           else $user_id = $this->session->userdata('id_user') ;              
          
       
		switch($param['option']) 
		{
			case 'all':
			$this->db->select('*');
			$this->db->select_as('pbk.id_pbk','id_pbk');
			$this->db->select_as('pbk_groups.Name', 'GroupName');	
			$this->db->from('pbk');
			$this->db->where('pbk.id_user', $user_id);
                        $this->db->join('user_group', 'user_group.id_pbk=pbk.id_pbk', 'left');
			$this->db->join('pbk_groups', 'pbk_groups.ID=user_group.id_pbk_groups', 'left');
			$this->db->order_by('pbk.Name');
			break;	
			
			case 'paginate':
			$this->db->select('*');
			$this->db->select_as('pbk.id_pbk', 'id_pbk');
			$this->db->from('pbk');
			$this->db->where('id_user',$user_id);
			$this->db->order_by('Name');
			$this->db->limit($param['limit'], $param['offset']);
			break;
			
			case 'by_idpbk':
			$this->db->select('*');
			$this->db->select_as('pbk_groups.ID','id_pbk_groups');
                        $this->db->select_as('pbk.Name', 'Name');
			$this->db->select_as('pbk_groups.Name', 'GroupName');	
			$this->db->from('pbk');
			$this->db->where('pbk.id_user', $user_id);
                        $this->db->join('user_group', 'user_group.id_pbk=pbk.id_pbk', 'left');
			$this->db->join('pbk_groups', 'pbk_groups.ID=user_group.id_pbk_groups', 'left');
			$this->db->where('pbk.id_pbk', $param['id_pbk']);
			break;
			
			case 'group':
			$this->db->select('*');
			$this->db->select_as('Name','GroupName');
			$this->db->from('pbk_groups');
			$this->db->where('id_user', $user_id);
			$this->db->order_by('Name');
			break;
		
			case 'group_paginate':
			$this->db->select('*');
			$this->db->select_as('Name', 'GroupName');
			$this->db->from('pbk_groups');
			$this->db->where('id_user', $user_id);
			$this->db->order_by('Name');
			$this->db->limit($param['limit'], $param['offset']);
			break;	
			
			case 'groupname':
			$this->db->select_as('Name', 'GroupName');
			$this->db->from('pbk_groups');
			$this->db->where('ID', $param['id']);
			$this->db->where('id_user', $user_id);
			break;
			
			case 'bynumber':
			$this->db->select('*');
			$this->db->select_as('id_pbk', 'id_pbk');
			$this->db->from('pbk');
			$this->db->where('Number', $param['number']);
			$this->db->where('id_user', $user_id);
			break;
			
			case 'bygroup':
                        $this->db->select('*');
			$this->db->from('pbk');
                        $this->db->select_as('pbk.Name', 'Name');
                        $this->db->join('user_group', 'user_group.id_pbk=pbk.id_pbk');
			$this->db->join('pbk_groups', 'pbk_groups.ID=user_group.id_pbk_groups');
                        $this->db->where('user_group.id_pbk_groups', $param['group_id']);
                        $this->db->where('pbk.id_user', $user_id);
                        $this->db->order_by("pbk.Name", "asc");
			break;
			
			case 'search':
			$this->db->select('*');
			$this->db->select_as('id_pbk', 'id_pbk');
			$this->db->from('pbk');
			$this->db->or_like(array('Name' => $this->input->post('search_name'), 'Number' =>$this->input->post('search_name')));  
			$this->db->having('id_user', $user_id);
			$this->db->order_by('Name');
			break;
                        
		}
		return $this->db->get();	
	}
        // --------------------------------------------------------------------
        /*
         * Get Village Data - Region
         */
        function get_villages_($params=''){
            if($params == 'all'){
                $this->db->select('*');
                $this->db->order_by('village_name');
                return $this->db->get('reg_village');
            }
            else if($params == 'list'){
                $this->db->select('village_id');
                $this->db->select('village_name');
                $this->db->order_by('village_name');
                $villages = $this->db->get('reg_village')->result();
                $village_list = array();
                foreach($villages AS $village){
                    $village_list[$village->village_id] = $village->village_name;
                }
                return $village_list;
            }
            
        }
        // --------------------------------------------------------------------
        /*
         * Get Province Data - Region
         */
        function get_provinces($param=array()){
            
            if(!isset($param['order']))$param['order'] = 'ASC';
            
            $this->db->select('*');
            
            if(isset($param['country_id']))$this->db->where('country_id',$param['country_id']);
            if(isset($param['province_id']))$this->db->where('province_id',$param['province_id']); 
            
            if(isset($param['order_by']))$this->db->order_by($param['order_by'],$param['order']);
            else $this->db->order_by('province_name',$param['order']);
            
            return $this->db->get('reg_province');
            
        }
        // --------------------------------------------------------------------
        /*
         * Get District Data - Region
         */
        function get_districts($param=array()){
            
            if(!isset($param['order']))$param['order'] = 'ASC';
            
            $this->db->select('*');
            
            if(isset($param['province_id']))$this->db->where('province_id',$param['province_id']);
            if(isset($param['district_id']))$this->db->where('district_id',$param['district_id']); 
            
            if(isset($param['order_by']))$this->db->order_by($param['order_by'],$param['order']);
            else $this->db->order_by('district_name',$param['order']);
            
            return $this->db->get('reg_district');
            
        }
        // --------------------------------------------------------------------
        /*
         * Get Sub District Data - Region
         */
        function get_subdistricts($param=array()){
            
            if(!isset($param['order']))$param['order'] = 'ASC';
            
            $this->db->select('*');
            
            if(isset($param['district_id']))$this->db->where('district_id',$param['district_id']);
            if(isset($param['subdistrict_id']))$this->db->where('subdistrict_id',$param['subdistrict_id']);          
            
            if(isset($param['order_by']))$this->db->order_by($param['order_by'],$param['order']);
            else $this->db->order_by('subdistrict_name',$param['order']);
            
            return $this->db->get('reg_subdistrict');
            
        }
        // --------------------------------------------------------------------
        /*
         * Get Village Data - Region
         */
        function get_villages($param=array()){
            
            if(!isset($param['order']))$param['order'] = 'ASC';
            if(isset($param['type'])){
                switch($param['type']){
                    case 'search':
                        $this->db->like($param['search_field'], $param['search_query']);
                }
            }
            
            if(isset($param['limit']))$this->db->limit($param['limit'],$param['offset']);
            
            if(isset($param['subdistrict_id']))$this->db->where('subdistrict_id',$param['subdistrict_id']);
            if(isset($param['village_id']))$this->db->where('village_id',$param['village_id']);          
            
            if(isset($param['order_by']))$this->db->order_by($param['order_by'],$param['order']);
            else $this->db->order_by('village_name',$param['order']);
            
            return $this->db->get('reg_village');
            
        }
	// --------------------------------------------------------------------
	
	/**
	 * Search Phonebook
	 *
	 * @access	public   		 
	 * @param	mixed $param
	 * @return	object
	 */		
	function search_phonebook($param)	
	{
		$this->db->from('pbk');
		$this->db->select_as('Number', 'id');
		$this->db->select_as('Name', 'name');
		$this->db->where('id_user', $param['uid']);
		$this->db->like('Name', $param['query']);
		$this->db->order_by('Name');                
		return $this->db->get();
	}

	// --------------------------------------------------------------------
	
	/**
	 * Add Contact
	 *
	 * @access	public   		 
	 * @param	mixed $param
	 * @return
	 */		
	function add_contact($param)
	{
	   
		$this->db->set('Name', $param['Name']);
		$this->db->set('Number', $param['Number']);
		$this->db->set('id_user', $param['id_user']);
		$this->db->set('alamat', $param['alamat']);
                $this->db->set('village_id', $param['village_id']);
                $this->db->set('latlong', $param['latlong']);
		// edit mode
		if(isset($param['id_pbk'])) 
		{
			$this->db->where('id_pbk', $param['id_pbk']);
			$this->db->update('pbk');
		}
		else $this->db->insert('pbk');
        
        // optimisation required.
        if(isset($param['id_pbk'])) 
		{
            $pbk_id = $param['id_pbk'];
        }
        else $pbk_id = $this->db->insert_id();
        
        //delete past groups
        $this->db->delete('user_group', array('id_pbk' => $pbk_id)); 
        
        // now insert the lastest
        //if(isset($param['GroupID']))
        if(isset($param['GroupID']) && !empty($param['GroupID']))
        {
            $this->db->set('id_pbk', $pbk_id);
            $this->db->set('id_pbk_groups', $param['GroupID']);
            $this->db->set('id_user', $param['id_user']);
            $this->db->insert('user_group');
        }
        //if(isset($param['Groups']))
        if(isset($param['Groups'])  && !empty($param['Groups'])){
            $groups = array_unique(explode(',',$param['Groups']));
            $CI =& get_instance();
            foreach($groups as $_grp)
            {   
                $group_id  = $CI->Phonebook_model->group_id($_grp,$param['id_user']);
                
                if($group_id != null)
                {
                    $this->db->set('id_pbk', $pbk_id);
                    $this->db->set('id_pbk_groups', $group_id);
                    $this->db->set('id_user', $param['id_user']);
                    $this->db->insert('user_group');
                }
            }
             
            
        }
        
	}
  
  function multi_attach_group()
  {
     
     $id_group = $this->input->post('id_group');
     $id_pbk = $this->input->post('id_pbk');
     
     if($id_group == 'null' ) die("Invalid Group ID");
     
     //parse group value
     if(preg_match('/-/',$id_group)) { $mode = 'delete'; $id_group = substr($id_group,1);  }
     else $mode = 'add';
     
     if($mode == 'delete')
     {
        $this->db->delete('user_group', array('id_pbk' => $id_pbk , 'id_pbk_groups' => $id_group)); 
     }
     else // Add Mode 
     {
        $this->db->from('user_group');
        $this->db->where('id_pbk', $id_pbk);
		    $this->db->where('id_pbk_groups', $id_group);
        
        if($this->db->get()->num_rows() < 1)
        {
           $this->db->set('id_pbk', $id_pbk);
           $this->db->set('id_pbk_groups', $id_group);
           $this->db->set('id_user',  $this->session->userdata('id_user'));
           $this->db->insert('user_group');
        }

     }
     
  }

	// --------------------------------------------------------------------
	
	/**
	 * Add Group
	 *
	 * @access	public   		 
	 * @param	mixed $param
	 * @return
	 */		
	function add_group()
	{
		$this->db->set('Name', trim($this->input->post('group_name')));
		$this->db->set('id_user', trim($this->input->post('pbkgroup_id_user')));
			
		// edit mode	
		if($this->input->post('pbkgroup_id'))
		{
			$this->db->where('ID', $this->input->post('pbkgroup_id'));
			$this->db->update('pbk_groups');
		}
		else $this->db->insert('pbk_groups');		
	}	
    
    // --------------------------------------------------------------------
    
     /**
	 * Get Groups ID for a Group Name
	 *
	 * @access	public   		 
	 * @param	text $group_name
     * @param	number $user_id
	 * @return
	 */	
    function group_id($group_name, $user_id)
    {
            $this->db->select('*');
            $this->db->from('pbk_groups');
            $this->db->where('Name', $group_name);
            $this->db->where('id_user', $user_id);
            return @$this->db->get()->row()->ID;
    }
    
    // --------------------------------------------------------------------
    
    /**
	 * Get Groups for  a contact id
	 *
	 * @access	public   		 
	 * @param	number $pbk_id
     * @param	number $user_id
	 * @return
	 */	
    function get_groups($pbk_id,$user_id)
    {
        $this->db->select_as('user_group.id_pbk_groups', 'GroupID');
        $this->db->select_as('pbk_groups.Name', 'GroupName');	
		$this->db->from('user_group');
        $this->db->join('pbk_groups', 'pbk_groups.ID=user_group.id_pbk_groups');           
        $this->db->where('user_group.id_user', $user_id);
		$this->db->where('user_group.id_pbk', $pbk_id);
        $q =  $this->db->get();
        $GroupID = $GroupName = '';
        foreach ($q->result() as $_gp) 
        {
            $GroupName .= $_gp->GroupName.',';
            $GroupID .= $_gp->GroupID .',';
        }
        $GroupName = substr($GroupName,0, strlen($GroupName)-1);
        $GroupID = substr($GroupID,0, strlen($GroupID)-1);
        return (object) array("GroupNames" => $GroupName, "GroupIDs" => $GroupID);
    }

	// --------------------------------------------------------------------
	
	/**
	 * Delete Contact
	 *
	 * @access	public   		 
	 * @param	number $id_contact
	 * @return
	 */		
	function delete_contact()
	{
            	$this->db->delete('pbk', array('id_pbk' => $this->input->post('id'))); 
                $this->db->delete('user_group', array('id_pbk' => $this->input->post('id')));                 
	}

	// --------------------------------------------------------------------
	
	/**
	 * Delete Group
	 *
	 * @access	public   		 
	 * @param	mixed $id_group
	 * @return
	 */	
	function delete_group()
	{
		$this->db->delete('pbk', array('ID' => $this->input->post('id')));
		$this->db->delete('pbk_groups', array('ID' => $this->input->post('id'))); 
                $this->db->delete('user_group', array('id_pbk_groups' => $this->input->post('id'))); 
	}
        /*
         * Get Phonebook Group ID
         * @access      public
         * @param       $phone_number
         * @return
         */
        function get_pbk ($phone_number){
                $this->db->select('*');
                $this->db->where('Number',$phone_number);
                $this->db->limit(1);
                return $this->db->get('pbk');
        }
        /*
         * Spam
         * -----------
         * Mark sender as spam
         * Insert number to blacklist_number
         */
         function mark_spam($phone_number){
             $data = array('phone_number'=>$phone_number,'reason'=>'Black Message');
             return $this->db->insert('blacklist_numbers',$data);
         }
         /*
          * Get blacklisted numbers
          */
         function get_blacklist_numbers($params=array()){
             $this->db->select('*');
             if(isset($params['phone_number']))$this->db->where('phone_number',$params['phone_number']);
             return $this->db->get('blacklist_numbers');
         }
         /*
          * Get phonebook order by village
          */
         function get_village_phonebook($village_id){
                $this->db->select('*');
                $this->db->where('village_id',$village_id);
                return $this->db->get('pbk');
         }
         
        
}

/* End of file phonebook_model.php */
/* Location: ./application/models/phonebook_model.php */