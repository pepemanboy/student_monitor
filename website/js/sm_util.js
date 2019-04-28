/**
 *	Log out
 */
$("#logout-buton").click(function() {
	$.post("modules/post.php", {
			module: "user_control",
			operation: "logout"
		},
		function(data, status) {
			window.location.replace("login.php");
		});
});