<?php

require_once(ABSPATH.'wp-admin/includes/user.php');
$GLOBALS['khai_temp_data']['submitpost']['post'] = wp_delete_user($_POST['ID']) ;



?>