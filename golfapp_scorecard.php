<?php #golfapp_scorecard.php
// https://www.w3schools.com/howto/tryit.asp?filename=tryhow_custom_select for how to style my user names and selects
//https://cplusplus.com/forum/beginner/237788/ to use a counter in a while loop

//Hanicap index, Par, Course Rating and Course Slope are courtesy of https://course.bluegolf.com/bluegolf/course/course/timberstonegc/detailedscorecard.htm
//If above site did not have values, used https://offcourse.co/courses/scorecard/sweetgrass-golf-club 

require('includes/golfapp_config.inc.php');
$page_title = 'Scorecard';
include('includes/golfapp_header.html');

require(MYSQLI);

$qCrsPar_m = "SELECT crs_par_m FROM course WHERE crs_name = '{$_SESSION['crs_name']}'";
$rCrsPar_m = @mysqli_query($dbc, $qCrsPar_m);	
$arrCrsPar_m = mysqli_fetch_array($rCrsPar_m, MYSQLI_ASSOC);

$qCrsPar_f = "SELECT crs_par_f FROM course WHERE crs_name = '{$_SESSION['crs_name']}'";
$rCrsPar_f = @mysqli_query($dbc, $qCrsPar_f);	
$arrCrsPar_f = mysqli_fetch_array($rCrsPar_f, MYSQLI_ASSOC);

$qRating_m = "SELECT crs_rating_m FROM course WHERE crs_name = '{$_SESSION['crs_name']}'";
$rRating_m = @mysqli_query($dbc, $qRating_m);
$arrRating_m = mysqli_fetch_array($rRating_m, MYSQLI_ASSOC);

$qSlope_m = "SELECT crs_slope_m FROM course WHERE crs_name = '{$_SESSION['crs_name']}'";
$rSlope_m = @mysqli_query($dbc, $qSlope_m);
$arrSlope_m = mysqli_fetch_array($rSlope_m, MYSQLI_ASSOC);

$qRating_f = "SELECT crs_rating_f FROM course WHERE crs_name = '{$_SESSION['crs_name']}'";
$rRating_f = @mysqli_query($dbc, $qRating_f);
$arrRating_f = mysqli_fetch_array($rRating_f, MYSQLI_ASSOC);

$qSlope_f = "SELECT crs_slope_f FROM course WHERE crs_name = '{$_SESSION['crs_name']}'";
$rSlope_f = @mysqli_query($dbc, $qSlope_f);	
$arrSlope_f = mysqli_fetch_array($rSlope_f, MYSQLI_ASSOC);



