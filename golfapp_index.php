<?php  
//This is the main page for the golf app site

//Set the webpage title and include the HTML header which calls the golf_app.css file
require('includes/golfapp_config.inc.php');
$page_title = "Play the Loop!";
include('includes/golfapp_header.html');

//Welcome the user by first name if logged in
echo '<h1>Welcome';
if(isset($_SESSION['golfer_fname'])) {
	echo ", {$_SESSION['golfer_fname']}! <br>";
	//Display handicap if recorded
	if(isset($_SESSION['golfer_hc'])) {
		echo "Your current handicap is:&nbsp&nbsp&nbsp{$_SESSION['golfer_hc']}";
	}
	else {
		echo "You must record a minimum of 54 holes to receive a handicap. Consider starting a game";
	}
}
echo '!</h1><br>';
?>

<div class = "flex_body_container">
	<div class = "pindex" style = "color:white">
	Information about the site itself will go here after the user has had some time to determine
	what needs to be here. They may want to give a brief few lines on how the site works, or 
	offer different functionality. This text serves as a placeholder.<br><br>
	Users who have registered will be able to see their current calculated handicap.<br><br>
	Again, this is a placeholder for the user to determine content.<br><br>
	Web accessibility should be addressed if the user would like to retain the graphics.
	After review, white text on a green background is potentially difficult to read for 
	those who experience any sort of vision-related concerns.
	</div>
	<div style="text-align:right"><img src = "/img/GolfSite/golfer02.png"></div>
</div>
	

<?php 
include('includes/golfapp_footer.html');
?>

