<!-- #!/usr/local/bin/php -q -->

<?php

	require_once('config.inc.php');
	require_once('functions.inc.php');


		$sid = $_POST["id"];
		$answer = $_POST["answer"];
		$sectionid = $_POST["sid"];

		echo($sid." ".$answer);


    	$id = null;
    	$qid = null;
    	$conn = connect_db();
    	$current_date = getCurrentDate();
    	if($current_question_query = mysqli_query($conn, "SELECT * FROM current_question WHERE Section_ID='$sectionid'")){
        	$current_question = mysqli_fetch_array($current_question_query);
        	$quiz_id = $current_question["Quiz_ID"];
			$qid = $current_question["Question_ID"];
		}
    	if($query_id = mysqli_query($conn, "SELECT id FROM account WHERE Student_ID='$sid'")){
    		$id = mysqli_fetch_array($query_id)['id'];
    	}
    	if(checkStudentRegister($conn ,$id, $sectionid)){
			if(hasSend($sid, $qid, $conn, $current_date, $quiz_id, $answer)){
				$question = mysqli_fetch_array(mysqli_query($conn, "SELECT Answer, Point FROM question WHERE id='".$current_question['Question_ID']."'"));
				$student_score = mysqli_query($conn, "SELECT id, Point FROM score WHERE Student_ID='$sid' AND Quiz_ID='".$current_question['Quiz_ID']."'");
				//echo($question['Point']);
				if($question['Answer'] == $answer){
					if($student_score->num_rows == 1){
						
						$student_score = mysqli_fetch_array($student_score);
						$point = (float)$student_score['Point'] + (float)$question['Point'];
						mysqli_query($conn, "UPDATE score SET Point = '$point' WHERE id='".$student_score['id']."'");
					}else{
						
						mysqli_query($conn,"INSERT INTO score (id, Student_ID, Quiz_ID, Point) VALUES (NULL,'$sid','".$current_question['Quiz_ID']."', '".$question['Point']."')");
					}
					//mysqli_query($conn, "INSERT INTO check_send_answer (Student_ID, Question_ID, Quiz_ID, Answer, Correct, Date) VALUES ('$sid', '$qid', '$quiz_id', '$answer', true, '$current_date')");
					mysqli_query($conn, "INSERT INTO check_send_answer (Student_ID, Question_ID, Quiz_ID, Answer, Correct) VALUES ('$sid', '$qid', '$quiz_id', '$answer', true)");
				}else{
					//mysqli_query($conn, "INSERT INTO check_send_answer (Student_ID, Question_ID, Quiz_ID, Answer, Correct, Date) VALUES ('$sid', '$qid', '$quiz_id', '$answer', false, '$current_date')");
					mysqli_query($conn, "INSERT INTO check_send_answer (Student_ID, Question_ID, Quiz_ID, Answer, Correct) VALUES ('$sid', '$qid', '$quiz_id', '$answer', false)");
				}
			}else echo("has send");//socket_write($msgsock, "send error", strlen("send error"));
		}else echo("register fail");//socket_write($msgsock, "regist error", strlen("regist error"));
		     

		function checkStudentRegister($conn, $id, $sid){
			if($id == null) return false;
			$isRegister = mysqli_query($conn, "SELECT * FROM section_has_student WHERE Student_ID='$id' AND Section_ID='$sid'");
			if($isRegister->num_rows == 1){ return true; }
			else{ return false; }
		}

		function hasSend($id, $qid, $conn, $current_date, $quiz_id, $answer){
			if($id == null || $qid == null) return false;
			if(mysqli_query($conn, "SHOW TABLES LIKE 'check_send_answer'")->num_rows==1){
				// $result = mysqli_query($conn, "SELECT * FROM check_send_answer WHERE Student_ID='$id' AND Question_ID='$qid' AND Date='$current_date'");
				$result = mysqli_query($conn, "SELECT * FROM check_send_answer WHERE Student_ID='$id' AND Question_ID='$qid'");
				if($result->num_rows == 1){
					echo("has send");
					return false;
				}else{
					return true;
				}
			}
			return false;
		}




?>