//Check if data was entered on the form. If not, present the form to the user.
if($_SERVER['REQUEST_METHOD'] == 'POST') {

	//After data is validated, check that info was entered and then insert records
	//To print the arrays that contain a single value, use the $arrXXX[''] syntax, where the value 
	//between single quotes is the item you want returned from the SELECT query.
	$today = date('Y-m-d'); 
	
	$qCourseID	= "SELECT crs_no FROM course WHERE crs_name = '{$_SESSION['crs_name']}'";
	$rCourseID = mysqli_query($dbc, $qCourseID) or trigger_error("Query: $qCourseID\n<br>MySQL Error: ".mysqli_error($dbc));
	$arrCourseID = mysqli_fetch_array($rCourseID, MYSQLI_ASSOC);
	
	$winner = '';
	$wScore = '';
	
	//Assign all golfer variables as FALSE to invoke corresponding ELSE statements if needed
	$golfer1 = $golfer2 = $golfer3 = $golfer4 = FALSE;

	//User 1 will always be the session user, so else statement is not required.
	//If User1 value exists and any hole values (scores) are not filled in, post error about missing scores.
	if(!empty($_POST['user1'])) {
		$golfer1 = mysqli_real_escape_string($dbc, $_POST['user1']);
		
		if(is_null($_POST['u1_holes1']) || is_null($_POST['u1_holes2']) || is_null($_POST['u1_holes3']) || is_null($_POST['u1_holes4']) || is_null($_POST['u1_holes5']) || 
			is_null($_POST['u1_holes6']) || is_null($_POST['u1_holes7']) || is_null($_POST['u1_holes8']) || is_null($_POST['u1_holes9']) || is_null($_POST['u1_holes_F9']) ||
			is_null($_POST['u1_holes10']) || is_null($_POST['u1_holes11']) || is_null($_POST['u1_holes12']) || is_null($_POST['u1_holes13'])  || is_null($_POST['u1_holes14']) || 
			is_null($_POST['u1_holes15']) || is_null($_POST['u1_holes16'])  || is_null($_POST['u1_holes17'])  || is_null($_POST['u1_holes18'])  || is_null($_POST['u1_holes_B9']) || 
			is_null($_POST['u1_holes_TOT'])){
				echo '<p class = "error">You forgot to enter at least one of the golf scores for '.$golfer1.'!</p>';
		}
		else {
			//If User1 value exists and all hole values (scores) are filled in, create arrays of scores and username to insert game record to database for user.
			$holes1 = array($_POST['u1_holes1'], $_POST['u1_holes2'], $_POST['u1_holes3'], $_POST['u1_holes4'],
				$_POST['u1_holes5'], $_POST['u1_holes6'], $_POST['u1_holes7'], $_POST['u1_holes8'], $_POST['u1_holes9'], $_POST['u1_holes_F9'], 
				$_POST['u1_holes10'], $_POST['u1_holes11'], $_POST['u1_holes12'], $_POST['u1_holes13'], $_POST['u1_holes14'], $_POST['u1_holes15'],
				$_POST['u1_holes16'], $_POST['u1_holes17'], $_POST['u1_holes18'], $_POST['u1_holes_B9'], $_POST['u1_holes_TOT']);
			
			$qUser1 = "SELECT golfer_id FROM golfers WHERE golfer_username = '$golfer1'";
			$rUser1 = mysqli_query($dbc, $qUser1) or trigger_error("Query: $qUser1\n<br>MySQL Error: ".mysqli_error($dbc));
			$arrUser1 = mysqli_fetch_array($rUser1, MYSQLI_ASSOC);
	
			$qUser1add = "INSERT INTO games(user_id, course_id, game_date, hole01, hole02, hole03, hole04, hole05, hole06, hole07, hole08, hole09, front09, 
				hole10, hole11, hole12, hole13, hole14, hole15, hole16, hole17, hole18, back09, total) VALUES ('{$arrUser1['golfer_id']}', '{$arrCourseID['crs_no']}', '$today', '$holes1[0]', '$holes1[1]', '$holes1[2]', '$holes1[3]',
				'$holes1[4]', '$holes1[5]', '$holes1[6]', '$holes1[7]', '$holes1[8]', '$holes1[9]', '$holes1[10]', '$holes1[11]', '$holes1[12]', '$holes1[13]', '$holes1[14]', '$holes1[15]', '$holes1[16]', 
				'$holes1[17]', '$holes1[18]', '$holes1[19]', '$holes1[20]')"; 
			$rUser1add = mysqli_query($dbc, $qUser1add) or trigger_error("Query: $qUser1add\n<br>MySQL Error: ".mysqli_error($dbc));	
			
			// Determine the current winner of the round
			if(mysqli_affected_rows($dbc) == 1) {
				$winner = $golfer1;
				$wScore = $holes1[20];
			}
			
			//Determine the strokes over par for the user
			if($_SESSION['golfer_gender'] == 'Male'){
				$strokesOverPar = ($holes1[20] - $arrCrsPar_m['crs_par_m']);
				//echo 'Total par for this user is: '.$holes1[20].'<br>';
				//echo 'Couse Par is: '.$arrCrsPar_m['crs_par_m'].'<br>';
				//echo 'Strokes over par for this user is: '.$strokesOverPar.'<br>';
				$qUser1hc = "INSERT INTO handicap(user_id, strokes_over_par) VALUES('{$arrUser1['golfer_id']}', '$strokesOverPar')";
				$rUser1hc = mysqli_query($dbc, $qUser1hc) or trigger_error("Query: $qUser1hc\n<br>MySQL Error: ".mysqli_error($dbc));
			}
			else {
				$strokesOverPar = ($holes1[20] - $arrCrsPar_f['crs_par_f']);
				$qUser1hc = "INSERT INTO handicap(user_id, strokes_over_par) VALUES('{$arrUser1['golfer_id']}','$strokesOverPar')";
				$rUser1hc = mysqli_query($dbc, $qUser1hc) or trigger_error("Query: $qUser1hc\n<br>MySQL Error: ".mysqli_error($dbc));
			}
			
			//Query the best 8 games for the user after this entry
			$qry = "SELECT AVG(strokes_over_par) FROM (SELECT strokes_over_par FROM handicap WHERE user_id = '{$_SESSION['golfer_id']}' 
				ORDER BY strokes_over_par ASC LIMIT 8) handicap";
			$rslt = mysqli_query($dbc, $qry) or trigger_error("Query: $qry\n<br>MySQL Error: ".mysqli_error($dbc));
			$arrRSLT = mysqli_fetch_array($rslt, MYSQLI_ASSOC);
			
			//Update the handicap for the user so the next login will display the new value.
			$qHandicap = "UPDATE golfers SET golfer_hc ='{$arrRSLT['AVG(strokes_over_par)']}' WHERE golfer_id = '{$_SESSION['golfer_id']}' "; 
			$rHandicap = mysqli_query($dbc, $qHandicap) or trigger_error("Query: $qHandicap\n<br>MySQL Error: ".mysqli_error($dbc));
		
			//Present the user with a notice that their scores were successfully submitted.
			echo '<p>Scores were submitted for '.$golfer1.'.Thank you!</p>';
		}
	}
	
	if(!empty($_POST['user2'])) {
		$golfer2 = mysqli_real_escape_string($dbc, $_POST['user2']);

		if(is_null($_POST['u2_holes1']) || is_null($_POST['u2_holes2']) || is_null($_POST['u2_holes3']) || is_null($_POST['u2_holes4']) || is_null($_POST['u2_holes5']) || 
			is_null($_POST['u2_holes6']) || is_null($_POST['u2_holes7']) || is_null($_POST['u2_holes8']) || is_null($_POST['u2_holes9']) || is_null($_POST['u2_holes_F9']) ||
			is_null($_POST['u2_holes10']) || is_null($_POST['u2_holes11']) || is_null($_POST['u2_holes12']) || is_null($_POST['u2_holes13'])  || is_null($_POST['u2_holes14']) || 
			is_null($_POST['u2_holes15']) || is_null($_POST['u2_holes16'])  || is_null($_POST['u2_holes17'])  || is_null($_POST['u2_holes18'])  || is_null($_POST['u2_holes_B9'])  || 
			is_null($_POST['u2_holes_TOT'])){
				echo '<p class = "error">You forgot to enter at least one of the golf scores for '.$golfer2.'!</p>';
		}
		else {
			$holes2 = array($_POST['u2_holes1'],$_POST['u2_holes2'],$_POST['u2_holes3'],$_POST['u2_holes4'],
				$_POST['u2_holes5'], $_POST['u2_holes6'],$_POST['u2_holes7'],$_POST['u2_holes8'],$_POST['u2_holes9'],$_POST['u2_holes_F9'], 
				$_POST['u2_holes10'], $_POST['u2_holes11'],$_POST['u2_holes12'],$_POST['u2_holes13'],$_POST['u2_holes14'],$_POST['u2_holes15'],
				$_POST['u2_holes16'], $_POST['u2_holes17'],$_POST['u2_holes18'],$_POST['u2_holes_B9'],$_POST['u2_holes_TOT']);
			
			$qUser2 = "SELECT golfer_id FROM golfers WHERE golfer_username = '$golfer2'";
			$rUser2 = mysqli_query($dbc, $qUser2) or trigger_error("Query: $qUser2\n<br>MySQL Error: ".mysqli_error($dbc));
			$arrUser2 = mysqli_fetch_array($rUser2, MYSQLI_ASSOC);
			
			$qUser2add = "INSERT INTO games(user_id, course_id, game_date, hole01, hole02, hole03, hole04, hole05, hole06, hole07, hole08, hole09, front09, 
				hole10, hole11, hole12, hole13, hole14, hole15, hole16, hole17, hole18, back09, total) VALUES ('{$arrUser2['golfer_id']}', '{$arrCourseID['crs_no']}', '$today', '$holes2[0]', '$holes2[1]', '$holes2[2]', '$holes2[3]',
				'$holes2[4]', '$holes2[5]', '$holes2[6]', '$holes2[7]', '$holes2[8]', '$holes2[9]', '$holes2[10]', '$holes2[11]', '$holes2[12]', '$holes2[13]', '$holes2[14]', '$holes2[15]', '$holes2[16]', 
				'$holes2[17]', '$holes2[18]', '$holes2[19]', '$holes2[20]')"; 
			$rUser2add = mysqli_query($dbc, $qUser2add) or trigger_error("Query: $qUser2add\n<br>MySQL Error: ".mysqli_error($dbc));
			
			//Determine the current winner for round
			if(mysqli_affected_rows($dbc) == 1) {
				if($holes2[20] < $wScore){
					$winner = $golfer2;
					$wScore = $holes2[20];
				}
			}
			
			//Determine the strokes over par for the user
			if($_SESSION['golfer_gender'] == 'Male'){
				$strokesOverPar = ($holes2[20] - $arrCrsPar_m['crs_par_m']);
				//echo 'Total par for this user is: '.$holes2[20].'<br>';
				//echo 'Couse Par is: '.$arrCrsPar_m['crs_par_m'].'<br>';
				//echo 'Strokes over par for this user is: '.$strokesOverPar.'<br>';
				$qUser2hc = "INSERT INTO handicap(user_id, strokes_over_par) VALUES('{$arrUser2['golfer_id']}', '$strokesOverPar')";
				$rUser2hc = mysqli_query($dbc, $qUser2hc) or trigger_error("Query: $qUser2hc\n<br>MySQL Error: ".mysqli_error($dbc));
			}
			else {
				$strokesOverPar = ($holes2[20] - $arrCrsPar_f['crs_par_f']);
				$qUser2hc = "INSERT INTO handicap(user_id, strokes_over_par) VALUES('{$arrUser2['golfer_id']}','$strokesOverPar')";
				$rUser2hc = mysqli_query($dbc, $qUser2hc) or trigger_error("Query: $qUser2hc\n<br>MySQL Error: ".mysqli_error($dbc));
			}
			
			//Calculate the new handicap for user by finding the best 8 games after this entry
			$qry2 = "SELECT AVG(strokes_over_par) FROM (SELECT strokes_over_par FROM handicap WHERE user_id = '{$arrUser2['golfer_id']}' 
				ORDER BY strokes_over_par ASC LIMIT 8) handicap";
			$rslt2 = mysqli_query($dbc, $qry2) or trigger_error("Query: $qry2\n<br>MySQL Error: ".mysqli_error($dbc));
			$arrRSLT2 = mysqli_fetch_array($rslt2, MYSQLI_ASSOC);
			
			//Update the handicap for the user so the next login will display the new value.
			$qHandicap2 = "UPDATE golfers SET golfer_hc ='{$arrRSLT2['AVG(strokes_over_par)']}' WHERE golfer_id = '{$arrUser2['golfer_id']}' "; 
			$rHandicap2 = mysqli_query($dbc, $qHandicap2) or trigger_error("Query: $qHandicap2\n<br>MySQL Error: ".mysqli_error($dbc));
			
			//Present the user with a notice that their scores were successfully submitted.
			echo '<p>Scores were submitted for '.$golfer2.'.Thank you!</p>';
		}
	}
	else {
		echo '<p class = "error">You forgot to choose a golfer username!</p>';
	}

	if(!empty($_POST['user3'])) {
		$golfer3 = mysqli_real_escape_string($dbc, $_POST['user3']);

		if(is_null($_POST['u3_holes1']) || is_null($_POST['u3_holes2']) || is_null($_POST['u3_holes3']) || is_null($_POST['u3_holes4']) || is_null($_POST['u3_holes5']) || 
			is_null($_POST['u3_holes6']) || is_null($_POST['u3_holes7']) || is_null($_POST['u3_holes8']) || is_null($_POST['u3_holes9']) || is_null($_POST['u3_holes_F9']) || 
			is_null($_POST['u3_holes10']) || is_null($_POST['u3_holes11']) || is_null($_POST['u3_holes12']) || is_null($_POST['u3_holes13'])  || is_null($_POST['u3_holes14']) || 
			is_null($_POST['u3_holes15']) || is_null($_POST['u3_holes16'])  || is_null($_POST['u3_holes17'])  || is_null($_POST['u3_holes18'])  || is_null($_POST['u3_holes_B9'])  || 
			is_null($_POST['u3_holes_TOT'])){
				echo '<p class = "error">You forgot to enter at least one of the golf scores for '.$golfer3.'!</p>';
		}
		else {
			$holes3 = array($_POST['u3_holes1'],$_POST['u3_holes2'],$_POST['u3_holes3'],$_POST['u3_holes4'],
				$_POST['u3_holes5'], $_POST['u3_holes6'],$_POST['u3_holes7'],$_POST['u3_holes8'],$_POST['u3_holes9'],$_POST['u3_holes_F9'], 
				$_POST['u3_holes10'], $_POST['u3_holes11'],$_POST['u3_holes12'],$_POST['u3_holes13'],$_POST['u3_holes14'],$_POST['u3_holes15'],
				$_POST['u3_holes16'], $_POST['u3_holes17'],$_POST['u3_holes18'],$_POST['u3_holes_B9'],$_POST['u3_holes_TOT']);	
			
			$qUser3 = "SELECT golfer_id FROM golfers WHERE golfer_username = '$golfer3'";
			$rUser3 = mysqli_query($dbc, $qUser3) or trigger_error("Query: $qUser3\n<br>MySQL Error: ".mysqli_error($dbc));
			$arrUser3 = mysqli_fetch_array($rUser3, MYSQLI_ASSOC);
			
			$qUser3add = "INSERT INTO games(user_id, course_id, game_date, hole01, hole02, hole03, hole04, hole05, hole06, hole07, hole08, hole09, front09, 
				hole10, hole11, hole12, hole13, hole14, hole15, hole16, hole17, hole18, back09, total) VALUES ('{$arrUser3['golfer_id']}', '{$arrCourseID['crs_no']}', '$today', '$holes3[0]', '$holes3[1]', '$holes3[2]', '$holes3[3]',
				'$holes3[4]', '$holes3[5]', '$holes3[6]', '$holes3[7]', '$holes3[8]', '$holes3[9]', '$holes3[10]', '$holes3[11]', '$holes3[12]', '$holes3[13]', '$holes3[14]', '$holes3[15]', '$holes3[16]', 
				'$holes3[17]', '$holes3[18]', '$holes3[19]', '$holes3[20]')"; 
			$rUser3add = mysqli_query($dbc, $qUser3add) or trigger_error("Query: $qUser3add\n<br>MySQL Error: ".mysqli_error($dbc));
			
			//Determine the current winner of the round
			if(mysqli_affected_rows($dbc) == 1) {
				if($holes3[20] < $wScore){
					$winner = $golfer3;
					$wScore = $holes3[20];
				}
			}
			
			//Determine the strokes over par for the user
			if($_SESSION['golfer_gender'] == 'Male'){
				$strokesOverPar = ($holes3[20] - $arrCrsPar_m['crs_par_m']);
				//echo 'Total par for this user is: '.$holes3[20].'<br>';
				//echo 'Couse Par is: '.$arrCrsPar_m['crs_par_m'].'<br>';
				//echo 'Strokes over par for this user is: '.$strokesOverPar.'<br>';
				$qUser3hc = "INSERT INTO handicap(user_id, strokes_over_par) VALUES('{$arrUser3['golfer_id']}', '$strokesOverPar')";
				$rUser3hc = mysqli_query($dbc, $qUser3hc) or trigger_error("Query: $qUser3hc\n<br>MySQL Error: ".mysqli_error($dbc));
			}
			else {
				$strokesOverPar = ($holes3[20] - $arrCrsPar_f['crs_par_f']);
				$qUser3hc = "INSERT INTO handicap(user_id, strokes_over_par) VALUES('{$arrUser3['golfer_id']}','$strokesOverPar')";
				$rUser3hc = mysqli_query($dbc, $qUser3hc) or trigger_error("Query: $qUser3hc\n<br>MySQL Error: ".mysqli_error($dbc));
			}
			
			//Calculate the new handicap for user by finding the best 8 games after this entry
			$qry3 = "SELECT AVG(strokes_over_par) FROM (SELECT strokes_over_par FROM handicap WHERE user_id = '{$arrUser3['golfer_id']}' 
				ORDER BY strokes_over_par ASC LIMIT 8) handicap";
			$rslt3 = mysqli_query($dbc, $qry3) or trigger_error("Query: $qry3\n<br>MySQL Error: ".mysqli_error($dbc));
			$arrRSLT3 = mysqli_fetch_array($rslt3, MYSQLI_ASSOC);
			
			//Update the handicap for the user so the next login will display the new value.
			$qHandicap3 = "UPDATE golfers SET golfer_hc ='{$arrRSLT3['AVG(strokes_over_par)']}' WHERE golfer_id = '{$arrUser3['golfer_id']}' "; 
			$rHandicap3 = mysqli_query($dbc, $qHandicap3) or trigger_error("Query: $qHandicap3\n<br>MySQL Error: ".mysqli_error($dbc));
			
			//Present the user with a notice that their scores were successfully submitted.
			echo '<p>Scores were submitted for '.$golfer3.'.Thank you!</p>';
		}
	}
	else {
		echo '<p class = "error">You forgot to choose a golfer username!</p>';
	}
	
	if(!empty($_POST['user4'])) {
		$golfer4 = mysqli_real_escape_string($dbc, $_POST['user4']);

		if(is_null($_POST['u4_holes1']) || is_null($_POST['u4_holes2']) || is_null($_POST['u4_holes3']) || is_null($_POST['u4_holes4']) || is_null($_POST['u4_holes5']) || 
			is_null($_POST['u4_holes6']) || is_null($_POST['u4_holes7']) || is_null($_POST['u4_holes8']) || is_null($_POST['u4_holes9']) || is_null($_POST['u4_holes_F9']) || 
			is_null($_POST['u4_holes10']) || is_null($_POST['u4_holes11']) || is_null($_POST['u4_holes12']) || is_null($_POST['u4_holes13'])  || is_null($_POST['u4_holes14']) || 
			is_null($_POST['u4_holes15']) || is_null($_POST['u4_holes16'])  || is_null($_POST['u4_holes17'])  || is_null($_POST['u4_holes18'])  || is_null($_POST['u4_holes_B9'])  || 
			is_null($_POST['u4_holes_TOT'])){
				echo '<p class = "error">You forgot to enter at least one of the golf scores for '.$golfer4.'!</p>';
		}
		else {
			$holes4 = array($_POST['u4_holes1'],$_POST['u4_holes2'],$_POST['u4_holes3'],$_POST['u4_holes4'],
				$_POST['u4_holes5'], $_POST['u4_holes6'],$_POST['u4_holes7'],$_POST['u4_holes8'],$_POST['u4_holes9'],$_POST['u4_holes_F9'], 
				$_POST['u4_holes10'], $_POST['u4_holes11'],$_POST['u4_holes12'],$_POST['u4_holes13'],$_POST['u4_holes14'],$_POST['u4_holes15'],
				$_POST['u4_holes16'], $_POST['u4_holes17'],$_POST['u4_holes18'],$_POST['u4_holes_B9'],$_POST['u4_holes_TOT']);
			
			$qUser4 = "SELECT golfer_id FROM golfers WHERE golfer_username = '$golfer4'";
			$rUser4 = mysqli_query($dbc, $qUser4) or trigger_error("Query: $qUser4\n<br>MySQL Error: ".mysqli_error($dbc));
			$arrUser4 = mysqli_fetch_array($rUser4, MYSQLI_ASSOC);
			
			$qUser4add = "INSERT INTO games(user_id, course_id, game_date, hole01, hole02, hole03, hole04, hole05, hole06, hole07, hole08, hole09, front09, 
				hole10, hole11, hole12, hole13, hole14, hole15, hole16, hole17, hole18, back09, total) VALUES ('{$arrUser4['golfer_id']}', '{$arrCourseID['crs_no']}', '$today', '$holes4[0]', '$holes4[1]', '$holes4[2]', '$holes4[3]',
				'$holes4[4]', '$holes4[5]', '$holes4[6]', '$holes4[7]', '$holes4[8]', '$holes4[9]', '$holes4[10]', '$holes4[11]', '$holes4[12]', '$holes4[13]', '$holes4[14]', '$holes4[15]', '$holes4[16]', 
				'$holes4[17]', '$holes4[18]', '$holes4[19]', '$holes4[20]')"; 
			$rUser4add = mysqli_query($dbc, $qUser4add) or trigger_error("Query: $qUser4add\n<br>MySQL Error: ".mysqli_error($dbc));	
			
			//Determine the winner of the current round
			if(mysqli_affected_rows($dbc) == 1) {
				if($holes4[20] < $wScore){
					$winner = $golfer4;
					$wScore = $holes4[20];
				}
			}
			
			//Determine the strokes over par for the user
			if($_SESSION['golfer_gender'] == 'Male'){
				$strokesOverPar = ($holes4[20] - $arrCrsPar_m['crs_par_m']);
				//echo 'Total par for this user is: '.$holes4[20].'<br>';
				//echo 'Couse Par is: '.$arrCrsPar_m['crs_par_m'].'<br>';
				//echo 'Strokes over par for this user is: '.$strokesOverPar.'<br>';
				$qUser4hc = "INSERT INTO handicap(user_id, strokes_over_par) VALUES('{$arrUser4['golfer_id']}', '$strokesOverPar')";
				$rUser4hc = mysqli_query($dbc, $qUser4hc) or trigger_error("Query: $qUser4hc\n<br>MySQL Error: ".mysqli_error($dbc));
			}
			else {
				$strokesOverPar = ($holes4[20] - $arrCrsPar_f['crs_par_f']);
				$qUser4hc = "INSERT INTO handicap(user_id, strokes_over_par) VALUES('{$arrUser4['golfer_id']}','$strokesOverPar')";
				$rUser4hc = mysqli_query($dbc, $qUser4hc) or trigger_error("Query: $qUser4hc\n<br>MySQL Error: ".mysqli_error($dbc));
			}
			
			//Calculate the new handicap for user by finding the best 8 games after this entry
			$qry4 = "SELECT AVG(strokes_over_par) FROM (SELECT strokes_over_par FROM handicap WHERE user_id = '{$arrUser4['golfer_id']}' 
				ORDER BY strokes_over_par ASC LIMIT 8) handicap";
			$rslt4 = mysqli_query($dbc, $qry4) or trigger_error("Query: $qry4\n<br>MySQL Error: ".mysqli_error($dbc));
			$arrRSLT4 = mysqli_fetch_array($rslt4, MYSQLI_ASSOC);
			
			//Update the handicap for the user so the next login will display the new value.
			$qHandicap4 = "UPDATE golfers SET golfer_hc ='{$arrRSLT4['AVG(strokes_over_par)']}' WHERE golfer_id = '{$arrUser4['golfer_id']}' "; 
			$rHandicap4 = mysqli_query($dbc, $qHandicap4) or trigger_error("Query: $qHandicap4\n<br>MySQL Error: ".mysqli_error($dbc));
			
			//Present the user with a notice that their scores were successfully submitted.
			echo '<p>Scores were submitted for '.$golfer4.'.Thank you!</p>';
		}
	}
	else {
		echo '<p class = "error">You forgot to choose a golfer username!</p>';
	}
	
	echo '<h3>Congratulations, '.$winner.'! You had the lowest score with: '.$wScore.'.</h3>';
	include('includes/golfapp_footer.html');
	exit(); //To stop the script from running further

}

