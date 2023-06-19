<?php # Script 18.11 - change_password.php
// This page allows a logged-in user to change their password.
require('includes/golfapp_config.inc.php');
$page_title = 'Change Your Password';
include('includes/golfapp_header.html');

// If no user_id session variable exists, redirect the user:
if(!isset($_SESSION['golfer_id'])) {

	$url = BASE_URL . 'golfapp_index.php'; // Define the URL.
	ob_end_clean(); // Delete the buffer.
	header("Location: $url");
	exit(); // Quit the script.

}

if($_SERVER['REQUEST_METHOD'] == 'POST') {
	require(MYSQLI);
	
	// Check for a new password and match against the confirmed password:
	$pw = FALSE;
	if(strlen($_POST['password1']) >= 10) {
		if($_POST['password1'] == $_POST['password2']) {
			$pw = password_hash($_POST['password1'], PASSWORD_DEFAULT);
		} else {
			echo '<p class="error">Your password did not match the confirmed password!</p>';
		}
	} 
	else {
		echo '<p class="error">Please enter a valid password!</p>';
	}

	if($pw) { // If everything's OK.

		// Make the query and redirect the user:
		$url = BASE_URL;
		$q = "UPDATE golfers SET golfer_pass='$pw' WHERE golfer_id={$_SESSION['golfer_id']} LIMIT 1";
		$r = mysqli_query($dbc, $q) or trigger_error("Query: $q\n<br>MySQL Error: " . mysqli_error($dbc));
		if(mysqli_affected_rows($dbc) == 1) { // If it ran OK.
			echo '<h3>Your password has been changed.</h3><br>';
			mysqli_close($dbc); // Close the database connection.
			echo '<h3>You will be redirected to the index page in 3 seconds.</h3>';
			header("refresh: 3; url = $url");
			include('includes/golfapp_footer.html');
			exit();

	
		} 
		else { // If it did not run OK.
			echo '<p class="error">Your password was not changed. Make sure your new password is different than the current password. Contact the system administrator if you think an error occurred.</p>';
		}
	} 
	else { // Failed the validation test.
		echo '<p class="error">Please try again.</p>';
	}
	
	mysqli_close($dbc); // Close the database connection.
} // End of the main Submit conditional.
?>

<h1>Change Your Password</h1>
<div class = "flex_body_container">
	<div>
		<form action="golfapp_change_password.php" method="post">
			<fieldset style = "background-color:#F0F0F0">
			<p><strong>New Password:</strong>    <input type="password" name="password1" size="20"> <small>At least 10 characters long.</small></p>
			<p><strong>Confirm New Password:</strong>    <input type="password" name="password2" size="20"></p>
			</fieldset>
			<br><br>
			<div align="center"><input type="submit" name="submit" value="Change Password"></div>
		</form>
	</div>
	<div style="text-align:right"><img src = "img/GolfSite/golfer02.png"></div>
</div>


<?php include('includes/golfapp_footer.html'); ?>
