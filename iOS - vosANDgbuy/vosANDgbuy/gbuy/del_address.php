<?php
	session_start();
	include('./lib/xmlrpc.inc');
	include('./conn.php');
	
	if (!isset($_SESSION['user_name'])){
		$login_ok = -1;
	}
	else {
		$login_ok = 1;
		$del_list = $_REQUEST['address_list'];	
		$ids = array();
		
		foreach ($del_list as $del_item){
			$ids[] = new xmlrpcval($del_item, "int");
		}
		
		
		$client = new xmlrpc_client($conn_common);
		$msg = new xmlrpcmsg("login");
		$msg->addParam(new xmlrpcval($db,"string"));
		$msg->addParam(new xmlrpcval($user,"string"));
		$msg->addParam(new xmlrpcval($pass,"string"));
		$resp = $client->send($msg);
		$uid = $resp->value()->scalarval();
		$client = new xmlrpc_client($conn_object);
		
		$msg = new xmlrpcmsg('execute');
		$msg->addParam(new xmlrpcval($db, "string"));
		$msg->addParam(new xmlrpcval($uid, "int"));
		$msg->addParam(new xmlrpcval($pass, "string"));
		$msg->addParam(new xmlrpcval("res.partner", "string"));
		$msg->addParam(new xmlrpcval("unlink", "string"));		
		$msg->addParam(new xmlrpcval($ids, "array"));		
		$resp = $client->send($msg);
		
	}
	
	if ($login_ok == 1){
		echo json_encode(array('login_ok'=>$login_ok,));
		}
	else {
		echo json_encode(array('login_ok'=>$login_ok,  ));
	}

?>