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
 * Kalkun Class
 *
 * @package		Kalkun
 * @subpackage	Base
 * @category	Controllers
 */
class Admin extends MY_Controller {

	/**
	 * Constructor
	 *
	 * @access	public
	 */	
	function Admin()
	{
		parent::MY_Controller();	
	}		
		
	// --------------------------------------------------------------------
	
	/**
	 * Index/Dashboard
	 *
	 * Display dashboard page
	 *
	 * @access	public   		 
	 */		
	function index() 
	{
            if(!$this->tank_auth->is_logged_in()) redirect('login');
            else if($this->tank_auth->get_user_profiles()->row('level') != 'administrator' && $this->tank_auth->get_user_profiles()->row('level') != 'author')redirect('');
		// language
		$lang = $this->Kalkun_model->get_setting()->row('language');
                $this->lang->load('kalkun', $lang);
			
		// Message routine
		$this->_message_routine();
                
		$data['main'] = 'main/dashboard/home';
		$data['title'] = 'Dashboard';
                $data['data_url'] = site_url('admin/get_statistic');
                if($this->config->item('disable_outgoing'))
                {
                  $data['alerts'][] = "<div class=\"warning\">Outgoing SMS Disabled. Contact System Administrator</div>";
                }
                $this->load->view('main/layout', $data);            
	}

	// --------------------------------------------------------------------
	
	/**
	 * About
	 *
	 * Display about page
	 *
	 * @access	public   		 
	 */
	function about()
	{
		$data['main'] = 'main/about';
		$this->load->view('main/layout', $data);		
	}	

	// --------------------------------------------------------------------
	
	/**
	 * Get Statistic
	 *
	 * Get statistic data that used to render the graph
	 *
	 * @access	public   		 
	 */	
	function get_statistic()
	{
		// generate 7 data points
		for ($i=0; $i<=7; $i++)
		{
		    $x[] = mktime(0, 0, 0, date("m"), date("d")-$i, date('Y'));	    
		    $param['sms_date'] = date('Y-m-d', mktime(0,0,0,date("m"),date("d")-$i,date("Y")));
		    $param['user_id'] = $this->session->userdata('id_user');		    
		    $y[] = $this->Kalkun_model->get_sms_used('date', $param);
		}
		$this->_render_statistic($x, $y, 'bar');
	}
	
