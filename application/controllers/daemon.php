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
 * Daemon Class
 *
 * @package		Kalkun
 * @subpackage	Daemon
 * @category	Controllers
 */
class Daemon extends Controller {

	/**
	 * Constructor
	 *
	 * @access	public
	 */				
	function Daemon()
	{	
		// Commented this for allow access from other machine
		// if($_SERVER['REMOTE_ADDR']!='127.0.0.1') exit("Access Denied.");		
						
		parent::Controller();
		parse_str($_SERVER['QUERY_STRING'],$_GET);
	}

	// --------------------------------------------------------------------
	
	/**
	 * Message routine
	 *
	 * Process the new/unprocessed incoming sms
	 * Called by shell/batch script on Gammu RunOnReceive directive
	 *
	 * @access	public   		 
	 */
	function message_routine()
	{
                
		$this->load->model('User_model');
		$this->load->model('Phonebook_model');
                $this->load->model('Kalkun_model');
                $this->load->model('tank_auth/users');
		// get unProcessed message
		$messages_unprocessed = $this->Message_model->get_messages(array('processed' => FALSE))->result();
		print_r($messages_unprocessed);
                foreach($messages_unprocessed as $tmp_message)
		{
                        $msg_user = $this->_set_ownership($tmp_message);
                        echo '---checking----';
                        //Blacklist - move to trash if sender message is blacklisted
                        if($this->Phonebook_model->get_blacklist_numbers(array('phone_number'=>$tmp_message->SenderNumber))->num_rows() > 0){
                            echo 'blacklisted';
                            $params = array(
                                'trash'=>'1',
                                'type'=>'conversation',
                                'number'=>$tmp_message->SenderNumber,
                                'current_folder'=>'',
                                'id_folder'=>5
                            );
                            $this->Message_model->move_messages($params);
                        }
			
                        //Custom message folder
                        $folders = $this->Kalkun_model->get_folders('custom_no_auth')->result();
                        foreach($folders as $folder){
			    //print_r($folders);
                            if(preg_match("/^$folder->name[\ ]/i", $tmp_message->TextDecoded))
                            {
                                $folder_name_length = strlen($folder->name)+1;
                                $message = substr($tmp_message->TextDecoded,$folder_name_length);

                                $this->Message_model->update_message_body($tmp_message->ID,$message);
                                $this->db->_reset_write();                                                                                           
                                $this->Message_model->set_message_published($tmp_message->ID);
                                $message = $this->_get_inbox_full($tmp_message->ID);
                                $this->db->_reset_write(); 
                                echo 'Move to : '.$folder->name;
                                $this->_move_message_folder($tmp_message->ID,$folder->id_folder); 
                                if($this->config->item('forward_message_to_owner')){
                                    $query = $this->Phonebook_model->get_pbk($tmp_message->SenderNumber);
                                    if($query->num_rows()>0){
                                        $id_user = $query->row('id_user');
                                        $senderName = $query->row('Name');
                                        $owner_number = $this->users->get_user_profiles($query->row('id_user'))->row('phone_number');
                                        
                                        //$before_message = $senderName.'('.$tmp_message->SenderNumber.'): ';
                                        $before_message = $senderName.': ';
                                        $after_message = PHP_EOL.'Format balas \''.$folder->name.':'.$tmp_message->ID.':[PESAN]\'';
                                        $message = $before_message.$message.$after_message;
                                        if(!empty($owner_number))$this->Message_model->send_messages(array(
                                            'dest'=>$owner_number,
                                            'date'=>date('Y-m-d H:i:s'),
                                            'message'=>$message,
                                            'coding'=>'unicode',
                                            'class'=>'1',
                                            'CreatorID'=>'BC',//$tmp_message->ID,
                                            'SenderID'=>'BC',//$id_user,
                                            'validity'=>'-1',
                                            'delivery_report'=>'default',
                                            'is_broadcast'=>'0',
                                            'is_forward'=>'1',
                                            'uid'=>$id_user
                                        ));
                                    }else{echo 'tidak ada di phonebook';}                                     
                                }                                
                            } 
                            else if(preg_match("/^".$folder->name.":+\d+\:/i", $tmp_message->TextDecoded)) // asumtion example  = BLS:BC:154:Message Body
                            {
                                $custom_array_key = explode(':',$tmp_message->TextDecoded);
                                $custom_key = $custom_array_key[0].':'.$custom_array_key[1].':'.$custom_array_key[2];  
                                $id_message = $custom_array_key[1];
                                $message    = $custom_array_key[2];
                                //$this->db->_reset_write();
                                $this->_move_message_folder($tmp_message->ID,$folder->id_folder); 
                                $this->Message_model->update_message_body($tmp_message->ID,$message);
                                //$this->db->_reset_write();  
                                $message = $this->_get_inbox_full($tmp_message->ID);
                                if($this->config->item('forward_message_to_owner')){
                                    $query = $this->Phonebook_model->get_pbk($tmp_message->SenderNumber);
                                    if($query->num_rows()>0){
                                        $id_user = $query->row('id_user');
                                        $owner_number = $this->Message_model->get_messages(array('id_message'=>$id_message))->row('SenderNumber');
                                        if(!empty($owner_number))$this->Message_model->send_messages(array(
                                            'dest'=>$owner_number,
                                            'date'=>date('Y-m-d H:i:s'),
                                            'message'=>$message,
                                            'coding'=>'default',
                                            'class'=>'1',
                                            'CreatorID'=>'BC',
                                            'SenderID'=>'BC',
                                            'validity'=>'-1',
                                            'delivery_report'=>'default',
                                            'is_broadcast'=>'0',
                                            'uid'=>$id_user,
                                            'id_folder'=>$folder->id_folder
                                            
                                        ));
                                        $this->Message_model->delete_messages(array(
                                            'type'=>'inbox',
                                            'id'=>$tmp_message->ID,
                                            'id_user'=>$id_user
                                        ));
                                    }                                     
                                }
                                                                
                            }else echo PHP_EOL." ---- Tidak ada custom message ----- \n".PHP_EOL;
                        }
                                                         
            
			// sms content
			if($this->config->item('sms_content'))
			{
				$this->_sms_content($tmp_message->TextDecoded, $tmp_message->SenderNumber);
			}	
			
			// simple autoreply
			if($this->config->item('simple_autoreply'))
			{
				$this->_simple_autoreply($tmp_message->SenderNumber);				
			}	

                        // external script
			if($this->config->item('ext_script_state'))
			{
				$this->_external_script($tmp_message->SenderNumber, $tmp_message->TextDecoded, $tmp_message->ID);				
			}					
			
			// update Processed
			$id_message[0] = $tmp_message->ID;
			$multipart = array('type' => 'inbox', 'option' => 'check', 'id_message' => $id_message[0]);
			$tmp_check = $this->Message_model->get_multipart($multipart);
			if($tmp_check->row('UDH')!='')
			{
				$multipart = array('option' => 'all', 'udh' => substr($tmp_check->row('UDH'),0,8));	
				$multipart['phone_number'] = $tmp_check->row('SenderNumber');
				$multipart['type'] = 'inbox';				
				foreach($this->Message_model->get_multipart($multipart)->result() as $part):
				$id_message[] = $part->ID;
				endforeach;	
			}		
                        $this->Message_model->update_processed($id_message);
            
                        // sms to email
			$this->_sms2email($tmp_message->TextDecoded, $tmp_message->SenderNumber , $msg_user);				
		 
            
		}	
	}

    


