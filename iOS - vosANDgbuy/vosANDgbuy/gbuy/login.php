<?php
	session_start();
	include('./lib/xmlrpc.inc');
	include('./conn.php');
	
	$login_ok = -1;
	$client = new xmlrpc_client($conn_common);
	
	// $db = "BOS";
	// $user = "test";
	// $pass = "test";
	
	$msg = new xmlrpcmsg("login");
	$msg->addParam(new xmlrpcval($db,"string"));
	$msg->addParam(new xmlrpcval($user,"string"));
	$msg->addParam(new xmlrpcval($pass,"string"));
	
	
	$resp = $client->send($msg);
	
	$s_login = $resp->faultCode();
	$s_uid = $resp->value()->scalarval();
	
	$arrayVal = array(
			new xmlrpcval(
			array(
			new xmlrpcval("email","string"),
			new xmlrpcval("=","string"),
			new xmlrpcval($_REQUEST['username'],"string")
			),"array"),
		);

	$client = new xmlrpc_client($conn_object);
	
	$msg = new xmlrpcmsg("execute");
	$msg->addParam(new xmlrpcval($db,"string"));
	$msg->addParam(new xmlrpcval($s_uid,"int"));
	$msg->addParam(new xmlrpcval($pass,"string"));
	$msg->addParam(new xmlrpcval("gbuy.user","string"));
	$msg->addParam(new xmlrpcval("search","string"));
	$msg->addParam(new xmlrpcval($arrayVal,"array"));
	
	$resp = $client->send($msg);
	$u_login = $resp->faultCode();
	$ids = $resp->value()->scalarval();
	if (count($ids) >=1) {
		$u_uid=$ids[0]->scalarval();
		
		$u_users = array();
		$u_users[0]=new xmlrpcval($u_uid,"int");
		$fields[0] = new xmlrpcval("email","string");
		$fields[1] = new xmlrpcval("name","string");
		$fields[2] = new xmlrpcval("password","string");
		
		$msg = new xmlrpcmsg("execute");
		$msg->addParam(new xmlrpcval($db,"string"));
		$msg->addParam(new xmlrpcval($s_uid,"int"));
		$msg->addParam(new xmlrpcval($pass,"string"));
		$msg->addParam(new xmlrpcval("gbuy.user","string"));
		$msg->addParam(new xmlrpcval("read","string"));
		$msg->addParam(new xmlrpcval($u_users,"array"));
		$msg->addParam(new xmlrpcval($fields,"array"));
		$resp = $client->send($msg);
		
		$field_vals = $resp->value()->scalarval();
		if (count($field_vals)>=1) {
			$field_val=$field_vals[0]->scalarval();
			$email = $field_val["email"]->scalarval();
			//echo "$email <br />";
			$name = utf8_encode($field_val["name"]->scalarval());
			//echo $name;
			$u_password = $field_val["password"]->scalarval();
			//echo $u_password;
			
			if ($_REQUEST['username'] == $email && $_REQUEST['password'] == $u_password) {
				$_SESSION['s_uid'] = $s_uid;
				$_SESSION['user_id'] = $u_uid;
				$_SESSION['user_name'] = $name;
				$_SESSION['user_email'] =$email;
				$login_ok = 1;
			}
			else {
				$u_uid = -1;
			};
		}
		else {
			$u_uid = -1;
		};
		

		
	}
	else {
		$u_uid = -1;
	};
	
	
	#echo $_REQUEST['username'];
	echo json_encode(array('login_ok'=>$login_ok,'s_login'=>$s_login,'s_uid'=>$s_uid,'u_login'=>$u_login,'u_uid'=>$u_uid, 'u_name'=>$name));

?>
