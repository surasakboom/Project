<?php

	require_once('config.inc.php');
	require('../fpdf/fpdf.php');

	define('FPDF_FONTPATH','font/');

	//$params = json_decode(file_get_contents('php://input'));

	// var_dump($_POST);

	$section_id = $_GET['section_id'];
	$quiz_id = $_GET['quiz_id'];

	//echo(sizeof($params));

	$conn = connect_db();
	$quiz_name = mysqli_fetch_array(mysqli_query($conn, "SELECT Quiz_Name FROM quiz WHERE id='$quiz_id'"));
	$subject_id = mysqli_fetch_array(mysqli_query($conn, "SELECT Subject_ID FROM section WHERE id='$section_id'"));
	$subject_name = mysqli_fetch_array(mysqli_query($conn, "SELECT Subject_Name FROM subject WHERE Subject_ID='".$subject_id['Subject_ID']."'"));

	function question2pdf($pdf, $conn, $quiz_id){
		$questions = mysqli_query($conn, "SELECT * FROM question WHERE Quiz_ID='$quiz_id'");
		$pdf->SetFont('angsana', '', 20);
		$count = 1;
		foreach ($questions as $question) {
			$pdf->Ln();
			$pdf->Cell(0,10,iconv( 'UTF-8','TIS-620',"$count. ".$question['Quiz']),0,1);
			$pdf->Cell(5);
			$pdf->Cell(0, 10, iconv( 'UTF-8','TIS-620',"1. ".$question['Choice1']),0,1);
			$pdf->Cell(5);
			$pdf->Cell(0, 10, iconv( 'UTF-8','TIS-620',"2. ".$question['Choice2']),0,1);
			$pdf->Cell(5);
			$pdf->Cell(0, 10, iconv( 'UTF-8','TIS-620',"3. ".$question['Choice3']),0,1);
			$pdf->Cell(5);
			$pdf->Cell(0, 10, iconv( 'UTF-8','TIS-620',"4. ".$question['Choice4']),0,1);
			$count++;
		}
		
		
	}

	$pdf = new FPDF();
	// เพิ่มฟอนต์ภาษาไทยเข้ามา ตัวธรรมดา กำหนด ชื่อ เป็น angsana
	$pdf->AddFont('angsana','','angsa.php');
	// เพิ่มฟอนต์ภาษาไทยเข้ามา ตัวหนา  กำหนด ชื่อ เป็น angsana
	$pdf->AddFont('angsana','B','angsab.php');
	// เพิ่มฟอนต์ภาษาไทยเข้ามา ตัวหนา  กำหนด ชื่อ เป็น angsana
	$pdf->AddFont('angsana','I','angsai.php');
	// เพิ่มฟอนต์ภาษาไทยเข้ามา ตัวหนา  กำหนด ชื่อ เป็น angsana
	$pdf->AddFont('angsana','BI','angsaz.php');
	$pdf->AddPage();
	$pdf->SetFont('angsana','B',30);
	// $pdf->Cell(0,10,iconv( 'UTF-8','TIS-620','คำถามชุดที่ '.$quiz_id.' : '.$quiz_name['Quiz_Name']),0,1);
	$pdf->Cell(0,10,iconv( 'UTF-8','TIS-620','ชุดแบบฝึกหัด '.$quiz_name['Quiz_Name']),0,1);
	$pdf->SetFont('angsana','',25);
	$pdf->Cell(0,10,iconv( 'UTF-8','TIS-620','รายวิชา : '.$subject_name['Subject_Name']));
	$pdf->Ln();
	question2pdf($pdf,$conn, $quiz_id);
	//echo($subject_id." ".$quiz_id);
	//ob_end_clean();
	$pdf->Output();
							
							


	

?>