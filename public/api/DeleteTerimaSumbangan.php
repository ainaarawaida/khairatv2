<?php

if($_POST['sumbangans']){
    $sumbangans = json_decode(stripslashes($_POST['sumbangans']));
}

$sumbangans = update_user_meta($_POST['id'], 'sumbangans', $sumbangans) ; 
$GLOBALS['khai_temp_data']['submitpost']['sumbangans'] = $sumbangans ;
$GLOBALS['khai_temp_data']['submitpost']['post'] = "Maklumat sumbangan berjaya dibuang"



?>