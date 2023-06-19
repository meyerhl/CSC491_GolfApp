<?php 
require('includes/golfapp_config.inc.php');
$page_title = 'Login';
include('includes/golfapp_header.html');

if($_SERVER['REQUEST_METHOD'] == 'POST') {
	require(MYSQLI);

	//Validate that the user entered form data for email and password
	if(!empty($_POST['email'])) {
		$em = mysqli_real_escape_string ($dbc, $_POST['email']);
	}
	else {
		$em = FALSE;
		echo '<p class = "error">You forgot to enter your email address!</p>';
	}
	if(!empty($_POST['pass'])) {
		$pw = trim($_POST['pass']);
	}
	else {
		$pw = FALSE;
		echo '<p class = "error">You forgot to enter your password!</p>';
	}
	
	//If both email and password data were entered, then query the database for a valid record
	if($em && $pw) {
		$qry = "SELECT golfer_id, golfer_fname, golfer_username, golfer_gender, golfer_pass, golfer_hc FROM golfers WHERE golfer_email = '$em' AND golfer_username IS NOT NULL";
		$rslt = mysqli_query($dbc, $qry) or trigger_error("Query: $qry\n<br>MySQL Error:".mysqli_error($dbc));
		//Check for exactly one record
		if(@mysqli_num_rows($rslt) == 1) {
			echo 'I found one record';
			list($golfer_id, $golfer_fname, $golfer_username, $golfer_gender, $golfer_pass, $golfer_hc) = mysqli_fetch_array($rslt, MYSQLI_NUM);
			mysqli_free_result($rslt);
			echo 'my password from db is' .$pw. '<br>';
			echo 'my password from hash is' .$golfer_pass. '<br>';
			$test01 = password_verify($pw, $golfer_pass);
			if($test01 == true) {
				echo 'Passwords match';
			}
			else {
				echo 'Passwords do not match';
			}
			//Check the password
			if(password_verify($pw, $golfer_pass)) {
			//Store the info in the session 
			$_SESSION['golfer_id'] = $golfer_id;
			$_SESSION['golfer_fname'] = $golfer_fname;
			$_SESSION['golfer_username'] = $golfer_username;
			$_SESSION['golfer_gender'] = $golfer_gender;			
			$_SESSION['golfer_hc'] = $golfer_hc;
			//Close the DB connection 
			mysqli_close($dbc);
			//Redirect the user to the main page to see options for a person who is now logged in
			$url = BASE_URL;
			ob_end_clean();
			header("Location:$url");
			exit();
			}
			
			else {
				echo '<p class = "error">Multiple rows.The email address and password entered do not match those on file.</p>';
			}
		}
		else{
			echo '<p class = "error">The email address and password entered do not match those on file.</p>';
		}
	}
	else {
		echo '<p class = "error">Information is missng. Something went wrong. Please try again.</p>';
	}
	mysqli_close($dbc);
}
?>
<h1>Login</h1>

<div class = "flex_body_container">
	<div>
		<form action = "golfapp_login.php" method = "post">
			<div style = "color:white">Your browser must allow cookies in order to log in.</div>
			<br><br>
			<fieldset style = "background-color:#F0F0F0">
			<p><strong>Email Address:</strong>&nbsp&nbsp&nbsp <input type = "email" name = "email" size = "20" maxlength = "60"></p>
			<p><strong>Password:</strong>&nbsp&nbsp&nbsp <input type = "password" name = "pass" size = "20"</p>
			</fieldset>
			<br><br>
			<div align = "center"><input type = "submit" name = "submit" value = "Login!"></div>
		</form>
	</div>
	<div style="text-align:right"><img src = "img/GolfSite/golfer02.png"></div>
</div>
<?php include('includes/golfapp_footer.html'); ?>