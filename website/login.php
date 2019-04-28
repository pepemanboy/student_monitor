<?php
if(session_status() == PHP_SESSION_NONE)
    session_start();

$userErr = $passErr = ""; 
$user = $pass = ""; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $r = True;
  if (empty($_POST["username"])) {
    $userErr = "User required";
    $r = False;
  } else {
    $user = test_input($_POST["username"]);
  }

  if (empty($_POST["password"])) {
    $passErr = "Password required";
    $r = False;
  } else {
    $pass = test_input($_POST["password"]);
  }

  if ($r)
  {
    $r = ($user == "teacher" && $pass == "teacher");
    if ($r)
    {
      $passErr = "exito";
      $_SESSION["user"] = $user;
      echo("<meta http-equiv='refresh' content='0; url=teacher.php' />");
    }
    else
    {
      $passErr = "Incorrect user / pass";
      session_destroy();
			$_SESSION = [];
    }
  }
  else
  {
  	session_destroy();
		$_SESSION = [];
  }
}

function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}
?>

<!doctype html>
<!-- login.php -->
<html lang="en">
<head>
  <!-- Header -->
  <?php include "header.php"; ?>
  <!-- Titulo de pagina --> 
  <title>STUDENT MONITOR - LOGIN</title>
</head>
<body>
  <!-- Inicia container -->
  <div class = "container sm-container">

  	<div class = "sm-login-container">

			<div class="row">
	  		<img src="img/sm-logo.png?v=4" alt="SM Logo">
	  	</div>

	  	<form method="post" class="login-form" action= "<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>">

		  		<div class="form-group">
					  <input name="username" type="text" class="form-control" placeholder="Teacher ID" value ="<?php echo $user;?>">
						<div class = "sm-login-form-error"> <?php echo $userErr;?> </div>
					</div>

		  		<div class="form-group">
					  <input name="password" type="password" class="form-control"  placeholder="Password"  value ="<?php echo $pass;?>">					  
						<div class = "sm-login-form-error"> <?php echo $passErr;?> </div>
					</div>

					<button type="submit" name="submit" class="btn btn-dark btn-lg btn-block">Log in</button>

		  </form>

  	</div>

  	

  </div> <!-- Acaba container -->
  <!-- Footer -->
  <?php include "footer.php"; ?>
</body>
</html> 