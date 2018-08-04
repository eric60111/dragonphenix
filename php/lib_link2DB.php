<?php

	$lib_address = "mysql://$OPENSHIFT_MYSQL_DB_HOST:$OPENSHIFT_MYSQL_DB_PORT/";
	$lib_id = "admin9xY2fSd";
	$lib_pwd = "19sb8INPF-S1";
	$lib_dbname = "dragonphenix";

	define('DB_HOST', getenv('OPENSHIFT_MYSQL_DB_HOST'));
	define('DB_PORT', getenv('OPENSHIFT_MYSQL_DB_PORT'));
	define('DB_USER', getenv('OPENSHIFT_MYSQL_DB_USERNAME'));
	define('DB_PASS', getenv('OPENSHIFT_MYSQL_DB_PASSWORD'));
	define('DB_NAME', getenv('OPENSHIFT_APP_NAME'));

	$dbhost = constant("DB_HOST"); // Host name 
	$dbport = constant("DB_PORT"); // Host port
	$dbusername = constant("DB_USER"); // Mysql username 
	$dbpassword = constant("DB_PASS"); // Mysql password 
	$db_name = constant("DB_NAME"); // Database name 
	

	$lib_link = mysqli_connect($dbhost, $dbusername, $dbpassword, "", $dbport) or die("Error " . mysqli_error($lib_link));
	mysqli_select_db($lib_link, $db_name) or die("Error: " . mysqli_error($lib_link));

	mysqli_query($lib_link, "SET CHARACTER SET 'UTF8';");
	mysqli_query($lib_link, 'SET NAMES UTF8;');
	mysqli_query($lib_link, 'SET SESSION wait_timeout = 2880000;');
	mysqli_query($lib_link, 'SET SESSION interactive_timeout = 2880000;');
	mysqli_query($lib_link, 'SET CHARACTER_SET_CLIENT=UTF8;');
	mysqli_query($lib_link, 'SET CHARACTER_SET_RESULTS=UTF8;');
	
	ini_set('mysql.connect_timeout', 300);
	ini_set('default_socket_timeout', 300);

	
	function LogSql($lib_link, $sql) {
		mysqli_query($lib_link, stripslashes("INSERT INTO Logs VALUES (NULL, \"".date('Y-m-d H:i:s')."\", \"$sql\")"));
	}
?>