<?php

if($_POST['senaraiTanggungan']){
    $tanggungan = json_decode(stripslashes($_POST['senaraiTanggungan']));
}

$tanggungan = update_user_meta($_POST['id'], 'tanggungan', $tanggungan) ; 
$GLOBALS['khai_temp_data']['submitpost']['tanggungans'] = $tanggungan ;
$GLOBALS['khai_temp_data']['submitpost']['post'] = "Maklumat Tanggungan berjaya dibuang"



?>