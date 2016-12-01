<?php

	require_once('config.inc.php');
	require_once('functions.inc.php');

	session_is_set();

	$q = $_GET['q'];
	$conn = connect_db();

	if($q == 'list_answer'){
		if($_GET['realtime'] == 'true'){
			$sid = $_GET['sid'];
			$date = getCurrentDate();
			$question_id = mysqli_fetch_array(mysqli_query($conn, "SELECT Question_ID FROM current_question WHERE Section_ID='$sid'"));
			// $answers = mysqli_query($conn, "SELECT * FROM check_send_answer WHERE Date='$date' AND Question_ID='".$question_id['Question_ID']."'");
			$answers = mysqli_query($conn, "SELECT * FROM check_send_answer WHERE Question_ID='".$question_id['Question_ID']."'");
			$data = array();
			foreach ($answers as $answer) {
				$data[] = $answer;
			}
			echo(json_encode($data));
		}
	}
?>