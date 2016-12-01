<?php
	if (!isset($_SESSION))
	{    
		session_start();
	}    

	$_SESSION['variable'] = "hello world";

	$sessions = array();

	$sessions['variable'] = $_SESSION['variable'];

	header('Content-Type: application/json');
	echo json_encode($sessions);
?>