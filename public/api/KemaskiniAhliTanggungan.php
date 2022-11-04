<?php

$GLOBALS['khai_temp_data']['submitpost'] = array(); 
$GLOBALS['khai_temp_data']['submitpost']['error'] = array() ; 
$tanggungan = get_user_meta( $_POST['id'], 'tanggungan', true ) ;

if($_POST['noKadPengenalanTanggungan']){ //dapatkan dulu maklumat sebelum paparkan form

    if($tanggungan){
        foreach($tanggungan AS $key => $val){
            if($val['noKadPengenalanTanggungan'] == $_POST['noKadPengenalanTanggungan']){
                $tanggungan[$key]['namaTanggungan'] = $_POST['namaTanggungan'] ; 
                $tanggungan[$key]['noKadPengenalanTanggungan'] = $_POST['noKadPengenalanTanggungan'] ; 
                $tanggungan[$key]['pertalianKeluargaTanggungan'] = $_POST['pertalianKeluargaTanggungan'] ; 
                $tanggungan[$key]['nomborTelefonTanggungan'] = $_POST['nomborTelefonTanggungan'] ; 
            }
        }
            update_user_meta($_POST['id'], 'tanggungan', $tanggungan) ; 
    }

    $GLOBALS['khai_temp_data']['submitpost']['post'] = "Berjaya Kemaskini ".$_POST['namaTanggungan'] ;
   
}

 
 $GLOBALS['khai_temp_data']['submitpost']['tanggungans'] = $tanggungan ;







?>