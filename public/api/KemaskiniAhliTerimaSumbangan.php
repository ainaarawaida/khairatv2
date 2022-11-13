<?php

$GLOBALS['khai_temp_data']['submitpost'] = array(); 
$GLOBALS['khai_temp_data']['submitpost']['error'] = array() ; 

$sumbangans = get_user_meta( $_POST['id'], 'sumbangans', true ) ;



 $GLOBALS['khai_temp_data']['submitpost']['sumbangans'] = $sumbangans ;
?>