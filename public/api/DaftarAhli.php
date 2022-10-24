<?php

$GLOBALS['khai_temp_data']['submitpost'] = array(); 
$GLOBALS['khai_temp_data']['submitpost']['error'] = array() ; 

if($_POST['katalaluan'] === ""){
     $random_password = wp_generate_password( 12, false );
      $check = wp_create_user( $_POST['emel'] , $random_password, $_POST['emel'] );
       if(!$check->errors){
        wp_new_user_notification( $check, $random_password);
       }
      
}else{
    $check = wp_create_user( $_POST['emel'] , $_POST['katalaluan'], $_POST['emel'] );
}

if(!$check->errors){
    update_user_meta($check, 'noKadPengenalanBaru', $_POST['noKadPengenalanBaru']) ; 
    update_user_meta($check, 'nomborTelefon', $_POST['nomborTelefon']) ; 
    update_user_meta($check, 'stage_daftar', 1) ; 
    update_user_meta( $check, 'role', $_POST['jenisAhli'] );
    update_user_meta( $check, 'kariah_id', $_POST['kariah_id'] );
    if($_POST['nomborKeahlian'] == ""){
        $_POST['nomborKeahlian'] = $check ;
    }
    update_user_meta( $check, 'nomborKeahlian', $_POST['nomborKeahlian'] );

    if($_POST['jenisAhli'] == '1'){
        update_user_meta( $check, 'wp_capabilities', array('pentadbir' => 1) );
    }else if($_POST['jenisAhli'] == '2'){
        update_user_meta( $check, 'wp_capabilities', array('ahli' => 2) );
    }else if($_POST['jenisAhli'] == '3'){
        update_user_meta( $check, 'wp_capabilities', array('asnaf' => 3) );
    }
   



    $userdata = array(
        'ID' => $check,
        'display_name' => $_POST['namaPenuh']
    );
    wp_update_user( $userdata );
    $GLOBALS['khai_temp_data']['submitpost']['id'] = $check ;
 
    
}else{
    $GLOBALS['khai_temp_data']['submitpost']['error'][] = array( "emel"=> "Emel ini telah digunakan") ; 
}




?>