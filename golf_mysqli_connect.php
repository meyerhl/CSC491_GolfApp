<?php # Script 18.4 - golf_mysqli_connect.php

//This file contains the database access information.
//This file also establishes a connection to MySQL,
//selects the database, and set the encoding.

//SEt the database access information as constants:
define('DB_USER', 'root');
define('DB_PASSWORD', '');
define('DB_HOST', '127.0.0.1');
define('DB_NAME', 'golf_scorecard');

//Make the connection:
//Previous versions had the OR DIE process to end the DB connection.
//We actually want to trigger an error message instead for troubleshooting.

$dbc = @mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

//If no connection can be made, trigger an error. Otherwise, set the encoding as normal.
if(!$dbc) {
	trigger_error('Could not connect to MySQL: '.mysqli_connect_error());
}
else {
	mysqli_set_charset($dbc, 'utf8');
}