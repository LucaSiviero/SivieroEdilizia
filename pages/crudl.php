<?php
  include('dbconnect.php');
  include('./libs/CRUDL.php');


    $crudl = new CRUDL($db, $database, 'sales', $_SERVER['REQUEST_URI'], 'idsal',
      Array(
        Array('idcat' => 'category.name'),
        Array('codcli' => 'clients.codfis'),
      )
    );
    $crudl->Core($_POST);
    $crudl->Table('Sconti');
    $crudl->Edit('Modifica Sconto');
    $crudl->Create('Nuovo Sconto');


/*
  $crudl = new CRUDL($db, $database, 'category', $_SERVER['REQUEST_URI'], 'idcat',
    Array(
      Array('code' => 'products.name')
    )
  );
  $crudl->Core($_POST);
  $crudl->Table('Categorie');
  $crudl->Edit('Modifica Categoria');
  $crudl->Create('Nuovo Categoria');
*/
/*

    $crudl = new CRUDL($db, $database, 'clients', $_SERVER['REQUEST_URI'], 'codcli');
    $crudl->Core($_POST);
    $crudl->Table('Clienti');
    $crudl->Edit('Modifica Cliente');
    $crudl->Create('Nuovo Cliente');
     */
?>
