<?php
  function cella($pdf, $x, $y, $w, $h, $title, $text, $offset=0) {
    $pdf->SetFont('Arial', '', 8);
    $pdf->SetXY($x, $y); $pdf->Cell($w,$h,'',1,1, 'L');
    $pdf->SetXY($x, $y-2+$offset); $pdf->Cell($w,$h,$title,0,0, 'L');
    $pdf->SetFont('Arial', '', 9);
    $pdf->SetXY($x, $y+2+$offset); $pdf->Cell($w,$h,$text,0,0, 'C');
  }
?>
