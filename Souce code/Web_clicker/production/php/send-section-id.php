<?php

	require_once('config.inc.php');
	require_once('functions.inc.php');

	$conn = connect_db();
	$section_id = $_GET['section_id'];
	/* connect to pi*/

	//$room = mysqli_fetch_array(mysqli_query($conn, "SELECT Classroom FROM section WHERE id='$section_id'"));
	//$qaddress = mysqli_fetch_array(mysqli_query($conn, "SELECT IP FROM map_ip WHERE Room='".$room['Classroom']."'"));
	//$address = $qaddress['IP'];
	$address = $_SERVER['REMOTE_ADDR'];
	$service_port = 12345;
	echo $address;

	error_reporting(E_ALL);

	/* Create a TCP/IP socket. */
	$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
	if ($socket === false) {
	    echo "socket_create() failed: reason: " . socket_strerror(socket_last_error()) . "\n";
	} 

	//echo "Attempting to connect to '$address' on port '$service_port'...";
	$result = socket_connect($socket, $address, $service_port);
	if ($result === false) {
	    echo "socket_connect() failed.\nReason: ($result) " . socket_strerror(socket_last_error($socket)) . "\n";
	}else{
		echo "success";
	}

	$msg = "section id/".$section_id;
	//echo "Sending HTTP HEAD request...";
	socket_write($socket, $msg, strlen($msg));
	// $msg = "section id ".$section_id." ip : ".$address;
	// socket_write($socket, $section_id, strlen($section_id));
	//echo "OK.\n";
	//echo "Closing socket...";
	socket_close($socket);
	//echo "OK.\n\n";

	?>