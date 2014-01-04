<?php
/*
include 'mysql.php';
error_reporting(E_ALL);
echo 'Migrasi INBOX';
$sql = $con->sql_query("SELECT * FROM filtered_BM"); 
while($result = $con->sql_fetchrow($sql)){
    $tgl = $result['tgl'];
    $nohp = $result['nohp'];
    $pesan = $result['pesan'];

    //$con->sql_query("INSERT INTO inbox (UpdateInDB,ReceivingDateTime,SenderNumber,TextDecoded, id_folder) VALUES (NOW(), '$tgl','$nohp','$pesan','6')");
    $sql1 = "INSERT INTO `inbox` (`UpdatedInDB`, `ReceivingDateTime`, `Text`, `SenderNumber`, `Coding`, `UDH`, `SMSCNumber`, `Class`, `TextDecoded`, `ID`, `RecipientID`, `Processed`, `id_folder`, `readed`, `Filtered`) VALUES
(NOW(), '$tgl', '0054006500730074', '$nohp', 'Default_No_Compression', '', '+6281100000', -1, '$pesan', '', 'PSDBM', 'false', 6, 'false', 'true')";
    $insert_inbox = $con->sql_query($sql1);
    if($insert_inbox){
        $id_inbox = $con->sql_lastid();
        $con->sql_query("INSERT INTO `folder_message_receive` (`id_inbox`)VALUES('$id_inbox')");
        $con->sql_query("INSERT INTO `user_inbox` (`id_inbox`,`id_user`)VALUES('$id_inbox',1)");
    }else echo mysql_error().'<br/>';
}
echo 'Joss';
 * 
 */
?>
