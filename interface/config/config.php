<?php
/**
 * 4PSA VoipNow App - Secure My Form
 *
 * This file contains configuration paramters
 *
 * @version 2.0.0
 * @license released under GNU General Public License
 * @copyright (c) 2012 4PSA. (www.4psa.com). All rights reserved.
 * @link http://wiki.4psa.com
 *
*/

 /* The IP/hostname of the VoipNow Professional server */
$config['VN_SERVER_IP'] = 'CHANGEME';

/* The number of the IVR extension */
$config['VN_IVR_EXTENSION'] = 'CHANGEME';

/* 
 * The number of the extension charged for the call; 
 * UnifiedAPI parameter ExtensionAccount 
*/
$config['VN_CHARGE_EXTENSION'] = 'CHANGEME';

/* The URL where the form is redirecting after validation */
$config['RETURN_URL'] = 'https://CHANGEME/interface/process.php';

/* Method that will be used by the form:  POST or GET*/
$config['FORM_METHOD'] = 'GET';   
  

/* The authentification credentials used to 
* connect to the VoipNow server using OAuth
*/
$config['OAUTH_ACCESS_TOKEN'] = 'CHANGEME'; 

/* Database credentials */
$config['MYSQL_HOST'] = 'CHANGEME'; 
$config['MYSQL_USER'] = 'CHANGEME';
$config['MYSQL_PASS'] = 'CHANGEME';
$config['MYSQL_DBNAME'] = 'CHANGEME'; 

/* Settings for avoid abuse */
$config['MAX_ATTEMPTS'] = 3; 
$config['MAX_SUBMITS_FOR_NUMBER'] = 100;
$config['MAX_SUBMITS_FOR_IP'] = 100; 


/* The maximum duration of the call (in seconds) */
$config['MAX_TIME_OF_CALL'] = 20; 

/* The limits of the random generated number */
$config['MIN_RANDOM_NUMBER'] = 10; 
$config['MAX_RANDOM_NUMBER'] = 99; 

?>
