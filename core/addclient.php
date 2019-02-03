<?php
  include ('../dbconnect.php');
  $sql = "INSERT INTO clients (".join(", " ,array_keys($_POST)).") VALUES ('".join("', '" ,array_values($_POST))."')";
  $query = mysql_query($sql);
  
  header("Location: ../index.php?page=clients")
?>