	// --------------------------------------------------------------------
	
	/**
	 * SMS content
	 *
	 * Process the SMS content procedure
	 *
	 * @access	private   		 
	 */
	function _sms_content($message, $number)
	{
		list($code) = explode(" ", $message);
		$reg_code = $this->config->item('sms_content_reg_code');
		$unreg_code = $this->config->item('sms_content_unreg_code');
		if (strtoupper($code)==strtoupper($reg_code))
		{ 
			$this->_register_member($number);
		}
		else if (strtoupper($code)==strtoupper($unreg_code))
		{
			$this->_unregister_member($number);
		}
	}

	// --------------------------------------------------------------------
	
	/**
	 * Register member
	 *
	 * Register member's phone number
	 *
	 * @access	private   		 
	 */
	function _register_member($number)
	{
		$this->load->model('Member_model');
		
		//check if number not registered
		if($this->Member_model->check_member($number)==0)
		$this->Member_model->add_member($number);
	}

	// --------------------------------------------------------------------
	
	/**
	 * Unregister member
	 *
	 * Unregister member's phone number
	 *
	 * @access	private   		 
	 */	
	function _unregister_member($number)
	{
		$this->load->model('Member_model');
		
		//check if already registered
		if($this->Member_model->check_member($number)==1)
		$this->Member_model->remove_member($number);
	}	

	// --------------------------------------------------------------------
	
	/**
	 * Simple autoreply
	 *
	 * Send autoreply message
	 *
	 * @access	private   		 
	 */
	function _simple_autoreply($phone_number)
	{
		$data['coding'] = 'default';
		$data['class'] = '1';
		$data['dest'] = $phone_number;
		$data['date'] = date('Y-m-d H:i:s');
		$data['message'] = $this->config->item('simple_autoreply_msg');
		$data['delivery_report'] = 'default';
		$data['CreatorID'] = 'BC';
                $data['SenderID'] = 'BC';
		$data['uid'] = $this->config->item('simple_autoreply_uid');	
		$this->Message_model->send_messages($data);				
	} 

