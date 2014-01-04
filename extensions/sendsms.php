<?php
/*function GetPing($ip=NULL) {
 if(empty($ip)) {$ip = $_SERVER['REMOTE_ADDR'];}
 if(getenv("OS")=="Windows_NT") {
  $exec = exec("ping -n 3 -l 64 ".$ip);
  return end(explode(" ", $exec ));
 }
 else {
  $exec = exec("ping -c 3 -s 64 -t 64 ".$ip);
  $array = explode("/", end(explode("=", $exec )) );
  return ceil($array[1]) . 'ms';
 }
}

echo GetPing();
*/
$sendsms = exec("echo 'Hallo Khayat' | /usr/local/bin/gammu --sendsms TEXT 081914942468");
print_r($sendsms);
?>
