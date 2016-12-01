<?php

	require_once('config.inc.php');
	require_once('functions.inc.php');

	session_is_set();

	$q = $_GET['q'];

	if($q == 'student_get_subject'){
		$class = $_GET['class'];
		$year = $_GET['year'];

		$conn = connect_db();

		$sql  = "SELECT * FROM section WHERE Class='$class' AND Years='$year' AND Deleted=0";
		$result = mysqli_query($conn, $sql);
		if($result->num_rows > 0){
			$data = array();
			check_login_status();
			foreach ($result as $row) {
				$teacher = mysqli_fetch_array(mysqli_query($conn, "SELECT FirstName, LastName, Image FROM account WHERE id='".$row['Teacher_ID']."'"));
				$subject = mysqli_fetch_array(mysqli_query($conn, "SELECT Subject_Name FROM subject WHERE Subject_ID='".$row['Subject_ID']."'"));
				$result = mysqli_query($conn, "SELECT * FROM section_has_student WHERE Section_ID='".$row['id']."' AND Student_ID='".$_SESSION['id']."'");
			 	if($result->num_rows ==1){
			 		$regist = true;
			 	}else{ $regist =  false; }
				$data[] = array(
							"id" 		=> $row['id'],
							"Section"	=> $row['Section'],
							"Class"		=> $row['Class'],
							"Years"		=> $row['Years'],
							"Term"		=> $row['Term'],
							"Classroom"	=> $row['Classroom'],
							"Day"		=> $row['Day'],
							"TimeStart"	=> $row['TimeStart'],
							"TimeEnd"	=> $row['TimeEnd'],
							"Teacher_ID"=> $row['Teacher_ID'],
							"Subject_ID"=> $row['Subject_ID'],
							"FirstName"	=> $teacher['FirstName'],
							"LastName"	=> $teacher['LastName'],
							"Image"		=> $teacher['Image'],
							"Subject_Name"=> $subject['Subject_Name'],
							"IsRegist"	=> $regist
					);
			}
			echo(json_encode($data));
		}
	}

	else if($q == 'teacher_get_subject'){

		$conn = connect_db();
		//echo($_SESSION['id']);

		$sql  = "SELECT * FROM section WHERE Teacher_ID='".$_SESSION['id']."' AND Deleted=0";
		$result = mysqli_query($conn, $sql);
		if($result->num_rows > 0){
			$data = array();
			check_login_status();
			foreach ($result as $row) {
				$subject = mysqli_fetch_array(mysqli_query($conn, "SELECT Subject_Name FROM subject WHERE Subject_ID='".$row['Subject_ID']."'"));
			 	if($result->num_rows ==1){
			 		$regist = true;
			 	}else{ $regist =  false; }
				$data[] = array(
							"id" 		=> $row['id'],
							"Section"	=> $row['Section'],
							"Class"		=> $row['Class'],
							"Years"		=> $row['Years'],
							"Term"		=> $row['Term'],
							"Classroom"	=> $row['Classroom'],
							"Day"		=> $row['Day'],
							"TimeStart"	=> $row['TimeStart'],
							"TimeEnd"	=> $row['TimeEnd'],
							"Teacher_ID"=> $row['Teacher_ID'],
							"Subject_ID"=> $row['Subject_ID'],
							"Subject_Name"=> $subject['Subject_Name']
					);
			}
			echo(json_encode($data));
		}
	}
	else if($q == 'create_subject'){
		$conn = connect_db();
		$sql1 = "INSERT INTO section (id, Section, Class, Years, Term, Classroom, Day, TimeStart, TimeEnd, Teacher_ID, Subject_ID, Period) VALUES (NULL, '".$_GET['section']."', '".$_GET['class']."', '".$_GET['year']."', '".$_GET['term']."', '".$_GET['classroom']."', '".$_GET['day']."', '".$_GET['start']."', '".$_GET['end']."', '".$_SESSION['id']."', '".$_GET['subject_id']."', '".$_GET['period']."')";
		$create_section = mysqli_query($conn, $sql1);
		$has_subject = mysqli_query($conn, "SELECT * FROM subject WHERE Subject_ID='".$_GET['subject_id']."' AND Subject_Name='".$_GET['subject_name']."'");
		echo($has_subject->num_rows == 1);
		if($has_subject->num_rows == 1){
			echo("success");
			redirect('../teacher-home.php');
		}
		else{
			$sql2 = "INSERT INTO subject (Subject_ID, Subject_Name) VALUES ('".$_GET['subject_id']."', '".$_GET['subject_name']."')";
			if( $create_section && mysqli_query($conn, $sql2)){
				echo("success");
				redirect('../teacher-home.php');
			}else{
				echo("error");
				redirect('../create_subject.php');
			}
		}

	}
	else if($q == 'get_subject_by_id'){
		$section_id = $_GET['section_id'];
		$conn = connect_db();
		$result = mysqli_query($conn, "SELECT * FROM section WHERE id='$section_id'");
		$data = mysqli_fetch_object($result);
		echo(json_encode($data));
	}
	else if($q == 'edit_subject'){
		$conn = connect_db();
		$sql = "UPDATE section SET Section = '".$_GET['section']."', Class = '".$_GET['class']."', Years = '".$_GET['year']."', Term = '".$_GET['term']."', Classroom = '".$_GET['classroom']."', Day = '".$_GET['day']."', TimeStart = '".$_GET['start']."', TimeEnd = '".$_GET['end']."', Teacher_ID = '".$_SESSION['id']."', Subject_ID = '".$_GET['subject_id']."', Period='".$_GET['period']."' WHERE id = '".$_GET['section_id']."'";
		if(mysqli_query($conn, $sql)){ 
			$has_subject = mysqli_query($conn, "SELECT * FROM subject WHERE Subject_ID='".$_GET['subject_id']."' AND Subject_Name='".$_GET['subject_name']."'");
			if($has_subject->num_rows == 1){
				echo("success");
				redirect('../teacher-home.php');
			}else{
				$sql2 = "INSERT INTO subject (Subject_ID, Subject_Name) VALUES ('".$_GET['subject_id']."', '".$_GET['subject_name']."')";
				if(mysqli_query($conn, $sql2)){
					echo("success");
					redirect('../teacher-home.php');
				}else{
					echo("error");
					redirect('../edit_subject.php');
				}
			}
		}
	}
	else if($q == 'delete_subject'){
		$conn = connect_db();
		$sql = "UPDATE section SET Deleted = '1' WHERE id = '".$_GET['section_id']."'";
		if(mysqli_query($conn, $sql)){
			echo("success");
		}else{
			echo("error");
		}
	}
	else if($q == 'get_subject'){
		$conn = connect_db();
		$subjects = mysqli_fetch_array(mysqli_query($conn,"SELECT Subject_ID,Section FROM section WHERE id='".$_GET['sid']."'"));
		$subjectss = mysqli_fetch_array(mysqli_query($conn,"SELECT Subject_Name FROM subject WHERE Subject_ID='".$subjects["Subject_ID"]."'"));
		$subjects['Subject_Name'] = $subjectss['Subject_Name'];
		echo(json_encode($subjects));


	}

?>