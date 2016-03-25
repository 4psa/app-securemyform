<?php
/**
 * 4PSA VoipNow app: Secure My Form
 *
 * This file contains all the function used by this app
 *
 * @version 2.0.0
 * @license released under GNU General Public License
 * @copyright (c) 2012 4PSA. (www.4psa.com). All rights reserved.
 * @link http://wiki.4psa.com
 *
*/

require_once('../../httplib/cURLRequest.php');

/**
 * Initiates the call to the VoipNow server
 *
 * @param $phone_number - the phone number entered by the user using the interface
 * @global $msgArr - the array that contains the language messages
 *
 * @return the ID of the call returned by UnifiedAPI or FALSE otherwise
 *
*/
function call($phoneNumber) {

	global $msgArr;
	
	$config = getConfig();
	/* This is the URL accessed using the REST protocol */
	$reqUrl = 'https://'.$config['VN_SERVER_IP'].'/unifiedapi/phoneCalls/@me/simple';
	
	$request = new cURLRequest();
	$request->setMethod(cURLRequest::METHOD_POST);
	$request->setHeaders(array(
		'Content-type' => 'application/json',
		'Authorization' => $config['OAUTH_ACCESS_TOKEN']
	));
	
	
	$jsonRequest = array(
		'extension' => $config['VN_CHARGE_EXTENSION'],
		'phoneCallView' => array(array(
			'source' => array($phoneNumber),
			'destination' => $config['VN_IVR_EXTENSION']))
	);
	
	$request->setBody(json_encode($jsonRequest));
	$response = $request->sendRequest($reqUrl);

	$jsonresult = json_decode($response->getBody(true) ,true);
	
	return $jsonresult[0]['id'];
}

/**
 * Generates the random number and introduce it in the database
 *
 * @param $phone_number - the customer's phone number
 * @param $APIID - the api id of the initiated call 
 * @global $msgArr - the array that contains the language messages
 * 
 * @return $random_number - the random number introduced into the database
 *			or false, on failure
*/
function processRequest($phone_number, $APIID) {

	global $msgArr;

	if (empty($APIID)) {
		return false;
	}
	
	$app_config = getConfig();
	
	/* Generate the random number */
	$random_number = rand($app_config['MIN_RANDOM_NUMBER'], $app_config['MAX_RANDOM_NUMBER']);
					
	/* Connect to database */
	$conn = mysqli_connect($app_config['MYSQL_HOST'], $app_config['MYSQL_USER'], $app_config['MYSQL_PASS']) or die ($msgArr['err_mysql_connection']);
	mysqli_select_db($conn, $app_config['MYSQL_DBNAME']);
			
	/* Insert into calls table */
	$db_error = 0;
	$db_email_sent = 0;
	$sql = "INSERT INTO calls(PhoneNumber, Time, AttemptsNumber, RandomNumber, APIID) 
			VALUES ('" . mysqli_real_escape_string($conn, $phone_number) . "', " . time() . ", -1, ".$random_number . ", '" . $APIID . "')";
	$res = mysqli_query($conn, $sql);
	if($res) {
		/* Insert into ips table */ 
		if (isset($_GET['HTTP_X_FORWARDED_FOR'])) {
			$ipaddress = $_GET['HTTP_X_FORWARDED_FOR'];
		} else {
			$ipaddress = $_SERVER['REMOTE_ADDR'];
		}
		$sql_insert = "INSERT INTO ips(Ip, Time) 
					   VALUES('" . $ipaddress . "', " . time() . ")";
		$res = mysqli_query($conn, $sql_insert);
		if ($res) {
			/* Insert into phones table */
			$sql_insert = "INSERT INTO phones(PhoneNumber, Time) 
						   VALUES('" . $phone_number . "', " . time() . ")";
			$res = mysqli_query($conn, $sql_insert); 
			if($res) {
				$conn->close();
				return $random_number;
			}
		}
	}
	
	// An error occured 
	error_log(mysqli_error($conn));
	
	// Close any opened connection 
	$conn->close();
	return false;
}

/**
 * Checks the user's permissions
 *
 * @param $phone_number - the phone number entered by the user using the interface
 *
 * @return boolean FALSE on error 
 * @return boolean TRUE if the user has permission to make the request 
*/	
function checkPermissions($phone_number, &$err_code) {

	$err_code = 0;
	$ip_permission = checkIPPermissions(false);	
	if ($ip_permission == 2 || $ip_permission == 3) {
		return false;
	} else {
		$phone_permission = checkPhonePermissions($phone_number, false);
		$call_permission = checkCallPermissions($phone_number);
		if ($ip_permission == 1 && $phone_permission == 1 && $call_permission == 1) {
			return true;
		} else {
			/* Database error */
			if ($call_permission == 2 || $call_permission == 3) {
				return false;
			} else {
				if ($phone_permission == 2 || $phone_permission == 3) {
					return false;
				} else {
					if ($call_permission == 0) {
						$err_code = 1;
					} else {
						if ($phone_permission == 0) {
							$err_code = 2;
						} else {
							if ($ip_permission == 0) {
								$err_code = 3;
							}
						}
					}
					return false;
				}
			}
		}
	}
	return true;
}

