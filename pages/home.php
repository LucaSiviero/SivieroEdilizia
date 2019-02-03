<h1>Home </h1>
<link rel="stylesheet" type="text/css" href="/css/bgcolor.css">
<a href="libs/FPDF/fpdf.php">Stampa una fattura </a>

<?php

  require ("libs/FPDF/fpdf.php");

  $pdf = new FPDF('P', 'mm', 'A4');
  $pdf-> AddPage();
  $pdf->SetFont('Arial', 'B', 16);
  $pdf->output();
 ?>