	function _render_statistic($x = array(), $y = array(), $type='bar')
	{
		$this->load->helper('date');
		$this->load->library('OpenFlashChartLib', NULL, 'OFCL');
		$data_1 = array();
		$data_2 = array();		

		switch($type)
		{	
			case 'bar':
				for($i=0; $i<=7;$i++)
				{
				    $data_1[$i] = date('M-d', $x[$i]);
				    $data_2[$i] = (int)$y[$i]; // force to integer
				}				
				
				$data_1 = array_reverse($data_1);
				$data_2 = array_reverse($data_2);
				
				$bar = new bar();
				$bar->set_values($data_2);
				$bar->set_colour('#21759B'); 
				$bar->set_tooltip('#x_label#<br>#val# SMS');
				$bar->set_key("SMS used in last 7 days", 10);
				
				$x = new x_axis();				
				$labels = new x_axis_labels();
				$labels->set_labels($data_1);
				$labels->set_steps(1);
				$x->set_labels($labels);
				
				$y = new y_axis();
				if(max($data_2)>0) $max=max($data_2); else $max=10;
				$y->set_range(0, $max, round($max/100)*10); 
				
				$element = $bar;
			break;
			
			case 'line':
				for($i=0; $i<=7;$i++)
				{
				    $data_1[$i] = new scatter_value($x[$i], $y[$i]);
				    $data_2[$i] = $y[$i];
				}
				    		
				$def = new solid_dot();
				$def->size(4)->halo_size(0)->colour('#21759B')->tooltip('#date:d M y#<br>#val# SMS');
				
				$line = new scatter_line('#21759B', 3); 
				$line->set_values($data_1);
				$line->set_default_dot_style($def);
				$line->set_key("SMS used in last 7 days", 10);

				$x = new x_axis();
				// grid line and tick every 10
				$x->set_range(
				    mktime(0, 0, 0, date("m"), date("d")-7, date('Y')), // <-- min == 7 day before
				    mktime(0, 0, 0, date("m"), date("d"), date('Y'))    // <-- max == Today
				    );
				
				// show ticks and grid lines for every day:
				$x->set_steps(86400);
				
				$labels = new x_axis_labels();
				// tell the labels to render the number as a date:
				$labels->text('#date:M-d#');
				// generate labels for every day
				$labels->set_steps(86400);
				// only display every other label (every other day)
				$labels->visible_steps(1);
				$labels->rotate(45);
				
				// finally attach the label definition to the x axis
				$x->set_labels($labels);
				
				$y = new y_axis();
				if(max($data_2)>0) $max=max($data_2); else $max=10;
				$y->set_range(0, $max, round($max/100)*10);	
							
				$element = $line;
			break;
		}		
		$chart = new open_flash_chart();
		$chart->add_element($element);
		$chart->set_x_axis($x);
		$chart->set_y_axis($y);
		
		echo $chart->toPrettyString();			
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Notification
	 *
	 * Display notification
	 * Modem status
	 * Used by the autoload function and called via AJAX.
	 *
	 * @access	public   		 
	 */		
	function notification()
	{
		$this->load->view('main/notification');
	}	

	// --------------------------------------------------------------------
	
	/**
	 * Unread Inbox
	 *
	 * Show unread inbox and alert when new sms arrived
	 * Used by the autoload function and called via AJAX.
	 *
	 * @access	public   		 
	 */		
	function unread_inbox()
	{		
		$tmp_unread = $this->Message_model->get_messages(array('readed' => FALSE,'uid'=>$this->session->userdata('id_user')))->num_rows();
		echo ($tmp_unread > 0)? "(".$tmp_unread.")" : "";		
	}	

	// --------------------------------------------------------------------
	
	/**
	 * Add Folder
	 *
	 * Add custom folder
	 *
	 * @access	public   		 
	 */				
	function add_folder()
	{
		$this->Kalkun_model->add_folder(); 
		redirect($this->input->post('source_url'));
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Rename Folder
	 *
	 * Rename custom folder
	 *
	 * @access	public   		 
	 */	
	function rename_folder()
	{
		$this->Kalkun_model->rename_folder();
		redirect($this->input->post('source_url'));
	}

	// --------------------------------------------------------------------
	
	/**
	 * Delete Folder
	 *
	 * Delete custom folder
	 *
	 * @access	public   		 
	 */		
	function delete_folder($id_folder=NULL)
	{
		$this->Kalkun_model->delete_folder($id_folder);
		redirect('/', 'refresh');
	}
	
//  alerts	// --------------------------------------------------------------------
	
	/**
	 * Settings
	 *
	 * Display and handle change on settings/user preference
	 *
	 * @access	public   		 
	 */	
	function settings()
	{
		$data['title'] = 'Settings';
		$type = $this->uri->segment(2);
		$valid_type = array('general', 'personal', 'profile', 'password', 'save');
		if(!in_array($type, $valid_type)) show_404();
		
		if($_POST && $type=='save') { 		
			$option = $this->input->post('option');
                        // Does password match hash in database?
			$hasher = new PasswordHash(PHPASS_HASH_STRENGTH, PHPASS_HASH_PORTABLE);				
			// check password
			if($option=='password' && !$hasher->CheckPassword($this->input->post('current_password'),$this->Kalkun_model->get_setting()->row('password'))) 
			{
				$this->session->set_flashdata('notif', 'You entered wrong password');
				redirect('settings/'.$option);
			}
			else if($option=='personal') 
			{
				if($this->input->post('username')!=$this->session->userdata('username'))
				{
					if($this->Kalkun_model->check_setting(array('option' => 'username', 'username' => $this->input->post('username')))->num_rows>0) 
					{
						$this->session->set_flashdata('notif', 'Username already exist');
						redirect('settings/'.$option);					
					}
				}
			}
			$this->Kalkun_model->update_setting($option);
			$this->session->set_flashdata('notif', 'Your settings has been saved');
			redirect('settings/'.$option);
		}
                $data['settings'] = $this->Kalkun_model->get_setting();
                
                if($type == 'personal'){
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
                    $village_id = $data['settings']->row('village_id');
                    
                        if(!empty($village_id)){
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
		$data['main'] = 'main/settings/setting';		
		$data['type'] = 'main/settings/'.$type;
		$this->load->view('main/layout', $data);
	}
	
}

/* End of file kalkun.php */
/* Location: ./application/controllers/kalkun.php */
