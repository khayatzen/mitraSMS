<?php
/**
 * Microblog
 * Addon Controller to handle SMS Microbloging
 *
 * @package		Kalkun
 * @author		Khayatzen
 * @license		http://kalkun.sourceforge.net/license.php
 * @link		http://khayat.wordpress.com
 */

// ------------------------------------------------------------------------

/**
 * Microblog Class
 *
 * @package	Kalkun
 * @subpackage	Base
 * @category	Controllers
 */
class Microblog extends MY_Controller {

    var $messages = array();
    var $servernumber = 'Borneo Climate';
    var $limit_msg = 15;
    var $limit_broadcast = 15;    
    
	/**
	 * Constructor
	 *
	 * @access	public
	 */
	function Microblog()
	{
                parse_str($_SERVER['QUERY_STRING'],$_GET);
		parent::MY_Controller();
                $this->lang->load('kalkun', 'bahasa');
                
                $this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		$this->load->library('tank_auth');
		$this->lang->load('tank_auth');                
	}
        function microblogging(){
                $data['title']  = 'SMS Microblogging';
                $data['page']   = 'microblog';
                $data['content']= 'microblogging';   
                $this->load->view('front/template',$data);
        }
              
        function get_broadcast_messages($callback=NULL){
            $data['messages'] = array();
            $_sentitems = array();
            if($_POST){                 
                $params = array(
                    'type'=>'sentitems',
                    'broadcast'=>'true',
                    'order_by'=>'SendingDateTime',
                    'limit'=>$this->limit_broadcast
                );
                $params['stream_type'] = $this->input->post('stream_type');
                $params['stream_date'] = $this->input->post('stream_date');
                
                $row = $this->Message_model->get_messages($params)->num_rows();
                
                    if($row>0){
                        $_sentitems = $this->Message_model->get_messages($params)->result_array();

                        // add global date for sorting
                        foreach($_sentitems as $key=>$tmp):
                            $_sentitems[$key]['globaldate'] = $_sentitems[$key]['SendingDateTime'];
                            $_sentitems[$key]['nice_date'] = nice_date($_sentitems[$key]['SendingDateTime']);
                            $_sentitems[$key]['number'] = $this->servernumber;
                            $_sentitems[$key]['source'] = 'sentitems';                            

                            //cek multipart message                        
                            $multipart = array('type' => 'sent', 'option' => 'check', 'id_message' => $_sentitems[$key]['ID']);
                            $tmp_check = $this->Message_model->get_multipart($multipart);
                            if($tmp_check->row('UDH')!='')
                            {				
                                    $multipart = array('option' => 'all','type'=>'sent','udh' => substr($tmp_check->row('UDH'),0,8));
                                    $multipart['phone_number'] = $tmp_check->row('DestinationNumber');
                                    $multipart['id_folder'] = $tmp_check->row('id_folder');
                                    foreach($this->Message_model->get_multipart($multipart)->result() as $part):
                                        $_sentitems[$key]['TextDecoded'] .= $part->TextDecoded;
                                    endforeach;                                
                            }                        

                        endforeach;
                        foreach($_sentitems as $key=>$tmp):
                            $data['messages'][]=$tmp; 
                        endforeach;                        
                        // sort data
                        $sort_option = $this->Kalkun_model->get_setting(1)->row('conversation_sort');
                        usort($data['messages'], "compare_date_".$sort_option);
                        
                        echo $callback.'('.json_encode($data).')';
                    }else{
                        echo $callback.'('.json_encode($data).')';
                    }
            }

        }
	function get_custom_messages($callback=NULL){
            if($_POST){ 
                $param['stream_type'] = $this->input->post('stream_type');
                $param['stream_date'] = $this->input->post('stream_date');
                $data = $this->_get_custom_messages($param);
                echo $callback.'('.json_encode($data).')';
                //print_r($data['messages']);
            }
        }
        function get_incoming_messages($callback=NULL){
            $data['messages'] = array();
            $_inbox = array();
            if($_POST){                 
                $params = array(
                    'type'=>'inbox',
                    'order_by'=>'ReceivingDateTime',
                    'limit'=>'10'
                );
                $params['stream_type'] = $this->input->post('stream_type');
                $params['stream_date'] = $this->input->post('stream_date');
                //$params['published'] = true;
                $row = $this->Message_model->get_messages($params)->num_rows();                
                    if($row>0){
                        $_inbox = $this->Message_model->get_messages($params)->result_array();
                        // add global date for sorting
                        foreach($_inbox as $key=>$tmp):
                            $_inbox[$key]['globaldate'] = $_inbox[$key]['ReceivingDateTime'];
                            $_inbox[$key]['nice_date'] = nice_date($_inbox[$key]['ReceivingDateTime']);
                            $phonebook = $this->Phonebook_model->get_phonebook(array('option'=>'bynumber','number'=>$_inbox[$key]['SenderNumber']));
                            if($phonebook->num_rows()>0)$_inbox[$key]['number'] = $phonebook->row('Name');
                            else $_inbox[$key]['number']     = substr_replace($_inbox[$key]['SenderNumber'],'xxx',-3);
                            $_inbox[$key]['source']     = 'inbox';
                            
                            //cek multipart message
                            $multipart = array('type' => 'inbox', 'option' => 'check', 'id_message' => $_inbox[$key]['ID']);
                            $tmp_check = $this->Message_model->get_multipart($multipart);


                            if($tmp_check->row('UDH')!='')
                            {                            
                                    $full_message = '';
                                    $multipart = array('option' => 'all', 'udh' => substr($tmp_check->row('UDH'),0,8));
                                    $multipart['phone_number'] = $tmp_check->row('SenderNumber');
                                    foreach($this->Message_model->get_multipart($multipart)->result() as $part):
                                        $_inbox[$key]['TextDecoded'] .= $part->TextDecoded;                                    
                                    endforeach;
                            }

                        endforeach;
                        foreach($_inbox as $key=>$tmp):
                            $data['messages'][]=$tmp; 
                        endforeach;
                        // sort data
                        $sort_option = $this->Kalkun_model->get_setting(1)->row('conversation_sort');
                        usort($data['messages'], "compare_date_".$sort_option);
                        
                        echo $callback.'('.json_encode($data).')';
                    }
                    else{
                        echo $callback.'('.json_encode($data).')';
                    }
            }
            

        }
        function get_microblog_conversation($callback=NULL){
                $param['stream_type'] = $this->input->post('stream_type');
                $param['stream_date'] = $this->input->post('stream_date');
                
                $custom_messages = $this->_get_custom_messages($param);
                $basic_messages = $this->_get_basic_messages($param);
                $conversation = array_merge_recursive($custom_messages,$basic_messages);
                usort($conversation['messages'], "compare_date_desc");
                foreach($conversation['messages'] AS $key=>$tmp){
                    $conversation['messages'][$key]['nice_date'] = nice_date($conversation['messages'][$key]['globaldate']);
                }
                //print_r($conversation);
                echo $callback.'('.json_encode($conversation).')';
                
        }
        
