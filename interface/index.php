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
	var msgArr = [];
	<?php foreach($msgArr as $key => $value) {
		echo 'msgArr["'.$key.'"] = "'.$value.'"; ';
	} ?>;

</script>
<script type="text/javascript" src="js/lib.js"></script>
<link rel="stylesheet" href="skin/main.css">
<title><?php echo $msgArr['app_title']; ?></title>
</head>
<body>
		
<div class="background">
	<div class="container">		
		<form id="securemyform" name="securemyform" method="<?php echo $method ?>" action="validate.php" onsubmit="return verifyFieldValue();">
			<div class="header">
				SecureMyForm
			</div>
			<div id="warn_msg" class="warning"></div>
			<div class="description">
				<?php echo $msgArr['form_info1']; ?>
			</div>
			
			<div class="form_content">
				<div class="input">
					<div class="label"><?php echo $msgArr['form_lbl_phone']; ?> <span class="required">*</span></div>
					<div class="field"><input type="text" size="30" id="phone_number" name="phone_number" /></div>
				</div>
				<div class="input">
					<div class="label"><?php echo $msgArr['form_lbl_email']; ?></div>
					<div class="field"><input type="text" size="30" id="email_address" name="email_address" /></div>
				</div>
				<div class="submit"><input type="submit" value="<?php echo $msgArr['form_btn_submit']; ?>" /></div>
			</div>
			<div id="info_msg" class="info" style="display:block;"></div>
		</form>	
	</div>
</div>		
</body>
</html>