//Set up the queries, results, and row captures for the static parts of the form
//Thanks to https://stackoverflow.com/questions/4268871/php-append-one-array-to-another-not-array-push-or for the idea to merge arrays

$extraStrokesArray = array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);

if($_SESSION['golfer_gender'] == 'Male') {
	$qHCindexF9_m = "SELECT hc01_m, hc02_m, hc03_m, hc04_m, hc05_m, hc06_m, hc07_m, hc08_m, hc09_m FROM course WHERE crs_name = '{$_SESSION['crs_name']}'";
	$rHCindexF9_m = @mysqli_query($dbc, $qHCindexF9_m);
	$arrHCindexF9_m = mysqli_fetch_array($rHCindexF9_m, MYSQLI_ASSOC);
	
	$qHCindexB9_m = "SELECT hc10_m, hc11_m, hc12_m, hc13_m, hc14_m, hc15_m, hc16_m, hc17_m, hc18_m FROM course WHERE crs_name = '{$_SESSION['crs_name']}'";
	$rHCindexB9_m = @mysqli_query($dbc, $qHCindexB9_m);
	$arrHCindexB9_m = mysqli_fetch_array($rHCindexB9_m, MYSQLI_ASSOC);
	
	$qParF9_m = "SELECT par01_m, par02_m, par03_m, par04_m, par05_m, par06_m, par07_m, par08_m, par09_m FROM course WHERE crs_name = '{$_SESSION['crs_name']}'";
	$rParF9_m = @mysqli_query($dbc, $qParF9_m);
	$arrParF9_m = mysqli_fetch_array($rParF9_m, MYSQLI_ASSOC);
	
	$qFront9_m = "SELECT front09_m FROM course WHERE crs_name = '{$_SESSION['crs_name']}'";
	$rFront9_m = @mysqli_query($dbc, $qFront9_m);
	$arrFront9_m = mysqli_fetch_array($rFront9_m, MYSQLI_ASSOC);
	
	$qParB9_m = "SELECT par10_m, par11_m, par12_m, par13_m, par14_m, par15_m, par16_m, par17_m, par18_m FROM course WHERE crs_name = '{$_SESSION['crs_name']}'";
	$rParB9_m = @mysqli_query($dbc, $qParB9_m);
	$arrParB9_m = mysqli_fetch_array($rParB9_m, MYSQLI_ASSOC);
	
	$qBack9_m = "SELECT back09_m FROM course WHERE crs_name = '{$_SESSION['crs_name']}'";
	$rBack9_m = @mysqli_query($dbc, $qBack9_m);
	$arrBack9_m = mysqli_fetch_array($rBack9_m, MYSQLI_ASSOC);
	
	$nk = 0;
	$merge_array = array_merge($arrHCindexF9_m, $arrHCindexB9_m);
	$mergeHCindex_m = array();
	foreach($merge_array as $key => $val){
		$mergeHCindex_m[$nk] = $val;
		$nk++;
	}
		
	$courseHC = round(($_SESSION['golfer_hc'] * ($arrSlope_m['crs_slope_m']/113)) + ($arrRating_m['crs_rating_m'] - $arrCrsPar_m['crs_par_m']));
	while($courseHC > 0){
		for($index = 0; $index <=17; $index++) {
			if($mergeHCindex_m[$index] <= $courseHC) {
				$extraStrokesArray[$index]++;
			}
		}
		$courseHC = $courseHC - 18;
	}

}

