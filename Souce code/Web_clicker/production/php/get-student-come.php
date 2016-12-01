<?php

	require_once('config.inc.php');

	$conn = connect_db();

	$q = $_GET['q'];

	if($q == 'get_by_day'){

		$date = $_POST['day'];
		$query = "SELECT DISTINCT Section_ID FROM check_student WHERE Date='$date'";
		$students_come = mysqli_query($conn, $query);

		if($students_come->num_rows > 0){
			$data = array();
			foreach ($students_come as $section) {
				$subject_id = mysqli_fetch_array(mysqli_query($conn, "SELECT Subject_ID, Period FROM section WHERE id='".$section['Section_ID']."'"));
				$subject_name = mysqli_fetch_array(mysqli_query($conn, "SELECT Subject_Name FROM subject WHERE Subject_ID='".$subject_id['Subject_ID']."'"));
				$section['Subject_ID'] = $subject_id['Subject_ID'];
				$section['Period'] = $subject_id['Period'];
				$section['Subject_Name'] = $subject_name['Subject_Name'];
				$section['Date'] = $date;
				$students = mysqli_query($conn, "SELECT Student_ID FROM section_has_student WHERE Section_ID='".$section['Section_ID']."'");
				$sections = array();
				foreach ($students as $student) {
					$student_info = mysqli_fetch_array(mysqli_query($conn, "SELECT FirstName, LastName, Student_ID FROM account WHERE id='".$student['Student_ID']."'"));
					$is_student_come = mysqli_query($conn, "SELECT * FROM check_student WHERE Student_ID='".$student_info['Student_ID']."' AND Section_ID='".$section['Section_ID']."' AND Date='$date'");
					if($is_student_come->num_rows == 1){
						$student['isCome'] = true;
					}else{
						$student['isCome'] = false;
					}
					$come = mysqli_fetch_array(mysqli_query($conn, "SELECT COUNT(*) AS Come FROM check_student WHERE Student_ID='".$student_info['Student_ID']."' AND Section_ID='".$section['Section_ID']."'"));
					$student['Student_ID'] = $student_info['Student_ID'];
					$student['FirstName'] = $student_info['FirstName'];
					$student['LastName'] = $student_info['LastName'];
					$student['Come'] = $come['Come'];
					$sections[] = $student;
				}
				$section['Student'] = $sections;
				$data[] = $section;
			}
			echo(json_encode($data));
		}else{
			echo("No Student");
		}
	}
	else if($q == 'get_all'){
		$query = "SELECT DISTINCT Section_ID, Date FROM check_student";
		$students_come = mysqli_query($conn, $query);
		if($students_come->num_rows > 0){
			$data = array();
			foreach ($students_come as $section) {
				$subject_id = mysqli_fetch_array(mysqli_query($conn, "SELECT Subject_ID, Period FROM section WHERE id='".$section['Section_ID']."'"));
				$subject_name = mysqli_fetch_array(mysqli_query($conn, "SELECT Subject_Name FROM subject WHERE Subject_ID='".$subject_id['Subject_ID']."'"));
				$section['Subject_ID'] = $subject_id['Subject_ID'];
				$section['Period'] = $subject_id['Period'];
				$section['Subject_Name'] = $subject_name['Subject_Name'];
				$students = mysqli_query($conn, "SELECT Student_ID FROM section_has_student WHERE Section_ID='".$section['Section_ID']."'");
				$sections = array();
				foreach ($students as $student) {
					$student_info = mysqli_fetch_array(mysqli_query($conn, "SELECT FirstName, LastName, Student_ID FROM account WHERE id='".$student['Student_ID']."'"));
					$is_student_come = mysqli_query($conn, "SELECT * FROM check_student WHERE Student_ID='".$student_info['Student_ID']."' AND Section_ID='".$section['Section_ID']."' AND Date='".$section['Date']."'");
					if($is_student_come->num_rows == 1){
						$student['isCome'] = true;
					}else{
						$student['isCome'] = false;
					}
					$come = mysqli_fetch_array(mysqli_query($conn, "SELECT COUNT(*) AS Come FROM check_student WHERE Student_ID='".$student_info['Student_ID']."' AND Section_ID='".$section['Section_ID']."'"));
					$student['Student_ID'] = $student_info['Student_ID'];
					$student['FirstName'] = $student_info['FirstName'];
					$student['LastName'] = $student_info['LastName'];
					$student['Come'] = $come['Come'];
					$sections[] = $student;
				}
				$section['Student'] = $sections;
				$data[] = $section;
			}
			echo(json_encode($data));
		}else{
			echo("No Student");
		}
	}

	

	

?>