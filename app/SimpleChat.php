<?php
/**
 * @author Ikaro Henao
 */

if(file_exists('settings.php'))
	require_once 'settings.php';
else
	require_once 'defaults/settings.php';

class SimpleChat
{
	public function send()
	{
		if(!empty($_REQUEST['message']) && !empty($_REQUEST['room']) && !empty($_REQUEST['userId']) && !empty($_REQUEST['userSecret'])) {
			$adminSecret = !empty($_REQUEST['adminSecret']) ? $_REQUEST['adminSecret'] : '';
			$message = $_REQUEST['message'];
			$room = $_REQUEST['room'];
			$userId = $_REQUEST['userId'];
			$userSecret = $_REQUEST['userSecret'];
			if($this->generateRoomUserSecret($room, $userId) == $userSecret) {

				$userIdSanitized = self::sanitizeUserId($userId);

				$isAdmin = ($this->generateRoomUserAdminSecret($room, $userId) == $adminSecret) ? 'yes' : 'no';

				$htmlMessage = "<div class='message' data-admin='$isAdmin' data-user='$userIdSanitized'><span>$userId</span>$message</div>";
				$this->writeRoomFile($room, $htmlMessage);

				echo $this->readRoomFile($room);
			}
		}
	}

	public function read()
	{
		if(!empty($_REQUEST['room']) && !empty($_REQUEST['userId']) && !empty($_REQUEST['userSecret'])) {
			$room = $_REQUEST['room'];
			$userId = $_REQUEST['userId'];
			$userSecret = $_REQUEST['userSecret'];
			if($this->generateRoomUserSecret($room, $userId) == $userSecret) {
				echo $this->readRoomFile($room);
			}
		}
	}

	public function renderChat()
	{
		if(!empty($_REQUEST['room']) && !empty($_REQUEST['secret']) && !empty($_REQUEST['userId'])) {

			$adminSecret = !empty($_REQUEST['adminSecret']) ? $_REQUEST['adminSecret'] : '';
			$room = $_REQUEST['room'];
			$secret = $_REQUEST['secret'];
			$userId = $_REQUEST['userId'];
			$userSecret = $this->generateRoomUserSecret($room, $userId);

			if($this->generateRoomSecret($room) == $secret) {
				echo $this->render('chat', compact('adminSecret', 'room', 'secret', 'userId', 'userSecret'));
			}
		}
	}

	public function renderError()
	{
		echo $this->render('error');
	}


	private function _renderView($view, $dataForView)
	{
		extract($dataForView);

		ob_start();
		include "views/{$view}.php";
		return ob_get_clean();
	}

	private function _renderLayout($layout, $dataForLayout)
	{
		extract($dataForLayout);

		ob_start();
		include "layouts/{$layout}.php";
		return ob_get_clean();
	}

	protected function render($view, $dataForView = [], $layout = 'default')
	{
		$viewContent = $this->_renderView($view, $dataForView);
		$viewTitle = ConstSettings::siteName;

		return $this->_renderLayout($layout, compact('viewContent', 'viewTitle'));
	}


	private function generateRoomSecret($room)
	{
		return md5($room . ConstSettings::secret);
	}

	private function generateRoomUserSecret($room, $username)
	{
		return md5($room . $username . ConstSettings::secret);
	}

	private function generateRoomUserAdminSecret($room, $username)
	{
		return md5('isRoot' . $room . $username . ConstSettings::secret);
	}


	private function getRoomFilename($room)
	{
		$filename = 'rooms/' . trim(strtolower(preg_replace("/[^a-zA-Z0-9]+/", '', $room))) . '.html';

		if(!file_exists($filename)) {
			file_put_contents($filename, '');
		}

		return $filename;
	}

	private function readRoomFile($room)
	{
		$filename = $this->getRoomFilename($room);
		return file_get_contents($filename);
	}

	private function writeRoomFile($room, $appendContent)
	{
		$filename = $this->getRoomFilename($room);
		file_put_contents($filename, $appendContent . "\n", FILE_APPEND);
	}

	public static function sanitizeUserId($userId)
	{
		return trim(preg_replace("/[^a-zA-Z0-9]+/", '-', $userId));
	}
}