<?php
/*
* Auto replay dan Auto forward
*/
include 'mysql.php';

class DaemonSMS{

        var $forwarding_numbers = array(
            'Fika'=>'082135503939',
            'Lamuk'=>'081390003508'
            );
    /*
     * Daemon SMS as Constructor
     * Checking and Filtering inbox messages
     */
    function DaemonSMS(){
        global $con;
            //$backlist_numbers = $this->getBlacklistNumbers();
            /*### For Single Inbox ###*/
            $inbox_single = $this->getMessages('inbox_single');
            foreach($inbox_single AS $key=>$messages){
                    if($this->isBlacklisted($messages['SenderNumber'])){
                        $this->moveToFolder($messages['ID'], '5');
                        $this->setFiltered($messages['SenderNumber']);
                    }else{
                        $this->filterMessages($messages['SenderNumber']);
                        $this->setFiltered($messages['SenderNumber']);
                    }
            }
            /*### For Multipart Inbox ###*/
            $inbox_multipart = $this->getMessages('inbox_multipart');
            foreach($inbox_multipart AS $key=>$messages){
                    if($this->isBlacklisted($messages['SenderNumber'])){
                        $this->moveToFolder($messages['ID'], '5');
                        $this->setFiltered($messages['SenderNumber']);
                    }else{
                        $this->filterMessages($messages['SenderNumber']);
                        $this->setFiltered($messages['SenderNumber']);
                    }
            }
        
    }//end function