        function micropost(){
            if ($this->tank_auth->is_logged_in()) {
                $this->load->view('front/includes/header');
                print_r($this->tank_auth->get_user_profiles());
                $this->load->view('front/includes/footer');
            }else{
                //echo $this->uri->uri_string();
                $redirect = urlencode($this->uri->uri_string());
                redirect('auth/login/?redirect='.$redirect);
            }
        }
        function micropost_map(){
            
        }
        function stream_map(){
            $data['title']  = 'Peta SMS';
            $data['page']   = 'microblog';
            $data['content']= 'stream_map';   
            $this->load->view('front/template',$data);
        }
        
        function _get_custom_messages($options=array()){
             
                $param['stream_type'] = $options['stream_type'];
                $param['stream_date'] = $options['stream_date'];
                $folders = $this->Microblog_model->getFolders(1)->result_array();            
                $data = array();
                $inbox = array();
                $sentitems = array();
                foreach($folders as $k=>$v):
                    $id_folder = $folders[$k]['id_folder'];
                    $folder = $folders[$k]['name'];


                    $param['type'] = 'inbox';
                    $param['id_folder'] = $id_folder;
                    $param['limit'] = $this->limit_msg;
                    $param['offset'] = '0';
                    $param['published'] = true;
                    $param['order_by'] = 'ReceivingDateTime';
                    $param['order_by_type'] = 'desc';
                    
                    $row = $this->Message_model->get_messages($param)->num_rows();
                    if($row>0){
                        $_inbox = $this->Message_model->get_messages($param)->result_array();
                        // add global date for sorting
                        foreach($_inbox as $key=>$tmp):
                            $_inbox[$key]['globaldate'] = $_inbox[$key]['ReceivingDateTime'];
                            $phonebook = $this->Phonebook_model->get_phonebook(array('id_user'=>'1','option'=>'bynumber','number'=>$_inbox[$key]['SenderNumber']));
                            if($phonebook->num_rows()>0)$_inbox[$key]['number'] = $phonebook->row('Name');
                            else $_inbox[$key]['number']     = substr_replace($_inbox[$key]['SenderNumber'],'xxx',-3);
                            $_inbox[$key]['source']     = 'inbox';
                            $_inbox[$key]['folder']     = $folder;
                            //cek multipart message
                            $multipart = array('type' => 'inbox', 'option' => 'check', 'id_message' => $_inbox[$key]['ID']);
                            $tmp_check = $this->Message_model->get_multipart($multipart);


                            if($tmp_check->row('UDH')!='')
                            {                            
                                    $full_message = '';
                                    $multipart = array('option' => 'all', 'udh' => substr($tmp_check->row('UDH'),0,8));
                                    $multipart['phone_number'] = $tmp_check->row('SenderNumber');
                                    foreach($this->Message_model->get_multipart($multipart)->result() as $part):
                                        $_inbox[$key]['TextDecoded'] .= $part->TextDecoded;                                    
                                    endforeach;
                            }

                        endforeach;
                        foreach($_inbox as $tmp):
                            $inbox[] = $tmp;
                        endforeach;
                    }


                    $param['type'] = 'sentitems';
                    $param['id_folder'] = $id_folder;
                    $param['limit'] = $this->limit_msg;
                    $param['offset'] = '0';
                    $param['hide_forwarded_message'] = true;
                    $param['order_by'] = 'SendingDateTime';
                    $param['order_by_type'] = 'desc';
                    $param['broadcast'] = false;
                    $row = $this->Message_model->get_messages($param)->num_rows();
                    if($row>0){
                        $_sentitems = $this->Message_model->get_messages($param)->result_array();

                        // add global date for sorting
                        foreach($_sentitems as $key=>$tmp):
                            $_sentitems[$key]['globaldate'] = $_sentitems[$key]['SendingDateTime'];
                            $_sentitems[$key]['number'] = $this->servernumber;
                            $_sentitems[$key]['source'] = 'sentitems';
                            $_sentitems[$key]['folder'] = $folder;

                            //cek multipart message                        
                            $multipart = array('type' => 'sent', 'option' => 'check', 'id_message' => $_sentitems[$key]['ID']);
                            $tmp_check = $this->Message_model->get_multipart($multipart);
                            if($tmp_check->row('UDH')!='')
                            {				
                                    $multipart = array('option' => 'all','type'=>'sent','udh' => substr($tmp_check->row('UDH'),0,8));
                                    $multipart['phone_number'] = $tmp_check->row('DestinationNumber');
                                    $multipart['id_folder'] = $tmp_check->row('id_folder');
                                    foreach($this->Message_model->get_multipart($multipart)->result() as $part):
                                        $_sentitems[$key]['TextDecoded'] .= $part->TextDecoded;
                                    endforeach;                                
                            }                        

                        endforeach;
                        foreach($_sentitems as $key=>$tmp):
                            $sentitems[] = $_sentitems[$key];
                        endforeach;
                    }

                endforeach;
                $data['messages']=$inbox;
                foreach($sentitems AS $tmp):
                   $data['messages'][]=$tmp;               
                endforeach;
                // sort data
                $sort_option = 'desc';
                usort($data['messages'], "compare_date_".$sort_option);
                foreach($data['messages'] AS $key=>$tmp){
                    $data['messages'][$key]['nice_date'] = nice_date($data['messages'][$key]['globaldate']);
                }
                return $data;               
            
        }
        function _get_basic_messages($options=array()){
            
                $param['stream_type'] = $options['stream_type'];
                $param['stream_date'] = $options['stream_date'];
                $data = array();
                $inbox = array();
                $sentitems = array();
                
                $param['type'] = 'inbox';
                $param['published'] = true;
                $param['limit'] = $this->limit_msg;
                $param['offset'] = '0';
                $param['order_by'] = 'ReceivingDateTime';
                $param['order_by_type'] = 'desc';
                
                $row = $this->Message_model->get_messages($param)->num_rows();
                if($row>0){
                    $_inbox = $this->Message_model->get_messages($param)->result_array();
                    // add global date for sorting
                    foreach($_inbox as $key=>$tmp):
                        $_inbox[$key]['globaldate'] = $_inbox[$key]['ReceivingDateTime'];
                        $phonebook = $this->Phonebook_model->get_phonebook(array('id_user'=>'1','option'=>'bynumber','number'=>$_inbox[$key]['SenderNumber']));
                        if($phonebook->num_rows()>0)$_inbox[$key]['number'] = $phonebook->row('Name');
                        else $_inbox[$key]['number']     = substr_replace($_inbox[$key]['SenderNumber'],'xxx',-3);
                        $_inbox[$key]['source']     = 'inbox';
                        
                        //cek multipart message
                        $multipart = array('type' => 'inbox', 'option' => 'check', 'id_message' => $_inbox[$key]['ID']);
                        $tmp_check = $this->Message_model->get_multipart($multipart);


                        if($tmp_check->row('UDH')!='')
                        {                            
                                $full_message = '';
                                $multipart = array('option' => 'all', 'udh' => substr($tmp_check->row('UDH'),0,8));
                                $multipart['phone_number'] = $tmp_check->row('SenderNumber');
                                foreach($this->Message_model->get_multipart($multipart)->result() as $part):
                                    $_inbox[$key]['TextDecoded'] .= $part->TextDecoded;                                    
                                endforeach;
                        }

                    endforeach;
                    foreach($_inbox as $tmp):
                        $inbox[] = $tmp;
                    endforeach;
                }


                $param['type'] = 'sentitems';
                $param['limit'] = $this->limit_msg;
                $param['offset'] = '0';
                $param['order_by'] = 'SendingDateTime';
                $param['order_by_type'] = 'desc';
                $param['hide_forwarded_message'] = true;
                $param['broadcast'] = false;
                $row = $this->Message_model->get_messages($param)->num_rows();
                if($row>0){
                    $_sentitems = $this->Message_model->get_messages($param)->result_array();

                    // add global date for sorting
                    foreach($_sentitems as $key=>$tmp):
                        $_sentitems[$key]['globaldate'] = $_sentitems[$key]['SendingDateTime'];
                        $_sentitems[$key]['number'] = $this->servernumber;
                        $_sentitems[$key]['source'] = 'sentitems';                        

                        //cek multipart message                        
                        $multipart = array('type' => 'sent', 'option' => 'check', 'id_message' => $_sentitems[$key]['ID']);
                        $tmp_check = $this->Message_model->get_multipart($multipart);
                        if($tmp_check->row('UDH')!='')
                        {				
                                $multipart = array('option' => 'all','type'=>'sent','udh' => substr($tmp_check->row('UDH'),0,8));
                                $multipart['phone_number'] = $tmp_check->row('DestinationNumber');
                                $multipart['id_folder'] = $tmp_check->row('id_folder');
                                foreach($this->Message_model->get_multipart($multipart)->result() as $part):
                                    $_sentitems[$key]['TextDecoded'] .= $part->TextDecoded;
                                endforeach;                                
                        }                        

                    endforeach;
                    foreach($_sentitems as $key=>$tmp):
                        $sentitems[] = $_sentitems[$key];
                    endforeach;
                }

                $data['messages']=$inbox;
                foreach($sentitems AS $tmp):
                   $data['messages'][]=$tmp;               
                endforeach;
                // sort data
                $sort_option = 'desc';
                usort($data['messages'], "compare_date_".$sort_option);
                foreach($data['messages'] AS $key=>$tmp){
                    $data['messages'][$key]['nice_date'] = nice_date($data['messages'][$key]['globaldate']);
                }
                return $data;
            
        }
        // --------------------------------------------------------------------
	
