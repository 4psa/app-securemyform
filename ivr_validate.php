<?php

/*
 * 4PSA VoipNow Professional Plug-ins: Secure My Form
 *
 * Validates the random number introduced by the user on the phone
 * It is called from CallAPI Interactive application
 *
 * Copyright (c) 2010 Rack-Soft (www.4psa.com). All rights reserved.
 *
*/


	
if(isset($_REQUEST)) {
	$req_vars = $_REQUEST;		
} elseif(isset($_POST)) {
	$req_vars = $_POST;		
} else {
	$req_vars = $_GET;		
}


error_log('Secure My Form: GET VARS= '.serialize($_GET));
error_log('Secure My Form: POST VARS= '.serialize($_POST));
error_log('Secure My Form: REQUEST VARS= '.serialize($_REQUEST));

require_once('language/en.php');

if(empty($req_vars['activationCode'])) {
	echo $msg_arr['err_no_activation_code'];
	error_log($msg_arr['error_log_no_activation_code']);
	exit(0);
}
	
include_once('config/config.php');
include_once('plib/lib.php');
		
$userCode = $req_vars['activationCode'];
	
$app_config = getConfig();
/* connect to database */
$dbhost = $app_config['MYSQL_HOST'];
$dbuser = $app_config['MYSQL_USER'];
$dbpass = $app_config['MYSQL_PASS'];

$conn = mysqli_connect($dbhost, $dbuser, $dbpass) or die ($msg_arr['err_mysql_connection']);

$dbname = $app_config['MYSQL_DBNAME'];
mysqli_select_db($conn, $dbname);
	
/* get the corect random number from the database */
$apiId = $req_vars['CallAPIID'];
$sql_find = "SELECT * FROM calls WHERE ApiId=\"" . $apiId . "\" ORDER BY Time desc";
$result = mysqli_query($conn, $sql_find);
$row = $result->fetch_assoc();
	
if ($row['RandomNumber'] == $req_vars['activationCode']) {
	/* the user introduced the correct number */
	/* note in the database that the attempt was correct */
	$sql_modify = "UPDATE calls SET AttemptsNumber = 1 where PhoneNumber = \"".$row['PhoneNumber']."\" AND Time = \"".$row['Time']."\" AND ApiId = \"" . $apiId . "\"";
	$res = mysqli_query($conn, $sql_modify);
	if ($res === false) {
		error_log($msg_arr['error_log_database_error'] . mysqli_error($conn));
	}
} else {
	/* the user failed to introduce the correct number */
	/* note in the database that the attempt was wrong */
	$sql_modify = "UPDATE calls SET AttemptsNumber=0 WHERE PhoneNumber=\"".$row['PhoneNumber']."\" AND Time=\"".$row['Time']."\" AND ApiId = \"" . $apiId . "\"";
	$res = mysqli_query($conn, $sql_modify);
	if ($res === false) {
		error_log($msg_arr['error_log_database_error'] . mysqli_error($conn));
	}
	/* count the wrong attempts */
	/* select the entries for this customer from the last 24h */
	$current_time = time();
	$sql_count = "SELECT * FROM calls WHERE PhoneNumber=\"" . $row['PhoneNumber'] . "\" AND (" . $current_time . " - Time)<24*60*60 ORDER BY Time asc";
	$attempts = mysqli_query($conn, $sql_count);
	$wrong_attempts = 0;
	while ($attempt = $attempts->fetch_assoc()) {
		/* if it is a good attempt reset the wrong attempt counter */
		if ($attempt['AttemptsNumber'] == 1) {
			$wrong_attempts = 0;
		}
		/* if it is a wrong attempt */
		if ($attempt['AttemptsNumber'] == 0) {
			$wrong_attempts = $wrong_attempts + 1;
		}
	}
	if ($wrong_attempts >= $app_config['MAX_ATTEMPTS']) {
		/* save in the database (ban table) */
		$sql_ban = "INSERT into bans(PhoneNumber, Time) values('".mysqli_real_escape_string($conn, $row['PhoneNumber'])."', " . time() .")";
		$res = mysqli_query($conn, $sql_ban);
		if ($res === false) {
			error_log($msg_arr['error_log_database_error'] . mysqli_error($conn));
		}
		$params = array("context" => "ban", "option" => "s", "priority" => 1);
	} else {
		$left_attempts = $app_config['MAX_ATTEMPTS'] - $wrong_attempts;
		$params = array("context" => "continue", "option" => "s", "priority" => 1);
	}		
}

$conn->close();
	
?>