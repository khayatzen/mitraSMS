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
 * Message_model Class
 *
 * Handle all messages database activity 
 *
 * @package		Kalkun
 * @subpackage	Messages
 * @category	Models
 */
class Message_model extends Model { 
	
	var $udh = '';
	
	/**
	 * Constructor
	 *
	 * @access	public
	 */
	function Message_model()
	{
		parent::Model();
                $this->load->model('tank_auth/users');
	}

	// --------------------------------------------------------------------
	
	/**
	 * Send Messages
	 *
	 * @access	public   		 
	 * @param	mixed $options 
	 * Option: Values
	 * --------------
	 * dest string, phone number destination
	 * date datetime
	 * message string
	 * coding default, unicode
	 * class -1, 0, 1
	 * delivery_report default, yes, no
	 * uid int
	 * @return	object
	 */	
	function send_messages($data)
	{	

        if($this->config->item('disable_outgoing'))
        {
            echo "<div class=\"notif\">Outgoing SMS Disabled</div>"; return;
        }
	if(isset($data['uid'])){
            $id_user = $data['uid'];
        }else{
            $id_user = $this->session->userdata('id_user');
        }
        if(!isset($data['is_forward']))$data['is_forward'] = '0';
        $data['sender_user_id'] = $id_user;
        if($data['dest']!=NULL && $data['date']!=NULL && $data['message']!=NULL)
		{
			// Check message's length	
			$messagelength = strlen($data['message']);
			
			// Check coding
			switch($data['coding'])
			{ 
				case 'default':
					$standar_length = 160;
					$data['coding'] = 'Default_No_Compression';
				break;
				
				case 'unicode':
					$standar_length = 70;
					$data['coding'] = 'Unicode_No_Compression';
				break;
			}
			
			$UDH_length = 7;
			$multipart_length = $standar_length - $UDH_length; 

                        if(!isset($data['id_folder']))$data['id_folder'] = 3;
                        if(!isset($data['id_inbox']))$data['id_inbox'] = -1;

			// Multipart message
			if($messagelength > $standar_length)
			{
				// generate UDH
				$UDH = "050003";
				$UDH .= strtoupper(dechex(mt_rand(0, 255)));
				$data['UDH'] = $UDH;
						
				// split string
				$tmpmsg = str_split($data['message'], $multipart_length);
				
				// count part message
				$part = count($tmpmsg);
				if($part < 10) $part = '0'.$part;
				
				// insert first part to outbox and get last outbox ID
				$data['option'] = 'multipart';
				$data['message'] = $tmpmsg[0];
				$data['part'] = $part;
                                
				$outboxid = $this->_send_message_route($data);
                                $this->Kalkun_model->add_sms_used($id_user);
				
				// insert the rest part to Outbox Multipart
				for($i=1; $i<count($tmpmsg); $i++) 
				{
				    $this->_send_message_multipart($outboxid, $tmpmsg[$i], $i, $part, $data['coding'], $data['class'], $UDH);
                                    $this->Kalkun_model->add_sms_used($id_user);
                                }
			}		
			else 
			{
                                $data['option'] = 'single';
				$this->_send_message_route($data);
				$this->Kalkun_model->add_sms_used($id_user);		
			}	
		}
		else 
		{
			echo 'Parameter invalid';	
		}
	}

	// --------------------------------------------------------------------
	
	/**
	 * Send Message Route
	 *
	 * @access	private   		 
	 * @param	mixed
	 * 
	 * @return
	 */		
	function _send_message_route($tmp_data)
	{
            //print_r($tmp_data);
		$data = array (
				'InsertIntoDB' => date('Y-m-d H:i:s'),
				'SendingDateTime' => $tmp_data['date'],
				'DestinationNumber' => $tmp_data['dest'],
				'Coding' => $tmp_data['coding'],
				'Class' => $tmp_data['class'],
				'CreatorID' => $tmp_data['CreatorID'],
				'SenderID' => $tmp_data['SenderID'],
				'TextDecoded' => $tmp_data['message'],
                                'RelativeValidity' => $tmp_data['validity'],
				'DeliveryReport' => $tmp_data['delivery_report'],
                                'is_broadcast' => $tmp_data['is_broadcast'],
                                'is_forward' => $tmp_data['is_forward'],
                                'sender_user_id'=>$tmp_data['sender_user_id']
				);
                if($tmp_data['option']=='multipart')
		{
			$data['MultiPart'] = 'true'; 
			$data['UDH'] = $tmp_data['UDH'].$tmp_data['part'].'01'; 
		}
					
		$this->db->insert('outbox', $data);		
		$last_outbox_id = $this->db->insert_id();
                //$message_outbox = $this->get_messages(array('type'=>'outbox','ID'=>$last_outbox_id));
                if($tmp_data['id_folder'] < 3) $tmp_data['id_folder'] = 3;
		$user = array(
				'id_outbox' => $last_outbox_id,
				'id_user' => $tmp_data['uid'],
                                'id_folder' => $tmp_data['id_folder'],
                                'is_broadcast' => $tmp_data['is_broadcast'],
                                'is_forward' => $tmp_data['is_forward']
				);
		$this->db->insert('user_outbox', $user);
                
                /*Insert Folder Message Sent*/
//                $folder_message_sent = array(
//                    'OutboxInsertIntoDB'=>$data['InsertIntoDB'],
//                    'DestinationNumber'=>$data['DestinationNumber'],
//                    'replayed_inbox_id'=>$tmp_data['id_inbox'],
//                    'id_folder'=>$tmp_data['id_folder']
//                );
//		$this->db->insert('folder_message_sent', $folder_message_sent);
		if($tmp_data['option']=='multipart') return $last_outbox_id;
	}	

