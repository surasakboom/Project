<?php

	require_once('config.inc.php');
	require_once('functions.inc.php');

	session_is_set();

	$conn = connect_db();
	$sql = "SELECT * FROM account WHERE id='".$_SESSION['id']."'";
	$result = mysqli_query($conn, $sql);
	$account = mysqli_fetch_object($result);
	echo(json_encode($account));


?>