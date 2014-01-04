<?php
$params = array(
    'type'=>'inbox',
    'order_by'=>'ReceivingDateTime'
);
$incoming_messages = $this->Message_model->get_messages($params)->result_array();
echo '<ul>';
foreach($incoming_messages AS $key=>$message){
    echo '<li>'.$message['TextDecoded'].'<br/><small>'.nice_date($message['ReceivingDateTime']).'</small></li>';
}
echo '</ul>';
?>