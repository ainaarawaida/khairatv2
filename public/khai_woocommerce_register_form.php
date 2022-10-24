<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
    <label for="type_reg"><?php esc_html_e( 'Type Registration', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
    <select name="type_reg" id="type_reg" class="form-control woocommerce-Input woocommerce-Input--select input-select" required>
        <option value="pentadbir">Daftar Pentadbir</option>
        <option value="ahli">Daftar Ahli</option>
    </select>

</p>


<?php 
global $wpdb ;

$kariah_id = $wpdb->get_results( 
    $wpdb->prepare("SELECT ID,post_name,post_title FROM {$wpdb->prefix}posts WHERE post_type =%s", array('khai_page_name')) 
 );

?>

<p id="p_kariah_id" class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
    <label for="kariah_id"><?php esc_html_e( 'Kariah', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
    <select name="kariah_id" id="kariah_id" class="form-control woocommerce-Input woocommerce-Input--select input-select" required>
        <option></option>
        <?php foreach($kariah_id AS $key => $val ) { ?>
        <option value="<?php echo $val->ID ; ?>"><?php echo ($val->post_title) ; ?> </option>
      
        <?php } ?> 
    </select>

</p>

<!-- Styles -->
<link href="<?php echo KHAI_URL. '/public/css/bootstrap.min.css' ; ?>" rel="stylesheet" >
<link rel="stylesheet" href="<?php echo KHAI_URL. '/public/css/select2.min.css' ; ?>" />
<link rel="stylesheet" href="<?php echo KHAI_URL. '/public/css/select2-bootstrap-5-theme.min.css' ; ?>" />


<!-- Scripts -->
<script src="<?php echo KHAI_URL. '/public/js/bootstrap.bundle.min.js' ; ?>"></script>
<script src="<?php echo KHAI_URL. '/public/js/jquery.slim.min.js' ; ?>"></script>
<script src="<?php echo KHAI_URL. '/public/js/select2.min.js' ; ?>"></script>

<script>


document.addEventListener("DOMContentLoaded", function(){

            let ele2 = document.querySelectorAll("input.woocommerce-Input") ;
            // console.log(ele);
            for(let i = 0 ; i < ele2.length ; i++){
                ele2[i].classList.add('form-control');
            }


            let ele = document.querySelector("#type_reg") ;
          
            if(ele.value == 'ahli'){
                    document.querySelector("#p_kariah_id").style.display = 'block' ;
                    document.querySelector("#kariah_id").setAttribute('required','');
                    // document.querySelector("#p_kariah_id").required = true ;
                   }else{
                
                    document.querySelector("#p_kariah_id").style.display = 'none' ;
                    document.querySelector("#kariah_id").removeAttribute('required');
                    // document.querySelector("#p_kariah_id").required = false ;
                   }


            ele.addEventListener("change", function(e){
                   if(e.target.value == 'ahli'){
                    document.querySelector("#p_kariah_id").style.display = 'block' ;
                    document.querySelector("#kariah_id").setAttribute('required','');
                    // document.querySelector("#p_kariah_id").required = true ;
                   }else{
                    document.querySelector("#p_kariah_id").style.display = 'none' ;
                    document.querySelector("#kariah_id").removeAttribute('required');
                    // document.querySelector("#p_kariah_id").required = false ;
                   }
                   
                  
                
                
            });

         
           
        
    });


$( '#kariah_id' ).select2( {
    theme: 'bootstrap-5'
} );
</script>