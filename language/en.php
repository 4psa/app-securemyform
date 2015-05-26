<?php
/*
 * 4PSA VoipNow Professional Plug-in: SecureMyForm
 *
 * This file contains all the language messages
 *
 * @version 1.0.0
 * @license released under GNU General Public License
 * @copyright (c) 2011 4PSA. (www.4psa.com). All rights reserved.
 * @link http://wiki.4psa.com
*/
$msg_arr = array();

$msg_arr['err_invalid_phone_number_entered'] = 'Please enter a valid phone number';
$msg_arr['err_ajax_not_supported'] = 'Your browser does not support AJAX!';
$msg_arr['err_processing_request'] = 'Error occured while processing request. Please contact the site administrator.';
$msg_arr['err_establishing_connection'] = 'We are sorry we could not establish a connection with the desired number. Please try again later.';
$msg_arr['err_no_activation_code'] = 'The activation code is missing or invalid.';
$msg_arr['err_mysql_connection'] = 'Error connecting to mysql';
$msg_arr['err_not_allowed'] = 'You are not allowed to make this request.';
$msg_arr['err_wrong_activation_code'] = 'You have entered the wrong activation code too many times.';
$msg_arr['err_phone_limit_calls'] = 'This phone number has excedeed the limit of permitted calls.';
$msg_arr['err_ip_limit_calls'] = 'This IP has excedeed the limit of permitted calls.';
$msg_arr['err_invalid_phone_number'] = 'Invalid phone number';
$msg_arr['err_connect_server'] = 'The application encountered an error while trying to connect to the server. Please contact the site administrator.';

$msg_arr['error_log_no_activation_code'] = 'Secure My Form: The activation code is missing or invalid.';
$msg_arr['error_log_database_error'] = 'CallAPI Validate Phone Number: database error: ';
$msg_arr['error_log_make_call'] = 'Secure My Form: Problem with the MakeCall request: ';
$msg_arr['error_log_email_sent'] = 'Secure My Form: An email was sent';
$msg_arr['error_log_email_not_sent'] = 'Secure My Form: Problem sending the mail';

$msg_arr['status_enter_number'] = 'Please enter the number below when you are called:';
$msg_arr['status_number_validating'] = 'Please wait. The number is beeing validated...';
$msg_arr['status_correct_number'] = 'The number you entered is correct. Please wait while you are redirected to the desired page.';
$msg_arr['status_max_attempts_reached'] = 'You have reached the number of attempts allowed. You don\'t have any attempts left.';
$msg_arr['status_incorrect_number'] = 'The number you entered is not correct. Number of attempts left: {attemptsLeft}.';

$msg_arr['email_subject'] = 'Secure My Form application';
$msg_arr['email_body'] = 'Hello,<br/><br/>Secure My Form application encountered the following error: {error_msg} <br/>Please check the configuration values from <b>config/config.php</b> file.<br/>';
$msg_arr['email_body_end'] = '<br/><br/>Have a nice day.';

$msg_arr['form_title'] = 'Registration form';
$msg_arr['form_info1'] = 'Please enter your contact details in the form below';
$msg_arr['form_info2'] = 'Please enter the following number when you are called';
$msg_arr['form_info3'] = 'Your request was processed. Information received is listed below:';
$msg_arr['form_lbl_phone'] = 'Phone number: ';
$msg_arr['form_lbl_email'] = 'Email Address:';
$msg_arr['form_btn_submit'] = 'Submit';

$msg_arr['app_title'] = 'Secure My Form';
?>