	// --------------------------------------------------------------------
	
	/**
	 * Send Message Multipart
	 *
	 * @access	private   		 
	 * @param	mixed
	 * 
	 * @return
	 */		
	function _send_message_multipart($outboxid, $message, $pos, $part, $coding, $class, $UDH) 
	{
		$code = $pos+1;
		if($code < 10) $code = '0'.$code;
		
		$data = array (
				'ID' => $outboxid,
				'UDH' => $UDH.$part.''.$code,
				'SequencePosition' => $pos+1,
				'Coding' => $coding,
				'Class' => $class,
				'TextDecoded' => $message,
				);	
		$this->db->insert('outbox_multipart',$data);						
	}

	// --------------------------------------------------------------------
	function search_messages($options = array())
	{
        if(!isset($options['number'])) if(!isset($options['search_string']))  die("No String to Search For");
		 
		$this->db->from('inbox');
        $tmp_number = 'SenderNumber'; 
		$tmp_order = 'ReceivingDateTime';
		$udh_where = "(".$this->_protect_identifiers("UDH")." = '' OR ".$this->_protect_identifiers("UDH")." LIKE '%1')";
		$this->db->where($udh_where, NULL, FALSE);				
		
        if(isset($options['search_string'])) $this->db->like('TextDecoded', $options['search_string']);
		
		// if phone number is set
		if(isset($options['number'])) $this->db->where($tmp_number, $options['number']);
        
        //remove already trashed
		if(!isset($options['trash'])) $this->db->where('id_folder !=', '5');
        
        // join user table
		if(isset($options['uid']))
		{
			$this->db->join($user_folder, $user_folder.'.id_'.$options['type'].'='.$options['type'].'.ID');
			$this->db->where($user_folder.'.id_user',$options['uid']);	
		}
        
        $result = $this->db->get();
        
        $inbox = $result->result_array();
        
        // add global date for sorting
		foreach($inbox as $key=>$tmp):
		$inbox[$key]['globaldate'] = $inbox[$key]['ReceivingDateTime'];
		$inbox[$key]['source'] = 'inbox';
		endforeach;
            
            
            
        $this->db->from('sentitems');
		$tmp_number = 'DestinationNumber';
		$tmp_order = 'SendingDateTime';	
        $this->db->where('SequencePosition', '1');		
			
	   if(isset($options['search_string'])) $this->db->like('TextDecoded', $options['search_string']);
		
		// if phone number is set
		if(isset($options['number'])) $this->db->where($tmp_number, $options['number']);
        
         //remove already trashed
		if(!isset($options['trash'])) $this->db->where('id_folder !=', '5');
        
        // join user table
		if(isset($options['uid']))
		{
			$this->db->join($user_folder, $user_folder.'.id_'.$options['type'].'='.$options['type'].'.ID');
			$this->db->where($user_folder.'.id_user',$options['uid']);	
		}
        
        $result = $this->db->get();
        
        $sentitems = $result->result_array();
         
        // add global date for sorting
		foreach($sentitems as $key=>$tmp):
			$sentitems[$key]['globaldate'] = $sentitems[$key]['SendingDateTime'];
			$sentitems[$key]['source'] = 'sentitems';
		endforeach;
			
		$data['messages'] = $inbox;
			
		// merge inbox and sentitems
		foreach($sentitems as $tmp):
		  $data['messages'][] = $tmp;
		endforeach;		
			
		// sort data
		$sort_option = $this->Kalkun_model->get_setting()->row('conversation_sort');
		usort($data['messages'], "compare_date_".$sort_option);
    
        $return_data= array();
        $return_data['total_rows'] = count($data['messages']);
        $return_data['messages'] = array();
       
        //paginate
        if(isset($options['offset'] ) && isset($options['limit'])) 
        {
          for($i = $options['offset'] ; $i  <  min( ($options['offset']+ $options['limit']), $return_data['total_rows']) ;  $i++)
          {
             $return_data['messages'][] = $data['messages'][$i];
          }
        }
        else 
        {
           $return_data['messages'] = $data['messages'];
        }
    	
        return  (object) $return_data;
	}
    
    
    // --------------------------------------------------------------------
	/**
	 * Get Messages
	 *
	 * Get messages from inbox, outbox, sentitems
	 *
	 * @access	public   		 
	 * @param	mixed $options 
	 * Option: Values
	 * --------------
	 * type inbox, outbox, sentitems
	 * id_folder int
	 * id_message int
	 * number string
	 * processed bool
	 * readed bool
	 * uid int
	 * @return	object
	 */	
	function get_messages($options = array())
	{
		// default values
                $options = $this->_default(array('type' => 'inbox'), $options);
    
                // register valid type
		$valid_type = array('inbox', 'outbox', 'sentitems');
		
		// check if it's valid type
		if(!in_array($options['type'], $valid_type)) 
		die('Invalid type request on class '.get_class($this).' function '.__FUNCTION__);		
		
                //get user level
                if(isset($options['uid']))$options['level'] = $this->users->get_user_profiles($options['uid'])->row('level');		
                //echo $options['level'];
		$user_folder = "user_".$options['type'];
		$this->db->from($options['type']);
		
		// set valid field name
		if($options['type']=='inbox')
		{                    
			$tmp_number = 'SenderNumber'; 
			$tmp_order = 'ReceivingDateTime';
			
			$udh_where = "(".$this->_protect_identifiers("UDH")." = '' OR ".$this->_protect_identifiers("UDH")." LIKE '%1')";
			$this->db->where($udh_where, NULL, FALSE);    
                        if(isset($options['published']))$this->db->where('published','true');
                        
		}
		else 
		{
                    //print_r($options);
			$tmp_number = 'DestinationNumber';
			$tmp_order = 'SendingDateTime';	
			if($options['type']=='sentitems'){
                            $this->db->where('SequencePosition', '1');
                            $this->db->where('is_forward',0);
                            if(isset($options['hide_forwarded_message'])){
                                $this->db->join($user_folder, $user_folder.'.id_'.$options['type'].'='.$options['type'].'.ID');
                            }
                            if(isset($options['broadcast']) && $options['broadcast']==true){
                                $this->db->distinct();
                                $this->db->where('is_broadcast','1');                                
                                $this->db->group_by('InsertIntoDB');
                            }
                        }		
		}
		// stream type : new_stream,old_stream
                if(isset($options['stream_type']) && !empty($options['stream_type'])){
                    $stream_date = $options['stream_date']; 
                    if($options['type']=='inbox')$this->db->where('published','true');
                    if($options['stream_type']=='new_stream'){
                       $this->db->where($tmp_order.' > ', $stream_date);
                    }
                    else if($options['stream_type']=='old_stream'){
                       $this->db->where($tmp_order.' < ', $stream_date);
                    }
                }
                
                        
		// if id message is set
		if(isset($options['id_message'])) $this->db->where('ID', $options['id_message']);
		else
		{
			// if id folder is set, else use default value (inbox = 1, sentitems = 3)
			if(isset($options['id_folder'])) $this->db->where('id_folder', $options['id_folder']);
			else
			{
				if($options['type']!='outbox') $this->db->where('id_folder', array_search($options['type'], $valid_type)+1);
			}
		}
         
                //if search string is set
		if(isset($options['search_string'])) $this->db->like('TextDecoded', $options['search_string']);
		
		// if phone number is set
		if(isset($options['number'])) $this->db->where($tmp_number, $options['number']);

		// if readed is set
		if(isset($options['readed']) && is_bool($options['readed'])) 
		{
			// valid only for inbox
			if($options['type']=='inbox')
			{
				$readed = ($options['readed']) ? 'true' : 'false'; 
				$this->db->where('readed', $readed);	
			}
		}

		// if processed is set
		if(isset($options['processed']) && is_bool($options['processed'])) 
		{
			// valid only for inbox
			if($options['type']=='inbox')
			{
				$processed = ($options['processed']) ? 'true' : 'false'; 
				$this->db->where('Processed', $processed);
			}
		}		
		
		// join user table
		if(isset($options['uid']))
		{
                        //echo $options['uid'];
                        //print_r($this->users->get_user_profiles($options['uid'])->row('level'));
                        //echo 'level = '.$this->users->get_user_profiles($options['uid'])->row('level');
                        //print_r($this->users->get_user_profiles($options['uid'])->row('level'));
                        $this->db->join($user_folder, $user_folder.'.id_'.$options['type'].'='.$options['type'].'.ID');
                        if($options['level'] != 'administrator'){
                            
                            $this->db->where($user_folder.'.id_user',$options['uid']);
                        }				
			
			// if trash is set
			if(isset($options['trash']) && is_bool($options['trash'])) $this->db->where($user_folder.'.trash', $options['trash']);	
		}
    
		if(isset($options['order_by'])) $this->db->order_by($options['order_by'], isset($options['order_by_type'])?$options['order_by_type']:'DESC');

		if(isset($options['limit']) && isset($options['offset'])) 
		{
			$this->db->limit($options['limit'], $options['offset']);
		}
                
		$result = $this->db->get();
		return $result;
	}

