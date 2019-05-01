/**
 *	Log out
 */
$("#logout-button").click(function() {
	$.post("logout.php", {},
		function(data, status) {
			window.location.replace("login.php");
		});
});