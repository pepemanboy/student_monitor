<?php
if(session_status() == PHP_SESSION_NONE)
    session_start();

if (!isset($_SESSION["user"]))
  echo("<meta http-equiv='refresh' content='0; url=login.php' />");
?>

<!doctype html>
<!-- index.php -->
<html lang="en">
<head>
  <!-- Header -->
  <?php include "header.php"; ?>
  <!-- Titulo de pagina --> 
  <title>STUDENT MONITOR - ROOM</title>
</head>
<body>
  <!-- Inicia container -->
  <div class = "container sm-container">
    <!-- Navbar -->
    <?php include "navbar.php"; ?>

    <div class="jumbotron">
      <div class = "row">
        <div class = "col-md-9">
          <div class="row">
            <h1>Computer Science 1</h1>  
          </div>   
          <div class="row">      
            <p>Pick one of your classes</p>
          </div>   
          <div class="row">
            <button type="button" class="btn btn-warning sm-teacher-room">May 1</button>
            <button type="button" class="btn btn-warning sm-teacher-room">May 3</button>
            <a href="class_archive.php"><button type="button" class="btn btn-warning sm-teacher-room">May 5</button></a>
            <a href="class.php"><button type="button" class="btn btn-warning sm-teacher-room">May 7</button></a>
            <button type="button" class="btn btn-warning sm-teacher-room">+</button>
            <a href="teacher.php"><button type="button" class="btn btn-secondary sm-teacher-room">Back to rooms</button></a>
          </div>  
        </div>
        <div class = "col-md-3">
          <img src="img/sm-logo.png?v=4" alt="SM Logo" height="auto" width="100%">
        </div>
      </div> 	

    </div>

  </div> <!-- Acaba container -->
  <!-- Footer -->
  <?php include "footer.php"; ?>
</body>
</html> 