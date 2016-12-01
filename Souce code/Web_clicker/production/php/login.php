<?php
	
	require_once('config.inc.php'); 
	require_once('functions.inc.php'); 

	session_start();


	$user = $_POST['username'];
	$pass = $_POST['password'];

	$conn = connect_db();

	$result = mysqli_query($conn,"SELECT * FROM account WHERE UserName='".$user."' AND Password='".$pass."'");
	if($result->num_rows == 1){
		$row = mysqli_fetch_array($result);
		echo($row['Role']);
		$_SESSION['Role'] = $row['Role'];
		$_SESSION['username'] = $row['UserName'];
		$_SESSION['id'] = $row['id'];
		$_SESSION['logged_in'] = true;
		$_SESSION['startquiz'] = false;
	}
	else echo("incorrect");
?>