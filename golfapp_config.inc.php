<?php #Script golfapp_config.inc.php  
//This script does the following:
//-defines constants and settings
//-dictates how errors are handled
//-defines useful functions

/*------------------------------------*/
/*Created by: Holly Meyer             */
/*Date: May 21, 2023                */
/*Purpose: For a registration Golf page    */
/*------------------------------------*/

//*******DEFINE CONSTANTS FOR THE SITE****************//
//Define site and Admin constants for error reporting. NOTE: set to TRUE when site is live
define('LIVE', FALSE);
define('EMAIL', 'holly.meyer@cuw.edu');

//Define site redirections and absolute DB connection path
define('BASE_URL', 'https://localhost/golfapp_index.php/');
define('MYSQLI', 'golf_mysqli_connect.php');

define('NEW_URL', 'https://localhost/golfapp_scorecard.php/');
define('MYSQLI2', 'golf_mysqli_connect(2).php');

//Set the default timezone to use based on site location 
date_default_timezone_set('America/New_York');

//**************ERROR MANAGEMENT*******************//
//Define the function of error handling which in PHP 8.2 only takes 4 parameters
function my_error_handler($err_no, $err_msg, $err_file, $err_line) {
	//Build the error message 
	$message = "An error occurred in the script <strong>$err_file</strong> on line <strong>$err_line</strong>: $err_msg\n";
	//Concatenate the message with the date and time of the error 
	$message.="Date/Time: ".date('n-j-Y H:i:s')."\n";
	//If site is in development, print the following msg for the Admin 
	if(!LIVE) {
		echo '<div class = "error">'.nl2br($message);
		//Add the variables and a backtrace NOTE: variables don't appear to work in PHP 8.2
		//echo '<pre>'.print_r($err_vars, 1). "\n";
		debug_print_backtrace();
		//echo '</pre></div>';
		echo '</div>';
	}
	//If site is LIVE, send info to the Admin and the end user
	else {
		//Email the Admin as defined above
		$body = $message."\n".print_r($err_vars, 1);
		mail(EMAIL, 'Site Error!', $body, 'From: system@system.com');
		//Print an error message to the user if it's not a notice
		if($err_no != E_NOTICE) {
			echo '<div class= "error">A system error occurred. We apologize for the inconvenience.</div><br>';
		}
	}
}
//Set the error handler
set_error_handler('my_error_handler');