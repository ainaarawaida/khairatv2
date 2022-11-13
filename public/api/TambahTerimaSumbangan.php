<?php

$GLOBALS['khai_temp_data']['submitpost'] = array(); 
$GLOBALS['khai_temp_data']['submitpost']['error'] = array() ; 
$sumbangans = get_user_meta( $_POST['id'], 'sumbangans', true ) ;

if($_POST['jumlahSumbangan']){ //dapatkan dulu maklumat sebelum paparkan form

    if($sumbangans){
            array_push($sumbangans,$_POST);
            update_user_meta($_POST['id'], 'sumbangans', $sumbangans) ; 
    }else{
        $sumbangans = array() ; 
        array_push($sumbangans,$_POST);
        update_user_meta($_POST['id'], 'sumbangans', $sumbangans) ; 
    }

    $GLOBALS['khai_temp_data']['submitpost']['post'] = "Berjaya Simpan" ;
   
}

 
$GLOBALS['khai_temp_data']['submitpost']['sumbangans'] = $sumbangans ;







?>