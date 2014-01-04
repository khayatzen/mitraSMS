<link rel="stylesheet" type="text/css" media="all" href="css/widget.css" />
<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/smsTicker.js"></script>
<script type="text/javascript">
    $(function(){
        $('#sms-container').vTicker({
           speed: 500,
           pause: 4000,
           animation: 'fade',
           mousePause: true,
           showItems: 5
       });
    });
</script>
<?php
include 'mysql.php';
$sql = $con->sql_query("SELECT tgl,nohp,pesan FROM filtered_BM ORDER BY id DESC");
//echo mysql_error();
$view='';
$view.='<div id="sms-container"><ul>';
$i=0;
while($result = $con->sql_fetchrow($sql)){
    $tgl = $result['tgl'];
    $nohp = $result['nohp'];
    $pesan = $result['pesan'];
    $phone = substr_replace($nohp,'xxx',-3);
    $i++;
    if($i%2==0)$li_style = 'style="background-color:#F0FBFF;"';
    else $li_style = 'style="background-color:#FEFEEE;"';
    $view.='<li '.$li_style.'>
        <div class="smstitle">'.$tgl.' ('.$phone.')</div>
        <div class="smsbody">'.$pesan.'</div>
        </li>';
}
$view.='</ul></div>';
echo $view;
?>