	// --------------------------------------------------------------------
	
	/**
	* _protect_identifiers
	*
	* Ugly hack to add backticks to database field
	*
	* @param string $identifier
	* @return string
	*/
	function _protect_identifiers($identifier=NULL)
	{
		$escape_char;
		$escaped_identifer="";
		
		// get database engine
		$db_engine = $this->db->platform();
		$escape_char = get_database_property($db_engine);
		$escape_char = $escape_char['escape_char'];
		
		$sub = explode(".", $identifier);
		$sub_count = count($sub);

		foreach($sub as $key => $tmp)
		{
			$escaped_identifer.=$escape_char.$tmp.$escape_char;
			
			// if this is not the last
			if($key!=$sub_count-1)
			{
				$escaped_identifer.=".";
			}
		}
		
	    return $escaped_identifer;
	}
		
	// --------------------------------------------------------------------
	
	/**
	* _default method combines the options array with a set of defaults 
	* giving the values in the options array priority.
	*
	* @param array $defaults
	* @param array $options
	* @return array
	*/
	function _default($defaults, $options)
	{
	    return array_merge($defaults, $options);
	}	

	// --------------------------------------------------------------------
	
	/**
	 * Get Conversation
	 *
	 * Get list of conversation
	 *
	 * @access	public   		 
	 * @param	mixed $options 
	 * Option: Values
	 * --------------
	 * type inbox, outbox, sentitems
	 * id_folder int
	 * limit int
	 * offset int
	 * trash bool
	 *
	 * @return	object
	 */			
	function get_conversation($options = array())
	{

        // default values
    	$options = $this->_default(array('type' => 'inbox'), $options);
    
    	// register valid type
		$valid_type = array('inbox', 'outbox', 'sentitems');
		
		// check if it's valid type
		if(!in_array($options['type'], $valid_type)) 
		die('Invalid type request on class '.get_class($this).' function '.__FUNCTION__);
							
		$user_id = $this->session->userdata('id_user');
                $user_level = $this->session->userdata('level');
		// if id folder is set, else use default value (inbox = 1, outbox = 2, sentitems = 3)
		if(isset($options['id_folder'])) $tmp_id_folder = $options['id_folder'];
		else $tmp_id_folder = array_search($options['type'], $valid_type)+1;
		
		if(isset($options['id_folder']) && $options['id_folder']=='5') $tmp_trash='1';
		else $tmp_trash='0';
		
                $is_global_folder = $this->is_global_folder($tmp_id_folder);
                switch($options['type'])
		{
			case 'inbox':
				$this->db->from('inbox');
				$this->db->select_max($this->_protect_identifiers('ReceivingDateTime'), $this->_protect_identifiers('maxdate'), FALSE);
				$this->db->join('user_inbox','user_inbox.id_inbox=inbox.ID');
				if($is_global_folder == false && $user_level != 'administrator'){
                                    $this->db->where('id_user', $user_id);
                                }
                                $this->db->where('id_folder', $tmp_id_folder);
				$this->db->group_by('SenderNumber');
				
				$sub_sql = $this->db->_compile_select();
				$this->db->_reset_select();
				
				$this->db->distinct();
				$this->db->from("($sub_sql) as ".$this->_protect_identifiers('maxresult').",inbox");
				$this->db->join('user_inbox','user_inbox.id_inbox=inbox.ID');
				if($is_global_folder == false  && $user_level != 'administrator'){
                                    $this->db->where('id_user', $user_id);
                                }
				$this->db->where('id_folder', $tmp_id_folder);
				$this->db->where('trash', $tmp_trash);
				
				$this->db->where($this->_protect_identifiers('ReceivingDateTime'), $this->_protect_identifiers('maxresult.maxdate'), FALSE);
				//$this->db->group_by('SenderNumber');
				$this->db->order_by('ReceivingDateTime', 'DESC');
			break;
			
			case 'outbox':
				$this->db->from('outbox');
				$this->db->select_max($this->_protect_identifiers('SendingDateTime'), $this->_protect_identifiers('maxdate'), FALSE);
				$this->db->join('user_outbox','outbox.ID=user_outbox.id_outbox');
				if($is_global_folder == false  && $user_level != 'administrator'){
                                    $this->db->where('id_user', $user_id);
                                }
                                $this->db->group_by('DestinationNumber');
				
				$sub_sql = $this->db->_compile_select();
				$this->db->_reset_select();
				
				$this->db->distinct();
				$this->db->from("($sub_sql) as ".$this->_protect_identifiers('maxresult').",outbox");
				$this->db->join('user_outbox','outbox.ID=user_outbox.id_outbox');
				if($is_global_folder == false  && $user_level != 'administrator'){
                                    $this->db->where('id_user', $user_id);
                                }
				$this->db->where($this->_protect_identifiers('SendingDateTime'), $this->_protect_identifiers('maxresult.maxdate'), FALSE);
				//$this->db->group_by('DestinationNumber');
				$this->db->order_by('SendingDateTime', 'DESC');
			break;
			
			case 'sentitems':                               
                                

				$this->db->from('sentitems');
				$this->db->select_max($this->_protect_identifiers('SendingDateTime'), $this->_protect_identifiers('maxdate'), FALSE);
				$this->db->join('user_sentitems','sentitems.ID=user_sentitems.id_sentitems');
				if($is_global_folder == false  && $user_level != 'administrator'){
                                    $this->db->where('id_user', $user_id);
                                }
                                $this->db->where('is_forward',0);                                
                                if(isset($options['broadcast'])){
                                    $this->db->distinct();
                                    $this->db->where('is_broadcast','1');                                
                                    $this->db->group_by('InsertIntoDB');
                                }
				$this->db->where('id_folder', $tmp_id_folder);
				$this->db->where('SequencePosition', '1');
				$this->db->group_by('DestinationNumber');
				
				$sub_sql = $this->db->_compile_select();
				$this->db->_reset_select();
				
				$this->db->distinct();
				$this->db->from("($sub_sql) as ".$this->_protect_identifiers('maxresult').",sentitems");
				$this->db->join('user_sentitems','sentitems.ID=user_sentitems.id_sentitems');
				if($is_global_folder == false  && $user_level != 'administrator'){
                                    $this->db->where('id_user', $user_id);
                                }
				$this->db->where('id_folder', $tmp_id_folder);
				$this->db->where('SequencePosition', '1');
				$this->db->where('trash', $tmp_trash);
				$this->db->where($this->_protect_identifiers('SendingDateTime'), $this->_protect_identifiers('maxresult.maxdate'), FALSE);
				//$this->db->group_by('DestinationNumber');
				$this->db->order_by('SendingDateTime', 'DESC');			
			break;
		}
				
		if(isset($options['limit']) && isset($options['offset'])) 
		{
			$this->db->limit($options['limit'], $options['offset']);
		}		
                return $this->db->get();
	}
        /*
         * Check is folder global, can be accessed by others folder owner
         * @access      public
         * @param       mixed
         * @return      boelan
         */
        function is_global_folder($id_folder){
            $this->db->select('is_global');
            $this->db->where('id_folder',$id_folder);
            $is_global = $this->db->get('user_folders')->row('is_global');
            if($is_global == '1')return true;
            return false;
        }
	// --------------------------------------------------------------------
	
