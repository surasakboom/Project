<?php

/** 
* Crucial Functions for Application 
* 
* @package tpc_tutorials
* @file /includes/functions.inc.php
*/ 

 

/** 
* Redirects to specified page 
* 
* @param string $page Page to redirect user to 
* @return void 
*/ 

require_once('config.inc.php');

function redirect($page) { 
 header('Location: ' . $page); 
 exit(); 
} 

 
/** 
* Check login status 
* 
* @return boolean Login status 
*/ 

function session_is_set(){
	if(!isset($_SESSION)){
		session_start();
	}
}

function check_login_status() { 
	 // If $_SESSION['logged_in'] is set, return the status 
	 if (isset($_SESSION['logged_in'])) { 
	 return $_SESSION['logged_in']; 
	 }
 } 

function check_status() { 
	 if (isset($_SESSION['Role'])) { 
	 	return $_SESSION['Role']; 
	 } 
}

 function check_teacher_role() {
 	if($_SESSION['Role'] == 0){ return true; }
 	return false;
 }

 function check_regist($secid){
 	$conn = connect_db();
 	$result = mysqli_query($conn, "SELECT * FROM section_has_student WHERE Section_ID='".$secid."' AND Student_ID='".$_SESSION['id']."'");
 	if($result->num_rows ==1){
 		return true;
 	}else{ return false; }
 }

 function unset_session(){
 	unset($_SESSION['logged_in']);
 	unset($_SESSION['Role']);
	unset($_SESSION['username']);
	unset($_SESSION['id']);
 }

 function getCurrentDate(){
	return date('Y-m-d', time());
 }


?>