<?php

	require_once('config.inc.php');
	require_once('functions.inc.php');

	session_is_set();

	$q = $_GET['q'];
	

	if($q == 'register'){
		
		register();
	}

	function register(){
		$sid = $_GET['section_id'];
		
		$conn = connect_db();

		$result = mysqli_query($conn, "INSERT INTO section_has_student (Student_ID, Section_ID) VALUES ('".$_SESSION['id']."', '".$sid."')");
		if($result){ echo("success");}
		else{echo("incomplete");}
	}


?>