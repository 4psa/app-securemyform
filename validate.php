<?php
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

/* Form's method */
$method = getConfig('FORM_METHOD');

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN" "http://www.w3.org/TR/html4/frameset.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<script type="text/javascript">
	/* Use the same messages as in php for JS*/
	var msg_arr = [];
	<?php foreach($msg_arr as $key => $value) {
		echo 'msg_arr["'.$key.'"] = "'.$value.'"; ';
	} ?>;

</script>
<script type="text/javascript" src="js/lib.js"></script>
<link rel="stylesheet" href="skin/main.css">
<title><?php echo $msg_arr['app_title']; ?></title>
</head>
<body>
		
<div class="background">
	<div class="container">		
<?php

/* Save the data submited in the form */
if($method=='POST') {
	$req_data = $_POST;
} else {
	$req_data = $_GET;
}

if(!empty($req_data['phone_number'])) {
	$perm = checkPermissions($req_data['phone_number'], $err_code);
	if($err_code != 0 || !$perm) {
		$err_msg = $msg_arr['err_not_allowed'];
		switch($err_code) {
			case 1:
				$err_msg .= $msg_arr['err_wrong_activation_code'];
			break;
			case 2:
				$err_msg .= $msg_arr['err_phone_limit_calls'];
			break;
			case 3:
				$err_msg .= $msg_arr['err_ip_limit_calls'];
			break;
			default:
			break;
		}
	}
} else {
	$err_msg = $msg_arr['err_invalid_phone_number'];
}

if (empty($err_msg)) {
	/* Display number that must be entered when called */
	$hidden_form = null;
	foreach ($req_data as $key => $value) {
		$hidden_form .= '<input type="hidden" id="' . $key . '" name="' . $key .'" value="' . $value . '"/>';
	}
	$APIID = call($req_data['phone_number']);
	$rand_number = processRequest($req_data['phone_number'], $APIID);
	if (empty($APIID) || empty($rand_number)) {
		$err_msg = $msg_arr['err_connect_server'];
	} else {
	?>
		<form id="securemyform" name="securemyform" method="<?php echo $method ?>" action="<?php echo getConfig('RETURN_URL'); ?>">
			<div class="header">
				<?php echo $msg_arr['form_title']; ?>
			</div>
			<div id="info_msg" class="info" style="display:block;"><?php echo $msg_arr['form_info2']; ?></div>
			<div id="warn_msg" class="warning" style="display:none;"></div>	
			<div id="random_number" class="random_number"><?php echo $rand_number;?></div>
			<?php echo "<script text=\"javascript\">getAttemptStatus('" . $req_data['phone_number'] . "'," . $rand_number . ");</script>";?>
			<?php echo $hidden_form; ?>
		</form>
	<?php
	} 
} 
if(!empty($err_msg)) {
	/* Display the error occured */
	?>
	<div class="header">
		<?php echo $msg_arr['form_title']; ?>
	</div>
	<div id="info_msg" class="info" style="display:none;"></div>
	<div id="warn_msg" class="warning" style="display:block;"><?php echo $err_msg ?> </div>
	<?php
	
}
?>	
	</div>
</div>		
</body>
</html>