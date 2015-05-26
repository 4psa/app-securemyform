<?php
/*
 * 4PSA VoipNow Professional Plug-ins - Secure My Form
 *
 * This file contains configuration paramters
 *
 * Copyright (c) 2011 Rack-Soft (www.4psa.com).
 *
*/

 /* The IP/hostname of the VoipNow Professional server */
$config['VN_SERVER_IP'] = 'CHANGEME';

/* the voipnow version (example: "250", "251")*/
$config['VN_VERSION'] = '251';

/* The number of the IVR extension */
$config['VN_IVR_EXTENSION'] = 'CHANGEME';

/* 
 * The number of the extension charged for the call; 
 * CallAPI parameter ExtensionAccount 
*/
$config['VN_CHARGE_EXTENSION'] = 'CHANGEME';

/* The URL where the form is redirecting after validation */
$config['RETURN_URL'] = 'https://CHANGEME/SecureMyForm/interface/process.php';

/* Method that will be used by the form:  POST or GET*/
$config['FORM_METHOD'] = 'GET';   
  
/* choose a 2-legged oauth or a 3-legged oauth */
/* true - 2-legged oauth */
/* false - 3-legged oauth */
$config['TWO_LEGGED_OAUTH'] = TRUE; 

/* The authentification credentials used to 
* connect to the VoipNow server using OAuth
*/
$config['OAUTH_CONSUMER_KEY'] = '';
$config['OAUTH_CONSUMER_SECRET'] = '';
$config['OAUTH_ACCESS_TOKEN'] = ''; //Must change only for n-legged OAuth
$config['OAUTH_ACCESS_SECRET'] = ''; //Must change only for n-legged OAuth

/* Database credentials */
$config['MYSQL_HOST'] = 'CHANGEME'; 
$config['MYSQL_USER'] = 'CHANGEME';
$config['MYSQL_PASS'] = 'CHANGEME';
$config['MYSQL_DBNAME'] = 'CHANGEME'; 

/* The site administrator's email */
/* This email will receive emails when errors occur */
$config['EMAIL'] = 'someone@example.com';

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
