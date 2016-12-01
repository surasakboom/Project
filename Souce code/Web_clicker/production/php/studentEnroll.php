<?php

	require_once('config.inc.php');
	require_once('functions.inc.php');

	session_is_set();

	$conn = connect_db();

	$sid = $_GET['sid'];

	$subject = mysqli_fetch_array(mysqli_query($conn, "SELECT id, Subject_ID, Section FROM section WHERE id='$sid'"));

	
	$subject_name = mysqli_fetch_array(mysqli_query($conn, "SELECT Subject_Name FROM subject WHERE Subject_ID='".$subject['Subject_ID']."'"));
	$subject['Subject_Name'] = $subject_name['Subject_Name'];
	$students = mysqli_query($conn, "SELECT Student_ID FROM section_has_student WHERE Section_ID='".$subject['id']."'");
	$data_student = array();
	foreach ($students as $student) {
		# code...
		$student_detail = mysqli_fetch_object(mysqli_query($conn, "SELECT id, FirstName, LastName, Student_ID FROM account WHERE id='".$student['Student_ID']."'"));
		$data_student[] = $student_detail;
	}
	$subject['Student_Detail'] = $data_student;
	$data[] = $subject;
		// }
	// echo(json_encode($data));
	echo(json_encode($subject));
	// }



?>