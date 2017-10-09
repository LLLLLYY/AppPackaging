<?php
	session_start();
	include('./lib/xmlrpc.inc');
	include('./conn.php');
	if (!isset($_SESSION['user_name'])) {
		$login_ok = -1;
	}
	else {
		$login_ok= 1;
		
		if (!(isset($_REQUEST['cart_list'])||($_REQUEST['cart_list'] == null))) {
			$request_ok = -1;			
		}
		else {
			$request_ok = 1;
			$cart_list = $_REQUEST['cart_list'];
			$cart_list = json_decode($cart_list);
			//if (is_array($cart_list)) {echo "cart_list is array.";};
			//if (is_string($cart_list)) {echo "cart_list is string.";};
			
			foreach ($cart_list as $cart_item){
				//echo $cart_item[0];
				//echo "  ";
				//echo $cart_item[1];
				$cart_item_id = $cart_item[0];
				$cart_item_qty = $cart_item[1];
				
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
				
				$user_ids = array();
				$user_ids[] = new xmlrpcval($_SESSION['user_id'],"int");
				$fields = array();
				$fields[0] = new xmlrpcval("name","string");
				$fields[1] = new xmlrpcval("country","string");
				$fields[2] = new xmlrpcval("group_id","string");
				
				$msg = new xmlrpcmsg("execute");
				$msg->addParam(new xmlrpcval($db,"string"));
				$msg->addParam(new xmlrpcval($uid,"int"));
				$msg->addParam(new xmlrpcval($pass,"string"));
				$msg->addParam(new xmlrpcval("gbuy.user","string"));
				$msg->addParam(new xmlrpcval("read","string"));
				$msg->addParam(new xmlrpcval($user_ids,"array"));
				$msg->addParam(new xmlrpcval($fields,"array"));
				$resp = $client->send($msg);
				$user_infos = $resp->value()->scalarval();
				$user_info = $user_infos[0]->scalarval();
				$user_countrys = $user_info['country']->scalarval();
				if (count($user_countrys)>0){
					
					$user_country = $user_countrys[0];
					if ($user_country == null){
						$user_country = "";
					}
					else {
						$user_country = $user_country->scalarval();
					}
				}
				
				$user_groups = $user_info['group_id']->scalarval();
				$user_group = $user_groups[0]->scalarval();
				echo "+" . $user_info['name']->scalarval() . "+" . $user_country . "+" . $user_group; 
				
				if ($user_country == "58"){
					$user_tax = "37";
				}
				else {
					$user_tax = "33";
				}
				
				$arrayVal = array(
					
					'product_id'=>new xmlrpcval($cart_item_id, "int") ,
					'tax'=>new xmlrpcval($user_tax , "int"),
					'qty'=>new xmlrpcval($cart_item_qty,"int"),
					'buyer'=>new xmlrpcval($_SESSION['user_id'],"int"),
					'state'=>new xmlrpcval('draft',"string")
				);
				
				
				$msg = new xmlrpcmsg("execute");
				$msg->addParam(new xmlrpcval($db,"string"));
				$msg->addParam(new xmlrpcval($uid,"int"));
				$msg->addParam(new xmlrpcval($pass,"string"));
				$msg->addParam(new xmlrpcval("gbuy.orderline","string"));
				$msg->addParam(new xmlrpcval("create","string"));
				$msg->addParam(new xmlrpcval($arrayVal,"struct"));
				//echo "!user_id:" . $_SESSION['user_id'];
				$resp = $client->send($msg);		
				//echo "!!!" ;
				$draft_item = $resp->value()->scalarval();
				$draft_items = array();
				$draft_items[] = new xmlrpcval($draft_item,"int");
				//echo "*" . $draft_item . "*";
				
				$msg = new xmlrpcmsg("execute");
				$msg->addParam(new xmlrpcval($db,"string"));
				$msg->addParam(new xmlrpcval($uid,"int"));
				$msg->addParam(new xmlrpcval($pass,"string"));
				$msg->addParam(new xmlrpcval("gbuy.orderline","string"));
				$msg->addParam(new xmlrpcval("apply_group_discount","string"));
				$msg->addParam(new xmlrpcval($draft_items,"array"));
				$resp = $client->send($msg);
			}
			
		}
	
	} 
	
	if ($login_ok == 1){
		echo json_encode(array('login_ok'=>$login_ok, 'request_ok'=>$request_ok ));
		}
	else {
		echo json_encode(array('login_ok'=>$login_ok,));
	}
?>