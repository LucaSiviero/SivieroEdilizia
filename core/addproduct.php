<?php
  include ('../dbconnect.php');
  $_POST['price'] = str_replace(',', '.', $_POST['price']);
  $sql = "INSERT INTO products (".join(", " ,array_keys($_POST)).") VALUES ('".join("', '" ,array_values($_POST))."')";
  $query = mysql_query($sql);

  header("Location: ../index.php?page=products")
?>