/**
 * Checks if the number is not banned (is not in the 'bans' table)
 * 
 * @param $phone_number - the phone number entered
 * @global $msgArr - the array that contains the language messages
 *
 * @return 1 if the number is not banned, 0 if the number is banned
*/
function checkCallPermissions($phone_number) {

	global $msgArr;
	
	if(empty($phone_number)) {
		return false;
	}
	
	$app_config = getConfig();
	
	/* Connect to database */
	$conn = mysqli_connect($app_config['MYSQL_HOST'], $app_config['MYSQL_USER'], $app_config['MYSQL_PASS']) or die ($msgArr['err_mysql_connection']);
	mysqli_select_db($conn, $app_config['MYSQL_DBNAME']);
	
	$current_time = time();
	$sql = "SELECT * FROM bans 
			WHERE PhoneNumber=\"" . $phone_number . "\" AND (" . $current_time . " - Time)<24*60*60 ORDER BY Time desc";
	$bans = mysqli_query($conn, $sql);
	
	$db_error = 0;
	$db_email_sent = 0;
	
	if ($bans === false) {
		$db_error = 1;
		
		error_log(mysqli_error($conn));
	}
	if ($db_error == 0) {
		/* if exists a ban for this customer in the last 24h */
		if ($ban = $bans->fetch_assoc()) {
			/* he should not be able to call */
			return 0;
		} else {
			return 1;
		}
	} else {
		return 2;
	}
	
	$conn->close();	
}

/**
 * Checks if the IP is allowed to submit the form
 *
 * @param boolean $equal - true if is permitted to have exact 
 * 							MAX_SUBMITS_FOR_IP (needed for AttemptStatus)
 * @global $msgArr - the array that contains the language messages
 *
 * @return 1 if the IP has permissions, 0 if it hasn't, 2 if there was a database error and an email was sent, 
 * 										3 if there was a db error and a sending email error
*/
function checkIPPermissions($equal) {

	global $msgArr;

	$app_config = getConfig();

	/* Get current IP address */
	if (isset($_GET['HTTP_X_FORWARDED_FOR'])) {
        $ipaddress = $_GET['HTTP_X_FORWARDED_FOR'];
	} else {
        $ipaddress = $_SERVER['REMOTE_ADDR'];
    }
	
	/* Connect to database */
	$conn = mysqli_connect($app_config['MYSQL_HOST'], $app_config['MYSQL_USER'], $app_config['MYSQL_PASS']) or die ($msgArr['err_mysql_connection']);
	mysqli_select_db($conn, $app_config['MYSQL_DBNAME']);
	
	$current_time = time();
	$sql_count = "SELECT COUNT(Ip) as ips 
				  FROM ips 
				  WHERE Ip=\"" . $ipaddress . "\" AND (" . $current_time . " - Time)<24*60*60";
	$attempts_result = mysqli_query($conn, $sql_count);
	$db_error = 0;
	$db_email_sent = 0;
	if ($attempts_result === false) {
		$db_error = 1;

		error_log(mysqli_error($conn));
	}
	if ($db_error == 0) {
		$attempts_array = ($attempts_result->fetch_assoc());
		$attempts_number = $attempts_array['ips'];
		
		if ($attempts_number < $app_config['MAX_SUBMITS_FOR_IP']) {
			return 1;
		} else {
			if ($equal) {
				if ($attempts_number == $app_config['MAX_SUBMITS_FOR_IP']) {
					return 1;
				}
			}
			return 0;
		}
	} else {
		return 2;
	}
	
	$conn->close();
}

/**
 * Checks if the phone number is allowed to submit the form
 *
 * @param string $phone_number - the phone number to check
 * @param boolean $equal - true if is permitted to have exact MAX_SUBMITS_FOR_NUMBER 
 *							(needed for AttemptStatus)
 * @global $msgArr - the array that contains the language messages
 *
 * @return 1 if it is allowed, 0 if it isn't
*/
function checkPhonePermissions($phone_number, $equal) {

	global $msgArr;

	if(empty($phone_number)) {
		return false;
	}
	
	$app_config = getConfig();

	/* Connect to database */
	$conn = mysqli_connect($app_config['MYSQL_HOST'], $app_config['MYSQL_USER'], $app_config['MYSQL_PASS']) or die ($msgArr['err_mysql_connection']);
	mysqli_select_db($conn, $app_config['MYSQL_DBNAME']);
	
	$current_time = time();
	$sql_count = "SELECT count(*) as phone_num 
				  FROM phones 
				  WHERE PhoneNumber=\"" . $phone_number . "\" AND (" . $current_time . " - Time)<24*60*60";
	$attempts_result = mysqli_query($conn, $sql_count);
	$db_error = 0;
	$db_email_sent = 0;
	if ($attempts_result === false) {
		$db_error = 1;
		error_log(mysqli_error($conn));
	}
	if ($db_error == 0) {
		$attempts_array = ($attempts_result->fetch_assoc());
		$attempts_number = $attempts_array['phone_num'];
		
		if ($attempts_number < $app_config['MAX_SUBMITS_FOR_NUMBER']) {
			return 1;
		} else {
			if ($equal) {
				if ($attempts_number == $app_config['MAX_SUBMITS_FOR_NUMBER']) {
					return 1;
				}
			}
			return 0;
		}
	} else {
		return 2;
	}
			
	$conn->close();
}

/**
 * Checks if the phone number is allowed to submit the form
 * 
 * @param string $param_name - the name of the parameter you are requesting
 *
 * @return an array with configuration parameters if var $param_name is missing
 *		   the value of the parameter associated with the $param_name
 *		   FALSE otherwise
 *		
*/
function getConfig($param_name = null){
	global $config;
	if(empty($param_name)) {
		return $config;
	}
	if(isset($config[$param_name])) {
		return $config[$param_name];
	}
	return false;
}

?>