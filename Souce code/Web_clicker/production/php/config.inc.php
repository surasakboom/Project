<?php

define('DB_HOSTNAME', 'localhost'); 
define('DB_USERNAME', 'root'); 
define('DB_PASSWORD', '');
define('DB_DATABASE', 'clicker'); 

function connect_db() { 
 $conn = mysqli_connect(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
 	if (!$conn) {
    	die("Connection failed: " . mysqli_connect_error());
	}
	// mysqli_query($conn, "SET character_set_results=utf8");
	// mysqli_query($conn, "SET character_set_client=utf8");
	// mysqli_query($conn, "SET character_set_connection=utf8");
	mysqli_query($conn, "SET NAMES UTF8");
 return $conn; 
 } 

?>