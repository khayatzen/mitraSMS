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
 * MY_Controller Class
 *
 * Base controller
 *
 * @package		Kalkun
 * @subpackage	Base
 * @category	Controllers
 */
class MY_Controller  extends Controller  {
	
	/**
	 * Constructor
	 *
	 * @access	public
	 */
	function MY_Controller()
	{
		parent::Controller();
		
		// installation mode
		//if(file_exists("install")) redirect('install');	
	
		$this->load->library('session');
                $this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		$this->load->library('tank_auth');
		$this->lang->load('tank_auth');
                
		// session check
		if($this->tank_auth->is_logged_in()){                   
                    // language
                    $lang = $this->Kalkun_model->get_setting()->row('language');
                    $this->lang->load('kalkun', 'bahasa');
                    // Message routine
                    $this->_message_routine();
                }else{
                    // default language                    
                    $this->lang->load('kalkun', 'bahasa');
                    $this->_message_routine_global();
                }	
		
                
	}
		
	function _message_routine()
	{
		$this->load->model('User_model');
		$uid = $this->session->userdata("id_user");
		
		$outbox = $this->Message_model->get_user_outbox($uid);
		foreach ($outbox->result() as $tmp)
		{
			$id_message = $tmp->id_outbox;
			
			// if still on outbox, means message not delivered yet
			if ($this->Message_model->get_messages(array('id_message' => $id_message, 'type' => 'outbox'))->num_rows()>0)
			{ 
				// do nothing
			}
			// if exist on sentitems then update sentitems ownership, else delete user_outbox
			else if ($this->Message_model->get_messages(array('id_message' => $id_message, 'type' => 'sentitems'))->num_rows()>0) 
			{
				if(!empty($tmp->sender_user_id))$this->Message_model->insert_user_sentitems($id_message, $tmp->sender_user_id); 
                                else $this->Message_model->insert_user_sentitems($id_message, $uid); 
                                $this->Message_model->update_message_broadcast($id_message,$tmp->is_broadcast);
                                $this->Message_model->update_message_forward($id_message,$tmp->is_forward);
                                $this->Message_model->update_message_folder($id_message,$tmp->id_folder);
				$this->Message_model->delete_user_outbox($id_message);               
			}
			else
			{
				$this->Message_model->delete_user_outbox($id_message);
			}
		}
	}
        function _message_routine_global()
	{
		$this->load->model('User_model');
		//$uid = $this->session->userdata("id_user");
		
		$outbox = $this->Message_model->get_all_user_outbox();
		foreach ($outbox->result() as $tmp)
		{
			$id_message = $tmp->id_outbox;
			
			// if still on outbox, means message not delivered yet
			if ($this->Message_model->get_messages(array('id_message' => $id_message, 'type' => 'outbox'))->num_rows()>0)
			{ 
				// do nothing
			}
			// if exist on sentitems then update sentitems ownership, else delete user_outbox
			else if ($this->Message_model->get_messages(array('id_message' => $id_message, 'type' => 'sentitems'))->num_rows()>0) 
			{
				if(!empty($tmp->sender_user_id))$this->Message_model->insert_user_sentitems($id_message, $tmp->sender_user_id); 
                                else $this->Message_model->insert_user_sentitems($id_message, $tmp->id_user); 
                                $this->Message_model->update_message_broadcast($id_message,$tmp->is_broadcast);
                                $this->Message_model->update_message_forward($id_message,$tmp->is_forward);
                                $this->Message_model->update_message_folder($id_message,$tmp->id_folder);
				$this->Message_model->delete_user_outbox($id_message);               
			}
			else
			{
				$this->Message_model->delete_user_outbox($id_message);
			}
		}
	}
}

/* End of file MY_Controller.php */
/* Location: ./application/libraries/MY_Controller.php */ 