	/**
	 * Move Messages
	 *
	 * Move messages from a folder to another folder
	 *
	 * @access	public   		 
	 * @param	mixed $options 
	 * Option: Values
	 * --------------
	 * type conversation, single
	 * current_folder int, source folder, use with conversation type
	 * id_folder int, destination folder, use with conversation and single type
	 * number string, use with conversation type
	 * folder inbox, sentitems, use with single type
	 * id_message, use with single type
	 * trash, bool
	 * 
	 * @return	object
	 */		 
	function move_messages($options = array())
	{
		// default values
    	$options = $this->_default(array('trash' => FALSE), $options);
    	$trash = ($options['trash']) ? '1':'0';
    			
		switch($options['type']) 
		{	
			case 'conversation':
			if(isset($options['id_folder']))$id_folder = $options['id_folder'];
                        else $id_folder = 1;
			$number = $options['number'];
					
			if($options['current_folder']=='') 
			{ 
				$inbox_folder=1;
				$sentitems_folder=3; 
			}
			else
			{
				$inbox_folder=$sentitems_folder=$options['current_folder'];
			}
									
			// proccess inbox
			$this->db->set('i.id_folder', $id_folder);
			$this->db->set('ui.trash', $trash);
			$this->db->where('i.id_folder', $inbox_folder);
			$this->db->where('i.SenderNumber', $number);
			$this->db->where($this->_protect_identifiers('i.ID'), $this->_protect_identifiers('ui.id_inbox'), FALSE);
			$update_inbox = $this->_protect_identifiers('inbox');
			$update_inbox_alias = $this->_protect_identifiers('i');
			$update_user_inbox = $this->_protect_identifiers('user_inbox');
			$update_user_inbox_alias = $this->_protect_identifiers('ui');			
			$this->db->update($update_inbox.' as '.$update_inbox_alias.', '.$update_user_inbox.' as '.$update_user_inbox_alias);
	
			// proccess sentitems
			$this->db->set('si.id_folder', $id_folder);
			$this->db->set('usi.trash', $trash);
			$this->db->where('si.id_folder', $sentitems_folder);
			$this->db->where('si.DestinationNumber', $number);
			$this->db->where($this->_protect_identifiers('si.ID'), $this->_protect_identifiers('usi.id_sentitems'), FALSE);
			$update_sentitems = $this->_protect_identifiers('sentitems');
			$update_sentitems_alias = $this->_protect_identifiers('si');
			$update_user_sentitems = $this->_protect_identifiers('user_sentitems');
			$update_user_sentitems_alias = $this->_protect_identifiers('usi');			
			$this->db->update($update_sentitems.' as '.$update_sentitems_alias.', '.$update_user_sentitems.' as '.$update_user_sentitems_alias);
			break;
				
			case 'single':
			$folder = $options['folder'];
			$id_folder = $options['id_folder'];
			$id_message = $options['id_message'];			
			$user_folder = "user_".$folder; // add user prefix
			$id_folder_field = "id_".$folder; // add id prefix


			foreach($id_message as $tmp):
				echo 'id_message = '.$tmp;
		 		$this->db->set('a.id_folder', $id_folder);
				$this->db->set('b.trash', $trash);
				$this->db->where('a.ID', $tmp);
				$update_id_folder_field = 'b.'.$id_folder_field;
				$this->db->where($this->_protect_identifiers('a.ID'), $this->_protect_identifiers($update_id_folder_field), FALSE);
				$update_folder = $this->_protect_identifiers($folder);
				$update_folder_alias = $this->_protect_identifiers('a');
				$update_user_folder = $this->_protect_identifiers($user_folder);
				$update_user_folder_alias = $this->_protect_identifiers('b');				
				$update = $this->db->update($update_folder.' as '.$update_folder_alias.', '.$update_user_folder.' as '.$update_user_folder_alias);
                                 
                                if($update)echo PHP_EOL."Success move to folder : $id_folder".PHP_EOL;
                                else echo 'gagal = '.mysql_error;
                                
			endforeach;
			break;
		}
	}
        
