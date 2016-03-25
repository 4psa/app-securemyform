<?php
/**
 * 4PSA VoipNow App: SecureMyForm
 *
 * This file contains all the language messages
 *
 * @version 2.0.0
 * @license released under GNU General Public License
 * @copyright (c) 2012 4PSA. (www.4psa.com). All rights reserved.
 * @link http://wiki.4psa.com
*/
$msgArr = array();

$msgArr['err_invalid_phone_number_entered'] = 'Please enter a valid phone number';
$msgArr['err_ajax_not_supported'] = 'Your browser does not support AJAX!';
$msgArr['err_processing_request'] = 'Error occured while processing request. Please contact the site administrator.';
$msgArr['err_establishing_connection'] = 'We are sorry we could not establish a connection with the desired number. Please try again later.';
$msgArr['err_no_activation_code'] = 'The activation code is missing or invalid.';
$msgArr['err_mysql_connection'] = 'Error connecting to mysql';
$msgArr['err_not_allowed'] = 'You are not allowed to make this request.';
$msgArr['err_wrong_activation_code'] = 'You have entered the wrong activation code too many times.';
$msgArr['err_phone_limit_calls'] = 'This phone number has excedeed the limit of permitted calls.';
$msgArr['err_ip_limit_calls'] = 'This IP has excedeed the limit of permitted calls.';
$msgArr['err_invalid_phone_number'] = 'Invalid phone number';
$msgArr['err_connect_server'] = 'The application encountered an error while trying to connect to the server. Please contact the site administrator.';

$msgArr['error_log_no_activation_code'] = 'Secure My Form: The activation code is missing or invalid.';
$msgArr['error_log_database_error'] = 'CallAPI Validate Phone Number: database error: ';
$msgArr['error_log_make_call'] = 'Secure My Form: Problem with the MakeCall request: ';
$msgArr['error_log_email_sent'] = 'Secure My Form: An email was sent';
$msgArr['error_log_email_not_sent'] = 'Secure My Form: Problem sending the mail';

$msgArr['status_enter_number'] = 'Please enter the number below when you are called:';
$msgArr['status_number_validating'] = 'Please wait. The number is beeing validated...';
$msgArr['status_correct_number'] = 'The number you entered is correct. Please wait while you are redirected to the desired page.';
$msgArr['status_max_attempts_reached'] = 'You have reached the number of attempts allowed. You don\'t have any attempts left.';
$msgArr['status_incorrect_number'] = 'The number you entered is not correct. Number of attempts left: {attemptsLeft}.';

$msgArr['email_subject'] = 'Secure My Form application';
$msgArr['email_body'] = 'Hello,<br/><br/>Secure My Form application encountered the following error: {error_msg} <br/>Please check the configuration values from <b>config/config.php</b> file.<br/>';
$msgArr['email_body_end'] = '<br/><br/>Have a nice day.';

$msgArr['form_title'] = 'Registration form';
$msgArr['form_info1'] = 'Please enter your contact details in the form below';
$msgArr['form_info2'] = 'Please enter the following number when you are called';
$msgArr['form_info3'] = 'Your request was processed. Information received is listed below:';
$msgArr['form_lbl_phone'] = 'Phone number: ';
$msgArr['form_lbl_email'] = 'Email Address:';
$msgArr['form_btn_submit'] = 'Submit';

$msgArr['app_title'] = 'Secure My Form';
?>