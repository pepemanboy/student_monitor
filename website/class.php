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
  <title>STUDENT MONITOR - CLASS</title>
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
            <h1>Computer Science 1 - May 7</h1>  
          </div>  
          <div class = "row">
            <p> Monitoring hours: 10:00AM to 11:30AM </p>
          </div> 
          <div class="row">      
            <button type="button" class="btn btn-warning sm-teacher-room" data-toggle="modal" data-target="#exampleModal">Show QR code</button>
            <button type="button" class="btn btn-warning sm-teacher-room">Broadcast question</button>
            <button type="button" class="btn btn-warning sm-teacher-room" id = "sm-class-reset-button">Reset class</button>
            <a href="room.php"><button type="button" class="btn btn-secondary sm-teacher-room">Back to classes</button></a>
          </div>   
        </div>
        <div class = "col-md-3">
          <img src="img/sm-logo.png?v=4" alt="SM Logo" height="auto" width="100%">
        </div>
      </div>  
    </div>

    <div class="row">
      <table class="table">
        <thead>
          <tr>
            <th scope="col">#</th>
            <th scope="col">Student</th>
            <th scope="col">Arrival</th>
            <th scope="col">Attention time</th>
            <th scope="col">Distracted time</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <th scope="row">1</th>
            <td>Mark</td>
            <td>-</td>
            <td>-</td>
            <td>-</td>
          </tr>
          <tr>
            <th scope="row">2</th>
            <td>Jacob</td>
            <td>-</td>
            <td>-</td>
            <td>-</td>
          </tr>
          <tr>
            <th scope="row">3</th>
            <td>Larry</td>
            <td>-</td>
            <td>-</td>
            <td>-</td>
          </tr>
          <tr>
            <th scope="row">4</th>
            <td>Valeria</td>
            <td id = "valeria-start">-</td>
            <td id = "valeria-good">-</td>
            <td id = "valeria-bad">-</td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- QR Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Computer Science 1 - May 7 QR Code</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
             <img src="img/qr-class.png?v=4" alt="SM Logo" height="auto" width="100%">
          </div>
        </div>
      </div>
    </div>    

  </div> <!-- Acaba container -->
  <!-- Footer -->
  <?php include "footer.php"; ?>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment-with-locales.min.js"></script>
  <script src = "js/class.js?v=45"> </script>
</body>
</html> 