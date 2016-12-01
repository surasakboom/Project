<?php
	require_once('functions.inc.php');
	session_is_set();
	unset_session();
	redirect('../login.html');

?>