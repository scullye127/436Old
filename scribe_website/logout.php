<?php

	/* TO-DO: Include session.php to handle login sessions
              Hint: Both header.php and session.php are inside the includes folder
    */
	include("./includes/session.php");


	logout();											// Call the logout function to terminate session
	header('Location: login.php');						// Redirect to login page
?>