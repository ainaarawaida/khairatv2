<?php
$GLOBALS['khai_temp_data']['submitpost']['error'] = array() ; 



$check_author_site_name = $wpdb->get_results( 
    $wpdb->prepare("SELECT ID,post_name,post_title FROM {$wpdb->prefix}posts WHERE post_type =%s AND post_author = %d", array('khai_page_name', $GLOBALS['khai_temp_data']['khai_user']->ID)) 
);

$check_exist_post_title = $wpdb->get_results( 
    $wpdb->prepare("SELECT ID,post_author FROM {$wpdb->prefix}posts WHERE post_type =%s AND (post_title = %s)", array('khai_page_name', wp_strip_all_tags( $_POST['namaKariah'] ))) 
);

$check_exist_site_name = $wpdb->get_results( 
    $wpdb->prepare("SELECT ID,post_author FROM {$wpdb->prefix}posts WHERE post_type =%s AND (post_name = %s)", array('khai_page_name', str_replace(' ', '-', strtolower($_POST['sitePageUrl'])))) 
);

$my_post = array(
    'post_title'    => wp_strip_all_tags( $_POST['namaKariah'] ),
    'post_content'  => '',
    'post_status'   => 'publish',
    'post_author'   => $GLOBALS['khai_temp_data']['khai_user']->ID,
    'post_type' => 'khai_page_name',
    'post_name' => $_POST['sitePageUrl']
);

if(!$check_author_site_name && !$check_exist_post_title && !$check_exist_site_name){
    $my_post['ID'] =  wp_insert_post( $my_post );
    update_post_meta($my_post['ID'], 'alamatKariah', $_POST['alamatKariah']) ; 
    update_user_meta($GLOBALS['khai_temp_data']['khai_user']->ID, 'stage_daftar', 1) ; 
    update_user_meta($GLOBALS['khai_temp_data']['khai_user']->ID, 'kariah_id', $my_post['ID']) ; 
}
if(!$check_author_site_name && !$check_exist_post_title && $check_exist_site_name){
     $GLOBALS['khai_temp_data']['submitpost']['error'][]['sitePageUrl'] = 'Site URL telah wujud' ; 
}
if(!$check_author_site_name && $check_exist_post_title && !$check_exist_site_name){
    $GLOBALS['khai_temp_data']['submitpost']['error'][]['namaKariah'] = 'Nama Kariah telah wujud' ; 
}
 if(!$check_author_site_name && $check_exist_post_title && $check_exist_site_name){
     $GLOBALS['khai_temp_data']['submitpost']['error'][]['namaKariah'] = 'Nama Kariah telah wujud' ; 
     $GLOBALS['khai_temp_data']['submitpost']['error'][]['sitePageUrl'] = 'Site URL telah wujud' ; 
}
 if($check_author_site_name && !$check_exist_post_title && !$check_exist_site_name){
    $my_post['ID'] = $check_author_site_name[0]->ID;
   
    wp_update_post( $my_post );
    update_post_meta($my_post['ID'], 'alamatKariah', $_POST['alamatKariah']) ; 
    update_user_meta($GLOBALS['khai_temp_data']['khai_user']->ID, 'stage_daftar', 1) ; 
    update_user_meta($GLOBALS['khai_temp_data']['khai_user']->ID, 'kariah_id', $my_post['ID']) ; 
}
 if($check_author_site_name && !$check_exist_post_title && $check_exist_site_name){
        $my_post['ID'] = $check_author_site_name[0]->ID;
    if($check_author_site_name[0]->post_name ===  str_replace(' ', '-', strtolower($_POST['sitePageUrl']))){
        
        wp_update_post( $my_post );
        update_post_meta($my_post['ID'], 'alamatKariah', $_POST['alamatKariah']) ; 
        update_user_meta($GLOBALS['khai_temp_data']['khai_user']->ID, 'stage_daftar', 1) ; 
        update_user_meta($GLOBALS['khai_temp_data']['khai_user']->ID, 'kariah_id', $my_post['ID']) ; 
    }else{
        $GLOBALS['khai_temp_data']['submitpost']['error'][]['sitePageUrl'] = 'Site URL telah wujud' ; 
    }

}
if($check_author_site_name && $check_exist_post_title && !$check_exist_site_name){
      $my_post['ID'] = $check_author_site_name[0]->ID;
       if($check_author_site_name[0]->post_title ===  wp_strip_all_tags( $_POST['namaKariah'] ) ){
          
            wp_update_post( $my_post );
            update_post_meta($my_post['ID'], 'alamatKariah', $_POST['alamatKariah']) ; 
            update_user_meta($GLOBALS['khai_temp_data']['khai_user']->ID, 'stage_daftar', 1) ; 
            update_user_meta($GLOBALS['khai_temp_data']['khai_user']->ID, 'kariah_id', $my_post['ID']) ; 
        }else{
            $GLOBALS['khai_temp_data']['submitpost']['error'][]['namaKariah'] = 'Nama Kariah telah wujud' ;
             
        }

}
if($check_author_site_name && $check_exist_post_title && $check_exist_site_name){
      $my_post['ID'] = $check_author_site_name[0]->ID;
    if($check_author_site_name[0]->post_name ===  str_replace(' ', '-', strtolower($_POST['sitePageUrl']))){
        if($check_author_site_name[0]->post_title ===  wp_strip_all_tags( $_POST['namaKariah'] ) ){
            
            wp_update_post( $my_post );
            update_post_meta($my_post['ID'], 'alamatKariah', $_POST['alamatKariah']) ;  
            update_user_meta($GLOBALS['khai_temp_data']['khai_user']->ID, 'stage_daftar', 1) ; 
            update_user_meta($GLOBALS['khai_temp_data']['khai_user']->ID, 'kariah_id', $my_post['ID']) ; 
        }else{
            $GLOBALS['khai_temp_data']['submitpost']['error'][]['namaKariah'] = 'Nama Kariah telah wujud' ; 
        }
    }else{
      
        $GLOBALS['khai_temp_data']['submitpost']['error'][][] = 'Site URL telah wujud' ; 
        if($check_author_site_name[0]->post_title !==  wp_strip_all_tags( $_POST['namaKariah'] ) ){
            $GLOBALS['khai_temp_data']['submitpost']['error'][]['namaKariah'] = 'Nama Kariah telah wujud' ; 
        }
    }


   

}

if($my_post['ID'] && count($GLOBALS['khai_temp_data']['submitpost']['error']) == 0){
    $GLOBALS['khai_temp_data']['submitpost']['kariah_id'] = $my_post['ID'] ; 
    $GLOBALS['khai_temp_data']['khai_user']->stage_daftar = 1 ; 
    $GLOBALS['khai_temp_data']['khai_user']->kariah_id = $my_post['ID'] ; 
    $GLOBALS['khai_temp_data']['khai_user']->namaKariah = $_POST['namaKariah'] ; 
    $GLOBALS['khai_temp_data']['khai_user']->alamatKariah = $_POST['alamatKariah'] ; 
    $GLOBALS['khai_temp_data']['khai_user']->sitePageUrl = $_POST['sitePageUrl'] ; 
}




?>