<?php

/* Request the configuration variables */
require_once('config/config.php');
require_once('language/en.php');
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
		<form id="securemyform" name="securemyform" method="<?php echo $method ?>" action="validate.php" onsubmit="return verifyFieldValue();">
			<div class="header">
				<?php echo $msg_arr['form_title']; ?>
			</div>
			<div id="warn_msg" class="warning" style="display:none;"></div>
			<div id="info_msg" class="info" style="display:block;"><?php echo $msg_arr['form_info1']; ?></div>
			
			<div class="form_content">
				<div class="input">
					<div class="label"><?php echo $msg_arr['form_lbl_phone']; ?> <span class="required">*</span></div>
					<div class="field"><input type="text" size="30" id="phone_number" name="phone_number" /></div>
				</div>
				<div class="input">
					<div class="label"><?php echo $msg_arr['form_lbl_email']; ?></div>
					<div class="field"><input type="text" size="30" id="email_address" name="email_address" /></div>
				</div>
				<div class="submit"><input type="submit" value="<?php echo $msg_arr['form_btn_submit']; ?>" /></div>
			</div>
		</form>	
	</div>
</div>		
</body>
</html>