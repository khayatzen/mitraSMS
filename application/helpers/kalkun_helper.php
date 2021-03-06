<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    
    /**
      * INDIA NDNC Registry Check
      * In order to avaoid sending sms to NDNC registered phone numbers
      **/
    function NDNCcheck($mobileno)
	{  
		    $mobileno = substr($mobileno, -10, 10);
			$url = "http://www.ndncregistry.gov.in/ndncregistry/saveSearchSub.misc";
			$postString = "phoneno=" . $mobileno;
			$request = curl_init($url);
            curl_setopt($request, CURLOPT_HEADER, 0);
			//curl_setopt($request , CURLOPT_PROXY , '10.3.100.211:8080' );
			curl_setopt($request, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($request, CURLOPT_POST, 1);
			curl_setopt($request, CURLOPT_POSTFIELDS, $postString);
			curl_setopt($request, CURLOPT_SSL_VERIFYPEER, FALSE);
			$response = curl_exec($request);
			curl_close ($request);
            		 
			return (is_int(strpos(strtolower(strip_tags($response)), "number is not")) ? false : true);
	
    }
	function filter_data($data) 
	{
		if($data==NULL) return "<i>Unknown</i>";
		else return $data;	
	}
	
   // convert an ascii string to its hex representation
   function AsciiToHex($ascii)
   {
      $hex = '';

      for($i = 0; $i < strlen($ascii); $i++)
         $hex .= str_pad(base_convert(ord($ascii[$i]), 10, 16), 2, '0', STR_PAD_LEFT);

      return $hex;
   }

   // convert a hex string to ascii, prepend with '0' if input is not an even number
   // of characters in length   
   function HexToAscii($hex)
   {
      $ascii = '';
   
      if (strlen($hex) % 2 == 1)
         $hex = '0'.$hex;
   
      for($i = 0; $i < strlen($hex); $i += 2)
         $ascii .= chr(base_convert(substr($hex, $i, 2), 16, 10));
   
      return $ascii;
   }	
   

//	function nice_date($str, $option=NULL)
//	{
//		// convert the date to unix timestamp
//		list($date, $time) = explode(' ', $str);
//		list($year, $month, $day) = explode('-', $date);
//		list($hour, $minute, $second) = explode(':', $time);
//
//		$timestamp = mktime($hour, $minute, $second, $month, $day, $year);
//		
//		$now = time();
//
//    	$blocks = array(
//			array('name'=>lang('kalkun_year'),'amount'    =>    60*60*24*365    ),
//			array('name'=>lang('kalkun_month'),'amount'    =>    60*60*24*31    ),
//			array('name'=>lang('kalkun_week'),'amount'    =>    60*60*24*7    ),
//			array('name'=>lang('kalkun_day'),'amount'    =>    60*60*24    ),
//			array('name'=>lang('kalkun_hour'),'amount'    =>    60*60        ),
//			array('name'=>lang('kalkun_minute'),'amount'    =>    60        ),
//			array('name'=>lang('kalkun_second'),'amount'    =>    1        )
//        );
//   
//   		if($timestamp > $now) $string_type = ' remaining';
//   		else $string_type = ' '.lang('kalkun_ago');
//   		
//		$diff = abs($now-$timestamp);
//	   	
//	   	if($option=='smsd_check')
//	   	{
//	   		return $diff;	
//	   	}
//	   	else {
//		   	if($diff < 60)
//		   	{
//		   		return "Beberapa detik yang lalu";
//		   	}
//		   	else
//		   	{
//				$levels = 1;
//				$current_level = 1;
//				$result = array();
//				foreach($blocks as $block)
//				{
//					if ($current_level > $levels) { break; }
//					if ($diff/$block['amount'] >= 1)
//					{
//						$amount = floor($diff/$block['amount']);
//						$plural = '';
//						//if ($amount>1) {$plural='s';} else {$plural='';}
//						$result[] = $amount.' '.$block['name'].$plural;
//						$diff -= $amount*$block['amount'];
//						$current_level+=1;	
//					}
//				}
//				$res = implode(' ',$result).''.$string_type;
//				return $res;
//		   	}
//		}	
//	}   

function nice_date($str, $option=NULL)
{
	// convert the date to unix timestamp
	list($date, $time) = explode(' ', $str);
	list($year, $month, $day) = explode('-', $date);
	list($hour, $minute, $second) = explode(':', $time);

	$timestamp = mktime($hour, $minute, $second, $month, $day, $year);
	$now = time();
	$blocks = array(
	array('name'=>lang('kalkun_year'), 'amount' => 60*60*24*365),
	array('name'=>lang('kalkun_month'), 'amount' => 60*60*24*31),
	array('name'=>lang('kalkun_week'), 'amount' => 60*60*24*7),
	array('name'=>lang('kalkun_day'), 'amount' => 60*60*24),
	array('name'=>lang('kalkun_hour'), 'amount' => 60*60),
	array('name'=>lang('kalkun_minute'), 'amount' => 60),
	array('name'=>lang('kalkun_second'), 'amount' => 1)
	);

	if($timestamp > $now) $string_type = ' remaining';
	else $string_type = ' '.lang('kalkun_ago');

	$diff = abs($now-$timestamp);

	if($option=='smsd_check')
	{
		return $diff;	
	}
	else
	{
		if($diff < 60)
		{
			return "Less than a minute ago";
		}
		else
		{
			$levels = 1;
			$current_level = 1;
			$result = array();
			foreach($blocks as $block)
			{
				if ($current_level > $levels) { break; }
				if ($diff/$block['amount'] >= 1)
				{
					$amount = floor($diff/$block['amount']);
					$plural = '';
					//if ($amount>1) {$plural='s';} else {$plural='';}
					$result[] = $amount.' '.$block['name'].$plural;
					$diff -= $amount*$block['amount'];
					$current_level+=1;	
				}
			}
			$res = implode(' ',$result).''.$string_type;
			return $res;
		}
	}	
}   
//	function nice_date($datetime){
//            return simple_date($datetime);
//        }
        
	function get_modem_status($status, $tolerant)
	{
		// convert the date to unix timestamp
		list($date, $time) = explode(' ', $status);
		list($year, $month, $day) = explode('-', $date);
		list($hour, $minute, $second) = explode(':', $time);

		$timestamp = mktime($hour, $minute+$tolerant, $second, $month, $day, $year);
		
		$now = time();
		
		//$diff = abs($now-$timestamp);
		if($timestamp>$now) 
			return "connect";
		else 
			return "disconnect";
	}
   
   	function message_preview($str, $n)
   	{
   		if (strlen($str) <= $n) return showtags($str);
		else return showtags(substr($str, 0, $n)).'&#8230;';
   	}
   	
    function showtags($msg)
    {
        $msg = preg_replace("/</","&lt;",$msg);
        $msg = preg_replace("/>/","&gt;",$msg);
        return $msg;
    }
    
    function showmsg($msg)
    {
        return nl2br(showtags($msg));
    }
   	
   	function compare_date_asc($a, $b)
	{
		$date1 = strtotime($a['globaldate']);
		$date2 = strtotime($b['globaldate']);
		
		if($date1 == $date2) return 0;
		return ($date1 < $date2) ? -1 : 1; 
	}

   	function compare_date_desc($a, $b)
	{
		$date1 = strtotime($a['globaldate']);
		$date2 = strtotime($b['globaldate']);
		
		if($date1 == $date2) return 0;
		return ($date1 > $date2) ? -1 : 1; 
	}	
	
	function check_delivery_report($report)
	{
		if($report=='SendingError' or $report=='Error' or $report=='DeliveryFailed'): $status = lang('tni_msg_stat_fail');
		elseif($report=='SendingOKNoReport'): $status = lang('tni_msg_stat_oknr');
		elseif($report=='SendingOK'): $status = lang('tni_msg_stat_okwr');
		elseif($report=='DeliveryOK'): $status = lang('tni_msg_stat_deliv');
		elseif($report=='DeliveryPending'): $status = lang('tni_msg_stat_pend');
		elseif($report=='DeliveryUnknown'): $status = lang('tni_msg_stat_unknown');
		endif;		
		
		return $status;
	}
	
	function simple_date($datetime)
	{
		list($date, $time) = explode(' ', $datetime);
		list($year, $month, $day) = explode('-', $date);		
		return $day.'/'.$month.'/'.$year.' '.$time;
	}
	
	function ByteSize($bytes) 
    {
		$size = $bytes / 1024;
		if($size < 1024)
		{
			$size = number_format($size, 2);
			$size .= ' KB';
		} 
		else 
		{
			if($size / 1024 < 1024) 
			{
				$size = number_format($size / 1024, 2);
				$size .= ' MB';
			} 
			else if ($size / 1024 / 1024 < 1024)  
			{
				$size = number_format($size / 1024 / 1024, 2);
				$size .= ' GB';
			} 
		}
		return $size;
    }     
    
	function get_hour()
	{
		for($i=0;$i<24;$i++) {
			$hour = $i;
			if($hour<10) $hour = "0".$hour;
			echo "<option value=\"".$hour."\">".$hour."</option>"; 
		}
	}
	
	function get_minute()
	{
		for($i=0;$i<60;$i=$i+5) {
			$min = $i;
			if($min<10) $min = "0".$min;
			echo "<option value=\"".$min."\">".$min."</option>"; 
		}
	} 
	
	function is_ajax()
	{
		if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')
			return TRUE;
		else 
			return FALSE;	
	}
	
	
	function get_database_property($driver)
	{
		// valid and supported driver
		$valid_driver = array('postgre', 'mysql', 'pdo');
		
		if(!in_array($driver, $valid_driver)) die ('Database driver you\'re using is not supported');
		
		$postgre['name'] = 'postgre';
		$postgre['file'] = 'pgsql';
		$postgre['human'] = 'PostgreSQL';
		$postgre['escape_char'] = '"';
		$postgre['driver'] = 'pgsql';
		
		$mysql['name'] = 'mysql';
		$mysql['file'] = 'mysql';
		$mysql['human'] = 'MySQL';
		$mysql['escape_char'] = '`';
		$mysql['driver'] = 'mysql';
		
		$pdo['name'] = 'sqlite';
		$pdo['file'] = 'sqlite';
		$pdo['human'] = 'SQLite3 (Using PDO)';
		$pdo['escape_char'] = '';
		$pdo['driver'] = 'pdo_sqlite';
		
		return ${$driver};
	}   
        
        #-- GMP
if (!function_exists("bcadd") && function_exists("gmp_strval")) {
   function bcadd($a, $b) {
      return gmp_strval(gmp_add($a, $b));
   }
   function bcsub($a, $b) {
      return gmp_strval(gmp_sub($a, $b));
   }
   function bcmul($a, $b) {
      return gmp_strval(gmp_mul($a, $b));
   }
   function bcdiv($a, $b, $precision=NULL) {
      $qr = gmp_div_qr($a, $b);
      $q = gmp_strval($qr[0]);
      $r = gmp_strval($qr[1]);
      if ((!$r) || ($precision===0)) {
         return($q);
      }
      else {
         if (isset($precision)) {
            $r = substr($r, 0, $precision);
         }
         return("$q.$r");
      }
   }
   function bcmod($a, $b) {
      return gmp_strval(gmp_mod($a, $b));
   }
   function bcpow($a, $b) {
      return gmp_strval(gmp_pow($a, $b));
   }
   function bcpowmod($x, $y, $mod) {
      return gmp_strval(gmp_powm($x, $y, $mod));
   }
   function bcsqrt($x) {
      return gmp_strval(gmp_sqrt($x));
   }
   function bccomp($a, $b) {
      return gmp_cmp($a, $b);
   }
   function bcscale($scale="IGNORED") {
      trigger_error("bcscale(): ignored", E_USER_ERROR);
   }
}//gmp emulation



#-- bigint
// @dl("php_big_int".PHP_SHLIB_SUFFIX))
if (!function_exists("bcadd") && function_exists("bi_serialize")) {
   function bcadd($a, $b) {
      return bi_to_str(bi_add($a, $b));
   }
   function bcsub($a, $b) {
      return bi_to_str(bi_sub($a, $b));
   }
   function bcmul($a, $b) {
      return bi_to_str(bi_mul($a, $b));
   }
   function bcdiv($a, $b) {
      return bi_to_str(bi_div($a, $b));
   }
   function bcmod($a, $b) {
      return bi_to_str(bi_mod($a, $b));
   }
   function bcpow($a, $b) {
      return bi_to_str(bi_pow($a, $b));
   }
   function bcpowmod($a, $b, $c) {
      return bi_to_str(bi_powmod($a, $b, $c));
   }
   function bcsqrt($a) {
      return bi_to_str(bi_sqrt($a));
   }
   function bccomp($a, $b) {
      return bi_cmp($a, $b);
   }
   function bcscale($scale="IGNORED") {
      trigger_error("bcscale(): ignored", E_USER_ERROR);
   }
}


/* End of file kalkun_helper.php */
/* Location: ./application/helpers/kalkun_helper.php */