	// --------------------------------------------------------------------
	
	/**
	 * External script
	 *
	 * Execute external script if condition match
	 *
	 * @access	private   		 
	 */	
	function _external_script($phone=NULL, $content=NULL, $id=NULL)
	{
		$shell_path = $this->config->item('ext_script_path');
				
		// Load all rules	
		foreach($this->config->item('ext_script') as $rule)
		{
			$script_name = $rule['name'];
			$value=$parameter="";
			
			// evaluate rule key
			switch($rule['key'])
			{
				case 'sender':
					$value = $phone;
				break;
				
				case 'content':
					$value = $content;
				break;
			}
			
			// evaluate rule type
			switch($rule['type'])
			{
				case 'match':
					$is_valid = $this->_is_match($rule['value'], $value);
				break;
				
				case 'contain':
					$is_valid = $this->_is_contain($rule['value'], $value);
				break;
			}
			
			// if we got valid rules
			if ($is_valid)
			{
				// build extra parameters
				if (!empty($rule['parameter']))
				{
					$valid_param = array('phone','content','id');
					$param = explode("|", $rule['parameter']);
					
					foreach ($param as $tmp)
					{
						if (in_array($tmp, $valid_param))
						{
							$parameter.=" ".${$tmp};
						}
					}
				}
				
				// execute it
				echo $shell_path." ".$script_name." ".$parameter;
			}
		}
		
	}	 
	/**
     *  function  _sms2email
     * 
     *  Function for sms to email feature
     *  
     *  @access	private
     **/ 
    function _sms2email($message , $from, $msg_user)
    {
        $this->load->library('email');
        $this->load->model('kalkun_model');
        $this->load->model('phonebook_model');
        $active  = $this->Kalkun_model->get_setting($msg_user)->row('email_forward');
        if($active != 'true') return;         
        $this->email->initialize($this->config);
        $mail_to = $this->Kalkun_model->get_setting($msg_user)->row('email_id');            
        $qry = $this->Phonebook_model->get_phonebook(array('option'=>'bynumber','number'=>$from , 'id_user' =>$msg_user));
		if($qry->num_rows()!=0) $from = $qry->row('Name');
        $this->email->from($this->config->item('mail_from'), $from);
        $this->email->to($mail_to); 
        $this->email->subject('New SMS');
        $this->email->message($message."\n\n". "- ".$from);	
        $this->email->send();

    }
    
	function _is_match($subject, $matched)
	{
		if ($subject===$matched) return TRUE;
		else return FALSE;
	}
	
	function _is_contain($subject, $matched)
	{
		if (!strstr($matched, $subject)) return FALSE;
		else return TRUE;
	}	
	
	// --------------------------------------------------------------------
	
	/**
	 * Server alert engine
	 *
	 * Scan host port and send SMS alert if the host is down
	 *
	 * @access	public   		 
	 */		
	function server_alert_engine()
	{
		// check plugin status
		$tmp_stat = $this->Plugin_model->getPluginStatus('server_alert');
		
		if($tmp_stat=='true')
		{
			$tmp_data = $this->Plugin_model->getServerAlert('active');
			foreach($tmp_data->result() as $tmp):
				$fp = fsockopen($tmp->ip_address, $tmp->port_number, $errno, $errstr, 60);
				if(!$fp)
				{
					$data['message'] = $tmp->respond_message."\n\nKalkun Server Alert";
					$data['date'] = date('Y-m-d H:i:s');
					$data['dest'] = $tmp->phone_number;
					$data['delivery_report'] = $this->Kalkun_model->get_setting('delivery_report', 'value')->row('value');
					$data['class'] = '1';
					
					$this->Message_model->sendMessages($data);
					log_message('info', 'Kalkun server alert=> Alert Name: '.$tmp->alert_name.', Dest: '.$tmp->phone_number);
					$this->Plugin_model->changeState($tmp->id_server_alert, 'false');
				} 
			endforeach;
		}
	}

	// --------------------------------------------------------------------
	