    /*
     * Get DISTINCT inbox messages by phone number
     */
    function getMessages($opt='inbox_single'){
	global $con;
	     $messages = array();
	     if($opt=='inbox_single'){
	     	$selectinbox = $con->sql_query("SELECT DISTINCT SenderNumber,ID,TextDecoded FROM inbox WHERE Filtered='false' AND UDH='' GROUP BY SenderNumber");
             	while($result = $con->sql_fetchrow($selectinbox)){
                    array_push($messages,$result);
             	}
	     }else if($opt=='inbox_multipart'){
                $selectinbox = $con->sql_query("SELECT DISTINCT SenderNumber,ID,TextDecoded FROM inbox WHERE Filtered='false' AND UDH LIKE '050003%' GROUP BY SenderNumber");
                while($result = $con->sql_fetchrow($selectinbox)){
                    array_push($messages,$result);
                }
             }

	return $messages;
    }
    /*
     * Reply SMS
     */
    function autoReply($number){
	global $con;
             //send SMS
	     $msg = 'Terimakasih atas informasinya, kami akan menindaklanjuti segera. [www.borneoclimate.info]';
	     $insert_outbox = $con->sql_query("INSERT INTO `outbox` (`UpdatedInDB`, `InsertIntoDB`, `SendingDateTime`, `Text`, `DestinationNumber`, `Coding`, `UDH`, `Class`, `TextDecoded`, `ID`, `MultiPart`, `RelativeValidity`, `SenderID`, `SendingTimeOut`, `DeliveryReport`, `CreatorID`) VALUES (CURRENT_TIMESTAMP, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL, '$number', 'Default_No_Compression', NULL, '-1', '$msg ', NULL, 'false', '-1', 'BC', '0000-00-00 00:00:00', 'default', 'BC')");
    }
    /*
     * Filter Message and move it to Folder Message
     */
    function filterMessages($number){
	global $con;
	     $select_messages = $con->sql_query("SELECT * FROM inbox WHERE SenderNumber='$number' AND Filtered='false'");
             while($result = $con->sql_fetchrow($select_messages)){
                 $ID           = $result['ID'];
                 $SenderNumber = $result['SenderNumber'];
                 $TextDecoded  = $result['TextDecoded'];
                 $UDH          = $result['UDH'];
                 $folders = $this->getFolders();
                 $inserted_user_inbox=false;
                 foreach($folders AS $key=>$val){
                     $folder_id = $val['id_folder'];
                     $folder_name = $val['name'];
                     if(!empty($UDH) && substr($UDH,11)=='1'){
                         if(preg_match("/^".$folder_name."[\ ]/i", $TextDecoded)){
                            $folder_name_length = strlen($folder_name)+1;
                            $message = substr($TextDecoded,$folder_name_length);
                            $con->sql_query("UPDATE inbox SET TextDecoded='$message' WHERE ID='$ID'");
                            $this->moveToFolder($ID,$folder_id);
                            if(!$this->isBlacklisted($SenderNumber)){
                                $this->autoReply($SenderNumber);
                                //$this->forwardMessage($ID);
                            }
                            $inserted_user_inbox=true;
                         }else if(preg_match("/^BLS:".$folder_name."[\ ]/i", $TextDecoded)){
                            $folder_name_length = strlen($folder_name)+4;
                            $message = substr($TextDecoded,$folder_name_length);
                            $con->sql_query("UPDATE inbox SET TextDecoded='$message' WHERE ID='$ID'");
                            $this->moveToFolder($ID,$folder_id);
                            if(!$this->isBlacklisted($SenderNumber)){
                                $this->autoReply($SenderNumber);
                                //$this->forwardMessage($ID);
                            }
                            $this->replyedMessageFolder($ID);
                            $inserted_user_inbox=true;
                         }
                     }else{
                        if(preg_match("/^".$folder_name."[\ ]/i", $TextDecoded)){
                            $folder_name_length = strlen($folder_name)+1;
                            $message = substr($TextDecoded,$folder_name_length);
                            $con->sql_query("UPDATE inbox SET TextDecoded='$message' WHERE ID='$ID'");
                            $this->moveToFolder($ID,$folder_id);
                            if(!$this->isBlacklisted($SenderNumber)){
                                $this->autoReply($SenderNumber);
                                //$this->forwardMessage($ID);
                            }

                            $inserted_user_inbox=true;
                        }else if(preg_match("/^BLS:".$folder_name."[\ ]/i", $TextDecoded)){
                            $folder_name_length = strlen($folder_name)+4;
                            $message = substr($TextDecoded,$folder_name_length);
                            $con->sql_query("UPDATE inbox SET TextDecoded='$message' WHERE ID='$ID'");
                            $this->moveToFolder($ID,$folder_id);
                            if(!$this->isBlacklisted($SenderNumber)){
                                $this->autoReply($SenderNumber);
                                //$this->forwardMessage($ID);
                            }
                            $this->replyedMessageFolder($ID);
                            $inserted_user_inbox=true;
                         }
                     } 
		 }//end foreach
                 if(!$inserted_user_inbox){
                    $this->moveToFolder($ID,1);
                 }
             }
    }
    /*
     * Move Message to custom Folder
     */
    function moveToFolder($messageId,$folderId){
        global $con;
             $con->sql_query("UPDATE inbox SET id_folder='$folderId' WHERE ID='$messageId'");
             $select_folder_owner = $con->sql_query("SELECT id_user FROM user_folders WHERE id_folder='$folderId'");
             $result = $con->sql_fetchrow($select_folder_owner);
             $folder_owner = $result['id_user'];
             if($folder_owner=='0')$folder_owner='1';
             $con->sql_query("INSERT INTO user_inbox(id_inbox,id_user)VALUES('$messageId','$folder_owner')");
    }
    /*
     * Get Folder List
     */
    function getFolders(){
        global $con;
	     $folders=array();
             $select_folders = $con->sql_query("SELECT id_folder,name FROM user_folders WHERE id_user='1' ORDER BY id_folder");
             while($result = $con->sql_fetchrow($select_folders)){
		array_push($folders,$result);
	     }
            return $folders;
    }
    /*
     * Get Blacklist Phone Numbers
     */
    function getBlacklistNumbers(){
       global $con;
             $blacklist = array();
             $select = $con->sql_query("SELECT * FROM blacklist_numbers");
             while($result = $con->sql_fetchrow($select)){
                 array_push($blacklist,$result);
             }
       return $blacklist;
    }

    function isBlacklisted($phonenumber){
        global $con;
             $select = $con->sql_query("SELECT * FROM blacklist_numbers WHERE number='$phonenumber'");
             $rows = $con->sql_numrows($select);
             if($rows > 0)return true;
             return false;
    }

    /*
     * Set Filtered to inbox message
     */
    function setFiltered($phonenumber){
        global $con;
             $con->sql_query("UPDATE inbox SET Filtered='true' WHERE SenderNumber='$phonenumber' AND Filtered='false'");
    }//end function

