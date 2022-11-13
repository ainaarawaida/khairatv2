<?php

if ( ! function_exists( 'wp_handle_upload' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/file.php' );
}

$GLOBALS['khai_temp_data']['submitpost'] = array(); 
$GLOBALS['khai_temp_data']['submitpost']['error'] = array() ; 
$dokumens = get_user_meta( $_POST['id'], 'dokumens', true ) ;
$_POST['tarikhDokumen'] = date("Y-m-d H:i:s");

if($_FILES['jenisDokumen']){ //dapatkan dulu maklumat sebelum paparkan form


    // for multiple file upload.
    $upload_overrides = array( 'test_form' => false );
    $files = $_FILES['jenisDokumen'];
        if ( $files['name']) {
            $file = array(
                'name' => $files['name'],
                'type' => $files['type'],
                'tmp_name' => $files['tmp_name'],
                'error' => $files['error'],
                'size' => $files['size']
            );

            $movefile = wp_handle_upload( $file, $upload_overrides );
            array_push($_POST,$movefile);
        }


    if($dokumens){
            array_push($dokumens,$_POST);
            update_user_meta($_POST['id'], 'dokumens', $dokumens) ; 
    }else{
        $dokumens = array() ; 
        array_push($dokumens,$_POST);
        update_user_meta($_POST['id'], 'dokumens', $dokumens) ; 
    }

    $GLOBALS['khai_temp_data']['submitpost']['post'] = "Berjaya Simpan" ;
   
}

 
$GLOBALS['khai_temp_data']['submitpost']['dokumens'] = $dokumens ;







?>