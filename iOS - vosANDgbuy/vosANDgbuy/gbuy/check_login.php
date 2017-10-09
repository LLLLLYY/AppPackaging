<?php
	session_start();
	if (!isset($_SESSION['user_name'])) {
		$login_ok = -1;
	}
	else {
		$login_ok = 1;
	}
	
	if ($login_ok == 1){
		echo json_encode(array('login_ok'=>$login_ok,'login_name'=>$_SESSION['user_name'],'login_email'=>$_SESSION['user_email']));
		}
	else {
		echo json_encode(array('login_ok'=>$login_ok,  ));
	}
?>