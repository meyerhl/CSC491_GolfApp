<?php # to register for golf app

require('includes/golfapp_config.inc.php');
$page_title = 'Register';
include('includes/golfapp_header.html');

//Check for form submission by user or present the form
if($_SERVER['REQUEST_METHOD'] == 'POST'){
	//Run the DB connection script from the config file for validation
	require(MYSQLI);
	//Then trim the data to eliminate any extra spaces 
	$trimmed = array_map('trim', $_POST);
	//Assign FALSE to each of these variables (as a shortcut) 
	$fn = $ln = $un = $sx = $em = $pw = $hc = FALSE;
	
	//Perform Regular Experession matches and check for the data to be entered on the form, or present error message
	if(preg_match('/^[A-Z\'.-]{2,20}$/i', $trimmed['first_name'])) {
		$fn = mysqli_real_escape_string($dbc, $trimmed['first_name']);
	}
	else {
		echo '<p class = "error">Please enter your first name!</p>';
	}
	if(preg_match('/^[A-Z\'.-]{2,40}$/i', $trimmed['last_name'])) {
		$ln = mysqli_real_escape_string($dbc, $trimmed['last_name']);
	}
	else {
		echo '<p class = "error">Please enter your last name!</p>';
	}
	if(preg_match('/^[a-z0-9]{2,15}$/i', $trimmed['user_name'])) {
		$un = mysqli_real_escape_string($dbc, $trimmed['user_name']);
	}
	else {
		echo '<p class = "error">Please enter your user name!</p>';
	}
	//Thanks to https://www.youtube.com/watch?v=KTa1_15_FZk for helping with radio button entry to DB
	if(isset($_POST['gender'])){
		$sx = $_POST['gender'];
	}
	else {
		echo '<p class = "error">Please enter gender to calculate the proper slope, rating, and handicap for the course!</p>';
	}
	if(filter_var($trimmed['email'], FILTER_VALIDATE_EMAIL)) {
		$em = mysqli_real_escape_string($dbc, $trimmed['email']);
	}
	else {
		echo '<p class= "error">Please enter a valid email address!</p>';
	}
	//Check for a password and match against the confirmed password 
	if(strlen($trimmed['password1']) >=10) {
		if($trimmed['password1'] == $trimmed['password2']) {
			$pw = password_hash($trimmed['password1'], PASSWORD_DEFAULT);
		}
		else {
			echo '<p class = "error">Your password did not match the confirmed password!</p>';
		}
	}
	else {
		echo '<p class = "error">Please enter a minimum length password!</p>';
	}
	//Check if a numerical 0-99 handicap was entered on the form. I don't know how to get this entered on DB
	if(is_numeric($trimmed['handicap'])) {
		$hc = mysqli_real_escape_string($dbc, $trimmed['handicap']);
	}

	//Now after data was verified to be entered, create a user account with the data
	if($fn && $ln && $un && $sx && $em && $pw && $hc) {
	//if($fn && $ln && $un && $sx && $em && $pw && $hc) {
		//Make sure the email address is available
		$q = "SELECT golfer_id FROM golfers WHERE golfer_email = '$em'";
		$r = mysqli_query($dbc, $q) or trigger_error("Query: $q\n<br>MySQL Error: ".mysqli_error($dbc));
		if(mysqli_num_rows($r) == 0) {
			//Skip the Activation Code for now until mailserver works
			//Create the Activation Code 
			//$a = md5(uniqid(rand(), true));
			//Add the user to the db 
			$q = "INSERT INTO golfers(golfer_fname, golfer_lname, golfer_username, golfer_gender, golfer_email, golfer_pass, golfer_hc) VALUES('$fn','$ln','$un','$sx','$em','$pw','$hc')";
			$r = mysqli_query($dbc, $q) or trigger_error("Query: $q\n<br>MySQL Error: ".mysqli_error($dbc));
			if(mysqli_affected_rows($dbc) == 1) {
				//Send the activation email 
				//We are skipping the Activation Code process: $body = "Thank you for registering at Play the Loop! To activate our account, please click on this link:\n\n";
				//I can't get the encoded email to work after it's in notepad. 
				// $body .= BASE_URL .'reg_activate.php?x='.urlencode($em). "&y=$a" ;
				//$body .= BASE_URL .'reg_activate.php?x='.$em. "&y=$a" ;
				// mail($trimmed['email'], 'Registration Confirmation', $body, 'From: admin$sitename.com');
				//Finish the page 
				echo '<h3>Thank you for registering!</h3>';
				//echo 'Please click on the link in that email to activate your account.';
				include('includes/golfapp_footer.html');
				exit(); //To stop the script from running further
			}
			else {
				echo '<p class = "error">You could not be registered due to a system error. We apologize for any inconvenience.</p>';
			}
		}
		else {
			echo '<p class = "error">The email address or username entered has already been registered. If you have forgotten your password, use the link at the right to have your password sent to you.</p>';
		}
	}
	else {
		echo '<p class = "error">Please try again.</p>';
	}
	mysqli_close($dbc);
}
?>

<h1>Register</h1>
<div class = "flex_body_container">
	<div>
		<form action = "golfapp_register.php" method = "post">
			<fieldset style = "background-color:#F0F0F0">
			<p><strong>First Name:</strong>    <input type = "text" name = "first_name" size = "20" maxlength = "20" value = "<?php if(isset($trimmed['first_name'])) echo $trimmed['first_name']; ?>"></p>
			<p><strong>Last Name:</strong>    <input type = "text" name = "last_name" size = "20" maxlength = "40" value = "<?php if(isset($trimmed['last_name'])) echo $trimmed['last_name']; ?>"></p>
			<p><strong>User Name:</strong>    <input type = "text" name = "user_name" size = "20" maxlength = "15" value = "<?php if(isset($trimmed['user_name'])) echo $trimmed['user_name']; ?>">&nbsp&nbsp<small>Maximum 15 letters and numbers allowed.</small></p>
			<p><strong>Gender:</strong>    <input type = "radio" name = "gender" value = "MALE"/> Male    <input type = "radio" name = "gender" value = "FEMALE"/> Female</p>		
			<p><strong>Email Address:</strong>    <input type = "email" name = "email" size = "30" maxlength = "60" value = "<?php if(isset($trimmed['email'])) echo $trimmed['email']; ?>"></p>
			<p><strong>Password:</strong>    <input type = "password" name = "password1" size = "20" value = "<?php if(isset($trimmed['password1'])) echo $trimmed['password1']; ?>">&nbsp&nbsp<small>Minimum 10 characters long.</small></p>
			<p><strong>Confirm Password:</strong>    <input type = "password" name = "password2" size = "20" value = "<?php if(isset($trimmed['password2'])) echo $trimmed['password2']; ?>"></p>
			<p><strong>Handicap:</strong>&nbsp&nbsp&nbsp <input type = "numberR" name = "handicap"  min = "0" max = "50" step = "0.1" value = "<?php if(isset($trimmed['handicap'])) echo ($trimmed['handicap']); ?>">&nbsp&nbsp<small>If none, enter 0.0.</small></p>
			</fieldset>
			<br><br>
			<div align = "center"><input type = "submit" name = "submit" value = "Register!"></div>
		</form>
	</div>
	<div style="text-align:right"><img src = "img/GolfSite/golfer02.png"></div>
</div>
<?php include('includes/golfapp_footer.html'); ?>