	// --------------------------------------------------------------------
	
	/**
	 * Delete Messages
	 *
	 * Delete messages, permanent or temporary.
	 *
	 * @access	public   		 
	 * @param	mixed $options 
	 * Option: Values
	 * --------------
	 * type conversation, single
	 * option permanent, temporary, outbox
	 * current_folder int, source folder, use with conversation type
	 * number string
	 * source inbox, outbox, sentitems
	 * id int, use with single type
	 * 
	 * @return	object
	 */	
	function delete_messages($options = array())
	{
		if(isset($options['id_user']))$user_id = $options['id_user'];
                else $user_id = $this->session->userdata('id_user');
		
		$type = $options['type'];
		$source = $options['source'];
		$option = $options['option'];

		if(isset($options['id'])) $tmp_id = $options['id'];		
		if(isset($options['number'])) $number = $options['number'];	
		if(isset($options['current_folder'])) $current_folder = $options['current_folder'];	
		
		$user_source = "user_".$source;
		$id_source = "id_".$source;	
							
		switch($type)
		{
			case 'conversation':
			if(!isset($options['current_folder'])) { $inbox_folder=1; $sentitems_folder=3; }
			else $inbox_folder=$sentitems_folder=$current_folder;			

			$trash = FALSE;
			switch($option)
			{
				case 'permanent':
				
				// if it's coming from trash
				if(isset($current_folder) && $current_folder=='5') $trash = TRUE;	
				
				// get inbox
				$param = array('id_folder' => $inbox_folder, 'number' => $number, 'trash' => $trash, 'uid' => $user_id);
				$inbox = $this->get_messages($param);
				
				foreach($inbox->result() as $tmp)
				{
					$this->db->where('ID', $tmp->id_inbox);
					$this->db->delete('inbox');
		
					$this->db->where('id_inbox', $tmp->id_inbox);
					$this->db->delete('user_inbox');					
				}
				
				// deprecated
				// inbox
				/*$inbox = "DELETE i, ui
						FROM inbox AS i
						LEFT JOIN user_inbox AS ui ON ui.id_inbox = i.ID
						WHERE i.SenderNumber = '".$number."' AND ui.trash='1'";
				$this->db->query($inbox);*/
				
				// get sentitems
				$param = array('id_folder' => $sentitems_folder, 'type' => 'sentitems', 'number' => $number, 'trash' => $trash, 'uid' => $user_id);
				$sentitems = $this->get_messages($param);
				
				foreach($sentitems->result() as $tmp)
				{
					$this->db->where('ID', $tmp->id_sentitems);
					$this->db->delete('sentitems');
		
					$this->db->where('id_sentitems', $tmp->id_sentitems);
					$this->db->delete('user_sentitems');					
				}				
				
				// sentitems
				/*$sentitems = "DELETE s, us
						FROM sentitems AS s
						LEFT JOIN user_sentitems AS us ON us.id_sentitems = s.ID
						WHERE s.DestinationNumber = '".$number."' AND us.trash='1'";
				$this->db->query($sentitems);*/
				break;	
				
				case 'temp':	
				// use move_messages function
				$param['type'] = 'conversation';
				$param['number'] = $number;
				$param['current_folder'] = $options['current_folder'];
				$param['id_folder'] = '5';
				$param['trash'] = TRUE;
				$this->move_messages($param);			
				break;
				
				case 'outbox':
				$tmp_sql = $this->get_messages(array('type' => 'outbox', 'number' => $number))->result_array();
				// looping all message
				foreach($tmp_sql as $tmp):
				//check multipart message
				$multipart = array('type' => 'outbox', 'option' => 'check', 'id_message' => $tmp['ID']);
				if($this->get_multipart($multipart)=='true')
				$this->db->delete('outbox_multipart', array('ID' => $tmp['ID']));
				
				$this->db->delete('outbox', array('ID' => $tmp['ID']));
				endforeach;		
				break;
			}
			break;		
			
			case 'single':
			switch($option)
			{				
				case 'permanent':
				foreach($tmp_id as $tmp):
					$this->db->delete("user_".$source, array('id_'.$source => $tmp));
					$this->db->delete($source, array('ID' => $tmp));				
				endforeach;
				break;	
				
				case 'temp':
				// use move_messages function
				$param['type'] = 'single';
				$param['id_message'] = $tmp_id;
				$param['folder'] = $source;
				$param['id_folder'] = '5';
				$param['trash'] = TRUE;
				$this->move_messages($param);
				break;
				
				case 'outbox':
				//check multipart message
				$multipart = array('type' => 'outbox', 'option' => 'check', 'id_message' => $tmp_id[0]);
				if($this->get_multipart($multipart)=='true')
				$this->db->delete('outbox_multipart', array('ID' => $tmp_id[0]));
				
				$this->db->delete('outbox', array('ID' => $tmp_id[0]));
				break;
			}		
			break;		
		}		
	}	
	
