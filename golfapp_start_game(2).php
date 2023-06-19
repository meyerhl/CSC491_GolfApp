<?php  

//Set the webpage title and include the HTML header which calls the golf_app.css file
require('includes/golfapp_config.inc.php');
$page_title = "Start Game";
include('includes/golfapp_header.html');

if($_SERVER['REQUEST_METHOD'] == 'POST') {
	require(MYSQLI);

	//Validate that the user entered form data for course, holes, tees, and players
	if(!empty($_POST['courses'])) {
		$userCourse = mysqli_real_escape_string ($dbc, $_POST['courses']);
	}
	else {
		$userCourse = FALSE;
		echo '<p class = "error">You forgot to select a course!</p>';
	}
	if(!empty($_POST['holes'])) {
		$userHoles = trim($_POST['holes']);
	}
	else {
		$userHoles = FALSE;
		echo '<p class = "error">You forgot to select the number of holes!</p>';
	}
	if(!empty($_POST['tee'])) {
		$userTee = trim($_POST['tee']);
	}
	else {
		$userTee = FALSE;
		echo '<p class = "error">You forgot to select tee box!</p>';
	}
	if(!empty($_POST['players'])) {
		$userPlayers = trim($_POST['players']);
	}
	else {
		$userPlayers = FALSE;
		echo '<p class = "error">You forgot to select the number of players!</p>';
	}
	
	//If all data were entered, then query the database for a valid course record
	if($userCourse && $userHoles && $userTee) {
		$qry = "SELECT crs_name, crs_holes, crs_tee FROM course WHERE crs_name = '$userCourse' AND crs_holes = '$userHoles' AND crs_tee = '$userTee'";
		$rslt = mysqli_query($dbc, $qry) or trigger_error("Query: $qry\n<br>MySQL Error:".mysqli_error($dbc));
		//Check for exactly one record
		echo $qry;
		print_r($rslt);
		if(@mysqli_num_rows($rslt) == 1) {
			list($crs_name, $crs_holes, $crs_tee) = mysqli_fetch_array($rslt, MYSQLI_NUM);
			mysqli_free_result($rslt);
			//Store the info in the session 
			$_SESSION['crs_name'] = $crs_name;
			$_SESSION['crs_holes'] = $crs_holes;
			$_SESSION['crs_tee'] = $crs_tee;
			$_SESSION['crs_players'] = $userPlayers;
			//Close the DB connection 
			mysqli_close($dbc);
			//Redirect the user to the scorecard
			$url = NEW_URL;
			ob_end_clean();
			header("Location:$url");
			exit();
			}	
		else {
			echo '<p class = "error">Sorry. That course is not a course in the system. Try again.</p>';
		}
	}
	else {
		echo '<p class = "error">Something went wrong. Please try again.</p>';
	}
}
require(MYSQLI);
//Set up the queries, results, and row captures for each option in the form
$qCourse = "SELECT crs_name FROM course ORDER BY crs_name ASC";
$rCourse = @mysqli_query($dbc, $qCourse);

//$qHoles = "SELECT * FROM holes ORDER BY hole_value ASC";
$qHoles = "SELECT * FROM holes ORDER BY hole_value DESC LIMIT 1";
$rHoles = @mysqli_query($dbc, $qHoles);

//$qTee = "SELECT * FROM tee ORDER BY tee_color ASC";
$qTee = "SELECT * FROM tee ORDER BY tee_color DESC LIMIT 1";
$rTee = @mysqli_query($dbc, $qTee); 



//Set up for the form and iterate through the options from the queries above
mysqli_close($dbc);
?>

<h1>Start a Game</h1>
<div class = "flex_body_container">
	<div>
		<form action = "golfapp_start_game(2).php" method = "post">
			<fieldset style = "background-color:#F0F0F0">
			
			<p><label><strong>Choose Course:&nbsp&nbsp&nbsp </strong></label>
			<select name = "courses"> 
			<option value disabled selected>Select Course</option>
			<?php while($crs = mysqli_fetch_array($rCourse, MYSQLI_ASSOC)){ ?> 
				<option value ="<?php echo $crs['crs_name']; ?>"> <?php echo $crs['crs_name']; echo '</option>';} ?>
			</select></p>
			
			<p><label><strong>Choose Holes:&nbsp&nbsp&nbsp </strong></label>
			<select name = "holes"> 
			<option value disabled selected>Select Holes</option>
			<?php while($holes = mysqli_fetch_array($rHoles, MYSQLI_ASSOC)){ ?>
				<option value ="<?php echo $holes['hole_index']; ?>"> <?php echo $holes['hole_value']; echo '</option>';} ?>
			</select></p>
			
			<p><label><strong>Choose Tee:&nbsp&nbsp&nbsp </strong></label>
			<select name = "tee"> 
			<option value disabled selected>Select Tee</option>
			<?php while($tee = mysqli_fetch_array($rTee, MYSQLI_ASSOC)){ ?>
				<option value ="<?php echo $tee['tee_index']; ?>"> <?php echo $tee['tee_color']; echo '</option>';} ?>
			</select></p>
			
			<p><label><strong>Number of Players:&nbsp&nbsp&nbsp </strong></label>
			<select name = "players">
			<?php for($players = 1; $players <=4; $players++) { ?>
				<option value ="<?php echo $players; ?>"><?php echo $players; echo '</option>';} ?>
			</select></p>
			</fieldset>
			<br><br>
			<div align = "center"><input type = "submit" name = "submit" value = "Get a Scorecard!"></div>
		</form>
	</div>
	<div style="text-align:right"><img src = "img/GolfSite/golfer02.png"></div>
</div>

	

<?php 
include('includes/golfapp_footer.html');
?>