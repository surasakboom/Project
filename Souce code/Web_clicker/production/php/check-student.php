<?php

	require_once('config.inc.php');
	require_once('functions.inc.php');

	$conn = connect_db();
	$section_id = $_GET['section_id'];
	$current_time = getCurrentDate();
	$data = array();
	$students = mysqli_query($conn, "SELECT Student_ID FROM section_has_student WHERE Section_ID='$section_id'");
	foreach ($students as $student) {
		# code...
		$student_id = mysqli_fetch_array(mysqli_query($conn, "SELECT FirstName, LastName, Student_ID FROM account WHERE id='".$student['Student_ID']."'"));
		$is_student_come = mysqli_query($conn, "SELECT * FROM check_student WHERE Student_ID='".$student_id['Student_ID']."' AND Section_ID='$section_id' AND Date='$current_time'");
		if($is_student_come->num_rows == 1){
			$student_id['isCome'] = true;
		}else{
			$student_id['isCome'] = false;
		}
		$data[] = $student_id;
		
	}
	echo(json_encode($data));
?>

