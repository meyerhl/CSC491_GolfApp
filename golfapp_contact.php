<?php  
//This is the main page for the golf app site

//Set the webpage title and include the HTML header which calls the golf_app.css file
require('includes/golfapp_config.inc.php');
$page_title = "Contact Us";
include('includes/golfapp_header.html');

echo '<h1>Contact Us</h1>';


//This code is commented out because a valid email address is not entered, 
//nor could the mail server be tested using external email addresses. 

if($_SERVER['REQUEST_METHOD'] == 'POST'){
	//Run the DB connection script from the config file for validation
	require(MYSQLI);
	//Then trim the data to eliminate any extra spaces 
	//Validate that data was entered on the form
	if(!empty($_POST['first_name']) && !empty($_POST['last_name']) && !empty($_POST['email']) && !empty($_POST['comments'])) {
		//How to handle the data from the form  for the mail server
		/* $body = "Name: {$_POST['name']}\n\nComments:{$_POST['comments']}";
		$body = wordwrap($body, 70);
		//Send the email 
		//mail('test_email@test.com','Contact Us Submission', $body, "From: {$_POST['email']}");
		//Print a message to the user 
		echo '<p><em>Thank you for contacting me. I will reply someday.</em></p>'; */
		$trimmed = array_map('trim', $_POST);
		if(preg_match('/^[A-Z\'.-]{2,20}$/i', $trimmed['first_name'])) {
			$fn = mysqli_real_escape_string($dbc, $trimmed['first_name']);
		}
		if(preg_match('/^[A-Z\'.-]{2,40}$/i', $trimmed['last_name'])) {
			$ln = mysqli_real_escape_string($dbc, $trimmed['last_name']);
		}
		if(filter_var($trimmed['email'], FILTER_VALIDATE_EMAIL)) {
			$em = mysqli_real_escape_string($dbc, $trimmed['email']);
		}
		if($_POST['comments']) {
			$cm = mysqli_real_escape_string($dbc, $_POST['comments']);
		}
	} 
	else {
		echo '<p style = "font-weight: bold; color: #C00">Please fill out the form completely.</p>';
	}
	//Clear $_POST so the form is not sticky 
	$_POST = [];	
	
	$q = "INSERT INTO contact(contact_fname, contact_lname, contact_email, contact_comments) VALUES('$fn', '$ln', '$em', '$cm')";
	$r = mysqli_query($dbc, $q) or trigger_error("Query: $q\n<br>MySQL Error: ".mysqli_error($dbc));

		
	if(mysqli_affected_rows($dbc) == 1) {
		echo '<h3>Your comments were forwarded. You will receive a reply.</h3>';
	}

}

?>

<div class = "flex_body_container">
	<div>
		<form action = "golfapp_contact.php" method = "post">
			<fieldset style = "background-color:#F0F0F0">
			<p><strong>First Name:</strong><input type = "text" name = "first_name" size = "20" maxlength = "20" value = "<?php if(isset($trimmed['first_name'])) echo $trimmed['first_name']; ?>"></p>
			<p><strong>Last Name:</strong><input type = "text" name = "last_name" size = "20" maxlength = "40" value = "<?php if(isset($trimmed['last_name'])) echo $trimmed['last_name']; ?>"></p>
			<p><strong>Email Address:</strong><input type = "email" name = "email" size = "30" maxlength = "60" value = "<?php if(isset($trimmed['email'])) echo $trimmed['email']; ?>"></p>
			<p><label><strong>Comments:</strong><textarea name = "comments" rows = "5" cols = "60"></textarea></label></p>
			</fieldset>
			<br><br>
			<div align = "center"><input type = "submit" name = "submit" value = "Send My Message!"></div>
		</form>
	</div>
	<div style="text-align:right"><img src = "img/GolfSite/golfer02.png"></div>
</div>

	

<?php 
include('includes/golfapp_footer.html');
?>