else if($_SESSION['golfer_gender'] == 'Female') {
	$qHCindexF9_f = "SELECT hc01_f, hc02_f, hc03_f, hc04_f, hc05_f, hc06_f, hc07_f, hc08_f, hc09_f FROM course WHERE crs_name = '{$_SESSION['crs_name']}'";
	$rHCindexF9_f = @mysqli_query($dbc, $qHCindexF9_f);
	$arrHCindexF9_f = mysqli_fetch_array($rHCindexF9_f, MYSQLI_ASSOC);
	
	$qHCindexB9_f = "SELECT hc10_f, hc11_f, hc12_f, hc13_f, hc14_f, hc15_f, hc16_f, hc17_f, hc18_f FROM course WHERE crs_name = '{$_SESSION['crs_name']}'";
	$rHCindexB9_f = @mysqli_query($dbc, $qHCindexB9_f);
	$arrHCindexB9_f = mysqli_fetch_array($rHCindexB9_f, MYSQLI_ASSOC);	
	
	$qParF9_f = "SELECT par01_f, par02_f, par03_f, par04_f, par05_f, par06_f, par07_f, par08_f, par09_f FROM course WHERE crs_name = '{$_SESSION['crs_name']}'";
	$rParF9_f = @mysqli_query($dbc, $qParF9_f);
	$arrParF9_f = mysqli_fetch_array($rParF9_f, MYSQLI_ASSOC);
	
	$qFront9_f = "SELECT front09_f FROM course WHERE crs_name = '{$_SESSION['crs_name']}'";
	$rFront9_f = @mysqli_query($dbc, $qFront9_f);
	$arrFront9_f = mysqli_fetch_array($rFront9_f, MYSQLI_ASSOC);
	
	$qParB9_f = "SELECT par10_f, par11_f, par12_f, par13_f, par14_f, par15_f, par16_f, par17_f, par18_f FROM course WHERE crs_name = '{$_SESSION['crs_name']}'";
	$rParB9_f = @mysqli_query($dbc, $qParB9_f);
	$arrParB9_f = mysqli_fetch_array($rParB9_f, MYSQLI_ASSOC);
	
	$qBack9_f = "SELECT back09_f FROM course WHERE crs_name = '{$_SESSION['crs_name']}'";
	$rBack9_f = @mysqli_query($dbc, $qBack9_f);
	$arrBack9_f = mysqli_fetch_array($rBack9_f, MYSQLI_ASSOC);

	$nk = 0;
	$merge_array = array_merge($arrHCindexF9_f, $arrHCindexB9_f);
	$mergeHCindex_f = array();
	foreach($merge_array as $key => $val){
		$mergeHCindex_f[$nk] = $val;
		$nk++;
	}
	
	$courseHC = round(($_SESSION['golfer_hc'] * ($arrSlope_f['crs_slope_f']/113)) + ($arrRating_f['crs_rating_f'] - $arrCrsPar_f['crs_par_f']));
	while($courseHC > 0){
		for($index = 0; $index <=17; $index++) {
			if($mergeHCindex_f[$index] <= $courseHC) {
				$extraStrokesArray[$index]++;
			}
		}
		$courseHC = $courseHC - 18;
	}
}

