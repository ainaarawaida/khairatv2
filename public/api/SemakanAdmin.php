<?php



if($_POST['notasemakanadmin']){

$query = $wpdb->query($wpdb->prepare("INSERT INTO `{$wpdb->prefix}comments` 
(`comment_type`, `comment_parent`, `comment_content`, `user_id`, `comment_post_ID`,`comment_date`) 
values 
('khai_message', 0, '{$_POST['notasemakanadmin']}','{$_POST['pelulus']}', '{$_POST['id']}', CURRENT_TIMESTAMP() )"));

}

 update_user_meta($_POST['id'], 'stage_daftar', $_POST['semakanadmin']) ; 
 update_user_meta($_POST['id'], 'pelulus', $_POST['pelulus']) ; 
 


?>