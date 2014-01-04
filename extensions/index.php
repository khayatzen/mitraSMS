<?php
include 'mysql.php';
echo 'Boss';
$sql = $con->sql_query("SELECT * FROM inbox");
echo mysql_error();
while($result = $con->sql_fetchrow($sql)){
    echo $result[0].'<br/>';
}

?>