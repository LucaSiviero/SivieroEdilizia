<?php
  include ('../dbconnect.php');

  $sql = "DELETE FROM products WHERE code = '".addslashes($_GET['code'])."'";
  $query = mysql_query($sql);

  header("Location: ../index.php?page=products")
?>
