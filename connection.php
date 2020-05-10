<?php
  $conn = mysqli_connect('localhost', 'root', '', 'expense-tracker');
  if (!$conn)
    die('Connection to database failed : ' . mysqli_connect_error());
?>
