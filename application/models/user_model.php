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
 * User_model Class
 *
 * Handle all user database activity 
 *
 * @package		Kalkun
 * @subpackage	User
 * @category	Models
 */
class User_model extends Model {
      private $user_table = 'auth_users';
      private $profile_table = 'auth_user_profiles';
      private $setting_table = 'user_settings';
	/**
	 * Constructor
	 *
	 * @access	public
	 */		
	function User_model()
	{
		parent::Model();
	}

	// --------------------------------------------------------------------
	
	/**
	 * Get User
	 *
	 * @access	public   		 
	 * @param	mixed $param
	 * @return	object
	 */		
	function getUsers($param)
	{
		$this->db->from($this->profile_table);
		$this->db->join($this->user_table, "$this->user_table.id = $this->profile_table.user_id");
                $this->db->join($this->setting_table, "$this->setting_table.id_user = $this->profile_table.user_id");
		switch($param['option'])
		{
			case 'all':
			$this->db->select('*');
			break;
			
			case 'paginate':
			$this->db->limit($param['limit'], $param['offset']);
			break;
			
			case 'by_iduser':
			$this->db->where("$this->setting_table.id_user", $param['id_user']);
			break;

			case 'search':
			$this->db->like('real_name', $this->input->post('search_name'));
			break;			
		}
		$this->db->order_by('real_name');
		return $this->db->get();
	}
        /*
         * Get levels from users - enum select
         * @access      public
         * @param       mixed
         * @return
         */
        function get_levels(){
            return $this->db->enum_select($this->profile_table,'level');
        }
	// --------------------------------------------------------------------
	
	/**
	 * Add User
	 *
	 * @access	public   		 
	 * @param	mixed
	 * @return
	 */	
	function addUser($param=array())
	{
                                
		// edit mode
		if(isset($param['id_user'])) 
		{
                        $this->db->set('username', $param['username']);
			$this->db->where('id', $param['id_user']);
			$this->db->update($this->user_table);
                        
                        $this->db->set('level', $param['level']);
                        $this->db->set('real_name', $param['real_name']);		
                        $this->db->set('phone_number', $param['phone_number']);
                        $this->db->where('user_id',$param['id_user']);
                        $this->db->update($this->profile_table);
		}
		else 
		{                 
                        $this->db->set('username', $param['username']);
			$this->db->set('password', $param['password']);
			$this->db->insert($this->user_table);
			
                        $id_user = $this->db->insert_id();
                        //user_profiles
                        $this->db->set('level', $param['level']);
                        $this->db->set('real_name', $param['real_name']);		
                        $this->db->set('phone_number', $param['phone_number']);                        
                        $this->db->set('user_id', $id_user);
                        $this->db->insert($this->profile_table);
                        
			// user_settings
			$this->db->set('theme', 'blue');
			$this->db->set('signature', 'false;');
			$this->db->set('permanent_delete', 'false');
			$this->db->set('paging', '20');
			$this->db->set('bg_image', 'true;background.jpg');
			$this->db->set('delivery_report', 'default');
			$this->db->set('language', 'english');	
			$this->db->set('conversation_sort', 'asc');
			$this->db->set('id_user', $id_user);
			
			$this->db->insert($this->setting_table);
			
		}
	}	

	// --------------------------------------------------------------------
	
	/**
	 * Delete User
	 *
	 * @access	public   		 
	 * @param	number $id_user ID user to delete
	 * @return
	 */		
	function delUsers($id_user)
	{		
		$this->db->delete('sms_used', array('id_user' => $id_user));
		$this->db->delete('user_folders', array('id_user' => $id_user));
		$this->db->delete('pbk', array('id_user' => $id_user));
		$this->db->delete('pbk_groups', array('id_user' => $id_user));
		$this->db->delete($this->setting_table, array('id_user' => $id_user));
                $this->db->delete($this->profile_table, array('user_id' => $id_user));
		$this->db->delete($this->user_table, array('id_user' => $id_user));
	}

}	

/* End of file user_model.php */
/* Location: ./application/models/user_model.php */