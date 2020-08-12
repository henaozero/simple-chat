<?php
/**
 * @var string $viewContent
 * @var string $viewTitle
 */
?>

<!DOCTYPE html>
<html>

<head>
	<title><?= $viewTitle ?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<link rel="stylesheet" type="text/css" href="assets/simple-chat.css"/>
	<link rel="shortcut icon" href="assets/favicon.png" />
	<script src="assets/simple-chat.js" type="text/javascript"></script>
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js?ver=1.3.2" type="text/javascript"></script>
</head>

<body>
<?= $viewContent ?>
</body>

</html>