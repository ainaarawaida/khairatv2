<?php

add_action( 'rest_api_init', 'khai_check_user');
function khai_check_user(){
    global $wpdb ; 
    // $GLOBALS['khai_temp_data']['khai_user'] = wp_get_current_user(); //<- production
    $GLOBALS['khai_temp_data']['khai_user'] =  get_user_by( 'id', 14 ); // development
    $GLOBALS['khai_temp_data']['khai_user']->role = get_user_meta( $GLOBALS['khai_temp_data']['khai_user']->ID, 'role', true ) ;
    $GLOBALS['khai_temp_data']['khai_user']->stage_daftar = get_user_meta( $GLOBALS['khai_temp_data']['khai_user']->ID, 'stage_daftar', true ) ;
    $GLOBALS['khai_temp_data']['khai_user']->kariah_id = get_user_meta( $GLOBALS['khai_temp_data']['khai_user']->ID, 'kariah_id', true ) ;
    $GLOBALS['khai_temp_data']['khai_user']->noKadPengenalanBaru = get_user_meta( $GLOBALS['khai_temp_data']['khai_user']->ID, 'noKadPengenalanBaru', true ) ;
    $GLOBALS['khai_temp_data']['khai_user']->nomborTelefon = get_user_meta( $GLOBALS['khai_temp_data']['khai_user']->ID, 'nomborTelefon', true ) ;

    
    if($GLOBALS['khai_temp_data']['khai_user']->kariah_id){
        $GLOBALS['khai_temp_data']['khai_user']->namaKariah = get_post($GLOBALS['khai_temp_data']['khai_user']->kariah_id )->post_title;
        $GLOBALS['khai_temp_data']['khai_user']->alamatKariah = get_post_meta(  $GLOBALS['khai_temp_data']['khai_user']->kariah_id, 'alamatKariah', true ) ;
        $GLOBALS['khai_temp_data']['khai_user']->sitePageUrl = get_post($GLOBALS['khai_temp_data']['khai_user']->kariah_id )->post_name;
    }
    

    if($_POST['action'] && $_POST['action'] === 'MaklumatProfil'){
        require_once KHAI_PATH . '/public/api/MaklumatProfil.php' ;
    }
    else if($_POST['action'] && $_POST['action'] === 'MaklumatKariah'){
        require_once KHAI_PATH . '/public/api/MaklumatKariah.php' ;
    }
    else if($_POST['action'] && $_POST['action'] === 'SenaraiAhli'){
        require_once KHAI_PATH . '/public/api/SenaraiAhli.php' ;
    }
    else if($_POST['action'] && $_POST['action'] === 'TidakAktifAhli'){
        require_once KHAI_PATH . '/public/api/TidakAktifAhli.php' ;
    }
    else if($_POST['action'] && $_POST['action'] === 'DeleteAhli'){
        require_once KHAI_PATH . '/public/api/DeleteAhli.php' ;
    }
    else if($_POST['action'] && $_POST['action'] === 'SenaraiAhliTidakAktif'){
        require_once KHAI_PATH . '/public/api/SenaraiAhliTidakAktif.php' ;
    }
    else if($_POST['action'] && $_POST['action'] === 'DaftarAhli'){
        require_once KHAI_PATH . '/public/api/DaftarAhli.php' ;
    }
    else if($_POST['action'] && $_POST['action'] === 'KemaskiniAhli'){
        require_once KHAI_PATH . '/public/api/KemaskiniAhli.php' ;
    }
    else if($_POST['action'] && $_POST['action'] === 'TambahAhliTanggungan'){
        require_once KHAI_PATH . '/public/api/TambahAhliTanggungan.php' ;
    }
    else if($_POST['action'] && $_POST['action'] === 'KemaskiniAhliTanggungan'){
        require_once KHAI_PATH . '/public/api/KemaskiniAhliTanggungan.php' ;
    }
    else if($_POST['action'] && $_POST['action'] === 'DeleteTanggungan'){
        require_once KHAI_PATH . '/public/api/DeleteTanggungan.php' ;
    } 
    else if($_POST['action'] && $_POST['action'] === 'JenisYuran'){
        require_once KHAI_PATH . '/public/api/JenisYuran.php' ;
    }

    


    

    

    //http://demo.test/wp-json/api/v1/data
    register_rest_route( 'api/v1', '/data', array(
        'methods' => 'POST',
        'callback' => 'khai_check_user_callback'
    ));


}

function khai_check_user_callback(){
    return json_encode($GLOBALS['khai_temp_data']); 
}

?>