	// --------------------------------------------------------------------
	
	/**
	 * Get Multipart
	 *
	 * Get and check for multipart messages
	 *
	 * @access	public   		 
	 * @param	mixed
	 * 
	 * @return object
	 */	
	function get_multipart($param)
	{	
		// default values
    	$param = $this->_default(array('type' => 'inbox'), $param);
    	
		switch($param['option'])
		{
			case 'check':
				if($param['type']=='outbox')
				{
					$this->db->select('MultiPart');
					$this->db->where('ID', $param['id_message']);
					return $this->db->get('outbox')->row('MultiPart');					
				}
				else if($param['type']=='inbox')
				{
					$this->db->where('ID', $param['id_message']);
					return $this->db->get('inbox');	
				}
				else if($param['type']=='sentitems')
				{	
					$this->db->where('ID', $param['id_message']);
                                        $this->db->where('Status !=', 'SendingError');
					$this->db->where('SequencePosition >', 1);
					return $this->db->get('sentitems')->num_rows();
				}else if($param['type']=='sent')
				{
					$this->db->where('ID', $param['id_message']);
					return $this->db->get('sentitems');
				}
			break;
			
			case 'all':
				if($param['type']=='outbox') 
				{
					$this->db->where('ID', $param['id_message']);
					$this->db->order_by('SequencePosition');
					return $this->db->get('outbox_multipart');
				}
				else if($param['type']=='inbox')
				{
					$this->db->where('SenderNumber', $param['phone_number']);
					$this->db->like('UDH', $param['udh'], 'after');
					$this->db->not_like('UDH', '1', 'before');
					$this->db->order_by('UDH');
					return $this->db->get('inbox');	
				}
				else if($param['type']=='sentitems')
				{
					$this->db->where('ID', $param['id_message']);
                                        $this->db->where('Status !=', 'SendingError');
					$this->db->where('SequencePosition >', 1);	
					$this->db->order_by('SequencePosition');
					return $this->db->get('sentitems');				
				}
                                else if($param['type']=='sent')
				{
					$this->db->where('DestinationNumber', $param['phone_number']);
                                        $this->db->where('Status !=', 'SendingError');
                                        $this->db->where('id_folder', $param['id_folder']);
					$this->db->like('UDH', $param['udh']);
					$this->db->where('SequencePosition >', 1);
					$this->db->order_by('SequencePosition');
					return $this->db->get('sentitems');
				}
			break;
		}			
	}

