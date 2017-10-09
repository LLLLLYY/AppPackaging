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
		$request_ok = 1;
		$postname=$_REQUEST['name'];
		$street=$_REQUEST['street'];
		$street2 = $_REQUEST['street2'];
		$city=$_REQUEST['city'];
		$phone =$_REQUEST['phone'];
		$country =$_REQUEST['country'];
		$postcode =$_REQUEST['postcode'];
		$email =$_REQUEST['email'];	
		$client = new xmlrpc_client($conn_common);
			
					
		$msg = new xmlrpcmsg("login");
		$msg->addParam(new xmlrpcval($db,"string"));
		$msg->addParam(new xmlrpcval($user,"string"));
		$msg->addParam(new xmlrpcval($pass,"string"));
		$resp = $client->send($msg);
		$uid = $resp->value()->scalarval();
			
		$client = new xmlrpc_client($conn_object);
				
							
				
		$arrayVal = array(
			'name'=>new xmlrpcval($postname, "string") ,
			'street'=>new xmlrpcval($street , "string"),					
			'ref'=>new xmlrpcval($_SESSION['user_name'],"string"),
			'street2'=>new xmlrpcval($street2 , "string"),
			'city'=>new xmlrpcval($city , "string"),
			'phone'=>new xmlrpcval($phone , "string"),
			'country_id'=>new xmlrpcval($country , "int"),
			'zip'=>new xmlrpcval($postcode , "string"),
			'email'=>new xmlrpcval($email , "string"),
			'notify_email'=>new xmlrpcval("none", "string"),
		);
				
				
		$msg = new xmlrpcmsg("execute");
		$msg->addParam(new xmlrpcval($db,"string"));
		$msg->addParam(new xmlrpcval($uid,"int"));
		$msg->addParam(new xmlrpcval($pass,"string"));
		$msg->addParam(new xmlrpcval("res.partner","string"));
		$msg->addParam(new xmlrpcval("create","string"));
		$msg->addParam(new xmlrpcval($arrayVal,"struct"));				
		$resp = $client->send($msg);
						
			}
			
		
	
	 
	
	if ($login_ok == 1){
		echo json_encode(array('login_ok'=>$login_ok, 'request_ok'=>$request_ok ));
		}
	else {
		echo json_encode(array('login_ok'=>$login_ok,));
	}
		
