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
 * Phonebook Class
 *
 * @package		Kalkun
 * @subpackage	Phonebook
 * @category	Controllers
 */
class Phonebook extends MY_Controller {
    
        var $limit_village = 12;
	/**
	 * Constructor
	 *
	 * @access	public
	 */		
	function Phonebook()
	{
		parent::MY_Controller();
                
	}
        function auth_check(){
                if(!$this->tank_auth->is_logged_in()) redirect('login');
                else if($this->tank_auth->get_user_profiles()->row('level') != 'administrator' && $this->tank_auth->get_user_profiles()->row('level') != 'author')redirect('');
        }

	// --------------------------------------------------------------------
	
	/**
	 * Index
	 *
	 * Display list of all contact
	 *
	 * @access	public   		 
	 */	
	function index($type = NULL) 
	{		
                $this->auth_check();
		$data['title'] = lang('tni_contacts');
		$this->load->library('pagination');
		$config['base_url'] = site_url().'/phonebook/index/';
		$config['total_rows'] = $this->Phonebook_model->get_phonebook(array('option' => 'all'))->num_rows();
		$config['per_page'] = $this->Kalkun_model->get_setting()->row('paging');
		$config['cur_tag_open'] = '<span id="current">';
		$config['cur_tag_close'] = '</span>';
		
		if($type == "ajax") $config['uri_segment'] = 4;
		else $config['uri_segment'] = 3;
                
		$this->pagination->initialize($config);
                $param = array('option' => 'paginate', 'limit' => $config['per_page'], 'offset' => $this->uri->segment($config['uri_segment']));
		//echo print_r($param);
                //echo $this->uri->segment(3);
		if ($type == "ajax"):
                    $data['phonebook'] = $this->Phonebook_model->get_phonebook($param);
                    $this->load->view('main/phonebook/contact/pbk_list', $data);
		else:
                    $data['main'] = 'main/phonebook/contact/index';
                    $data['pbkgroup'] = $this->Phonebook_model->get_phonebook(array('option' => 'group'))->result();
                    if($_POST)
                    {
                      $data['phonebook'] = $this->Phonebook_model->get_phonebook(array('option' => 'search'));
                      $data['search_string'] = $this->input->post('search_name');
                    }
                    else $data['phonebook'] = $this->Phonebook_model->get_phonebook($param);

                    $this->load->view('main/layout', $data);
		endif;
                //print_r($param);
	}

	// --------------------------------------------------------------------
	
	/**
	 * Group
	 *
	 * Display list of all group
	 *
	 * @access	public   		 
	 */	
	function group($group_id = NULL)
	{    
            $this->auth_check();
        if(!empty($group_id))
        {
            $data['title'] =  'Group Contacts';
    		$this->load->library('pagination');
    		$param = array( 'option' => 'bygroup' , 'group_id' =>$group_id );
    		$data['main'] = 'main/phonebook/contact/index';	
                $data['phonebook'] = $this->Phonebook_model->get_phonebook($param);
    		$data['pbkgroup'] = $this->Phonebook_model->get_phonebook(array('option' => 'group'))->result();
    	 	//print_r($data['phonebook']);
                $this->load->view('main/layout', $data);
        } 
        else
        {
    		$data['title'] = 'Groups';
    		$this->load->library('pagination');
    		$config['base_url'] = site_url().'/phonebook/group/';
    		$config['total_rows'] = $this->Phonebook_model->get_phonebook(array('option' => 'group'))->num_rows();
    		$config['per_page'] = $this->Kalkun_model->get_setting()->row('paging');
    		$config['cur_tag_open'] = '<span id="current">';
    		$config['cur_tag_close'] = '</span>';	
    		$config['uri_segment'] = 3;
    		$this->pagination->initialize($config);
    		$param = array('option' => 'group_paginate', 'limit' => $config['per_page'], 'offset' => $this->uri->segment($config['uri_segment']));
    		
    		$data['main'] = 'main/phonebook/group/index';
    		$data['group'] = $this->Phonebook_model->get_phonebook($param);
    		
    		$this->load->view('main/layout', $data);
        }
	}

	// --------------------------------------------------------------------
	
	/**
	 * Delete contact
	 *
	 * Delete a contact
	 *
	 * @access	public   		 
	 */		
	function delete_contact()
	{
            $this->auth_check();
		$this->Phonebook_model->delete_contact();
                echo 'Deleting '.$this->input->post('id');
        }

	// --------------------------------------------------------------------
	
	/**
	 * Delete group
	 *
	 * Delete a group and all contact on that group
	 *
	 * @access	public   		 
	 */		
	function delete_group()
	{
            $this->auth_check();
		$this->Phonebook_model->delete_group();
	}

	// --------------------------------------------------------------------
	
