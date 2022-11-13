<?php

$GLOBALS['khai_temp_data']['submitpost'] = array(); 
$GLOBALS['khai_temp_data']['submitpost']['error'] = array() ; 
$dokumens = get_user_meta( $_POST['id'], 'dokumens', true ) ;

 
 $GLOBALS['khai_temp_data']['submitpost']['dokumens'] = $dokumens ;







?>