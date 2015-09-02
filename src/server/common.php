<?php
	ini_set('display_errors',0);
	error_reporting(0);
	date_default_timezone_set('Asia/Shanghai');
	session_start(); 

	
	function getParam($name, $value){
		return isset($_REQUEST[$name])?$_REQUEST[$name]:$value;
	}


	//die($_SESSION['username']);
	if(!isset($_SESSION['username'])||$_SESSION['username']==''){
		header("location: index.php");
	}// else {
	//	die($_SESSION['username']);
	//}


?>