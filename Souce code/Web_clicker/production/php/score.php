<?php

	require_once('config.inc.php');
	require_once('functions.inc.php');

	session_is_set();

	$q = $_GET['q'];

 	if($q == 'get_score_by_sid'){

		$sid = $_GET['sid'];

		$conn = connect_db();
		$data = array();
		$quiz_scores = mysqli_query($conn, "SELECT id, Quiz_Name FROM quiz WHERE Section_ID='$sid' AND Deleted=0");
		$student_id = mysqli_fetch_array(mysqli_query($conn, "SELECT Student_ID FROM account WHERE id='".$_SESSION['id']."'"));
		if($quiz_scores->num_rows > 0){
			foreach ($quiz_scores as $score) {
				# code...
				$point = mysqli_fetch_array(mysqli_query($conn, "SELECT Point FROM score WHERE Student_ID='".$student_id['Student_ID']."' AND Quiz_ID='".$score['id']."'"));
				$sum = mysqli_fetch_array(mysqli_query($conn, "SELECT SUM(Point) AS Total FROM question WHERE Quiz_ID='".$score['id']."'"));
				$correct = mysqli_fetch_array(mysqli_query($conn, "SELECT Count(Correct) AS Correct FROM check_send_answer WHERE Quiz_ID='".$score['id']."' AND Correct='1'"));
				$wrong = mysqli_fetch_array(mysqli_query($conn, "SELECT Count(Correct) AS Wrong FROM check_send_answer WHERE Quiz_ID='".$score['id']."' AND Correct='0'"));
				$question_count = mysqli_fetch_array(mysqli_query($conn, "SELECT Count(Quiz_ID) AS Question_Count FROM question WHERE Quiz_ID='".$score['id']."' "));
				$score['Point'] = $point['Point'] == null? 0: $point['Point'];
				$score['Total'] = $sum['Total'] == null? 0 : $sum['Total'];
				$score['Correct'] = $correct['Correct'] == null? 0 : $correct['Correct'];
				$score['Wrong'] = $wrong['Wrong'] == null? 0 : $wrong['Wrong'];
				if($score['Correct'] == 0 && $score['Wrong'] == 0){
					$score['Correct'] = '-';
					$score['Wrong'] = '-';
					$score['Point'] = '-';
				}
				$score['Question_Count'] = $question_count['Question_Count'] == null? 0 : $question_count['Question_Count'];
				$data[] = $score;
			}
		}
		echo(json_encode($data));
	}
	else if($q == 'get_student_score'){
		$section_id = $_GET['section_id'];
		$quiz_id = $_GET['quiz_id'];
		$data = array();
		$conn = connect_db();
		$students = mysqli_query($conn, "SELECT Student_ID FROM section_has_student WHERE Section_ID='$section_id'");
		foreach ($students as $student) {
			# code...
			$student_id = mysqli_fetch_array(mysqli_query($conn, "SELECT Student_ID, FirstName, LastName FROM account WHERE id='".$student['Student_ID']."'"));
			$correct = mysqli_fetch_array(mysqli_query($conn, "SELECT Count(Correct) AS Correct FROM check_send_answer WHERE Quiz_ID='$quiz_id' AND Student_ID='".$student_id["Student_ID"]."' AND Correct=1"));
			$incorrect = mysqli_fetch_array(mysqli_query($conn, "SELECT Count(Correct) AS Wrong FROM check_send_answer WHERE Quiz_ID='$quiz_id' AND Student_ID='".$student_id["Student_ID"]."' AND Correct=0"));
			if(($score_query = mysqli_query($conn, "SELECT Point FROM score WHERE Quiz_ID='$quiz_id' AND Student_ID='".$student_id['Student_ID']."'"))){
				$score = mysqli_fetch_array($score_query);
				$data[] = array(
									'Student_ID' 	=>	$student_id['Student_ID'],
									'FirstName'		=> 	$student_id['FirstName'],
									'LastName'		=> 	$student_id['LastName'],
									'Point'			=>	$score['Point'],
									'Correct'		=> 	$correct['Correct'],
									'Wrong'			=> 	$incorrect['Wrong']
								);
			}
			
		}
		echo(json_encode($data));
	}
	else if($q == 'get_total_score'){
		$quiz_id = $_GET['quiz_id'];
		$conn =connect_db();
		$total_score = mysqli_fetch_array(mysqli_query($conn, "SELECT SUM(Point) as Point FROM question WHERE Quiz_ID='$quiz_id'"));
		echo($total_score['Point']);
	}
	else if($q == 'get_score_pass'){
		$sid = $_GET['sectionid'];
		$quizid = $_GET['quizid'];
		$questionid = $_GET['questionid'];
		$conn = connect_db();
		$questions = mysqli_query($conn, "SELECT id FROM question WHERE Quiz_ID='$quizid' AND id <='$questionid'");
		//echo($questions->num_rows);
		$students = mysqli_query($conn, "SELECT Student_ID FROM section_has_student WHERE Section_ID='$sid'");
		$data_student = array();
		foreach ($students as $student) {
			$student_detail = mysqli_fetch_array(mysqli_query($conn, "SELECT id, FirstName, LastName, Student_ID FROM account WHERE id='".$student['Student_ID']."'"));
			$data = array();
			$total = 0;
			foreach ($questions as $question) {
				// echo($question['id'].' and '.$student['Student_ID']);
				$score_query = mysqli_query($conn, "SELECT * FROM check_send_answer WHERE Student_ID='".$student_detail['Student_ID']."' AND Question_ID='".$question['id']."' AND Correct=1");
				//echo($score_query->num_rows);
				if($score_query->num_rows>0){
					$point = mysqli_fetch_array(mysqli_query($conn, "SELECT Point FROM question WHERE id='".$question['id']."'"));
					$total += $point['Point'];
				}
			}
			$student_detail['Total'] = $total;
			$data_student[] = $student_detail;
			
		}

		echo(json_encode($data_student));	
	}


?>