	/**
	 * Add group
	 *
	 * Add a group
	 *
	 * @access	public   		 
	 */		
	function add_group()
	{
            $this->auth_check();
		if($_POST) 
		{ 
			$this->Phonebook_model->add_group(); redirect('phonebook/group'); 
		}
	}

	// --------------------------------------------------------------------
  
  	/**
	 * Add/Remove Group from Contact
	 *
	 * Add a group
	 *
	 * @access	public   		 
	 */		
	function update_contact_group()
	{
            $this->auth_check();
		if($_POST) 
		{ 
			$this->Phonebook_model->multi_attach_group();  
		}
	}

	// --------------------------------------------------------------------
	
	/**
	 * Import contact
	 *
	 * Add contact from CSV file
	 * The CSV file must contain Name and Number as table header
	 *
	 * @access	public   		 
	 */		
	function import_phonebook()
	{
            $this->auth_check();
		$this->load->library('csvreader');
		$filePath = $_FILES["csvfile"]["tmp_name"];
		$csvData = $this->csvreader->parse_file($filePath, true);	
		
		$n=0;
		foreach($csvData as $field):
			$pbk['Name'] = $field["Name"];
			$pbk['Number'] = $field["Number"];
			$pbk['GroupID'] = $this->input->post('importgroupvalue');
			$pbk['id_user'] = $this->input->post('pbk_id_user');			
			$this->Phonebook_model->add_contact($pbk);
			$n++;
		endforeach;
		
		$this->session->set_flashdata('notif', $n.' contacts successfully imported');
		redirect('phonebook');		 
	}

	// --------------------------------------------------------------------
	
	/**
	 * Add contact
	 *
	 * Display add/update contact form
	 *
	 * @access	public   		 
	 */		
	function add_contact()
	{
            $this->auth_check();
		$data['pbkgroup'] = $this->Phonebook_model->get_phonebook(array('option' => 'group'));
                
                //Processing Province
                $provinces = $this->Phonebook_model->get_provinces(array(
                    'country_id'=>62 
                ));
                $data['province_list'] = array();
                foreach($provinces->result() AS $province){
                    $data['province_list'] [$province->province_id] = $province->province_name;
                }
                //Processing District
                $districts = $this->Phonebook_model->get_districts(array(
                    'province_id'=>62 
                ));
                $data['district_list'] = array();
                foreach($districts->result() AS $district){
                    $data['district_list'] [$district->district_id] = $district->district_name;
                }
                
                
                //$data['district_list'] = $this->Phonebook_model->get_districts('list');
                //$data['subdistrict_list'] = $this->Phonebook_model->get_subdistricts('list');
                //$data['village_list'] = $this->Phonebook_model->get_villages('list');
		$type = $this->input->post('type');
		$data['type'] = $type;
		if ($type=='edit')
		{
			$id_pbk = $this->input->post('param1');
		 	$data['contact'] = $this->Phonebook_model->get_phonebook(array('option' => 'by_idpbk', 'id_pbk' => $id_pbk));
                        
                        $phonebook = $data['contact'];
                        $village_id = $phonebook->row('village_id');
                        if(!empty($village_id) && $this->Phonebook_model->get_villages(array('village_id'=>$village_id))->num_rows() > 0){
                            //$district_id = substr($village_id,0,4);
                            //$subdistrict_id = substr($village_id,0,7);
                            $get_subdistrict = $this->Phonebook_model->get_villages(array('village_id'=>$village_id));
                            $subdistrict_id = $get_subdistrict->row('subdistrict_id');
                            
                            $get_district = $this->Phonebook_model->get_subdistricts(array('subdistrict_id'=>$subdistrict_id));
                            $district_id = $get_district->row('district_id');
                            
                            $data['subdistrict_id'] = $subdistrict_id;
                            $data['district_id'] = $district_id;
                            
                            //Processing Sub District
                            $subdistricts = $this->Phonebook_model->get_subdistricts(array(
                                'district_id'=>$district_id 
                            ));
                            $data['subdistrict_list'] = array();
                            foreach($subdistricts->result() AS $subdistricts){
                                $data['subdistrict_list'] [$subdistricts->subdistrict_id] = $subdistricts->subdistrict_name;
                            }
                            //Processing Village
                            $villages = $this->Phonebook_model->get_villages(array(
                                'subdistrict_id'=>$subdistrict_id 
                            ));
                            $data['village_list'] = array();
                            foreach($villages->result() AS $village){
                                $data['village_list'] [$village->village_id] = $village->village_name;
                            }
                        }else{
                            $data['subdistrict_list'] = array('default'=>' -- Pilih -- ');
                            $data['village_list'] = array('default'=>' -- Pilih -- ');
                        }
                        
		}
		else if ($type=='message')
		{
			$data['number'] = $this->input->post('param1');
		}
                
		$this->load->view('main/phonebook/contact/add_contact', $data);	
	}
        
