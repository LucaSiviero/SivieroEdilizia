<?php
  include ('../dbconnect.php');

  $sql = "DELETE FROM clients WHERE codcli = '".addslashes($_GET['codcli'])."'";
  $query = mysql_query($sql);
  header("Location: ../index.php?page=clients");
?>
