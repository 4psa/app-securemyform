<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN" "http://www.w3.org/TR/html4/frameset.dtd">
<?php
/* Request the language file */
require_once('language/en.php');
?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<script type="text/javascript">
	/* Use the same messages as in php for JS*/
	var msgArr = [];
	<?php foreach($msg_arr as $key => $value) {
		echo 'msgArr["'.$key.'"] = "'.$value.'"; ';
	} ?>;

</script>
<script type="text/javascript" src="js/lib.js"></script>
<link rel="stylesheet" href="skin/main.css">
<title><?php echo $msg_arr['app_title']; ?></title>
</head>
<body>
		
<div class="background">
	<div class="container">	
		<div class="header">
			<?php echo $msg_arr['form_title']; ?>
		</div>
		<div id="info_msg" class="info" style="display:block;">
			<?php echo $msg_arr['form_info3']; ?>
		</div>
		<div class="report">
			<div><span><?php echo $msg_arr['form_lbl_phone']; ?></span><?php if(isset($_REQUEST['phone_number'])) {echo $_REQUEST['phone_number']; } else {echo '-';}?></div>
			<div><span><?php echo $msg_arr['form_lbl_email']; ?></span><?php if(isset($_REQUEST['email_address'])) {echo $_REQUEST['email_address']; } else {echo '-';}?></div>
		</div>
	</div>
</div>		
</body>
</html>