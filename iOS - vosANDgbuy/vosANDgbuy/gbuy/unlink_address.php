<?php
	session_start();
	include('./lib/xmlrpc.inc');
	include('./conn.php');
	$xmlrpc_internalencoding = 'UTF-8';
	if (!isset($_SESSION['user_name'])){
		$login_ok = -1;
	}
	else {
		$login_ok = 1;
		$del_list = $_REQUEST['cart_item_list'];		
		
	    foreach ($del_list as $product){
			$client = new xmlrpc_client($conn_common);
			
			$msg = new xmlrpcmsg("login");
			$msg->addParam(new xmlrpcval($db,"string"));
			$msg->addParam(new xmlrpcval($user,"string"));
			$msg->addParam(new xmlrpcval($pass,"string"));
			$resp = $client->send($msg);
			$uid = $resp->value()->scalarval();
			
			$client = new xmlrpc_client($conn_object);
			
			$data = array(
				'partner_id'=>new xmlrpcval("","int"),
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
		
		};
	}
	
	if ($login_ok == 1){
		echo json_encode(array('login_ok'=>$login_ok,));
		}
	else {
		echo json_encode(array('login_ok'=>$login_ok,  ));
	}

?>