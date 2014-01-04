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
 * Users Class
 *
 * @package		Kalkun
 * @subpackage	Users
 * @category	Controllers
 */
class UserManager extends MY_Controller 
{	

	/**
	 * Constructor
	 *
	 * @access	public
	 */	
	function UserManager()
	{
		parent::MY_Controller();                
		
		// check level
		if($this->session->userdata('level')!='administrator')
		{
			$this->session->set_flashdata('notif', 'Access denied');
			redirect('');
		}
		
		$this->load->model('User_model');
	}

	// --------------------------------------------------------------------
	
	/**
	 * Index
	 *
	 * Display list of all users
	 *
	 * @access	public   		 
	 */	
	function index()
	{            
		$data['title'] = 'Users';
		$this->load->library('pagination');
                
		$config['base_url'] = site_url().'/users/index/';
		$config['total_rows'] = $this->User_model->getUsers(array('option' => 'all'))->num_rows();
		$config['per_page'] = $this->Kalkun_model->get_setting()->row('paging');
		$config['cur_tag_open'] = '<span id="current">';
		$config['cur_tag_close'] = '</span>';
		$config['uri_segment'] = 3;
		
		$this->pagination->initialize($config);
		$param = array('option' => 'paginate', 'limit' => $config['per_page'], 'offset' => $this->uri->segment(3,0));		
		
		$data['main'] = 'main/usermanager/index';
		if($_POST) $data['users'] = $this->User_model->getUsers(array('option' => 'search'));
		else $data['users'] = $this->User_model->getUsers($param);		
		
		$this->load->view('main/layout', $data);
                
                 
	}

	// --------------------------------------------------------------------
	
	/**
	 * Add user
	 *
	 * Display Add/Update an user form
	 *
	 * @access	public   		 
	 */	
	function add_user()
	{
		$type = $this->input->post('type');
		$data['tmp'] = "";
		
		if($type=='edit')
		{
			$id_user = $this->input->post('param1');
		 	$data['users'] = $this->User_model->getUsers(array('option' => 'by_iduser', 'id_user' => $id_user));
		}
                $data['levels'] = $this->User_model->get_levels();
		$this->load->view('main/usermanager/add_user', $data);	
	}	

	// --------------------------------------------------------------------
	
	/**
	 * Add user process
	 *
	 * Process the add/update user
	 *
	 * @access	public   		 
	 */	
	function add_user_process()
	{
            $param['real_name'] = trim($this->input->post('real_name'));
            $param['phone_number'] = trim($this->input->post('phone_number'));
            $param['username'] = trim($this->input->post('username'));
            $param['level'] = trim($this->input->post('level'));
            if($this->input->post('password')){
                $param['password'] = $this->input->post('password');
                // Hash password using phpass
                $hasher = new PasswordHash(PHPASS_HASH_STRENGTH, PHPASS_HASH_PORTABLE);
                $param['password'] = $hasher->HashPassword($this->input->post('password'));
            }            
            if($this->input->post('id_user'))$param['id_user'] = (int)$this->input->post('id_user');           
            
            
		$this->User_model->adduser($param);
		if($this->input->post('id_user')) echo "<div class=\"notif\">User has been updated.</div>";
		else echo "<div class=\"notif\">User has been added.</div>";
	}	

	// --------------------------------------------------------------------
	
	/**
	 * Delete user
	 *
	 * Delete an user
	 * All data related to deleted user (sms, phonebook, preference, etc) also deleted 
	 *
	 * @access	public   		 
	 */		
	function delete_user()
	{
		$uid = $this->input->post('id_user');
		
		// get and delete all user_outbox
		$res = $this->Message_model->get_messages(array('uid' => $uid, 'type' => 'outbox'));
                if($res->num_rows > 0){
                    foreach($res->result as $tmp) 
                    {
                            $param = array('type' => 'single', 'option' => 'outbox', 'id_message' => $tmp->id_outbox);
                            $this->Message_model->delMessages($param);
                    }    
                }
		
		
		// get and delete all user_inbox
		$res = $this->Message_model->get_messages(array('uid' => $uid, 'type' => 'inbox'));
                if($res->num_rows > 0){
                    foreach($res->result as $tmp) 
                    {
                            $param = array('type' => 'single', 'option' => 'permanent', 'source' => 'inbox', 'id_message' => $tmp->id_inbox);
                            $this->Message_model->delete_messages($param);
                    }    
                }		
		
		// get and delete all user_sentitems
		$res = $this->Message_model->delete_messages(array('uid' => $uid, 'type' => 'sentitems'));
                if($res->num_rows > 0){
                    foreach($res->result as $tmp)
                    {
                            $param = array('type' => 'single', 'option' => 'permanent', 'source' => 'sentitems', 'id_message' => $tmp->id_sentitems);
                            $this->Message_model->delete_messages($param);
                    }    
                }		
		
		// delete the rest (user, user_settings, pbk, pbk_groups, user_folders, sms_used)
		$this->User_model->delUsers($this->input->post('id_user'));	
	}
}	

/* End of file users.php */
/* Location: ./application/controllers/users.php */ 
