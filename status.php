<?php
/*
 * 4PSA VoipNow - CallAPI validate phone number
 *
 * Verifies the status of the attempt
 * Called in AJAX at 3 seconds
 *
 * Copyright (c) 2010 Rack-Soft (www.4psa.com). All rights reserved.
 *
*/

/* Request the configuration variables */
require_once('config/config.php');

/* Request the language file */
require_once('language/en.php');

/* Request the defintions */
require_once('plib/definitions.php');

/* Request email settings */
require_once('plib/email.php');

/* Request the functions used for the requests */
require_once('plib/lib.php');

$customer_number = $_GET['customer_number'];
$random_number = $_GET['random_number'];
	
/* if this customer still has submit permissions */
$ip_permission = checkIPPermissions(true);
if ($ip_permission == 2 || $ip_permission == 3) {
	if ($ip_permission == 2) {
		echo DB_ERROR_EMAIL;
	} else {
		echo DB_ERROR_NO_EMAIL;
	}
} else {
	$call_permission = checkCallPermissions($customer_number);
	$phone_permission = checkPhonePermissions($customer_number, true);
	if ($ip_permission == 1 && $phone_permission == 1 && $call_permission == 1) {
		$app_config = getConfig();
		/* Connect to database */
		$conn = mysqli_connect($app_config['MYSQL_HOST'], $app_config['MYSQL_USER'], $app_config['MYSQL_PASS']) or die ($msg_arr['err_mysql_connection']);
		mysqli_select_db($conn, $app_config['MYSQL_DBNAME']);
	
		/* same phone number, same random number, within the last 2 minutes */
		$sql_find = "SELECT * FROM calls WHERE PhoneNumber=\"" . $customer_number . "\" AND RandomNumber=" . $random_number . " ORDER BY Time desc";
		$result = mysqli_query($conn, $sql_find);
		/* take the most recently call to this number */
		if ($result) {
			$row = $result->fetch_assoc();
			if ($row) {
				/* check if it is from the last two minutes */
				$current_time = time();
				if ($current_time - $row['Time'] < 60 * 2) {
					if ($row['AttemptsNumber'] == -1) {
						echo RINGING;
					}
					if ($row['AttemptsNumber'] == 1) {
						/* the ajax to this script, with this random number must stop */
						/* send the random number to the js script, so it knows which ajax to stop */
						echo CORRECT_NUMBER . "," . $random_number;
					}	
					if ($row['AttemptsNumber'] == 0) {
						/* count the wrong attempts */
						/* select the entries for this customer from the last 24h */
						$current_time = time();
						$sql_count = "SELECT * FROM calls WHERE PhoneNumber=\"" . $row['PhoneNumber'] . 
							"\" AND (" . $current_time . " - Time)<24*60*60 ORDER BY Time asc";
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
						$left_attempts = MAXIMUM_WRONG_ATTEMPTS - $wrong_attempts;
						/* the ajax to this script, with this random number must stop */
						/* send the random number to the js script, so it knows which ajax to stop */
						echo WRONG_NUMBER . "," . $left_attempts . "," . $random_number;
					}
				}
			} else {
				echo RINGING;
			}
		} else {
			echo RINGING;
		}
	} else {
		echo WRONG_NUMBER . ",0," . $random_number;
	}
}
	
?>