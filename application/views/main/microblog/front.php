<?php
/*
 * Handling view of SMS Microbloging tabs by Folder name
 */
$view = array();
$view['content'] = '<ul class="menutab">';
foreach($main AS $key=>$temp){
   $view['content'] .='<li><a href="#foldermessage-'.$key.'"><span>'.$this->Microblog_model->getFolderName($key).'</span></a></li>';
}
$view['content'] .= '</ul>';
foreach($main AS $key=>$temp){
    $view['content'].='<div id="foldermessage-'.$key.'" class="divtab">';
    foreach($temp AS $i=>$j){
        $id_inbox = $j['ID'];
        $view['content'].='<div class="mainfoldermessage">';
        $view['content'].='<div class="mainfoldermessage-sender">'.$j['number'].'</div>';
        $view['content'].='<div class="smstext">'.$j['TextDecoded'].'</div>';
        $view['content'].='<div class="smsdate">'.simple_date($j['globaldate']).'</div>';
        $view['content'].='</div>';
        
        if(isset($comment[$key][$id_inbox]) && count($comment[$key][$id_inbox]) > 0){
            foreach($comment[$key][$id_inbox] AS $k=>$c){
                $view['content'].='<div class="commentfoldermessage">';
                $view['content'].='<div class="commentfoldermessage-sender">'.$c['number'].'</div>';
                $view['content'].='<div class="smstext">'.$c['TextDecoded'].'</div>';
                $view['content'].='<div class="smsdate">'.simple_date($c['globaldate']).'</div>';
                $view['content'].='</div>';
            }
        }
        
         
    }
    $view['content'].='</div>';
}
echo $callback.'('.json_encode($view).')';
//echo $view['content'];
?>
