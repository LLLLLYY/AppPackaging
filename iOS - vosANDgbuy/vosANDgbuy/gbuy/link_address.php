<?php
	session_start();
	include('./lib/xmlrpc.inc');
	include('./conn.php');
	$xmlrpc_internalencoding = 'UTF-8';
	if (!isset($_SESSION['user_name'])) {
		$login_ok = -1;
	}
	else {
		$login_ok = 1;
		$product_id=$_REQUEST['product_id'];
		//$product_id=[3];
		
		//if (is_array($product_id)) {echo "postname is array.";};
		$partner_id=$_REQUEST['partner_id'];
		
		//$partner_id=[31];
		$client = new xmlrpc_client($conn_common);
		/*$db = "test";
		$user = "vos_admin";
		$pass = "vos_admin";*/
		foreach ($product_id as $product){
			$client = new xmlrpc_client($conn_common);
			
			$msg = new xmlrpcmsg("login");
			$msg->addParam(new xmlrpcval($db,"string"));
			$msg->addParam(new xmlrpcval($user,"string"));
			$msg->addParam(new xmlrpcval($pass,"string"));
			$resp = $client->send($msg);
			$uid = $resp->value()->scalarval();
			
			$client = new xmlrpc_client($conn_object);
			
			$data = array(
				'partner_id'=>new xmlrpcval($partner_id,"int"),
			);
			
			$ids =array();
			$ids[] = new xmlrpcval($product,"int");
			$msg = new xmlrpcmsg('execute');
			$msg->addParam(new xmlrpcval($db, "string"));
			$msg->addParam(new xmlrpcval($uid, "int"));
			$msg->addParam(new xmlrpcval($pass, "string"));
			
			$msg->addParam(new xmlrpcval("gbuy.orderline", "string"));			
			$msg->addParam(new xmlrpcval("write", "string"));			
			$msg->addParam(new xmlrpcval($ids, "array"));
			$msg->addParam(new xmlrpcval($data, "struct"));
			
			
			echo "///";
			$resp = $client->send($msg);
		
	}
	}
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
	if ($login_ok == 1){
		echo json_encode(array('login_ok'=>$login_ok,'product_id'=>$product_id,'partner_id'=>$partner_id));
		}
	else {
		echo json_encode(array('login_ok'=>$login_ok));
	}

?>