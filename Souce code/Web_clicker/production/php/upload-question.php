<?php

require_once('config.inc.php');

require('php-excel-reader/excel_reader2.php');

require('SpreadsheetReader.php');

move_uploaded_file($_FILES["file"]["tmp_name"],"../upload_file/".$_FILES["file"]["name"]); // Copy/Upload xls

$quiz_id = $_GET['quiz_id'];
$upload_complete = true;
$conn = connect_db();

$Reader = new SpreadsheetReader("../upload_file/".$_FILES["file"]["name"]);
    // insert every row just after reading it
$i=0;
foreach ($Reader as $row)
{
	$i++;
	if($i>1)
	{
		$strSQL = "INSERT INTO question ";
		$strSQL .="(id, Quiz, Choice1, Choice2, Choice3, Choice4, Answer, Answer_Description, Lecture, Quiz_ID, Point) ";
		$strSQL .="VALUES ";
		$strSQL .="(NULL,'".$row[0]."','".$row[1]."','".$row[2]."' ";
		$strSQL .=",'".$row[3]."','".$row[4]."','".$row[5]."' ";
		$strSQL .=",'".$row[6]."','".$row[7]."','".$quiz_id."','".$row[8]."') ";

		if(!mysqli_query($conn,$strSQL)){
			$upload_complete = false;
			break;
		}
	}
    //echo($row[0]);
}
if($upload_complete)
	echo "Upload & Import Done.";
else
	echo "Upload Fail.";

/*$objCSV = fopen("../upload_file/".$_FILES["file"]["name"], "r");
echo mb_detect_encoding(file_get_contents("../upload_file/".$_FILES["file"]["name"]), 'UTF-8')." test";
while (($objArr = fgetcsv($objCSV, 1000, ",")) !== FALSE) {
	
	$strSQL = "INSERT INTO question ";
	$strSQL .="(id, Quiz, Choice1, Choice2, Choice3, Choice4, Answer, Answer_Description, Lecture, Quiz_ID, Point) ";
	$strSQL .="VALUES ";
	$strSQL .="(NULL,'".$objArr[0]."','".$objArr[1]."','".$objArr[2]."' ";
	$strSQL .=",'".$objArr[3]."','".$objArr[4]."','".$objArr[5]."' ";
	$strSQL .=",'".$objArr[6]."','".$objArr[7]."','".$quiz_id."','".$objArr[8]."') ";

	if(!mysqli_query($conn,$strSQL)){
		$upload_complete = false;
		break;
	}
}
fclose($objCSV);

if($upload_complete)
	echo "Upload & Import Done.";
else
	echo "Upload Fail.";*/
?>