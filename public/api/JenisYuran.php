<?php

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
    paparanYuran.meta_value as paparanYuran
   
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
    (SELECT post_id, meta_value FROM {$wpdb->prefix}postmeta WHERE meta_key = 'kariah_id' ) kariah_id 
    ON a.ID = kariah_id.post_id
    LEFT JOIN 
    (SELECT post_id, meta_value FROM {$wpdb->prefix}postmeta WHERE meta_key = 'paparanYuran' ) paparanYuran 
    ON a.ID = paparanYuran.post_id
   
   

   
    WHERE 
    a.post_type = 'product'
    AND kariah_id.meta_value = '{$GLOBALS['khai_temp_data']['khai_user']->kariah_id}'
    ") 
);



$GLOBALS['khai_temp_data']['submitpost']['get_jenisyuran'] = $get_jenisyuran ;


?>