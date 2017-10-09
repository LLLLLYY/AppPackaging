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
				
		$arrayVal = array();
			$term = $_REQUEST['term'];
			$values=  array(array('name','ilike',$_REQUEST['term']),array('ref','=',$_SESSION['user_name']));
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
		// file_put_contents('testlog.txt', print_r($resp, true));//zz test
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
            $o = array(
             'id'=>$address['id']->scalarval(),             
             'name'=>$address['name']->scalarval(),
			 'email'=>$address['email']->scalarval(), 
             'street'=>$address['street']->scalarval(),   
			 'zip'=>$address['zip']->scalarval(),
             );
			$address_list[]=$o;			
		};		
			
	}



	if ($login_ok == 1){        
		//echo json_encode(array('login_ok'=>$login_ok, 'user_id'=>$_SESSION['user_id'], 'page'=>$offset+1,'address_count'=>$address_count, 'address_list'=>$address_list));
		echo json_encode($address_list);
		}
	else {
		echo json_encode(array('login_ok'=>$login_ok));
	}

?>