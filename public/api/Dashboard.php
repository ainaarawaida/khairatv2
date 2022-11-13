<?php

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
    e.meta_value IN ('0','1','2','3')
    AND b.ID = '{$GLOBALS['khai_temp_data']['khai_user']->data->kariah_id}'
    ") 
);


$GLOBALS['khai_temp_data']['submitpost']['get_senarai_ahli'] = $get_senarai_ahli ;


?>