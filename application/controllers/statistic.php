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
class Statistic extends MY_Controller {

	/**
	 * Constructor
	 *
	 * @access	public
	 */	
	function Statistic()
	{
		parent::MY_Controller();	
	}

	function index()
	{
		// generate 7 data points
		for ($i=0; $i<=7; $i++)
		{
		    $x[] = mktime(0, 0, 0, date("m"), date("d")-$i, date('Y'));	    
		    $param['sms_date'] = date('Y-m-d', mktime(0,0,0,date("m"),date("d")-$i,date("Y")));
		    $param['user_id'] = $this->session->userdata('id_user');		    
		    $y[] = $this->Kalkun_model->get_sms_used('date', $param);
		}

		$data['data'] = $x;
		$data['sms'] = $y;

		$data['main'] = 'main/statistic/stats';
		$data['title'] = 'Dashboard';
		$this->load->view('main/layout', $data);
	}
}
