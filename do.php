<?php
	include_once 'jymengine.class.php';
	include_once 'config.php';
	session_start();
	if(isset($_REQUEST['action'])){
		if($debug) echo "I m in action.<br />";
		$action = $_REQUEST['action'];
		if(strcmp($_REQUEST['action'], "login")==0){
			if($debug) echo "I m in action login";
			$user = $_REQUEST['user'];
			$password = $_REQUEST['password'];
			$engine = new JYMEngine(CONSUMER_KEY, SECRET_KEY, $user, $password);
			if (!$engine->fetch_request_token()) die('Fetching request token failed');
			if (!$engine->fetch_access_token()) die('Fetching access token failed');
			if (!$engine->signon('Me on slac.in! Yay! :)')) die('Signon failed');
			$fh = fopen(".tmp/$user", "wb");
			fwrite($fh, serialize($engine));
			if(sizeof($engine)>10000)
				error_log("Damn! So big object got written check $user");
			$_SESSION['loggedIn']=1;
			$_SESSION['user']=$user;
			if($debug) echo "Logged in!";
			if($debug) var_dump($_REQUEST);
			header('Location: /');
		}
		if(strcmp($_REQUEST['action'], "getContacts")==0){
			if(!isset($_SESSION['loggedIn'])||$_SESSION['loggedIn']!=1){
				exit();
			}
			$user = $_SESSION['user'];
			$fh = fopen(".tmp/$user", "rb");
			$serialized_data= fread($fh, 10000);
			$engine=unserialize($serialized_data);
			var_dump( $engine->fetch_contact_list());
		}
		if(strcmp($_REQUEST['action'], "send")==0){
			if(!isset($_SESSION['loggedIn'])||$_SESSION['loggedIn']!=1){
				exit();
			}
			$user = $_SESSION['user'];
			$fh = fopen(".tmp/$user", "rb");
			$serialized_data= fread($fh, 10000);
			if($debug) var_dump($serialized_data);
			$engine=unserialize($serialized_data);
			if($debug) var_dump($engine);
			$msg = $_REQUEST['msg'];
			$to = $_REQUEST['to'];
			if($debug) echo "Sending $msg to $to";
			var_dump($engine->send_message($to, json_encode($msg)));
			fclose($fh);
			$fh = fopen(".tmp/$user", "wb");
			fwrite($fh, serialize($engine));
		}
		if(strcmp($_REQUEST['action'], "logout")==0){
			if(!isset($_SESSION['loggedIn'])||$_SESSION['loggedIn']!=1){
				exit();
			}
			$user = $_SESSION['user'];
			$fh = fopen(".tmp/$user", "rb");
			$serialized_data= fread($fh, 10000);
			$engine=unserialize($serialized_data);
			$engine->signoff();
			unlink(".tmp/$user");
			session_destroy();
			header('Location: /');
		}
	}
?>
