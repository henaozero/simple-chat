window.addEventListener('DOMContentLoaded', function() {

}, false);


function sendMessage() {
	$.ajax({
		type: "POST",
		url: "jumpin.php",
		data: { userid : username },
		dataType: "json",
		success: function(data){
			$("#status").html(data.result);

			if (data.inuse == "inuse") {
				$("#jumpin").val("Check");
			} else {
				$("#jumpin").val("Go in!");
			}

		}
	});
}