	// --------------------------------------------------------------------
	
	/**
	 * Update readed inbox
	 *
	 * @access	public   		 
	 * @param	mixed
	 * 
	 * @return
	 */	
	function update_read($id)
	{
		$data = array ('readed' => 'true');
		$this->db->where('ID', $id);		
		$this->db->update('inbox', $data);
	}
			
	// --------------------------------------------------------------------
	
	/**
	 * Utility function used by the daemon and base controller
	 *
	 * @access	public   		 
	 * @param	mixed
	 * 
	 * @return
	 */		
	function insert_user_sentitems($id_message, $user_id)
	{
		$this->db->set('id_user', $user_id);
		$this->db->set('id_sentitems', $id_message);	
                $this->db->insert('user_sentitems');              
                
	}

	function get_user_outbox($user_id)
	{
		$this->db->where('id_user', $user_id);
		return $this->db->get("user_outbox");
	}		
        function get_all_user_outbox(){
                $this->db->select('*');
                return $this->db->get("user_outbox");
        }
	function delete_user_outbox($id_message)
	{
		$this->db->where('id_outbox', $id_message);
		$this->db->delete("user_outbox");
	}	

	// Update processed inbox
	function update_processed($id)
	{
		foreach($id as $tmp):
			$data = array ('Processed' => 'true');
			$this->db->where('ID', $tmp);		
			$this->db->update('inbox', $data);
		endforeach;
	}

