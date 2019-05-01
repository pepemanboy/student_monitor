<?php

include_once( dirname(__FILE__) . '/definitions.php');
include_once( dirname(__FILE__) . '/connect.php');

$link = null;
$table_name = "sm_valeria";

$r = DbConnection::connectToDatabase($link);
if ($r != OK)
  die($r);

$exists = false;
$r = DbConnection::tableExists($link, $table_name, $exists);
if ($r != OK)
  die($r);

if (!$exists)
{
  $query = "
  CREATE TABLE {$table_name} (
  timeStamp TIMESTAMP NOT NULL PRIMARY KEY,
  status VARCHAR(20) NOT NULL
  )
  ";
  $r = mysqli_query($link, $query);
  if (!$r)
    die(ERROR_QUERY);
}

// Website request
if (isset($_POST["website"])) 
{
  if (!isset($_POST["operation"]))
    die(ERROR_ARGUMENTS);

  $operation = $_POST["operation"];
  if ($operation == "reset")
  {
    $query = "DELETE FROM {$table_name} WHERE 1";
    $r = mysqli_query($link, $query);
    if (!$r)
      die($r); 
    die(OK);
  }
  else if ($operation == "get")
  {
    $query = "SELECT * FROM {$table_name} ORDER BY timeStamp";

    $result = mysqli_query($link, $query);
    if (!$result)
      die(ERROR_QUERY);

    $dates = array();
    $values = array();
    
    if (($n = mysqli_num_rows($result)) > 0) 
    {
      $values = array();
      $dates= array();
      $i = 0;
      while($row = mysqli_fetch_assoc($result)) 
      {
          $values[$i] = $row["status"];
          $dates[$i] = $row["timeStamp"];
          $i = $i + 1;
      }
      mysqli_free_result($result);
    }
  
    $val_date = array("values" => $values, "dates" => $dates);
    echo json_encode($val_date, JSON_NUMERIC_CHECK);
  }
  else
    die(ERROR_ARGUMENTS);
}

// Android request
else if (isset($_POST["android"]) && isset($_POST["status"]))
{
  $status = $_POST["status"];
  $query = "INSERT INTO {$table_name} (status) VALUES ('{$status}')";
  $r = mysqli_query($link, $query);
  if (!$r)
    die($r);  
  die(OK);
}

// Request error
else
  die(ERROR_ARGUMENTS);
?>