
<?php
  require ("libs/FPDF/fpdf.php");
  require ("libs/FPDF/pdfhtml.php");
  require ("libs/easypdf.php");

  $is_ddt = false;
  
  //DATI CLIENTI
  $nome_cliente = "Nome Cliente";
  $via_cliente = "Via Cliente";
  $cap_cliente = "0000";
  $citta_cliente = "Citta";
  $provincia_cliente = "PR";
  $cf_cliente = "000000000000000";
  $piva_cliente = "000000000000000";
  $codice_cliente = "CC000";
  $codice_agente = "CA";

  //DATI FATTURA
  $data_documento = "00/00/0000";
  $numero_documento = "0";
  $pagina = "1";
  $riferimenti = "-";
  $condizioni_di_pagamento = "Rimessa Diretta 90gg FM";
  $banca_di_appoggio = "-";

  //PRODOTTI FATTURA
  $prodotti = Array(
    Array(
      'code' => 'COD000',
      'name' => 'Descrizione prodotto 0',
      'unit' => 'PZ',
      'price' => '1.0000',
      'amount' => '2',
      'sale' => '25',
      'total' => '1.5000',
      'iva' => '0022'
    ),
    Array(
      'code' => 'COD001',
      'name' => 'Descrizione prodotto 1',
      'unit' => 'ML',
      'price' => '2.0000',
      'amount' => '3',
      'sale' => '0',
      'total' => '6.0000',
      'iva' => '0022'
    )
  );

  //CALCOLI FINE FATTURA
  $totale_merce = $is_ddt ? '' : '-';
  $piede_sconto = $is_ddt ? '' : '-';
  $trasporto = $is_ddt ? '' : '-';
  $spese = $is_ddt ? '' : '-';
  $spese_bancarie = $is_ddt ? '' : '-';
  $bolli = $is_ddt ? '' : '-';

  $imponibile = $is_ddt ? '' : '-';
  $percentuale_iva = $is_ddt ? '' : '-';
  $imposta = $is_ddt ? '' : '-';
  $totale_imponibile = $is_ddt ? '' : '-';
  $totale_imposta = $is_ddt ? '' : '-';
  $totale_esente = $is_ddt ? '' : '-';
  $acconto = $is_ddt ? '' : '-';

  $scadenze = $is_ddt ? '' : '-';
  $totale_documento = $is_ddt ? '' : '-';
  $netto_a_pagare = $is_ddt ? '' : '-';

  $trasporto_a_cura_del = $is_ddt ? '' : '-';
  $causale_trasporto = $is_ddt ? '' : '-';
  $aspetto_merce = $is_ddt ? '' : '-';
  $numero_colli = $is_ddt ? '' : '-';
  $data_trasporto = $is_ddt ? '' : '-';
  $ora_trasporto = $is_ddt ? '' : '-';

  $vettore_domicilio_vettore = $is_ddt ? '' : '-';

  $annotazioni = $is_ddt ? '' : '-';



  $pdf = new PDF('P', 'mm', 'A4');
  $pdf-> AddPage();

  $pdf->Image('img/logo.png',15,15,70);

  //INTESTAZIONE ESSEPLAST
  $pdf->SetXY(10, 40);
  $pdf->SetFont('Arial', 'B', 14);
  $pdf->Cell(0,6,'ESSEPLAST S.r.l.s.',0,1,'L');
  $pdf->SetFont('Arial', '', 9);
  $pdf->SetX(10);
  $pdf->Cell(0,4,'Via Caserta 4. Dep: Via Santagata 69',0,1,'L');
  $pdf->SetX(10);
  $pdf->Cell(0,4,'81055 - SANTA MARIA CAPUA VETERE - CE',0,1,'L');
  $pdf->SetX(10);
  $pdf->Cell(0,4,'Tel: +39 0823 810002 Fax: +39 0823 819876',0,1,'L');
  $pdf->SetX(10);
  $pdf->Cell(0,4,'e-mail: esseplast@gmail.com PEC: esseplastsrl@pec.it',0,1,'L');
  $pdf->SetX(10);
  $pdf->Cell(0,4,'C.F./P.IVA: 04212150611',0,0,'L');

  //INTESTAZIONE CLIENTE
  $html = "<b>Spett.le<br>$nome_cliente</b><br>$via_cliente<br>$cap_cliente $citta_cliente $provincia_cliente";
  $pdf->SetXY(106, 15); $pdf->Cell(95,40,'',1,1, 'L');
  $pdf->SetXY(0, 18); $pdf->SetLeftMargin(106); $pdf->WriteHTML($html);

  //INFO CLIENTE
  //$pdf->SetFont('Arial', '', 9);
  $pdf->SetXY(106, 56); $pdf->Cell(50,10,'',1,1, 'L');
  $pdf->SetXY(106, 54); $pdf->Cell(50,10,'P. IVA '.$piva_cliente,0,0, 'L');
  $pdf->SetXY(106, 58); $pdf->Cell(50,10,'C. F. Cliente '.$cf_cliente,0,0, 'L');

  $pdf->SetXY(157, 56); $pdf->Cell(22,10,'',1,1, 'L');
  $pdf->SetXY(157, 54); $pdf->Cell(22,10,'Cod. Cliente',0,0, 'L');
  $pdf->SetXY(157, 58); $pdf->Cell(22,10,$codice_cliente,0,0, 'L');

  $pdf->SetXY(180, 56); $pdf->Cell(21,10,'',1,1, 'L');
  $pdf->SetXY(180, 54); $pdf->Cell(22,10,'Cod. Agente',0,0, 'L');
  $pdf->SetXY(180, 58); $pdf->Cell(22,10,$codice_agente,0,0, 'L');

  //INFO PAGAMENTO
  $pdf->setInterlinea(4);
  $html = " Ns. Coordinate Bancarie per bonifico:<br>
