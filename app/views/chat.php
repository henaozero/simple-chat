<?php
/**
 * @var string $adminSecret
 * @var string $room
 * @var string $userId
 * @var string $userSecret
 */
?>

<div id="ChatRoom">

</div>
<div id="ChatMessageBox">
	<textarea id="ChatMessage" maxlength="<?= ConstSettings::messageMaxLength ?>"></textarea>
	<button id="ChatSend">Envíar</button>
</div>

<style>
	#ChatRoom .message[data-user="<?= SimpleChat::sanitizeUserId($userId) ?>"] {
		margin: 10px 10px 10px 20%;
		background: #ddd;
		color: #444;
	}
	#ChatRoom .message[data-user="<?= SimpleChat::sanitizeUserId($userId) ?>"] span {
		display: none;
	}
</style>

<script>
	window.addEventListener('DOMContentLoaded', function() {
		document.getElementById('ChatSend').addEventListener('click', sendMessage, false);
		getMessages();
	}, false);

	function drawMessages(html) {
		var chatRoomBox = document.getElementById('ChatRoom');

		chatRoomBox.innerHTML = html;

		chatRoomBox.scrollTop = chatRoomBox.scrollHeight;
	}

	function getMessages() {
		$.ajax({
			type: "POST",
			url: "index.php?action=read",
			data: {
				room: '<?= $room ?>',
				userId: '<?= $userId ?>',
				userSecret: '<?= $userSecret ?>'
			},
			dataType: "html",
			success: function(data){
				drawMessages(data);
			}
		});

		window.setTimeout(getMessages, <?= ConstSettings::messageRefreshInterval * 1000 ?>);
	}

	function sendMessage() {
		var message = document.getElementById('ChatMessage').value.trim();

		document.getElementById('ChatMessage').value = '';

		if(message.length > 0) {
			$.ajax({
				type: "POST",
				url: "index.php?action=send",
				data: {
					adminSecret: '<?= $adminSecret ?>',
					message: message,
					room: '<?= $room ?>',
					userId: '<?= $userId ?>',
					userSecret: '<?= $userSecret ?>'
				},
				dataType: "html",
				success: function(data){
					drawMessages(data);
				}
			});
		}
	}
</script>