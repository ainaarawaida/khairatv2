<?php

$GLOBALS['khai_temp_data']['submitpost'] = array(); 
$GLOBALS['khai_temp_data']['submitpost']['error'] = array() ; 
$tanggungan = get_user_meta( $_POST['id'], 'tanggungan', true ) ;

if($_POST['noKadPengenalanTanggungan']){ //dapatkan dulu maklumat sebelum paparkan form

    if($tanggungan){
            array_push($tanggungan,$_POST);
            update_user_meta($_POST['id'], 'tanggungan', $tanggungan) ; 
    }else{
        $tanggungan = array() ; 
        array_push($tanggungan,$_POST);
        update_user_meta($_POST['id'], 'tanggungan', $tanggungan) ; 
    }

    $GLOBALS['khai_temp_data']['submitpost']['post'] = "Berjaya Simpan" ;
   
}

 
 $GLOBALS['khai_temp_data']['submitpost']['tanggungans'] = $tanggungan ;







?>