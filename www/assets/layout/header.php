<!DOCTYPE html>

<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="description" content="<?php echo APP_DESCRIPTION; ?>">
		<meta name="author" content="<?php echo APP_OWNER; ?>">
		<title><?php echo APP_NAME.' | '.TITLE; ?></title>

		<link rel="icon" type="image/png" href="<?php echo __URL_LAYOUT__; ?>/images/favicon.png">
		<!-- Custom styles -->
		<link rel="stylesheet" href="<?php echo __URL_LAYOUT__; ?>/css/app.css?<?php echo substr(md5(rand()), 0, 7); ?>">
		<link rel="stylesheet" href="<?php echo __URL_LAYOUT__; ?>/css/custom.css?<?php echo substr(md5(rand()), 0, 7); ?>"> 
		<script src="<?php echo __URL_LAYOUT__; ?>/js/app.js?<?php echo substr(md5(rand()), 0, 7); ?>"></script>

		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js" integrity="sha384-cuYeSxntonz0PPNlHhBs68uyIAVpIIOZZ5JqeqvYYIcEL727kskC66kF92t6Xl2V" crossorigin="anonymous"></script>
		<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous"></script>    
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
	</head>
	<body>
	<?php require __LAYOUT_NAVBAR__; ?>
	<!-- end of header.php -->
