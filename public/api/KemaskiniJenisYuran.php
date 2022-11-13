<?php

$GLOBALS['khai_temp_data']['submitpost']['error'] = array() ; 


if(!$_POST['tajukYuran']){
    $get_jenisyuran = $wpdb->get_results( 
    $wpdb->prepare("
    SELECT 
    a.ID, 
    a.post_title as Tajuk, 
    jenisYuran.meta_value as jenisYuran, 
    Jumlah.meta_value as Jumlah, 
    a.post_excerpt as keteranganYuran,
    mulaYuran.meta_value as mulaYuran, 
    statusYuran.meta_value as statusYuran,
    paparanYuran.meta_value as paparanYuran,
    arraykadarYuran.meta_value as arraykadarYuran
   
    FROM {$wpdb->prefix}posts a 
    LEFT JOIN 
    (SELECT post_id, meta_value FROM {$wpdb->prefix}postmeta WHERE meta_key = 'jenisYuran' ) jenisYuran 
    ON a.ID = jenisYuran.post_id
    LEFT JOIN 
    (SELECT post_id, meta_value FROM {$wpdb->prefix}postmeta WHERE meta_key = '_price' ) Jumlah 
    ON a.ID = Jumlah.post_id
    LEFT JOIN 
    (SELECT post_id, meta_value FROM {$wpdb->prefix}postmeta WHERE meta_key = 'mulaYuran' ) mulaYuran 
    ON a.ID = mulaYuran.post_id
    LEFT JOIN 
    (SELECT post_id, meta_value FROM {$wpdb->prefix}postmeta WHERE meta_key = 'statusYuran' ) statusYuran 
    ON a.ID = statusYuran.post_id
     LEFT JOIN 
    (SELECT post_id, meta_value FROM {$wpdb->prefix}postmeta WHERE meta_key = 'paparanYuran' ) paparanYuran 
    ON a.ID = paparanYuran.post_id
     LEFT JOIN 
    (SELECT post_id, meta_value FROM {$wpdb->prefix}postmeta WHERE meta_key = 'arraykadarYuran' ) arraykadarYuran 
    ON a.ID = arraykadarYuran.post_id
   

   
    WHERE 
    a.post_type = 'product'
    AND a.ID = '{$_POST['id']}'
    ") 
);
foreach($get_jenisyuran AS $key => $val){
     $get_jenisyuran[$key]->arraykadarYuran = unserialize($val->arraykadarYuran);
}

$GLOBALS['khai_temp_data']['submitpost']['get_jenisyuran'] = $get_jenisyuran ;


}else{

$arg = array(
    'ID' => $_POST['id'],
    'post_title' => $_POST['tajukYuran'],
    'post_excerpt' => $_POST['keteranganYuran'],
    'post_author' =>  $GLOBALS['khai_temp_data']['khai_user']->ID
);

wp_update_post( $arg );
update_post_meta($_POST['id'], 'kariah_id', $_POST['kariah_id']) ; 
update_post_meta($_POST['id'], 'jenisYuran', $_POST['jenisYuran']) ; 
update_post_meta($_POST['id'], '_price', $_POST['jumlahYuran']) ; 
update_post_meta($_POST['id'], 'mulaYuran', $_POST['mulaYuran']) ; 
update_post_meta($_POST['id'], 'statusYuran', $_POST['statusYuran']) ; 
update_post_meta($_POST['id'], 'paparanYuran', $_POST['paparanYuran']) ; 
update_post_meta($_POST['id'], '_sold_individually', 'yes') ; 

//hide product
$terms = array( 'exclude-from-catalog', 'exclude-from-search' );
wp_set_object_terms( $_POST['id'], $terms, 'product_visibility' );


update_post_meta($_POST['id'], 'arraykadarYuran', json_decode(stripslashes($_POST['arraykadarYuran']))) ; 

$GLOBALS['khai_temp_data']['submitpost']['post'] = "Berjaya Kemaskini Jenis Yuran" ;



}


?>