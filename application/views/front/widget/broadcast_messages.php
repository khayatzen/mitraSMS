<?php
$broadcast_messages = $this->Message_model->get_message_broadcast();
echo '<ul>';
foreach($broadcast_messages AS $key=>$message){
    echo '<li>'.$message['TextDecoded'].'<br/><small>'.nice_date($message['InsertIntoDB']).'</small></li>';
}
echo '</ul>';
?>
