<?php

	require_once('config.inc.php');
	require_once('functions.inc.php');

	$student_id = $_POST['student_id'];
	$current_section = $_POST['section_id'];

	$conn = connect_db();

	$current_time=getCurrentDate();
	//$current_section = mysqli_fetch_array(mysqli_query($conn, "SELECT Section_ID FROM current_question"));
	//$isStudentCheck = mysqli_query($conn, "SELECT Student_ID FROM check_student WHERE Student_ID='$student_id' AND Date='$current_time' AND Section_ID='".$current_section['Section_ID']."'");
	$isStudentCheck = mysqli_query($conn, "SELECT Student_ID FROM check_student WHERE Student_ID='$student_id' AND Date='$current_time' AND Section_ID='".$current_section."'");
	echo($isStudentCheck->num_rows);
	if($isStudentCheck->num_rows !=1){
		//if(mysqli_query($conn, "INSERT INTO check_student(Student_ID, Section_ID, Date) VALUES ('$student_id','".$current_section['Section_ID']."', '$current_time')")){
		if(mysqli_query($conn, "INSERT INTO check_student(Student_ID, Section_ID, Date) VALUES ('$student_id','".$current_section."', '$current_time')")){
			echo("success");
		}else{
			echo("fail on check");
		}

	}else{
		echo("fail");
	}

	?>