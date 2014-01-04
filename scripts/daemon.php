<?php
// Make sure it's run from CLI
//if(php_sapi_name() != 'cli' && !empty($_SERVER['REMOTE_ADDR'])) exit("Access Denied.");	
//
//// Please configure this
//$url = "http://smsburuhmigran.infest.or.id";
//
///*## Panggil Controller Daemon Kalkun Boss ##*/
//
//#fclose(fopen($url."/index.php/daemon/message_routine/", "r"));
//fclose(fopen('http://smsburuhmigran.infest.or.id/index.php?c=daemon&m=message_routine','r'));

// Make sure it's run from CLI
if(php_sapi_name() != 'cli' && !empty($_SERVER['REMOTE_ADDR'])) exit("Access Denied.");	

// Please configure this
$url = "http://localhost/borneoclimate/index.php/daemon/message_routine";

//fclose(fopen($url."/index.php/daemon/message_routine/", "r"));
$handle = fopen($url, "rb");
$contents = '';
while (!feof($handle)) {
  $contents .= fread($handle, 8192);
}
fclose($handle);


?>
