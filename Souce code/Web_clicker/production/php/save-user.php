<?php
	
	require_once('config.inc.php'); 
	require_once('functions.inc.php'); 

	if($_GET['q'] == 'check_username'){
		$conn = connect_db();
		$result = mysqli_query($conn, "SELECT * FROM account WHERE UserName='".$_GET['username']."'");
		if($result->num_rows > 0){
			echo("false");
			return;
		}else{
			echo("true");
			return;
		}
	}

	
	$role = $_GET['role'];
	$Prefix = $_POST['prefix'];
	$FirstName = $_POST['firstname'];
	$LastName = $_POST['lastname'];
	$UserName = $_POST['username'];
	$Password = $_POST['password'];
	$Email = $_POST['email'];

	if(sizeof($_FILES) != 0){
		foreach ($_FILES as $file) {
			$file_name = $file['name'];
			$file_tmp = $file['tmp_name'];
			$file_path = "user_image/".$file_name;
			move_uploaded_file($file_tmp,"../".$file_path);
		}
	}else{
		$file_path = 'user_image/undefinde.png';
	}

	$conn  = connect_db();

	if($_GET['q'] == 'edit_teacher_profile'){
		$id = $_POST['id'];
		$ShortName = $_POST['shortname'];
		$sql = "UPDATE `account` SET Prefix = '$Prefix', UserName = '$UserName', Password = '$Password', FirstName = '$FirstName', LastName = '$LastName', ShortName = '$ShortName', Email = '$Email', Image = '$file_path' WHERE id = '$id'";

	}else if($_GET['q']== 'edit_student_profile'){
		$id = $_POST['id'];
		$stduent_id = $_POST['student_id'];
		$sql = "UPDATE `account` SET Prefix = '$Prefix', UserName = '$UserName', Password = '$Password', FirstName = '$FirstName', LastName = '$LastName', Student_ID = '$stduent_id', Email = '$Email', Image = '$file_path' WHERE id = '$id'";
	}
	else{
		//create user account
		if($role){
			$Student_ID = $_POST['id'];
			$sql = "INSERT INTO account (id, Prefix, UserName, Password, FirstName, LastName, ShortName, Email, Student_ID, Role, Image) VALUES (NULL, '$Prefix', '$UserName', '$Password', '$FirstName', '$LastName', '', '$Email', '$Student_ID', '$role', '$file_path')";
		}
		else{
			$ShortName = $_POST['shortname'];
			$sql = "INSERT INTO account (id, Prefix, UserName, Password, FirstName, LastName, ShortName, Email, Student_ID, Role, Image) VALUES (NULL, '$Prefix', '$UserName', '$Password', '$FirstName', '$LastName', '$ShortName', '$Email', '', '$role', '$file_path')";
		}
	}

	

	$result = mysqli_query($conn, $sql);
	if($result){
		print_r("complete");
	}
	else{
		print_r("incomplete");
	}

	$conn->close();


	

?>