<b>Banco di Napoli - Agenzia di Curti</b><br>
IT34T 01010 75190 10000 0004340<br>
<b>Banca di Credito Popolare - Agenzia S. Maria C. V.</b><br>
IT60J 05142 75040 16457 1151681";
  $pdf->SetXY(106, 67); $pdf->Cell(95,21,'',1,1, 'L');
  $pdf->SetXY(0, 68); $pdf->SetLeftMargin(106); $pdf->WriteHTML($html);

  //TITOLO FATTURA VENDITA
  $pdf->SetXY(10, 67); $pdf->Cell(95,10,'',1,1, 'L');
  $pdf->SetFont('Arial', 'B', 14);
  $pdf->SetXY(10, 67); $pdf->Cell(95,10,'FATTURA DI VENDITA',0,0, 'C');

  //INFO FATTURA
  $pdf->SetFont('Arial', '', 9);
  $pdf->SetXY(10, 78); $pdf->Cell(28,10,'',1,1, 'L');
  $pdf->SetXY(10, 75); $pdf->Cell(28,10,'Data Documento',0,0, 'L');
  $pdf->SetXY(10, 80); $pdf->Cell(28,10,$data_documento,0,0, 'L');

  $pdf->SetXY(39, 78); $pdf->Cell(28,10,'',1,1, 'L');
  $pdf->SetXY(39, 75); $pdf->Cell(28,10,'Num. Documento',0,0, 'L');
  $pdf->SetXY(39, 80); $pdf->Cell(28,10,$numero_documento,0,0, 'L');

  $pdf->SetXY(68, 78); $pdf->Cell(10,10,'',1,1, 'L');
  $pdf->SetXY(68, 75); $pdf->Cell(28,10,'Pag.',0,0, 'L');
  $pdf->SetXY(68, 80); $pdf->Cell(28,10,$pagina,0,0, 'L');

  $pdf->SetXY(79, 78); $pdf->Cell(26,10,'',1,1, 'L');
  $pdf->SetXY(79, 75); $pdf->Cell(28,10,'Riferimenti',0,0, 'L');
  $pdf->SetXY(79, 80); $pdf->Cell(28,10,$riferimenti,0,0, 'L');

  //INFO PAGAMENTO
  $pdf->SetXY(10, 89); $pdf->Cell(68,10,'',1,1, 'L');
  $pdf->SetXY(10, 87); $pdf->Cell(28,10,'Condizioni di Pagamento',0,0, 'L');
  $pdf->SetXY(10, 91); $pdf->Cell(28,10,$condizioni_di_pagamento,0,0, 'L');

  $pdf->SetXY(79, 89); $pdf->Cell(122,10,'',1,1, 'L');
  $pdf->SetXY(79, 87); $pdf->Cell(28,10,'Banca di appoggio                                            ABI - CAB',0,0, 'L');
  $pdf->SetXY(79, 91); $pdf->Cell(28,10,$banca_di_appoggio,0,0, 'L');

  //PRODOTTI
  $p_height = 94;

  //TITOLI PRODOTTI
  $pdf->SetFont('Arial', 'B', 8);

  $pdf->SetXY(10, 100); $pdf->Cell(25,$p_height,'',1,1, 'L');
  $pdf->SetXY(10, 100); $pdf->Cell(25,5,'Codice Articolo',1,1, 'L');

  $pdf->SetXY(35, 100); $pdf->Cell(68,$p_height,'',1,1, 'L');
  $pdf->SetXY(35, 100); $pdf->Cell(68,5,'Descrizione',1,1, 'L');

  $pdf->SetXY(103, 100); $pdf->Cell(10,$p_height,'',1,1, 'L');
  $pdf->SetXY(103, 100); $pdf->Cell(10,5,'U.M.',1,1, 'L');

  $pdf->SetXY(113, 100); $pdf->Cell(17,$p_height,'',1,1, 'L');
  $pdf->SetXY(113, 100); $pdf->Cell(17,5,"Quantita'",1,1, 'L');

  $pdf->SetXY(130, 100); $pdf->Cell(24,$p_height,'',1,1, 'L');
  $pdf->SetXY(130, 100); $pdf->Cell(24,5,'Prezzo Unitario',1,1, 'L');

  $pdf->SetXY(154, 100); $pdf->Cell(15,$p_height,'',1,1, 'L');
  $pdf->SetXY(154, 100); $pdf->Cell(15,5,'Sconto',1,1, 'L');

  $pdf->SetXY(169, 100); $pdf->Cell(22,$p_height,'',1,1, 'L');
  $pdf->SetXY(169, 100); $pdf->Cell(22,5,'Importo Totale',1,1, 'L');

  $pdf->SetXY(191, 100); $pdf->Cell(10,$p_height,'',1,1, 'L');
  $pdf->SetXY(191, 100); $pdf->Cell(10,5,'C. IVA',1,1, 'L');

  //VALORI PRODOTTI
  //foreach sui prodotti estrapolati dal db (y variabile x fisse)
  $pdf->SetFont('Arial', '', 8);
  for($i = 0; $i < count($prodotti); $i++) {
    $i_height = ($i+1)*3+2;
    $prod = $prodotti[$i];

    $pdf->SetXY(10, 100+$i_height); $pdf->Cell(25,5,$prod['code'],0,0, 'L');
    $pdf->SetXY(35, 100+$i_height); $pdf->Cell(68,5,$prod['name'],0,0, 'L');
    $pdf->SetXY(103, 100+$i_height); $pdf->Cell(10,5,$prod['unit'],0,0, 'L');
    $pdf->SetXY(113, 100+$i_height); $pdf->Cell(17,5,$prod['amount'],0,0, 'L');
    $pdf->SetXY(130, 100+$i_height); $pdf->Cell(24,5,$prod['price'],0,0, 'L');
    $pdf->SetXY(154, 100+$i_height); $pdf->Cell(15,5,$prod['sale'],0,0, 'L');
    $pdf->SetXY(169, 100+$i_height); $pdf->Cell(22,5,$prod['total'],0,0, 'L');
    $pdf->SetXY(191, 100+$i_height); $pdf->Cell(10,5,$prod['iva'],0,0, 'L');
  }

  //INFO FATTURA
  $p_height -= 6;
  cella($pdf, 10, 106+$p_height+1, 31, 9, 'Totale Merce', $totale_merce);
  cella($pdf, 42, 106+$p_height+1, 31, 9, 'Piede Sconto', $piede_sconto);
  cella($pdf, 74, 106+$p_height+1, 31, 9, 'Trasporto', $trasporto);
  cella($pdf, 106, 106+$p_height+1, 31, 9, 'Spese', $spese);
  cella($pdf, 138, 106+$p_height+1, 31, 9, 'Spese Bancarie', $spese_bancarie);
  cella($pdf, 170, 106+$p_height+1, 31, 9, 'Bolli', $bolli);


  cella($pdf, 10, 116+$p_height+1, 31, 9, 'Imponibile', $imponibile);
  cella($pdf, 42, 116+$p_height+1, 17, 9, '% IVA', $percentuale_iva);
  cella($pdf, 60, 116+$p_height+1, 27, 9, 'Imposta', $imposta);
  cella($pdf, 88, 116+$p_height+1, 27, 9, 'Totale Imponibile', $totale_imponibile);
  cella($pdf, 116, 116+$p_height+1, 27, 9, 'Totale Imposta', $totale_imposta);
  cella($pdf, 144, 116+$p_height+1, 27, 9, 'Totale Esente', $totale_esente);
  cella($pdf, 172, 116+$p_height+1, 29, 9, 'Acconto', $acconto);

  cella($pdf, 10, 126+$p_height+1, 90, 9, 'Scadenze', $scadenze);
  cella($pdf, 101, 126+$p_height+1, 50, 9, 'Totale Documento', $totale_documento);
  cella($pdf, 152, 126+$p_height+1, 49, 9, 'Netto a Pagare', $netto_a_pagare);

  cella($pdf, 10, 136+$p_height+1, 31, 9, 'Trasporto a cura del', $trasporto_a_cura_del);
  cella($pdf, 42, 136+$p_height+1, 31, 9, 'Causale Trasporto', $causale_trasporto);
  cella($pdf, 74, 136+$p_height+1, 31, 9, 'Aspetto Merce', $aspetto_merce);
  cella($pdf, 106, 136+$p_height+1, 31, 9, 'Nr. colli', $numero_colli);
  cella($pdf, 138, 136+$p_height+1, 31, 9, 'Data Trasporto', $data_trasporto);
  cella($pdf, 170, 136+$p_height+1, 31, 9, 'Ora Trasporto', $ora_trasporto);

  cella($pdf, 10, 146+$p_height+1, 125, 9, 'Vettore                          Domicilio Vett.', $vettore_domicilio_vettore);
  cella($pdf, 136, 146+$p_height+1, 65, 9, 'Firma del vettore / Conducente', '');


  cella($pdf, 10, 156+$p_height+1, 125, 15, 'Annotazioni', $annotazioni, -3);
  cella($pdf, 136, 156+$p_height+1, 65, 15, 'Timbro e Firma del Destinatario', '', -3);

  $pdf->SetFont('Arial', '', 8);
  $pdf->SetXY(10, 167+$p_height+1);
  $pdf->Cell(125, 4,'Contributo CONAI assolto ove dovuto',0,0, 'R');

  $pdf->SetFont('Arial', '', 7);
  $pdf->setInterlinea(3);
  $html = " Non si accettano reclami trascorsi 5gg dalla consegna della merce.<br>
I pagamenti pattuiti devono essere tassativamente rispettati. In caso di mancato pagamento saranno addebitati interessi bancari al tasso vigente, oltre le relative spese.<br>
Per motivi fiscali, non applicare al totale fattura alcun arrotondamento o sconto non concordato.<br>
I dati anagrafici sono trattati solo per finalita' amministrative e per gli adempimenti agli obblighi di legge. Vi preghiamo di controllare i vs. dati fiscali e<br>
comunicarci tempestivamente eventuali errori. Per ogni controversia sara' competente il foro di Santa Maria Capua Vetere.";
  $pdf->SetXY(0, 172+$p_height+1); $pdf->SetLeftMargin(8); $pdf->WriteHTML($html);


  $pdf->Output();
 ?>
