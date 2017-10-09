<?php
	# -*- encoding: utf-8 -*-
	session_start();
	include('./lib/xmlrpc.inc');
	include('./conn.php');

	$xmlrpc_internalencoding = 'UTF-8';

	if (!isset($_SESSION['user_name'])) {
		$login_ok = -1;
	}
	else {
		$login_ok = 1;
		$user_email = $_SESSION['user_email'];
		if ($_REQUEST['search_by'] == 'ar_no') {
			$arrayVal = array(
				new xmlrpcval(
				array(
				new xmlrpcval("default_code","string"),
				new xmlrpcval("ilike","string"),
				new xmlrpcval($_REQUEST['term'],"string")
				),"array"),
			);

		};
		
		if ($_REQUEST['search_by'] == 'ar_name') {
			$arrayVal = array(
				new xmlrpcval(
				array(
				new xmlrpcval("name","string"),
				new xmlrpcval("ilike","string"),
				//new xmlrpcval("少儿","string")
				new xmlrpcval($_REQUEST['term'],"string")
				),"array"),
			);
			
		};
		if (isset($_REQUEST['page'])){
			$offset=$_REQUEST['page']-1;
		}
		else {
			$offset=0;
		}
		
		$client = new xmlrpc_client($conn_common);
	
		// $db = "BOS";
		// $user = "test";
		// $pass = "test";
	
		$msg = new xmlrpcmsg("login");
		$msg->addParam(new xmlrpcval($db,"string"));
		$msg->addParam(new xmlrpcval($user,"string"));
		$msg->addParam(new xmlrpcval($pass,"string"));
		
		$resp = $client->send($msg);

		$uid = $resp->value()->scalarval();
		
		$client = new xmlrpc_client($conn_object);


		$msg = new xmlrpcmsg("execute");
		$msg->addParam(new xmlrpcval($db,"string"));
		$msg->addParam(new xmlrpcval($uid,"int"));
		$msg->addParam(new xmlrpcval($pass,"string"));
		$msg->addParam(new xmlrpcval("product.product","string"));		
		$msg->addParam(new xmlrpcval("search_count","string"));
		$msg->addParam(new xmlrpcval($arrayVal,"array"));

		$resp = $client->send($msg);		
		$product_count = $resp->value()->scalarval();
		
		
		
		$msg = new xmlrpcmsg("execute");
		$msg->addParam(new xmlrpcval($db,"string"));
		$msg->addParam(new xmlrpcval($uid,"int"));
		$msg->addParam(new xmlrpcval($pass,"string"));
		$msg->addParam(new xmlrpcval("product.product","string"));
		$msg->addParam(new xmlrpcval("search","string"));
		$msg->addParam(new xmlrpcval($arrayVal,"array"));

		$msg->addParam(new xmlrpcval((string)($offset*40),"int"));
		$msg->addParam(new xmlrpcval("40","int"));
		
		$resp = $client->send($msg);
		
		$ids = $resp->value()->scalarval();
		$db_ids = array();
		$i = 0;
		foreach ($ids as $id) {		
				
			$db_ids[$i] = $id->scalarval();
			$i++;
			
		}
		
		$fields = array();
		$fields[0] = new xmlrpcval("default_code","string");
		$fields[1] = new xmlrpcval("name","string");
		$fields[2] = new xmlrpcval("sale_ok","string");
		$fields[3] = new xmlrpcval("virtual_available","string");
		$fields[4] = new xmlrpcval("list_price","string");
		$msg = new xmlrpcmsg("execute");
		$msg->addParam(new xmlrpcval($db,"string"));
		$msg->addParam(new xmlrpcval($uid,"int"));
		$msg->addParam(new xmlrpcval($pass,"string"));
		$msg->addParam(new xmlrpcval("product.product","string"));
		$msg->addParam(new xmlrpcval("read","string"));
		$msg->addParam(new xmlrpcval($ids,"array"));
		$msg->addParam(new xmlrpcval($fields,"array"));
		$msg->addParam(new xmlrpcval(array("lang" => new xmlrpcval("zh_CN", "string") ), "struct") );
		
		$resp = $client->send($msg);
		$products = $resp->value()->scalarval();
/*
		$html_str = '';
		
		$html_str .= '<table><tr><th>db_id</th><th>Article No.</th><th>Name</th></tr>';
		
		foreach ($products as $prod) {
			$product = $prod->scalarval();
			$html_str .= '<tr>';
			$html_str .= '<td>' . $product['id']->scalarval() . '</td>';
			$html_str .= '<td>' . $product['default_code']->scalarval() . '</td>';
			$html_str .= '<td>' . $product['name']->scalarval() . '</td>';
			$html_str .= '</tr>';
			
		}
		
		$html_str .= "</table>";

	}
	//echo $html_str;
*/	
		$prod_list = array();
		
		foreach ($products as $prod) {
			$product = $prod->scalarval();
			if ($product['sale_ok']->scalarval()){
				$p = array(
					'id'=>$product['id']->scalarval(),
					'default_code'=>urlencode($product['default_code']->scalarval()),
					//'name'=>urlencode($product['name']->scalarval()),//zzzz0
					'name'=>$product['name']->scalarval(),//zzzz	cn		
					'list_price'=>$product['list_price']->scalarval(),//zzzz price			
					'virtual_available'=>$product['virtual_available']->scalarval()
					);
				$prod_list[]=$p;
			}
			/*$p = array(
				'id'=>$product['id']->scalarval(),
				'default_code'=>$product['default_code']->scalarval(),
				'name'=>$product['name']->scalarval()
				);
			$prod_list[]=$p;
			*/
			
		};
		
	}
	//echo json_encode(array('login_ok'=>$login_ok, 'user_email'=>$user_email, 'html_str'=>urlencode($html_str)));
	
	if ($login_ok == 1){
		echo json_encode(array('login_ok'=>$login_ok, 'user_email'=>$user_email, 'page'=>$offset+1,'product_count'=>$product_count, 'prod_list'=>$prod_list));
		}
	else {
		echo json_encode(array('login_ok'=>$login_ok));
	}
?>
