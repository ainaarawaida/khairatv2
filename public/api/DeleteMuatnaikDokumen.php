<?php

if($_POST['dokumens']){
    $dokumens = json_decode(stripslashes($_POST['dokumens']));
}

$dokumens = update_user_meta($_POST['id'], 'dokumens', $dokumens) ; 
$GLOBALS['khai_temp_data']['submitpost']['dokumens'] = $dokumens ;
$GLOBALS['khai_temp_data']['submitpost']['post'] = "Maklumat dokumens berjaya dibuang"



?>