	// Update ownership
	function update_owner($msg_id, $user_id)
	{
                $this->db->where('id_inbox',$msg_id);
                if($this->db->get('user_inbox')->num_rows() > 0){
                    $this->db->set('id_user',$user_id);
                    $this->db->where('id_inbox',$msg_id);
                    $this->db->update('user_inbox');
                }else{
                    $data = array ('id_user' => $user_id, 'id_inbox' => $msg_id);
                    $this->db->insert('user_inbox', $data);
                }
					
	}	
    
    //Save Canned Response
    function canned_response($name,$message, $action)
    {
        if($action == 'list')
        {
        	return $this->db->get_where('user_templates', array( 'id_user'=> $this->session->userdata('id_user') ));
        }
        else if($action == 'get')
        {
            $message = $this->db->get_where('user_templates', array('Name' => $name), 1, 0)->row('Message');
            echo $message;
        }
        else if($action == 'save')
        {
            $record = array('Name'=>$name, 'Message'=>$message, 'id_user'=> $this->session->userdata('id_user'));
             
            $query = $this->db->get_where('user_templates', array('Name'=> $name, 'id_user'=> $this->session->userdata('id_user')), 1, 0);
            if ($query->num_rows() == 0) {
              // A record does not exist, insert one.
              $query = $this->db->insert('user_templates', $record);
            } else {
              // A record does exist, update it.
              $query = $this->db->update('user_templates', $record, array('id_template'=>$query->row('id_template')));
            }
        }
        else if($action == 'delete')
        {
            $this->db->delete('user_templates', array('Name' => $name));
        }
        else
            die("Invalid Option");
    }

    /*
     * Reply message in folder sent -- Additional function
     */
    function update_folder_message_sent($id_sentitems){
        //$sentitem = $this->Message_model->get_messages(array('id_message' => $id_sentitems, 'type' => 'sentitems'))->result_array();
        //print_r($sentitem);
        
            // get sentitems
            $param = array('id_message' => $id_sentitems, 'type' => 'sentitems');
            $sentitems = $this->get_messages($param);

            foreach($sentitems->result() as $tmp){

                $this->db->select('ID');
                $this->db->select('id_folder');
                $this->db->from('folder_message_sent');
                $this->db->where('OutboxInsertIntoDB',$tmp->InsertIntoDB);
                $this->db->where('DestinationNumber',$tmp->DestinationNumber);
                //$row = $this->db->get('folder_message_sent')->num_rows();
                //$folder_message_sent = $this->db->get('folder_message_sent');
                $result = $this->db->get();
                $row = $result->num_rows();
                $folder_message_sents = $result->result_array();

                if($row>0){
                    $this->db->set('id_sentitems',$id_sentitems);
                    $this->db->where('OutboxInsertIntoDB',$tmp->InsertIntoDB);
                    $this->db->where('DestinationNumber',$tmp->DestinationNumber);
                    $this->db->update('folder_message_sent');                    
                    
                    foreach($folder_message_sents as $key=>$val){
                        $this->db->set('id_folder',$folder_message_sents[$key]['id_folder']);
                        $this->db->where('ID',$id_sentitems);
                        $this->db->update('sentitems');
                    }
                    
                }
            }

        
    }
    function update_message_folder($id_message,$id_folder=3){        
            $this->db->set('id_folder',$id_folder);
            $this->db->where('ID',$id_message);
            $this->db->update('sentitems');        
    }
    function update_message_broadcast($id_message,$is_broadcast=0){
        
            $this->db->set('is_broadcast',$is_broadcast);
            $this->db->where('ID',$id_message);
            $this->db->update('sentitems');        
    }
    function update_message_forward($id_message,$is_forward=0){
        
            $this->db->set('is_forward',$is_forward);
            $this->db->where('ID',$id_message);
            $this->db->update('sentitems');        
    }
    function get_message_broadcast(){
        $options = array(
            'type'=>'sentitems',
            'broadcast'=>'true',
            'order_by'=>'SendingDateTime'
        );
        $messages = $this->get_messages($options)->result_array();
        return $messages;
    }
    /*
     * Set Message Published
     */
    function set_message_published($id_message){
        $this->db->set('published','true');
        $this->db->where('ID',$id_message);
        return $this->db->update('inbox');
    }
    /*
     * Update inbox message
     * from filterization
     */
    function update_message_body($id_message,$message_body){
        $this->db->set('TextDecoded',$message_body);
        $this->db->where('ID',$id_message);
        $this->db->update('inbox');
    }
    
}

/* End of file messages_model.php */
/* Location: ./application/models/messages_model.php */
