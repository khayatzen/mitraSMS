<?php
include_once 'mysql.php';
global $con;
$select_groups = $con->sql_query("SELECT * FROM pbk_groups ORDER BY ID");
while($result = $con->sql_fetchrow($select_groups)){
    $id_group = $result['ID'];
    $id_user = $result['id_user'];
    $select_phonebook = $con->sql_query("SELECT * FROM pbk WHERE ID = '$id_group' AND id_user='$id_user'");
    while($result1 = $con->sql_fetchrow($select_phonebook)){
        $id_pbk = $result1['id_pbk'];
        $con->sql_query("INSERT INTO user_group(id_pbk,id_pbk_groups,id_user)VALUES('$id_pbk','$id_group','$id_user')");
    }
}
//if(empty(mysql_error()))echo 'Sukses Boss';
//else echo 'Error : '.mysql_error();
echo 'Sukses';

?>
