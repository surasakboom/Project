<?php

	define('HOSTNAME', '172.20.10.2'); 
	define('PORT', 10000); 

	function create_socket(){

		error_reporting(E_ALL);

		/* Allow the script to hang around waiting for connections. */
		set_time_limit(0);

		/* Turn on implicit output flushing so we see what we're getting
		 * as it comes in. */
		ob_implicit_flush();

		if (($sock = socket_create(AF_INET, SOCK_STREAM, SOL_TCP)) === false) {
		    //echo "socket_create() failed: reason: " . socket_strerror(socket_last_error()) . "\n";
		   	die("socket_create() failed: reason: " . socket_strerror(socket_last_error()) . "\n");
		}

		if (socket_bind($sock, HOSTNAME, PORT) === false) {
		    //echo "socket_bind() failed: reason: " . socket_strerror(socket_last_error($sock)) . "\n";
		    die("socket_bind() failed: reason: " . socket_strerror(socket_last_error($sock)) . "\n");
		}

		if (socket_listen($sock, 5) === false) {
		    //echo "socket_listen() failed: reason: " . socket_strerror(socket_last_error($sock)) . "\n";
		    die("socket_listen() failed: reason: " . socket_strerror(socket_last_error($sock)) . "\n");
		}

		return $sock;
	}


?>