	/**
	 * Blacklist number
	 *
	 * Force delete SMS if coming from blacklist phone number
	 *
	 * @access	private   		 
	 */	
	function _blacklist_number()
	{
		// check plugin status
		$tmp_stat = $this->Plugin_model->getPluginStatus('blacklist_number');
		
		if($tmp_stat=='true')
		{		
		// get Blacklist Number
		$number = $this->Plugin_model->getBlacklistNumber('all');
		
		// get unProcessed message
		$message = $this->Message_model->getMessages('inbox', 'unprocessed');
		
		foreach($message->result() as $tmp_message):
		foreach($number->result() as $tmp_number):
			if($tmp_message->SenderNumber==$tmp_number->phone_number)
			{
				$this->Message_model->delMessages('single', 'inbox', 'permanent', $tmp_message->ID);
				break;
			}
		endforeach;
		
		// update Processed
		$this->Message_model->update_processed($tmp_message->ID);
		endforeach;
		}		
	}
        
        function _move_message_folder($id_message,$id_folder){
            echo PHP_EOL."-- Try move to folder :  $id_folder --".PHP_EOL;
            //echo $id_message.'---'.$id_folder;
            $params = array();
            $multipart = array('type' => 'inbox', 'option' => 'check', 'id_message' => $id_message);
            $tmp_check = $this->Message_model->get_multipart($multipart);
            if($tmp_check->row('UDH')!='')
            {
                    $multipart = array('option' => 'all', 'udh' => substr($tmp_check->row('UDH'),0,8));	
                    $multipart['phone_number'] = $tmp_check->row('SenderNumber');
                    $this->db->_reset_select();
                    foreach($this->Message_model->get_multipart($multipart)->result() as $part):
                    $params['id_message'][] = $part->ID;
                    endforeach;	
            }else{
                $params['id_message'][] = $id_message;
            }
            //print_r($params['id_message']);
//            echo '-----------';
//            echo $this->db->last_query();
//            echo '-----------';
            //$this->db->_reset_write();
            $this->Message_model->move_messages(array(
                'type'=>'single',
                'id_message'=>$params['id_message'],
                'folder'=>'inbox',
                'id_folder'=>$id_folder,
                'current_folder'=>''
            ));
            //echo 'Mbuh lah';
        }
                
        function _get_inbox_full($id_message){
            $id_messages = array();
            $multipart = array('type' => 'inbox', 'option' => 'check', 'id_message' => $id_message);
            $tmp_check = $this->Message_model->get_multipart($multipart);
            if($tmp_check->row('UDH')!='')
            {
                    $multipart = array('option' => 'all', 'udh' => substr($tmp_check->row('UDH'),0,8));	
                    $multipart['phone_number'] = $tmp_check->row('SenderNumber');				
                    foreach($this->Message_model->get_multipart($multipart)->result() as $part):
                        $id_messages['id'][] = $part->ID;
                    endforeach;	
            }else{
                $id_messages['id'][] = $id_message;
            }
            $body_message = '';
            foreach($id_messages['id'] AS $key=>$id){
                $body_message .= $this->Message_model->get_messages(array('id_message'=>$id))->row('TextDecoded');
            }
            return $body_message;
        }
        // --------------------------------------------------------------------
	
	/**
	 * Set Ownership
	 *
	 * Set ownership for incoming message
	 *
	 * @access	private	 
	 */
    function _set_ownership($tmp_message)
    {
    	$this->load->model(array('Message_model', 'User_model'));
                $check = false;
		// check @username tag
		$users = $this->User_model->getUsers(array('option' => 'all'));
		foreach ($users->result() as $tmp_user)
		{
			$tag = "@".$tmp_user->username;
			$msg_word = array();
			$msg_word = explode(" ", $tmp_message->TextDecoded);
			$check = in_array($tag, $msg_word);
						
			// update ownership
			if($check!==false) { 
                            $query = $this->Phonebook_model->get_pbk($tmp_message->SenderNumber);
                            if($query->num_rows()>0){
                                $id_user = $query->row('id_user');
                                $this->Message_model->update_owner($tmp_message->ID, $id_user); $msg_user =  $id_user;  break; 
                            }else{
                                $this->Message_model->update_owner($tmp_message->ID, $tmp_user->id_user); $msg_user =  $tmp_user->id_user;  break; 
                            }


                        }
		} 
                // if no matched username, set owner to Inbox Master
                if($check===false){
                    echo 'update owner';
                    //$group_id = $this->Phonebook_model->get_group_id($tmp_message->SenderNumber);
                    $pbk_owner = $this->Phonebook_model->get_pbk($tmp_message->SenderNumber)->row('id_user');
                    if(isset($pbk_owner) && !empty($pbk_owner))$this->Message_model->update_owner($tmp_message->ID, $pbk_owner); 
                    else $pbk_owner = $this->config->item('inbox_owner_id');                              
                    $this->Message_model->update_owner($tmp_message->ID, $pbk_owner); 
                    $msg_user =  $pbk_owner;                              
                }
                
                return $msg_user;
    }	
}

/* End of file daemon.php */
/* Location: ./application/controllers/daemon.php */ 
