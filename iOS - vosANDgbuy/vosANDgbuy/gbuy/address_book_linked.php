<?php
	session_start();
	include('./lib/xmlrpc.inc'); 
	$GLOBALS['xmlrpc_internalencoding']='UTF-8';
	include('./conn.php');
	if (!isset($_SESSION['user_name'])) {
		$login_ok = -1;
	}
	else {
		$login_ok = 1;
		$client = new xmlrpc_client($conn_common);		
				
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
			new xmlrpcval("ref","string"),
			new xmlrpcval("=","string"),
			new xmlrpcval($_SESSION['user_name'],"string")
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
		$msg->addParam(new xmlrpcval("res.partner","string"));
		$msg->addParam(new xmlrpcval("search_count","string"));
		$msg->addParam(new xmlrpcval($arrayVal,"array"));
		$resp = $client->send($msg);		
		$address_count = $resp->value()->scalarval();
		
		$msg = new xmlrpcmsg("execute");
		$msg->addParam(new xmlrpcval($db,"string"));
		$msg->addParam(new xmlrpcval($uid,"int"));
		$msg->addParam(new xmlrpcval($pass,"string"));
		$msg->addParam(new xmlrpcval("res.partner","string"));
		$msg->addParam(new xmlrpcval("search","string"));
		$msg->addParam(new xmlrpcval($arrayVal,"array"));
		$msg->addParam(new xmlrpcval((string)($offset*40),"int"));
		$msg->addParam(new xmlrpcval("40000","int"));//////////////40 pan
		
		$resp = $client->send($msg);
		
		$ids = $resp->value()->scalarval();
		$address_ids = array();
		$i = 0;
		foreach ($ids as $id) {		
				
			$address_ids[$i] = $id->scalarval();
			$i++;
			
		}
		
		$fields = array();
		$fields[0] = new xmlrpcval("name","string");
		$fields[1] = new xmlrpcval("email","string");
		$fields[2] = new xmlrpcval("street","string");
		$fields[3] = new xmlrpcval("zip","string");		
		$fields[4] = new xmlrpcval("phone","string");
		$fields[5] = new xmlrpcval("city","string");
		$fields[6] = new xmlrpcval("country_id","string");
		$fields[7] = new xmlrpcval("street2","string");
		
		$msg = new xmlrpcmsg("execute");
		$msg->addParam(new xmlrpcval($db,"string"));
		$msg->addParam(new xmlrpcval($uid,"int"));
		$msg->addParam(new xmlrpcval($pass,"string"));
		$msg->addParam(new xmlrpcval("res.partner","string"));
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
			$country_id= $address['country_id']->scalarval();
			$country_id= $country_id[1];
			if ($country_id == null){
						$country_id = "";
					}
					else {
						$country_id = $country_id->scalarval();
					}
            $o = array(
             'id'=>$address['id']->scalarval(),             
             'name'=>$address['name']->scalarval(),
			 'email'=>$address['email']->scalarval(), 
             'street'=>$address['street']->scalarval(),   
			 'zip'=>$address['zip']->scalarval(),
			 'phone'=>$address['phone']->scalarval(),
			 'city'=>$address['city']->scalarval(),
			 'country_id'=>$country_id,
			 'street2'=>$address['street2']->scalarval(),
             );
			$address_list[]=$o;			
		};		
			
	}



	if ($login_ok == 1){        
		echo json_encode(array('login_ok'=>$login_ok, 'user_id'=>$_SESSION['user_id'], 'page'=>$offset+1,'address_count'=>$address_count, 'address_list'=>$address_list));
		//echo json_encode($address_list);
		}
	else {
		echo json_encode(array('login_ok'=>$login_ok));
	}

?>