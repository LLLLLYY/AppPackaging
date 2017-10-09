<?php
	session_start();
	include('./lib/xmlrpc.inc');
	include('./conn.php');
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
			new xmlrpcval("!=","string"),
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
		$order_count = $resp->value()->scalarval();
		
		$msg = new xmlrpcmsg("execute");
		$msg->addParam(new xmlrpcval($db,"string"));
		$msg->addParam(new xmlrpcval($uid,"int"));
		$msg->addParam(new xmlrpcval($pass,"string"));
		$msg->addParam(new xmlrpcval("gbuy.orderline","string"));
		$msg->addParam(new xmlrpcval("search","string"));
		$msg->addParam(new xmlrpcval($arrayVal,"array"));
		$msg->addParam(new xmlrpcval((string)($offset*40),"int"));
		$msg->addParam(new xmlrpcval("40000","int"));//////////////40 pan
		
		$resp = $client->send($msg);
		
		$ids = $resp->value()->scalarval();
		$order_ids = array();
		$i = 0;
		foreach ($ids as $id) {		
				
			$order_ids[$i] = $id->scalarval();
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
		$fields[9] = new xmlrpcval("confirm_time","string");
		
		$msg = new xmlrpcmsg("execute");
		$msg->addParam(new xmlrpcval($db,"string"));
		$msg->addParam(new xmlrpcval($uid,"int"));
		$msg->addParam(new xmlrpcval($pass,"string"));
		$msg->addParam(new xmlrpcval("gbuy.orderline","string"));
		$msg->addParam(new xmlrpcval("read","string"));
		$msg->addParam(new xmlrpcval($ids,"array"));
		$msg->addParam(new xmlrpcval($fields,"array"));
		$resp = $client->send($msg);
		$orders = $resp->value()->scalarval();
		
		$order_list = array();
		
		foreach ($orders as $ord) {
			$order = $ord->scalarval();
			$buyer_name = $order['buyer']->scalarval();
			$buyer_name = $buyer_name[1]->scalarval();
			$product = $order['product_id']->scalarval();
			$product_id = $product[0]->scalarval();
			$product_name =$product[1]->scalarval();
            $o = array(
             'id'=>$order['id']->scalarval(),
             //'buyer'=>$order['buyer']->scalarval(),
             //'buyer'=>$buyer_name,
             
             'product_name'=>urlencode($product_name),
             'list_price'=>$order['list_price']->scalarval(),
             //'product_name'=>$product,
             'discount'=>$order['discount']->scalarval(),
             'qty'=>$order['qty']->scalarval(),
             'subtotal'=>$order['subtotal']->scalarval(),
             'state'=>$order['state']->scalarval(),
             'confirm_time'=>$order['confirm_time']->scalarval(),

             );/////////pan
			/*$o = array(
				'id'=>$order['id']->scalarval(),
				//'buyer'=>$order['buyer']->scalarval(),
				'buyer'=>$buyer_name,
				'list_price'=>$order['list_price']->scalarval(),
				'product_name'=>urlencode($product_name),
				//'product_name'=>$product,
				'list_price'=>$order['list_price']->scalarval(),
				'qty'=>$order['qty']->scalarval(),
				'subtotal'=>$order['subtotal']->scalarval(),
				'confirm_time'=>$order['confirm_time']->scalarval(),
				'state'=>$order['state']->scalarval(),
				'discount'=>$order['discount']->scalarval(),
				'state'=>$order['state']->scalarval(),
				'confirm_time'=>$order['confirm_time']->scalarval(),
				);*/////////pan
			$order_list[]=$o;
			

			
		};
	}

/*///////////////////sort by date
    $order_list.sort(function(a, b){
                 a=new Date(a.confirm_time), b=new Date(b.confirm_time);
                 //return dateB-dateA; //sort by date ascending

                 return a>b ? -1 : a<b ? 1 : 0;
                                
                 });
/////////////////*/
// arsort($order_list);

// usort($order_list, function($a, $b) {
//     return $b['id'] - $a['id'];
// });

//usort($order_list, function ($a, $b) {
    //return a>b ? 1 : a<b ? -1 : 0;
//});





	if ($login_ok == 1){
		//echo json_encode(array('login_ok'=>$login_ok, 'user_id'=>$_SESSION['user_id'], 'page'=>$offset+1,'order_count'=>$order_count, 'order_list'=>$order_list));
        echo json_encode($order_list);
		}
	else {
		echo json_encode(array('login_ok'=>$login_ok));
	}

?>