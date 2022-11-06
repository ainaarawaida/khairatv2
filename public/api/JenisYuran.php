<?php

$product = new WC_Product_Simple();
$product->set_name( $_POST['yuran_nama']); // product title
$product->set_regular_price( $_POST['yuran_kadar'] ); // in current shop currency
$product->set_short_description( $_POST['yuran_keterangan'] );
// you can also add a full product description
// $product->set_description( 'long description here...' );
// $product->set_image_id( 90 );
// let's suppose that our 'Accessories' category has ID = 19 
// $product->set_category_ids( array( 19 ) );
// you can also use $product->set_tag_ids() for tags, brands etc
$product_id = $product->save();
$arg = array(
    'ID' => $product_id,
    'post_author' =>  $GLOBALS['khai_temp_data']['user_id']->ID
);
wp_update_post( $arg );
update_post_meta($product_id, 'yuran_jenis', $_POST['yuran_jenis']) ; 
update_post_meta($product_id, 'yuran_mula', $_POST['yuran_mula']) ; 
update_post_meta($product_id, 'yuran_status', $_POST['yuran_status']) ; 
update_post_meta($product_id, 'tambahkadaryuran', json_decode(stripslashes($_POST['tambahkadaryuran']))) ; 

$GLOBALS['khai_temp_data']['submitpost'] = true ;


?>