	// --------------------------------------------------------------------
	
	/**
	 * Add contact process
	 *
	 * Process the submitted add/update contact form
	 *
	 * @access	public   		 
	 */		
	function add_contact_process()
	{
            $this->auth_check();
            //print_r($this->input->post());
		$pbk['Name'] = trim($this->input->post('name'));
		$pbk['Number'] = trim($this->input->post('number'));
		$pbk['Groups'] = $this->input->post('groups');
		$pbk['id_user'] = $this->input->post('pbk_id_user');
                $pbk['village_id'] = $this->input->post('village_id');                
		$pbk['alamat'] = $this->input->post('alamat');
                $pbk['latlong'] = $this->input->post('alamatLatLong');
		if($this->input->post('editid_pbk'))
		{
			$pbk['id_pbk'] = $this->input->post('editid_pbk');
			$msg = "<div class=\"notif\">Contact has been updated.</div>";
		}
		else
			$msg = "<div class=\"notif\">Contact has been added.</div>";
			
		$this->Phonebook_model->add_contact($pbk);
		echo $msg;
                //print_r($pbk);
	}
        
	// --------------------------------------------------------------------
	
	/**
	 * Get phonebook
	 *
	 * Search contact by name
	 * Used by compose window as autocompleter
	 *
	 * @access	public   		 
	 */	
	function get_phonebook()
	{
            $this->auth_check();
		$q = $this->input->post('q', TRUE);
		if (isset($q) && strlen($q) > 0)
		{
			$user_id = $this->session->userdata("id_user");
			$param = array('uid' => $user_id, 'query' => $q);
			$query = $this->Phonebook_model->search_phonebook($param);
                        echo json_encode($query->result());
		}
	}
        
        function get_subdistricts(){            
            $is_ajax = $_GET['is_ajax'];
            $district_id = $_GET['district_id'];
            
            $param = array(
                'district_id'=>$district_id
            );
            
            $subdistricts = $this->Phonebook_model->get_subdistricts($param)->result_array();
            if($is_ajax){
                echo json_encode($subdistricts);
            }
            
        }
        
        /*
          * Get villages
          * @param GET parameter
          */
         function get_villages(){
            $param = array();
            if(isset($_GET['is_ajax']))$is_ajax = $_GET['is_ajax'];
            if(isset($_GET['subdistrict_id'])){
                $subdistrict_id = $_GET['subdistrict_id'];
                $param['subdistrict_id'] = $subdistrict_id;
            } 
            
            $villages = $this->Phonebook_model->get_villages($param)->result_array();
            if($is_ajax){
                echo json_encode($villages);
            }else{
                return $villages;
            }
            
        }
        /*
         * Get Village as List
         * @param GET parameter
         */
        function get_village_list($paging=0, $is_ajax=1){
	
            
            $param = array(
                'offset'=>$paging,
                'limit'=>$this->limit_village,
                'order_by'=>'village_id'
            );
            $villages = $this->Phonebook_model->get_villages($param);
            $rows = $this->Phonebook_model->get_villages()->num_rows();
            $data['villages'] = $villages->result_array();
            if($is_ajax==1){
                
            /*#####################   PAGINATION   ####################*/
                
                if($rows > 0){                    
                    $pagination['rows'] = bcdiv($rows,$this->limit_village,0)+1;                    
                    $pagination['current_page'] = $paging + 1;
                    $pagination['prev'] = $paging-1;
                    $pagination['next'] = $paging + 1;
                    $pagination['last'] = $pagination['rows'] - 1;
                    $data['pagination'][] = $pagination;
                }
                
        
                echo json_encode($data);
            }else{
                return $data;
            }
        }
        /*
         * Search Village 
         * @param GET parameter
         */
        function search_village($query, $paging=0, $is_ajax=1){
            $param = array(
                'type'=>'search',
                'search_field'=>'village_name',
                'search_query'=>$query
            );
            $villages = $this->Phonebook_model->get_villages($param);
            $rows = $this->Phonebook_model->get_villages()->num_rows();
            $data['villages'] = $villages->result_array();
            if($is_ajax){
                
            /*#####################   PAGINATION   ####################*/
                
                if($rows > 0){                    
                    $pagination['rows'] = bcdiv($rows,$this->limit_village,0)+1;                    
                    $pagination['current_page'] = $paging + 1;
                    $pagination['prev'] = $paging-1;
                    $pagination['next'] = $paging + 1;
                    $pagination['last'] = $pagination['rows'] - 1;
                    $data['pagination'][] = $pagination;
                }               
        
                echo json_encode($data);
            }else{
                return $data;
            }
        }       
    
}

/* End of file phonebook.php */
/* Location: ./application/controllers/phonebook.php */
