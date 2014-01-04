<?php
class Tinyurl {

	function Tinyurl(){

	}

	function shorten($url){
		$curl = curl_init();  
		$timeout = 5;  
		curl_setopt($curl,CURLOPT_URL,'http://tinyurl.com/api-create.php?url='.$url);  
		curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);  
		curl_setopt($curl,CURLOPT_CONNECTTIMEOUT,$timeout);  
		$shortented_url = curl_exec($curl);  
		curl_close($curl);  
		return $shortented_url; 
	}

}
?> 
