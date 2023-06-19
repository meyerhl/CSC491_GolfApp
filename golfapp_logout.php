<?php #Script 18.9 reg_logout.php   

require('includes/golfapp_config.inc.php');
$page_title = 'Logout';
include('includes/golfapp_header.html');

//If no first_name session variable exists, redirect user_error 
if(!isset($_SESSION['golfer_fname'])) {
	$url = BASE_URL;
	ob_end_clean();
	header("Location: $url");
	exit();
}
else {
	//Destroy session data, the session container, and set cookie to expire in the past
	$_SESSION = [];
	session_destroy();
	setcookie(session_name(), '', time()-3600);
}
//Print a customized message
$url = BASE_URL;
echo '<h3>You are now logged out. Goodbye, until next time!<br>
You will be redirected to the index page in 3 seconds.</h3>';

header("refresh: 3; url = $url");



include('includes/golfapp_footer.html');
?>
