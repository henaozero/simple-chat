<?php
require_once 'app/SimpleChat.php';

$simpleChat = new SimpleChat();

if(!empty($_REQUEST['action'])) {
	$action = $_REQUEST['action'];
} else {
	$action = 'default';
}

switch($action) {
	case 'read' :
		$simpleChat->read();
		break;

	case 'send' :
		$simpleChat->send();
		break;

	default :
		$simpleChat->renderChat();
		break;
}


