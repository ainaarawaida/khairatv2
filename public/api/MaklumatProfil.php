<?php

$GLOBALS['khai_temp_data']['submitpost']['error'] = array() ; 

$args = array(
    'ID'         => $GLOBALS['khai_temp_data']['khai_user']->ID,
    'user_email' => esc_attr( $_POST['emel'] ),
    'display_name' => esc_attr( $_POST['namaPenuh'] ),
);
$check = wp_update_user( $args );

if(!$check->errors){
    if($_POST['katalaluan'] != ''){
        wp_set_password( $_POST['katalaluan'], $GLOBALS['khai_temp_data']['khai_user']->ID );
    }

    update_user_meta($GLOBALS['khai_temp_data']['khai_user']->ID, 'noKadPengenalanBaru', $_POST['noKadPengenalanBaru']) ; 
    update_user_meta($GLOBALS['khai_temp_data']['khai_user']->ID, 'nomborTelefon', $_POST['nomborTelefon']) ; 
    update_user_meta($GLOBALS['khai_temp_data']['khai_user']->ID, 'stage_daftar', 1) ; 

    $GLOBALS['khai_temp_data']['khai_user']->data->user_email = esc_attr( $_POST['emel'] ) ; 
    $GLOBALS['khai_temp_data']['khai_user']->data->display_name = esc_attr( $_POST['namaPenuh'] ) ; 
    $GLOBALS['khai_temp_data']['khai_user']->data->noKadPengenalanBaru = esc_attr( $_POST['noKadPengenalanBaru'] ) ; 
    $GLOBALS['khai_temp_data']['khai_user']->data->nomborTelefon = esc_attr( $_POST['nomborTelefon'] ) ; 
    $GLOBALS['khai_temp_data']['khai_user']->data->stage_daftar = 1 ;
}else{
    $GLOBALS['khai_temp_data']['submitpost']['error'][] = array( "emel"=> "Emel ini telah digunakan") ; 
}

?>