	/**
	 * Conversation
	 *
	 * List messages on conversation (based on phone number)
	 *
	 * @access	public
	 */		
	function conversation($source=NULL, $type=NULL, $number=NULL, $id_folder=NULL, $return=false, $callback=NULL)
	{
		// Pagination
//		$this->load->library('pagination');
//		$config['per_page'] = 15;		
//		$config['cur_tag_open'] = '<span id="current">';
//		$config['cur_tag_close'] = '</span>';
		
		if($source=='folder' && $type!='outbox' && $type!='phonebook'  ) 
		{
			$data['main'] = 'main/messages/index';
			$param['type'] = 'inbox';
			$param['number'] = trim($number);
                        
//			$config['base_url'] = site_url('/messages/conversation/folder/'.$type.'/'.$number);
//			$config['total_rows'] = $this->Message_model->get_messages($param)->num_rows();
//			$config['uri_segment'] = 6;			
//			$this->pagination->initialize($config); 
//			
//			$param['limit'] = $config['per_page'];
//			$param['offset'] = $this->uri->segment(6,0);
                        $param['order_by'] = 'ReceivingDateTime';
                        $param['order_by_type'] = 'desc';
                        $param['published'] = true;
			$inbox = $this->Message_model->get_messages($param)->result_array();


			// add global date for sorting
                        foreach($inbox as $key=>$tmp):
                            $inbox[$key]['globaldate'] = $inbox[$key]['ReceivingDateTime'];
                            $phonebook = $this->Phonebook_model->get_pbk($inbox[$key]['SenderNumber']);
                            //print_r($phonebook);
                            if($phonebook->num_rows()>0)$inbox[$key]['number'] = $phonebook->row('Name');
                            else $inbox[$key]['number']     = substr_replace($inbox[$key]['SenderNumber'],'xxx',-3);
                            $inbox[$key]['source']     = 'inbox';
                            $inbox[$key]['nicedate']   = nice_date($inbox[$key]['ReceivingDateTime']);
                            $inbox[$key]['id_folder']  = $id_folder;
                            //cek multipart message
                            $multipart = array('type' => 'inbox', 'option' => 'check', 'id_message' => $inbox[$key]['ID']);
                            $tmp_check = $this->Message_model->get_multipart($multipart);


                            if($tmp_check->row('UDH')!='')
                            {                            
                                    $full_message = '';
                                    $multipart = array('option' => 'all', 'udh' => substr($tmp_check->row('UDH'),0,8));
                                    $multipart['phone_number'] = $tmp_check->row('SenderNumber');
                                    foreach($this->Message_model->get_multipart($multipart)->result() as $part):
                                        $inbox[$key]['TextDecoded'] .= $part->TextDecoded;                                    
                                    endforeach;
                            }
                        endforeach;
                        
			$param['type'] = 'sentitems';
			$param['number'] = trim($number);
                        $param['order_by'] = 'SendingDateTime';
                        $param['order_by_type'] = 'desc';
			$sentitems = $this->Message_model->get_messages($param)->result_array();
                        
                        // add global date for sorting
                        foreach($sentitems as $key=>$tmp):
                            $sentitems[$key]['globaldate'] = $sentitems[$key]['SendingDateTime'];
                            $sentitems[$key]['number'] = $this->servernumber;
                            $sentitems[$key]['source'] = 'sentitems';
                            $sentitems[$key]['id_folder'] = $id_folder;
                            $sentitems[$key]['nicedate']   = nice_date($sentitems[$key]['SendingDateTime']);
                            //cek multipart message                        
                            $multipart = array('type' => 'sent', 'option' => 'check', 'id_message' => $sentitems[$key]['ID']);
                            $tmp_check = $this->Message_model->get_multipart($multipart);
                            if($tmp_check->row('UDH')!='')
                            {				
                                    $multipart = array('option' => 'all','type'=>'sent','udh' => substr($tmp_check->row('UDH'),0,8));
                                    $multipart['phone_number'] = $tmp_check->row('DestinationNumber');
                                    $multipart['id_folder'] = $tmp_check->row('id_folder');
                                    foreach($this->Message_model->get_multipart($multipart)->result() as $part):
                                        $sentitems[$key]['TextDecoded'] .= $part->TextDecoded;
                                    endforeach;                                
                            }                        

                        endforeach;
                        
			$data['messages'] = $inbox;
			
			// merge inbox and sentitems
			foreach($sentitems as $tmp):
			$data['messages'][] = $tmp;
			endforeach;
			
			// sort data
			$sort_option = 'desc';
			usort($data['messages'], "compare_date_".$sort_option);
			if($return)return $data['messages'];
                        if(is_ajax())
                        {
                            $this->load->view('main/messages/conversation', $data);
                        }
                        else{                            
                            $this->load->view('main/layout', $data);
                        }	
		}
		else if($source=='folder' && $type=='outbox')
		{
			$data['main'] = 'main/messages/index';
			$param['type'] = 'outbox';
			$param['number'] = trim($number);

			$config['base_url'] = site_url('/messages/conversation/folder/'.$type.'/'.$number);
			$config['total_rows'] = $this->Message_model->get_messages($param)->num_rows();
			$config['uri_segment'] = 6;			
			$this->pagination->initialize($config); 
			
			$param['limit'] = $config['per_page'];
			$param['offset'] = $this->uri->segment(6,0);
                        $param['order_by'] = 'SendingDateTime';	
                        $param['order_by_type'] = 'desc';		
			$outbox = $this->Message_model->get_messages($param)->result_array();	
			
			foreach($outbox as $key=>$tmp):
			$outbox[$key]['source'] = 'outbox';
			endforeach;	
			$data['messages'] = $outbox;
			if($return)return $data['messages'];
			if(is_ajax())
                        {
                            if(isset($param['json']))echo json_encode($data);
                            else $this->load->view('main/messages/conversation', $data);
                        }
                        else{
                          $this->load->view('main/layout', $data);
                        }							
		}
		else if($source=='my_folder' ) // my folder
		{
			$data['main'] = 'main/messages/index';
			$param['type'] = 'inbox';
			$param['id_folder'] = $id_folder;
			$param['number'] = trim($number);

//			$config['base_url'] = site_url('/messages/conversation/my_folder/'.$type.'/'.$number.'/'.$id_folder);
//			$config['total_rows'] = $this->Message_model->get_messages($param)->num_rows();
//			$config['uri_segment'] = 7;			
//			$this->pagination->initialize($config); 
			
//			$param['limit'] = $config['per_page'];
//			$param['offset'] = $this->uri->segment(7,0);
                        $param['order_by'] = 'ReceivingDateTime';
                        $param['order_by_type'] = 'desc';
			$inbox = $this->Message_model->get_messages($param)->result_array();	
			
			// add global date for sorting
//			foreach($inbox as $key=>$tmp):
//			$inbox[$key]['globaldate'] = $inbox[$key]['ReceivingDateTime'];
//			$inbox[$key]['source'] = 'inbox';
//			endforeach;
                        // add global date for sorting
                        foreach($inbox as $key=>$tmp):
                            $inbox[$key]['globaldate'] = $inbox[$key]['ReceivingDateTime'];
                            $phonebook = $this->Phonebook_model->get_pbk($inbox[$key]['SenderNumber']);
                            if($phonebook->num_rows()>0)$inbox[$key]['number'] = $phonebook->row('Name');
                            else $inbox[$key]['number']     = substr_replace($inbox[$key]['SenderNumber'],'xxx',-3);
                            $inbox[$key]['source']     = 'inbox';
                            $inbox[$key]['nicedate']   = nice_date($inbox[$key]['ReceivingDateTime']);
                            $inbox[$key]['id_folder']  = $id_folder;
                            //cek multipart message
                            $multipart = array('type' => 'inbox', 'option' => 'check', 'id_message' => $inbox[$key]['ID']);
                            $tmp_check = $this->Message_model->get_multipart($multipart);


                            if($tmp_check->row('UDH')!='')
                            {                            
                                    $full_message = '';
                                    $multipart = array('option' => 'all', 'udh' => substr($tmp_check->row('UDH'),0,8));
                                    $multipart['phone_number'] = $tmp_check->row('SenderNumber');
                                    foreach($this->Message_model->get_multipart($multipart)->result() as $part):
                                        $inbox[$key]['TextDecoded'] .= $part->TextDecoded;                                    
                                    endforeach;
                            }
                        endforeach;    
			$param['type'] = 'sentitems';
			$param['id_folder'] = $id_folder;
			$param['number'] = trim($number);	
                        $param['order_by'] = 'SendingDateTime';	
                        $param['order_by_type'] = 'desc';	
			$sentitems = $this->Message_model->get_messages($param)->result_array();							

			// add global date for sorting
//			foreach($sentitems as $key=>$tmp):
//			$sentitems[$key]['globaldate'] = $sentitems[$key]['SendingDateTime'];
//			$sentitems[$key]['source'] = 'sentitems';
//			endforeach;
			// add global date for sorting
                        foreach($sentitems as $key=>$tmp):
                            $sentitems[$key]['globaldate'] = $sentitems[$key]['SendingDateTime'];
                            $sentitems[$key]['number'] = $this->servernumber;
                            $sentitems[$key]['source'] = 'sentitems';
                            $sentitems[$key]['id_folder'] = $id_folder;
                            $sentitems[$key]['nicedate']   = nice_date($sentitems[$key]['SendingDateTime']);
                            //cek multipart message                        
                            $multipart = array('type' => 'sent', 'option' => 'check', 'id_message' => $sentitems[$key]['ID']);
                            $tmp_check = $this->Message_model->get_multipart($multipart);
                            if($tmp_check->row('UDH')!='')
                            {				
                                    $multipart = array('option' => 'all','type'=>'sent','udh' => substr($tmp_check->row('UDH'),0,8));
                                    $multipart['phone_number'] = $tmp_check->row('DestinationNumber');
                                    $multipart['id_folder'] = $tmp_check->row('id_folder');
                                    foreach($this->Message_model->get_multipart($multipart)->result() as $part):
                                        $sentitems[$key]['TextDecoded'] .= $part->TextDecoded;
                                    endforeach;                                
                            }                        

                        endforeach;
			$data['messages'] = $inbox;
			
			// merge inbox and sentitems
			foreach($sentitems as $tmp):
			$data['messages'][] = $tmp;
			endforeach;		
			
			// sort data
			$sort_option = 'desc';
			usort($data['messages'], "compare_date_".$sort_option);
			if($return)return $data['messages'];
			if(is_ajax())
                        {
                            if(isset($param['json']))echo json_encode($data);
                            else $this->load->view('main/messages/conversation', $data);
                        }
                        else{
                            $this->load->view('main/layout', $data);
                        }				
		}
                else //all 
                {

                  $data['main'] = 'main/messages/index';
                  $param['number'] = $number;

//                  $config['per_page'] = 15;		
//                  $config['cur_tag_open'] = '<span id="current">';
//                  $config['cur_tag_close'] = '</span>';
//                  $config['base_url'] = site_url('/messages/conversation/folder/'.$type.'/'.$number);
//                  $config['total_rows'] = $this->Message_model->search_messages($param)->total_rows;
//                  $config['uri_segment'] = 6;			
//                  $this->pagination->initialize($config);
//                  $param['limit'] = $config['per_page'];
//                  $param['offset'] = $this->uri->segment(6,0); 
                  
                  $data['messages'] = $this->Message_model->search_messages($param)->messages;
                  
                  if($return)return $data['messages'];                  
                  if(is_ajax())
                  {
                      if(isset($param['json']))echo json_encode($data);                      
                      else $this->load->view('main/messages/conversation', $data);
                  }
                  else{
                      $this->load->view('main/layout', $data);
                           
                  }		
               }
	}
        /*
         * Display Microblog Base Village
         */
        function village_microblog($village_id=NULL, $paging=0,$callback=NULL){
            $phonebooks = $this->Phonebook_model->get_village_phonebook($village_id)->result();
            $messages = array();
            foreach($phonebooks AS $pbk){
                $pull_messages = array();
                $regular_message = $this->conversation('folder','inbox',$pbk->Number,NULL,true);
                $folder_message = $this->conversation('my_folder','inbox',$pbk->Number,6,true);

                $pull_messages = $regular_message;
                foreach($folder_message as $tmp):
			$pull_messages[] = $tmp;
		endforeach;
                //print_r($pull_messages);
                if(count($messages) <= 0 && count($pull_messages) > 0){   
                    //echo 'first : ';                    
                    $messages = $pull_messages; 
                    //print_r($messages);
                }else if(count($messages) > 0 && count($pull_messages) > 0){
                    //echo 'next : '; 
                    foreach($pull_messages AS $part){
                        $messages[] = $part;
                    }                    
                    //print_r($messages);
                }
                //$messages = $pull_messages;
            }
            
            //sort by date
            usort($messages, "compare_date_desc");
                        
            $village = $this->Phonebook_model->get_villages(array('village_id'=>$village_id));
            $data['village_name'] = $village->row('village_name');
            $subdistrict = $this->Phonebook_model->get_subdistricts(array('subdistrict_id'=>$village->row('subdistrict_id')));
            $data['subdistrict_name'] = $subdistrict->row('subdistrict_name');
            $district = $this->Phonebook_model->get_districts(array('district_id'=>$subdistrict->row('district_id')));
            $data['district_name'] = $district->row('district_name');
            //print_r($messages);
            $data['messages'] = $messages;
            $data['rows'] = count($messages);
            $microblog = array();
            $microblog['messages'] = $this->load->view('front/main/microblog/village_stream',$data,true);
            $microblog['rows'] = count($messages);
            $microblog['village'] = $this->Phonebook_model->get_villages(array('village_id'=>$village_id))->result_array();
            echo json_encode($microblog);            
        }
	
        
}//end Class
/* End of file microblog.php */
/* Location: ./application/controllers/microblog.php */

?>
