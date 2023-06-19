<?php  
//This is the main page for the golf app site

//Set the webpage title and include the HTML header which calls the golf_app.css file
$page_title = "About Golf";
include('includes/golfapp_header.html');

//Welcome the user by first name if logged in 
?>

<?php
echo '<h1>About Golf</h1>';

?>
<div class = "flex_body_container">
	<div class = "pindex" style = "color:white">
	This is about golf.<br><br>
	Not sure how much information to put here.<br><br>
	But this is where I would put it.
	</div>
	<div style="text-align:right"><img src = "img/GolfSite/golfer02.png"></div>
</div>
	

<?php 
include('includes/golfapp_footer.html');
?>