$qGolfer = "SELECT golfer_username FROM golfers ORDER BY golfer_username ASC";
$rGolfer2 = @mysqli_query($dbc, $qGolfer);
$rGolfer3 = @mysqli_query($dbc, $qGolfer);
$rGolfer4 = @mysqli_query($dbc, $qGolfer);
$rGolferTEST = @mysqli_query($dbc, $qGolfer);

$countF9 = 9;
$countB9 = 18;

echo '<h3>'.$_SESSION['golfer_fname'].', you are playing at '.$_SESSION['crs_name'].'. Your current personal handicap is '.$_SESSION['golfer_hc'].', and your <br>
	<u>course</u> handicap strokes are listed in the blue boxes below based on the course. <br><br>
	<em>Best of luck!</em></h3><br>';

mysqli_close($dbc);


?>

<h1>Scorecard</h1>

<div class = "scorecard_container">
		<form action = "golfapp_scorecard.php" method = "post">
		<fieldset style = "background-color:#F0F0F0">		
			<div class = "scorecard_row">
				<div class = "column_label"><p><label><strong>Holes: </strong></label></div>
				<div class = "column_data">
					<?php for($i= 1; $i <=9; $i++) { 
						echo '<input type= "holes" id = "holes" name = "holes" value = "'.$i.'">';
						echo "&nbsp";
					} ?>
					<input type="holes" id="holes" name="holes" value = "OUT">
					<?php for($i= 10; $i <=18; $i++) { 
						echo '<input type= "holes" id = "holes" name = "holes" value = "'.$i.'">';
						echo "&nbsp";
					} ?>
					<input type="holes" id="holes" name="holes" value = "IN">
					<input type="holes" id="holes" name="holes" value = "TOT">
				</div>
			</div>	
			
			<div class = "scorecard_row">
				<div class = "column_label"><p><label><strong>HC Index: </strong></label></div>
				<div class = "column_data">
					<?php if($_SESSION['golfer_gender'] == 'Male') {
						foreach($arrHCindexF9_m as $value) { 
							echo '<input type= "hc_index" id = "holes" name = "holes" value = "'.$value.'">';
							echo "&nbsp";
						}
					}
					else {
						foreach($arrHCindexF9_f as $value) { 
							echo '<input type= "hc_index" id = "holes" name = "holes" value = "'.$value.'">';
							echo "&nbsp";
						}
					} ?>
					<input type= "hc_index">
					<?php if($_SESSION['golfer_gender'] == 'Male') {
						foreach($arrHCindexB9_m as $value) { 
							echo '<input type= "hc_index" id = "holes" name = "holes" value = "'.$value.'">';
							echo "&nbsp";
						}
					}
					else{
						foreach($arrHCindexB9_f as $value) { 
							echo '<input type= "hc_index" id = "holes" name = "holes" value = "'.$value.'">';
							echo "&nbsp";
						}
					} ?>
					<input type= "hc_index">
					<input type= "hc_index">
				</div>
			</div>

			<div class = "scorecard_row">
				<div class = "column_label"><p><label><strong>Strokes: </strong></label></div>
				<div class = "column_data">
					<?php for($x = 0; $x <= 8; $x++){
							echo '<input type= "my_xtra" id = "holes" name = "extra" value = "'.$extraStrokesArray[$x].'">';
							echo "&nbsp";
					} ?>
					<input type="my_xtra" id="holes" name="extra" value = "">
					<?php for($x = 9; $x <= 17; $x++) { 
						echo '<input type= "my_xtra" id = "holes" name = "extra" value = "'.$extraStrokesArray[$x].'">';
						echo "&nbsp";
					} ?>
					<input type="my_xtra" id="holes" name="extra" value = "">
					<input type="my_xtra" id="holes" name="extra" value = "">
				</div>
			</div>

			<div class = "scorecard_row">
				<div class= "custom_select" style="width:10%;">
					<select name= "user1">
						<option value ="<?php echo $_SESSION['golfer_username']; ?>"> <?php echo $_SESSION['golfer_username']; echo '</option>'; ?>
					</select>
				</div>
				<div class = "column_data">
					<?php for($i= 1; $i <=$countF9; $i++) { 
						echo '<input type= "number" id ="holes" name="u1_holes'.$i.'"  min="1" max="15" step="1.0" value="">';
						echo "&nbsp";
					} ?>
					<input type= "number" name = "u1_holes_F9" min="1" max="75" step= "1.0" value="">
					<?php for($i= 10; $i <=$countB9; $i++) { 
						echo '<input type= "number" id ="holes" name="u1_holes'.$i.'"  min="1" max="15" step="1.0" value="">';
						echo "&nbsp";
					} ?>
					<input type= "number" name = "u1_holes_B9" min="1" max="75" step= "1.0" value="">
					<input type= "number" name = "u1_holes_TOT" min="1" max="150" step= "1.0" value="">
				</div>
			</div>

			<div class = "scorecard_row">
				<div class= "custom_select" style="width:10%;">
					<select name= "user2">
						<option value disabled selected>Select User 2</option>
							<?php while($golfer = mysqli_fetch_array($rGolfer2, MYSQLI_ASSOC)){ ?>
								<option value ="<?php echo $golfer['golfer_username']; ?>"> <?php echo $golfer['golfer_username']; echo '</option>';} ?>
					</select>
				</div>
				<div class = "column_data">
					<?php for($i= 1; $i <=$countF9; $i++) { 
						echo '<input type= "number" id ="holes" name="u2_holes'.$i.'"  min="1" max="15" step="1.0" value="">';
						echo "&nbsp";
					} ?>
					<input type= "number" name = "u2_holes_F9" min="1" max="90" step= "1.0" value="">
					<?php for($i= 10; $i <=$countB9; $i++) { 
						echo '<input type= "number" id ="holes" name="u2_holes'.$i.'"  min="1" max="15" step="1.0" value="">';
						echo "&nbsp";
					} ?>
					<input type= "number" name = "u2_holes_B9" min="1" max="90" step= "1.0" value="">
					<input type= "number" name = "u2_holes_TOT" min="1" max="180" step= "1.0" value="">
				</div>
			</div>
			
			
			<div class = "scorecard_row">
				<div class= "custom_select" style="width:10%;">
					<select name= "user3">
						<option value disabled selected>Select User 3</option>
							<?php while($golfer = mysqli_fetch_array($rGolfer3, MYSQLI_ASSOC)){ ?>
								<option value ="<?php echo $golfer['golfer_username']; ?>"> <?php echo $golfer['golfer_username']; echo '</option>';} ?>
					</select>
				</div>
				<div class = "column_data">
					<?php for($i= 1; $i <=$countF9; $i++) { 
						echo '<input type= "number" id ="holes" name="u3_holes'.$i.'"  min="1" max="15" step="1.0" value="">';
						echo "&nbsp";
					} ?>
					<input type= "number" name = "u3_holes_F9" min="1" max="90" step= "1.0" value="">
					<?php for($i= 10; $i <=$countB9; $i++) { 
						echo '<input type= "number" id ="holes" name="u3_holes'.$i.'"  min="1" max="15" step="1.0" value="">';
						echo "&nbsp";
					} ?>
					<input type= "number" name = "u3_holes_B9" min="1" max="90" step= "1.0" value="">
					<input type= "number" name = "u3_holes_TOT" min="1" max="180" step= "1.0" value="">
				</div>
			</div>
			
			<div class = "scorecard_row">
				<div class= "custom_select" style="width:10%;">
					<select name= "user4">
						<option value disabled selected>Select User 4</option>
							<?php while($golfer = mysqli_fetch_array($rGolfer4, MYSQLI_ASSOC)){ ?>
								<option value ="<?php echo $golfer['golfer_username']; ?>"> <?php echo $golfer['golfer_username']; echo '</option>';} ?>
					</select>
				</div>
				<div class = "column_data">
					<?php for($i= 1; $i <=$countF9; $i++) { 
						echo '<input type= "number" id ="holes" name="u4_holes'.$i.'"  min="1" max="15" step="1.0" value="">';
						echo "&nbsp";
					} ?>
					<input type= "number" name = "u4_holes_F9" min="1" max="90" step= "1.0" value="">
					<?php for($i= 10; $i <=$countB9; $i++) { 
						echo '<input type= "number" id ="holes" name="u4_holes'.$i.'"  min="1" max="15" step="1.0" value="">';
						echo "&nbsp";
					} ?>
					<input type= "number" name = "u4_holes_B9" min="1" max="90" step= "1.0" value="">
					<input type= "number" name = "u4_holes_TOT" min="1" max="180" step= "1.0" value="">
				</div>
			</div>

			<div class = "scorecard_row">
				<div class = "column_label"><p><label><strong>Par: </strong></label></div>
				<div class = "column_data">
					<?php if($_SESSION['golfer_gender'] == 'Male') {
						foreach($arrParF9_m as $value) { 
							echo '<input type= "par" id = "holes" name = "holes" value = "'.$value.'">';
							echo "&nbsp";
						}
					}
					else{
						foreach($arrParF9_f as $value) { 
							echo '<input type= "par" id = "holes" name = "holes" value = "'.$value.'">';
							echo "&nbsp";	
						}
					}
					echo "&nbsp";
					if($_SESSION['golfer_gender'] == 'Male') {
						foreach($arrFront9_m as $value) { 
							echo '<input type= "par" id = "holes" name = "holes" value = "'.$value.'">';
						}
					}
					else {
						foreach($arrFront9_f as $value) { 
							echo '<input type= "par" id = "holes" name = "holes" value = "'.$value.'">';
						}
					}
					echo "&nbsp";
					if($_SESSION['golfer_gender'] == 'Male') {
						foreach($arrParB9_m as $value) { 
							echo '<input type= "par" id = "holes" name = "holes" value = "'.$value.'">';
							echo "&nbsp";
						} 
					}
					else{
						foreach($arrParB9_f as $value) { 
							echo '<input type= "par" id = "holes" name = "holes" value = "'.$value.'">';
							echo "&nbsp";
						} 
					}
					echo "&nbsp";
					if($_SESSION['golfer_gender'] == 'Male') {
						foreach($arrBack9_m as $value) { 
							echo '<input type= "par" id = "holes" name = "holes" value = "'.$value.'">';
							echo "&nbsp";
						} 
					}
					else{
						foreach($arrBack9_f as $value) { 
							echo '<input type= "par" id = "holes" name = "holes" value = "'.$value.'">';
							echo "&nbsp";
						} 
					}
					if($_SESSION['golfer_gender'] == 'Male'){
						foreach($arrCrsPar_m as $value) { 
							echo '<input type= "par" id = "holes" name = "holes" value = "'.$value.'">';
						} 
					}
					else{
						foreach($arrCrsPar_f as $value) { 
							echo '<input type= "par" id = "holes" name = "holes" value = "'.$value.'">';
						} 
					} ?>
				</div>
			</div>
		
		</fieldset>
		<br><br>
		<div align = "center"><input type = "submit" name = "submit" value = "Submit Scores!"></div>
		</form>
</div>

<?php 
include('includes/golfapp_footer.html');
?>