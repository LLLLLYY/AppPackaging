<?php

	session_start();
	include('./lib/xmlrpc.inc');
	include('./conn.php');
	
	if (!isset($_SESSION['user_name'])) {
		$login_ok = -1;		
	}
	else {
		$login_ok = 1;
		$cart_list = $_REQUEST['cart_list'];
		$cart_list = json_decode($cart_list);
		foreach ($cart_list as $cart_item){
			$cart_item_id = $cart_item[0];
			$cart_item_id = str_replace("cart_","",$cart_item_id);
			
			$cart_item_qty = $cart_item[1];
			echo $cart_item_qty . "||";
			$client = new xmlrpc_client($conn_common);
	
			/*$db = "test";
			$user = "vos_admin";
			$pass = "vos_admin";*/
				
			$msg = new xmlrpcmsg("login");
			$msg->addParam(new xmlrpcval($db,"string"));
			$msg->addParam(new xmlrpcval($user,"string"));
			$msg->addParam(new xmlrpcval($pass,"string"));
			$resp = $client->send($msg);
			$uid = $resp->value()->scalarval();
			$client = new xmlrpc_client($conn_object);
			
			
			$data = array(
				'qty'=>new xmlrpcval($cart_item_qty,"int"),
			);
			$ids =array();
			$ids[] = new xmlrpcval($cart_item_id,"int");
			$msg = new xmlrpcmsg('execute');
			$msg->addParam(new xmlrpcval($db, "string"));
			$msg->addParam(new xmlrpcval($uid, "int"));
			$msg->addParam(new xmlrpcval($pass, "string"));
			
			$msg->addParam(new xmlrpcval("gbuy.orderline", "string"));
			if ($cart_item_qty == "") {
				$msg->addParam(new xmlrpcval("unlink", "string"));
			}
			else {
				$msg->addParam(new xmlrpcval("write", "string"));
			}
			$msg->addParam(new xmlrpcval($ids, "array"));
			if (!$cart_item_qty == ""){
				$msg->addParam(new xmlrpcval($data, "struct"));
			}
			
			echo "///";
			$resp = $client->send($msg);

		}
		
		
		
		echo $cart_item_id . "||";
	}
	
	if ($login_ok == 1){
		echo json_encode(array('login_ok'=>$login_ok,));
		}
	else {
		echo json_encode(array('login_ok'=>$login_ok,  ));
	}
?>