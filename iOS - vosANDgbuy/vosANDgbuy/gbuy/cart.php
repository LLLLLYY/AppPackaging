<?php
	session_start();
	include('./lib/xmlrpc.inc');
	include('./conn.php');
	$GLOBALS['xmlrpc_internalencoding']='UTF-8';
	if (!isset($_SESSION['user_name'])) {
		$login_ok = -1;
	}
	else {
		$login_ok = 1;
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
		
		$arrayVal = array(
			new xmlrpcval(
			array(
			new xmlrpcval("buyer.id","string"),
			new xmlrpcval("=","string"),
			new xmlrpcval($_SESSION['user_id'],"string")
			),"array"),
			new xmlrpcval(
			array(
			new xmlrpcval("state","string"),
			new xmlrpcval("=","string"),
			new xmlrpcval("draft","string")
			),"array"),
		);
		
		if (isset($_REQUEST['page'])){
			$offset=$_REQUEST['page']-1;
		}
		else {
			$offset=0;
		}
		
		$msg = new xmlrpcmsg("execute");
		$msg->addParam(new xmlrpcval($db,"string"));
		$msg->addParam(new xmlrpcval($uid,"int"));
		$msg->addParam(new xmlrpcval($pass,"string"));
		$msg->addParam(new xmlrpcval("gbuy.orderline","string"));
		$msg->addParam(new xmlrpcval("search_count","string"));
		$msg->addParam(new xmlrpcval($arrayVal,"array"));
		$resp = $client->send($msg);		
		$cart_count = $resp->value()->scalarval();
		
		$msg = new xmlrpcmsg("execute");
		$msg->addParam(new xmlrpcval($db,"string"));
		$msg->addParam(new xmlrpcval($uid,"int"));
		$msg->addParam(new xmlrpcval($pass,"string"));
		$msg->addParam(new xmlrpcval("gbuy.orderline","string"));
		$msg->addParam(new xmlrpcval("search","string"));
		$msg->addParam(new xmlrpcval($arrayVal,"array"));
		$msg->addParam(new xmlrpcval((string)($offset*40),"int"));
		$msg->addParam(new xmlrpcval("40","int"));
		
		$resp = $client->send($msg);
		
		$ids = $resp->value()->scalarval();
		$cart_ids = array();
		$i = 0;
		foreach ($ids as $id) {		
				
			$cart_ids[$i] = $id->scalarval();
			$i++;
			
		}
		
		$fields = array();
		$fields[0] = new xmlrpcval("product_id","string");
		$fields[1] = new xmlrpcval("list_price","string");
		$fields[2] = new xmlrpcval("qty","string"); 
		$fields[3] = new xmlrpcval("confirm_time","string");
		$fields[4] = new xmlrpcval("list_price","string");
		$fields[5] = new xmlrpcval("subtotal","string");
		$fields[6] = new xmlrpcval("state","string");
		$fields[7] = new xmlrpcval("buyer","string");
		$fields[8] = new xmlrpcval("discount","string");
		$fields[9] = new xmlrpcval("partner_id","string");
		
		$msg = new xmlrpcmsg("execute");
		$msg->addParam(new xmlrpcval($db,"string"));
		$msg->addParam(new xmlrpcval($uid,"int"));
		$msg->addParam(new xmlrpcval($pass,"string"));
		$msg->addParam(new xmlrpcval("gbuy.orderline","string"));
		$msg->addParam(new xmlrpcval("read","string"));
		$msg->addParam(new xmlrpcval($ids,"array"));
		$msg->addParam(new xmlrpcval($fields,"array"));
		$msg->addParam(new xmlrpcval(array("lang" => new xmlrpcval("zh_CN", "string") ), "struct") );
		$resp = $client->send($msg);
		$carts = $resp->value()->scalarval();
		
		$cart_list = array();
		
		foreach ($carts as $car) {
			$cart = $car->scalarval();
			$buyer_name = $cart['buyer']->scalarval();
			$buyer_name = $buyer_name[1]->scalarval();
			$product = $cart['product_id']->scalarval();			
			$product_id = $product[0]->scalarval();
			$product_name =$product[1]->scalarval();
			$partner = $cart['partner_id']->scalarval();
			if ($partner==null){
				$partner_id = "";
				$partner_name = "";
			}
			else {
				$partner_id = $partner[0]->scalarval();
				$partner_name = $partner[1]->scalarval();
			};
			$o = array(
				'id'=>$cart['id']->scalarval(),
				'buyer'=>$buyer_name,
				'list_price'=>$cart['list_price']->scalarval(),
				'product_name'=>$product_name,
				'list_price'=>$cart['list_price']->scalarval(),
				'qty'=>$cart['qty']->scalarval(),
				'subtotal'=>$cart['subtotal']->scalarval(),
				'confirm_time'=>$cart['confirm_time']->scalarval(),
				'state'=>$cart['state']->scalarval(),
				'discount'=>$cart['discount']->scalarval(),
				'state'=>$cart['state']->scalarval(),
				'partner_id'=>$partner_id,
				'partner_name'=>$partner_name,
				);
			$cart_list[]=$o;
						
		};
	}
	
	if ($login_ok == 1){
		echo json_encode(array('login_ok'=>$login_ok, 'user_id'=>$_SESSION['user_id'], 'page'=>$offset+1,'cart_count'=>$cart_count, 'cart_list'=>$cart_list));
		}
	else {
		echo json_encode(array('login_ok'=>$login_ok));
	}

?>