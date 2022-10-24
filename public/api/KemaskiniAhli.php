<?php

$GLOBALS['khai_temp_data']['submitpost'] = array(); 
$GLOBALS['khai_temp_data']['submitpost']['error'] = array() ; 


if(!$_POST['noKadPengenalanBaru']){ //dapatkan dulu maklumat sebelum paparkan form
    $get_senarai_ahli = $wpdb->get_results( 
        $wpdb->prepare("
        SELECT 
        a.ID, 
        a.ID as no_ahli, 
        a.display_name,
        c.meta_value as noKadPengenalanBaru, 
        b.post_title as namaKariah,
        d.meta_value as nomborTelefon,
        e.meta_value as stage_daftar,
        a.user_registered, 
        a.user_email,
        f.meta_value as role
       
        FROM {$wpdb->prefix}users a 
        LEFT JOIN 
        (SELECT user_id, meta_value FROM {$wpdb->prefix}usermeta WHERE meta_key = 'kariah_id' ) kariah_id 
        ON a.ID = kariah_id.user_id
        LEFT JOIN 
        (SELECT ID,post_author,post_name,post_title FROM {$wpdb->prefix}posts where post_type = 'khai_page_name') b 
        ON kariah_id.meta_value = b.ID
        LEFT JOIN 
        (SELECT post_id, meta_value FROM {$wpdb->prefix}postmeta where meta_key = 'namaKariah' ) ba 
        ON kariah_id.meta_value = ba.post_id
        LEFT JOIN 
        (SELECT user_id, meta_value FROM {$wpdb->prefix}usermeta WHERE meta_key = 'noKadPengenalanBaru' ) c 
        ON a.ID = c.user_id
        LEFT JOIN 
        (SELECT user_id, meta_value FROM {$wpdb->prefix}usermeta WHERE meta_key = 'nomborTelefon' ) d 
        ON a.ID = d.user_id
        LEFT JOIN 
        (SELECT user_id, meta_value FROM {$wpdb->prefix}usermeta WHERE meta_key = 'stage_daftar' ) e 
        ON a.ID = e.user_id
        LEFT JOIN 
        (SELECT user_id, meta_value FROM {$wpdb->prefix}usermeta WHERE meta_key = 'role' ) f 
        ON a.ID = f.user_id
    
       
        WHERE 
        e.meta_value = '1'
        AND b.ID = '{$GLOBALS['khai_temp_data']['khai_user']->data->kariah_id}'
        AND a.ID = '{$_POST['id']}'
        ") 
    );
    $GLOBALS['khai_temp_data']['submitpost']['get_senarai_ahli'] = $get_senarai_ahli ;
}else{
    
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

}






?>