    /*
     * Incoming message folder
     */
    function replyedMessageFolder($id_inbox){
        global $con;
             $select = $con->sql_query("SELECT SenderNumber FROM inbox WHERE ID='$id_inbox'");
             $result = $con->sql_fetchrow($select);
             $sender = $result['SenderNumber'];

             $_select = $con->sql_query("SELECT ID FROM folder_message_sent WHERE DestinationNumber='$sender' ORDER BY ID DESC LIMIT 0,1");
             $_result = $con->sql_fetchrow($_select);
             $folder_message_sent_id = $_result['ID'];

             $con->sql_query("INSERT INTO folder_message_receive (id_inbox,folder_message_sent_id)VALUES('$id_inbox','$folder_message_sent_id')");
    }
    /*
     * Forward Message to Admin SMS Server
     */
    function forwardMessage($id_inbox){
        global $con;
        if($this->isMultipartInbox($id_inbox)){
            $inbox = $this->getInbox($id_inbox);
            $UDH = substr($inbox['UDH'],0,4);
            $number = $inbox['SenderNumber'];
            $sql = $con->sql_query("SELECT TextDecoded FROM inbox WHERE SenderNumber='$number' AND UDH LIKE '$UDH%' ORDER BY UDH");
            $row = $con->sql_numrows($sql);
            if($row<10)$row = '0'.$row;
            $i=0;
            $id_outbox = -1;
            foreach($this->forwarding_numbers AS $key=>$forwardnumber):
            while($result = $con->sql_fetchrow($sql)){
                $msg = $result['TextDecoded'];
                $i++;
                if($i<10)$j = '0'.$i;
                $setUDH = '050003D3'.$row.$j;
                
                    if($i==1){
                        $con->sql_query("INSERT INTO `outbox` (`UpdatedInDB`, `InsertIntoDB`, `SendingDateTime`, `Text`, `DestinationNumber`, `Coding`, `UDH`, `Class`, `TextDecoded`, `ID`, `MultiPart`, `RelativeValidity`, `SenderID`, `SendingTimeOut`, `DeliveryReport`, `CreatorID`) VALUES (CURRENT_TIMESTAMP, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL, '$forwardnumber', 'Default_No_Compression', '$setUDH', '-1', '$msg', NULL, 'true', '-1', 'PSDBM', '0000-00-00 00:00:00', 'default', 'PSDBM')");
                        $id_outbox = $con->sql_lastid();
                    }else{
                        $con->sql_query("INSERT INTO `outbox_multipart` (`Text`,`Coding`,`UDH`,`Class`,`TextDecoded`,`ID`,`SequencePosition`)VALUES(NULL,'Default_No_Compression','$setUDH','-1','$msg','$id_outbox','$i')");
                    }
                
            }
            endforeach;
        }else{
            $inbox = $this->getInbox($id_inbox);
            $msg = $inbox['TextDecoded'];
            foreach($this->forwarding_numbers AS $key=>$forwardnumber):
                   $con->sql_query("INSERT INTO `outbox` (`UpdatedInDB`, `InsertIntoDB`, `SendingDateTime`, `Text`, `DestinationNumber`, `Coding`, `UDH`, `Class`, `TextDecoded`, `ID`, `MultiPart`, `RelativeValidity`, `SenderID`, `SendingTimeOut`, `DeliveryReport`, `CreatorID`) VALUES (CURRENT_TIMESTAMP, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL, '$forwardnumber', 'Default_No_Compression', NULL, '-1', '$msg ', NULL, 'false', '-1', 'PSDBM', '0000-00-00 00:00:00', 'default', 'PSDBM')");
            endforeach;
        }
    }
    /*
     * Checking is message multippart
     */
    function isMultipartInbox($id_inbox){
        global $con;
        $sql = $con->sql_query("SELECT UDH FROM inbox WHERE ID='$id_inbox'");
        $result = $con->sql_fetchrow($sql);
        if(!empty($result['UDH']))return true;
        return false;
    }
    function getInbox($id_inbox){
        global $con;
        $sql = $con->sql_query("SELECT * FROM inbox WHERE ID='$id_inbox'");
        $result = $con->sql_fetchrow($sql);
        return $result;
    }
}//end class
$sms = new DaemonSMS();
//include_once 'phonebook.migration.php';
?>
