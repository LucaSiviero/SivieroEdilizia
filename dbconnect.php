<?php
  global $database;
  $database = 'my_sivieroedilizia';
  global $db;
  $db = mysql_connect("localhost", "sivieroedilizia", "");
  mysql_select_db($database);

?>
