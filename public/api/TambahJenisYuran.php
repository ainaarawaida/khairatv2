<?php



$GLOBALS['khai_temp_data']['submitpost']['error'] = array() ; 
$product = new WC_Product_Simple();
$product->set_name( $_POST['tajukYuran']); // product title
$product->set_regular_price( $_POST['jumlahYuran'] ); // in current shop currency
$product->set_short_description( $_POST['keteranganYuran'] );
// you can also add a full product description
// $product->set_description( 'long description here...' );
// $product->set_image_id( 90 );
// let's suppose that our 'Accessories' category has ID = 19 
// $product->set_category_ids( array( 19 ) );
// you can also use $product->set_tag_ids() for tags, brands etc
$product_id = $product->save();
$arg = array(
    'ID' => $product_id,
    'post_author' =>  $GLOBALS['khai_temp_data']['khai_user']->ID
);

wp_update_post( $arg );
update_post_meta($product_id, 'kariah_id', $_POST['kariah_id']) ; 
update_post_meta($product_id, 'jenisYuran', $_POST['jenisYuran']) ; 
update_post_meta($product_id, 'mulaYuran', $_POST['mulaYuran']) ; 
update_post_meta($product_id, 'statusYuran', $_POST['statusYuran']) ; 
update_post_meta($product_id, 'paparanYuran', $_POST['paparanYuran']) ; 
// update_post_meta($product_id, '_sold_individually', 'yes') ; 

//hide product
$terms = array( 'exclude-from-catalog', 'exclude-from-search' );
wp_set_object_terms( $product_id, $terms, 'product_visibility' );

update_post_meta($product_id, 'arraykadarYuran', json_decode(stripslashes($_POST['arraykadarYuran']))) ; 

$GLOBALS['khai_temp_data']['submitpost']['post'] = "Berjaya Tambah Jenis Yuran" ;



?>