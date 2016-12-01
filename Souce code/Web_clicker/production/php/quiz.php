<?php
	
	require_once('config.inc.php');
	require_once('functions.inc.php');

	session_start();

	$q = $_GET['q'];

	if($q == 'get_quiz'){
		getQuiz();
	}
	else if($q == 'create_quiz'){
		createQuiz();
	}
	else if($q == 'get_question_display'){
		getQuestionDisplay();
	}
	else if($q == 'get_question_list'){
		getQuestionList();
	}
	else if($q == 'create_question'){
		createQuestion();
	}
	else if($q == 'delete_question'){
		deleteQuestion();
	}
	else if($q == 'get_question_by_id'){
		getQuestionById();
	}
	else if($q == 'edit_quiz'){
		editQuiz();
	}
	else if($q == 'delete_quiz'){
		deleteQuiz();
	}
	else if($q == 'update_question'){
		updateQuestion();
	}
	else if($q == 'set_current_question'){
		setCurrentQuestion();
	}
	else if($q =='get_question_count'){
		getQuestionCount();
	}
	else if($q == 'get_answer_history'){
		getAnswerHistory();
	}
	else if($q == 'get_all_quiz'){
		getAllQuiz();
	}
	else if($q == 'get_all_quiz_score'){
		getAllQuizScore();
	}

	function getQuiz(){
		$conn = connect_db();
		$sid = $_GET['sid'];

		/*$insert = mysqli_query($conn, "SELECT * FROM current_question WHERE Section_ID='$sid'");
		if($insert->num_rows == 0){
			mysqli_query($conn, "INSERT INTO current_question (Section_ID) VALUES ('$sid')");
		}else{
			mysqli_query($conn, "UPDATE current_question SET Quiz_ID=NULL, Question_ID=NULL WHERE Section_ID='$sid'");
		}*/

		$sql  = "SELECT * FROM quiz WHERE Section_ID='$sid' AND Deleted=0";
		$result = mysqli_query($conn, $sql);
		if($result->num_rows>0){
		//	mysqli_query($conn, "CREATE TABLE current_question (Section_ID int, Quiz_ID int, Question_ID int)");
		//	mysqli_query($conn, "INSERT INTO current_question (Section_ID) VALUES ('$sid')");
			$data = array();
			foreach ($result as $row) {
				# code...
				$data[] = $row;
			}
			echo(json_encode($data));
		}
	}

	function createQuiz(){
		$conn = connect_db();
		$qname = $_GET['qname'];
		$sid = $_GET['sid'];
		$sql  = "INSERT INTO quiz (id, Section_ID, Quiz_Name) VALUES (NULL, '$sid', '$qname')";
		if(mysqli_query($conn, $sql)){
			$max = mysqli_fetch_array(mysqli_query($conn, "SELECT MAX(id)  FROM quiz"));
			$result = mysqli_fetch_object(mysqli_query($conn, "SELECT * FROM quiz WHERE id='".$max[0]."'"));
			echo(json_encode($result));
		}else{echo('error');}

	}

	function getQuestionDisplay(){
		$conn = connect_db();

		$qid = $_GET['qid'];
		$sid = $_GET['sid'];

		$data = getQuestion($sid, $qid);
		$questionid = $data[0]['id'];
		$result = mysqli_query($conn, "SELECT * FROM current_question WHERE Section_ID='$sid'");
		if($result->num_rows > 0)
			mysqli_query($conn, "UPDATE current_question SET Quiz_ID = '$qid', Question_ID='$questionid' WHERE Section_ID='$sid'");
		else
			mysqli_query($conn, "INSERT INTO current_question (Section_ID, Quiz_ID, Question_ID) VALUES ('$sid', '$qid', '$questionid')");
		echo(json_encode($data));
	}

	function getQuestionList(){
		$qid = $_GET['qid'];
		$sid = $_GET['sid'];

		$data = getQuestion($sid, $qid);
		echo(json_encode($data));
	}

	function getQuestion($sid , $qid){
		$conn = connect_db();
		//createCheckSendAnswer($conn);
		

		/*if(($question_id = $_GET['question_id']) != ""){
				mysqli_query($conn, "UPDATE current_question SET Question_ID = '$question_id' WHERE Section_ID='$sid'");
		}else{
			mysqli_query($conn, "UPDATE current_question SET Quiz_ID = '$qid' WHERE Section_ID='$sid'");
		}*/
		
		$sql  = "SELECT * FROM question WHERE Quiz_ID='$qid'";
		$result = mysqli_query($conn, $sql);
		if($result->num_rows>0){
			$data = array();
			foreach ($result as $row) {
				# code...
				$sid_result = mysqli_fetch_array(mysqli_query($conn, "SELECT Subject_ID FROM section WHERE id='".$sid."'"));
				$qid_result = mysqli_fetch_array(mysqli_query($conn, "SELECT Quiz_Name FROM quiz WHERE id='".$qid."'"));
				//echo($sid_result['Subject_ID']);
				$subject_result = mysqli_fetch_array(mysqli_query($conn, "SELECT Subject_Name FROM subject WHERE Subject_ID='".$sid_result['Subject_ID']."'"));
				//echo($subject_result['Subject_Name']);
				//array_push($row, $subject_result['Subject_Name']);
				$row['Subject_Name'] = $subject_result['Subject_Name'];
				$row['Quiz_Name'] = $qid_result['Quiz_Name'];
				$data[] = $row;
			}

			//mysqli_query($conn, "UPDATE current_question SET Question_ID = '".$data[0]['id']."' WHERE Section_ID='$sid'");
			return $data;
			//echo(json_encode($data));
		}
	}

	function updateQuestion(){
		$conn = connect_db();
		$sql = "UPDATE question SET Quiz = '".$_POST['question']."', Choice1 = '".$_POST['choice1']."', Choice2 = '".$_POST['choice2']."', Choice3 = '".$_POST['choice3']."', Choice4 = '".$_POST['choice4']."', Answer = '".$_POST['choice']."', Answer_Description = '".$_POST['description']."', Lecture = '".$_POST['lecture']."', Quiz_ID = '".$_POST['qid']."', Point = '".$_POST['point']."' WHERE id = '".$_GET['question_id']."'";
		if(mysqli_query($conn, $sql)){
			echo("success");
		}else{
			echo("error");
		}
	}

	function createQuestion(){
		$conn = connect_db();
		//var_dump($_POST);
		$sql = "INSERT INTO question (id, Quiz, Choice1, Choice2, Choice3, Choice4, Answer, Answer_Description, Lecture, Quiz_ID, Point) VALUES (NULL, '".$_POST['question']."', '".$_POST['choice1']."', '".$_POST['choice2']."', '".$_POST['choice3']."', '".$_POST['choice4']."', '".$_POST['choice']."', '".$_POST['description']."', '".$_POST['lecture']."', '".$_POST['qid']."', '".$_POST['point']."')";
		if(mysqli_query($conn, $sql)){
			echo("success");
		}
		else{
			echo("error");
		}
	}

	function deleteQuestion(){
		$conn = connect_db();
		$sql = "DELETE FROM question WHERE id='".$_GET['qid']."'";
		if(mysqli_query($conn, $sql)){
			echo("success");
		}else{ echo("error"); }
	}

	function getQuestionById(){
		$conn = connect_db();
		$result = mysqli_query($conn, "SELECT * FROM question WHERE id='".$_GET['qid']."'");
		if($result->num_rows == 1){
			$data = mysqli_fetch_object($result);
			echo(json_encode($data));
		}

	}

	function editQuiz(){
		$conn = connect_db();
		$quiz_name = $_GET['quiz_name'];
		$quiz_id = $_GET['quiz_id'];
		$sql = "UPDATE quiz SET Quiz_Name = '$quiz_name' WHERE id = '$quiz_id'";
		if(mysqli_query($conn, $sql)){
			echo("success");
		}else{ 
			echo("error");
		}
	}

	function deleteQuiz(){
		$conn = connect_db();
		$quiz_id = $_GET['quiz_id'];
		$sql = "UPDATE quiz SET Deleted=1 WHERE id='$quiz_id'";
		if(mysqli_query($conn, $sql)){
			echo("success");
		}else{
			echo("error");
		}
	}

	function setCurrentQuestion(){
		$conn = connect_db();
		//createCheckSendAnswer($conn);
		$question_id = $_GET['question_id'];
		$sid = $_GET['sid'];
		if(mysqli_query($conn, "UPDATE current_question SET Question_ID = '$question_id' WHERE Section_ID='$sid'")){
			echo("success");
		}else{ echo("error"); }
	}

	function createCheckSendAnswer($conn){
		mysqli_query($conn, "DROP TABLE IF EXISTS check_send_answer");
		mysqli_query($conn, "CREATE TABLE check_send_answer (Student_ID double, Question_ID int)");
	}

	function getQuestionCount(){
		$quiz_id = $_GET['quiz_id'];
		$conn  = connect_db();
		$counts = mysqli_query($conn, "SELECT id FROM question WHERE Quiz_ID='$quiz_id'");
		$data = array();
		foreach ($counts as $count) {
			# code...
			$data[] = $count;
		}

		echo(json_encode($data));
	}

	function getAnswerHistory(){
		$question_id = $_GET['question_id'];
		$sid = $_GET['sid'];
		// $day = $_POST['day'];

		$conn = connect_db();

		$students = mysqli_query($conn, "SELECT Student_ID AS ID FROM section_has_student WHERE Section_ID='$sid'");
		// $result = mysqli_query($conn, "SELECT * FROM check_send_answer WHERE Question_ID='$question_id' AND Date='$day'");
		$result = mysqli_query($conn, "SELECT * FROM check_send_answer WHERE Question_ID='$question_id'");
		$data = array();
		if($students->num_rows > 0){
			foreach ($students as $student) {
				$has_send = false;
				$std = mysqli_fetch_array(mysqli_query($conn, "SELECT FirstName, LastName, Student_ID FROM account WHERE id='".$student['ID']."'"));
				$student['FirstName'] = $std['FirstName'];
				$student['LastName'] = $std['LastName'];
				$student['Student_ID'] = $std['Student_ID'];
				foreach ($result as $row) {
					if($row['Student_ID'] == $student['Student_ID']){
						$student['Correct'] = $row['Correct'];
						$student['Answer'] = $row['Answer'];
						$has_send = true;
					}
				}
				if(!$has_send){
					$student['Answer'] = '-';
					$student['Correct'] = -1;
				}
				$data[] = $student;

			}
			echo(json_encode($data));
			return;
		}
		echo("No Result");
	}

	function getAllQuiz(){
		$sid = $_GET['sid'];

		$conn = connect_db();
		$result = mysqli_query($conn, "SELECT id, Quiz_Name FROM quiz WHERE Section_ID='$sid' AND Deleted=0");
		$data = array();
		if($result->num_rows > 0){
			foreach ($result as $row) {
				$data[] = $row;
			}
		}

		echo(json_encode($data));

	}

	function getAllQuizScore(){
		$sid = $_GET['sid'];

		$conn = connect_db();
		$quizes = mysqli_query($conn, "SELECT id FROM quiz WHERE Section_ID='$sid' AND Deleted=0");
		$students = mysqli_query($conn, "SELECT Student_ID FROM section_has_student WHERE Section_ID='$sid'");
		$data_student = array();
		foreach ($students as $student) {
			$student_detail = mysqli_fetch_array(mysqli_query($conn, "SELECT id, FirstName, LastName, Student_ID FROM account WHERE id='".$student['Student_ID']."'"));
			$data = array();
			$total = 0;
			foreach ($quizes as $quiz) {
				if(($score_query = mysqli_query($conn, "SELECT Point FROM score WHERE Quiz_ID='".$quiz['id']."' AND Student_ID='".$student_detail['Student_ID']."'"))){
					$score = mysqli_fetch_array($score_query);
					//echo($score['Point']);
					$data[] = array(
							'Point' => $score['Point'] == null? 0: $score['Point']
						);
				}
				$total += $score['Point'];
			}
			$student_detail['Quiz'] = $data;
			$student_detail['Total'] = $total;
			$data_student[] = $student_detail;
		}

		echo(json_encode($data_student));	
		
		
	}

	
?>