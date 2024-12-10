<?php 
  $db_server ="localhost";
  $db_user ="root";
  $db_pass="";
  $db_name ="notebook"; 
  $conn ="";
  $conn = mysqli_connect($db_server, $db_user, $db_pass, $db_name);

  mysqli_set_charset($conn, "utf8mb4");

  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }
?>