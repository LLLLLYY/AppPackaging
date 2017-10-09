<?php
	session_start();
	unset($_SESSION['s_uid']);
	unset($_SESSION['user_id']);
	unset($_SESSION['user_name']);
	unset($_SESSION['user_email']);
	
	echo json_encode(array('login_ok'=>-1));
	
	
?>