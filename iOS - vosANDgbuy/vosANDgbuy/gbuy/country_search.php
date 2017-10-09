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
		/*if ($_REQUEST['search_by'] == 'ar_no') {
			$arrayVal = array(
				new xmlrpcval(
				array(
				new xmlrpcval("default_code","string"),
				new xmlrpcval("ilike","string"),
				new xmlrpcval($_REQUEST['term'],"string")
				),"array"),
			);

		};
		*/
		if ($_REQUEST['search_by'] == 'name') {
			/*$arrayVal_term = array(
				new xmlrpcval(
				array(
				new xmlrpcval("name","string"),
				new xmlrpcval("ilike","string"),				
				new xmlrpcval($_REQUEST['term'],"string")				
				),"array"),
			);
			
			$arrayVal_user = array(
			new xmlrpcval(
			array(
			new xmlrpcval("buyer.id","string"),
			new xmlrpcval("=","string"),
			new xmlrpcval($_SESSION['user_id'],"string")
			),"array"),			
			);
			$arrayVal=array();
			
			array_push($arrayVal,arrayVal_term);
			array_push($arrayVal,arrayVal_user);*/
			
			$arrayVal = array();
			$term = $_REQUEST['term'];
			$values=  array(array('name','ilike',$_REQUEST['term']));
			foreach($values as $x){
					if(!empty($x)){
						array_push( $arrayVal,  new xmlrpcval( 
									array(  new xmlrpcval($x[0], "string" ),
									new xmlrpcval( $x[1],"string" ),
									new xmlrpcval( $x[2], xmlrpc_get_type($x[2]) )
									),"array")
								);
					}
				};
			/*
			$arrayVal = array(
				new xmlrpcval(
				array(
				new xmlrpcval("name","string"),
				new xmlrpcval("ilike","string"),				
				new xmlrpcval($_REQUEST['term'],"string")				
				),"array"),
			);*/
			
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
		$msg->addParam(new xmlrpcval("res.country","string"));		
		$msg->addParam(new xmlrpcval("search_count","string"));
		
		$msg->addParam(new xmlrpcval($arrayVal,"array"));

		$resp = $client->send($msg);		
		$product_count = $resp->value()->scalarval();
		
		
		
		$msg = new xmlrpcmsg("execute");
		$msg->addParam(new xmlrpcval($db,"string"));
		$msg->addParam(new xmlrpcval($uid,"int"));
		$msg->addParam(new xmlrpcval($pass,"string"));
		$msg->addParam(new xmlrpcval("res.country","string"));
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
		$fields[0] = new xmlrpcval("name","string");
		$fields[1] = new xmlrpcval("code","string");
		$fields[2] = new xmlrpcval("id","string");
				
		
		
		$msg = new xmlrpcmsg("execute");
		$msg->addParam(new xmlrpcval($db,"string"));
		$msg->addParam(new xmlrpcval($uid,"int"));
		$msg->addParam(new xmlrpcval($pass,"string"));		
		$msg->addParam(new xmlrpcval("res.country","string"));
		$msg->addParam(new xmlrpcval("read","string"));
		$msg->addParam(new xmlrpcval($ids,"array"));
		$msg->addParam(new xmlrpcval($fields,"array"));
		
		$resp = $client->send($msg);
		$addresses = $resp->value()->scalarval();
		
		$address_list = array();
		
		foreach ($addresses as $add) {
			$address = $add->scalarval();
			//$buyer_name = $address['buyer']->scalarval();
			//$buyer_name = $buyer_name[1]->scalarval();			
            $o = array(
             'id'=>$address['id']->scalarval(),             
             'name'=>$address['name']->scalarval(),             
             'code'=>$address['code']->scalarval(),   

             );
			$address_list[]=$o;			
		};		
		/*
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
		$msg->addParam(new xmlrpcval("gbuy.shipmentaddress","string"));
		$msg->addParam(new xmlrpcval("read","string"));
		$msg->addParam(new xmlrpcval($ids,"array"));
		$msg->addParam(new xmlrpcval($fields,"array"));
		$msg->addParam(new xmlrpcval(array("lang" => new xmlrpcval("zh_CN", "string") ), "struct") );
		
		$resp = $client->send($msg);
		$products = $resp->value()->scalarval();

		$address_list = array();
		
		foreach ($addresses as $add) {
			$address = $add->scalarval();
			$buyer_name = $address['buyer']->scalarval();
			$buyer_name = $buyer_name[1]->scalarval();			
            $o = array(
             'id'=>$address['id']->scalarval(),             
             'name'=>$address['name']->scalarval(),             
             'street'=>$address['street']->scalarval(),   

             );
			$address_list[]=$o;			
		};		

			
		};
		*/
	}
	
	
	if ($login_ok == 1){
		echo json_encode($address_list);
		}
	else {
		echo json_encode(array('login_ok'=>$login_ok));
	}
?>
