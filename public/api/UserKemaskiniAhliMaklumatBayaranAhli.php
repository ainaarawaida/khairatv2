<?php


$GLOBALS['khai_temp_data']['submitpost'] = array(); 
$GLOBALS['khai_temp_data']['submitpost']['error'] = array() ; 

$pakej = get_user_meta( $_POST['id'], 'pakej', true ) ;


$get_jenisyuran = $wpdb->get_results( 
    $wpdb->prepare("
    SELECT 
    a.ID, 
    a.post_title as Tajuk, 
    jenisYuran.meta_value as jenisYuran, 
    Jumlah.meta_value as Jumlah, 
    a.post_excerpt as keteranganYuran,
    mulaYuran.meta_value as mulaYuran, 
    statusYuran.meta_value as statusYuran
   
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
   

   
    WHERE 
     a.post_type = 'product'
    AND kariah_id.meta_value = '{$_POST['kariah_id']}'
    ") 
);



$curr_date = date("Y-m-d");
// deb($get_jenisyuran);exit();
// deb($curr_date);
$senarai_yuran = array() ;

if($pakej){
    foreach($get_jenisyuran AS $key => $val){
        if(!in_array($val->ID, $pakej)){
            continue;
        }
    if($val->jenisYuran == "One-off"){
        $senarai_yuran[] = ["product_id" => $val->ID, "Tajuk" => $val->Tajuk, "Jenis Yuran" =>$val->jenisYuran, "Mula Yuran" =>$val->mulaYuran , "Jumlah" => $val->Jumlah, "Tarikh Yuran" => $val->mulaYuran];
           
    }
    if($val->jenisYuran == "Mingguan"){
           $checkdiffdate =  datediffInWeeks($val->mulaYuran, $curr_date);

           $tarikhyuran = $val->mulaYuran ; 
           for($i =  0 ; $i <= $checkdiffdate ; $i++){
            $senarai_yuran[] = ["product_id" => $val->ID, "Tajuk" => $val->Tajuk, "Jenis Yuran" =>$val->jenisYuran, "Mula Yuran" =>$val->mulaYuran, "Jumlah" => $val->Jumlah, "Tarikh Yuran" => $tarikhyuran];
            $tarikhyuran = date('Y-m-d', strtotime($tarikhyuran. '+ 7 days'));
           
          
            }
    }
    if($val->jenisYuran == "Bulanan"){
           $checkdiffdate =  datediffInMonths($val->mulaYuran, $curr_date);
           $tarikhyuran = $val->mulaYuran ; 
           for($i =  0 ; $i <= $checkdiffdate ; $i++){
            $senarai_yuran[] = ["product_id" => $val->ID, "Tajuk" => $val->Tajuk, "Jenis Yuran" =>$val->jenisYuran, "Mula Yuran" =>$val->mulaYuran, "Jumlah" => $val->Jumlah, "Tarikh Yuran" => $tarikhyuran];
            $tarikhyuran = date('Y-m-d', strtotime($tarikhyuran. '+ 1 month'));
           
          
            }
    }

     if($val->jenisYuran == "Tahunan"){
           $checkdiffdate =  datediffInYears($val->mulaYuran, $curr_date);
           
           $tarikhyuran = $val->mulaYuran ; 
           for($i =  0 ; $i <= $checkdiffdate ; $i++){
            $senarai_yuran[] = ["product_id" => $val->ID, "Tajuk" => $val->Tajuk, "Jenis Yuran" =>$val->jenisYuran, "Mula Yuran" =>$val->mulaYuran, "Jumlah" => $val->Jumlah, "Tarikh Yuran" => $tarikhyuran];
            $tarikhyuran = date('Y-m-d', strtotime($tarikhyuran. '+ 1 year'));
           
          
            }
    }
}


usort($senarai_yuran, function($a, $b) {
    return $a['Tarikh Yuran'] <=> $b['Tarikh Yuran'];
});


}


$GLOBALS['khai_temp_data']['submitpost']['senarai_yuran'] = $senarai_yuran ;


function datediffInWeeks($date1, $date2)
{
    $date1 = new DateTime($date1);
    $date2 = new DateTime($date2);
    // I have left the remainer. You may need to round up/down. 
    $differenceInWeeks = $date1->diff($date2)->days / 7;
    return ($differenceInWeeks);
}

function datediffInMonths($date1, $date2)
{
   $d1 = new DateTime($date1);
    $d2 = new DateTime($date2);
// @link http://www.php.net/manual/en/class.dateinterval.php
    $interval = $d2->diff($d1);
    $differenceInMonths = $interval->format('%m');
    return ($differenceInMonths);
}

function datediffInYears($date1, $date2)
{
   $d1 = new DateTime($date1);
    $d2 = new DateTime($date2);
// @link http://www.php.net/manual/en/class.dateinterval.php
    $interval = $d2->diff($d1);
    $differenceInYears = $interval->format('%y');
    return ($differenceInYears);
}

?>