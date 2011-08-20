<?php
    include_once 'jymengine.class.php';
	include_once 'config.php';
	session_start();
	if(isset($_REQUEST['action'])){
		$action = $_REQUEST['action'];
		if(strcmp($_REQUEST['action'], "login")){
			$user = $_REQUEST['user'];
			$password = $_REQUEST['password'];
			$engine = new JYMEngine(CONSUMER_KEY, SECRET_KEY, $user, $password);
			if (!$engine->fetch_request_token()) die('Fetching request token failed');
			if (!$engine->fetch_access_token()) die('Fetching access token failed');
			if (!$engine->signon('Me on slac.in! Yay! :)')) die('Signon failed');
			$fh = fopen(".$user", "wb");
			fwrite($fh, serialize($engine));
			if(sizeof($engine)>10000)
				error_log("Damn! So big object got written check $user");
			$_SESSION['loggedIn']=1;
			$_SESSION['user']=$user;
		}
		if(strcmp($_REQUEST['action'], "send")){
			if(!isset($_SESSION['loggedIn'])||$_SESSION['loggedIn']!=1){
				exit();
			}
			$fh = fopen(".$user", "rb");
			$engine=unserialize(fread($fh, 10000));
			$msg = $_REQUEST['msg'];
			$to = $_REQUEST['to'];
			$engine->send_message($to, json_encode($msg));
			fclose($fh);
			$fh = fopen(".$user", "wb");
			fwrite($fh, serialize($engine));
		}
		if(strcmp($_REQUEST['action'], "logout")){
			if(!isset($_SESSION['loggedIn'])||$_SESSION['loggedIn']!=1){
				exit();
			}
			unlink(".$user");
			session_destroy();
		}
	}
?>