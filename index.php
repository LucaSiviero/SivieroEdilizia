<?php
  session_start();
  $logged = isset($_SESSION['logged']) && $_SESSION['logged'] == true;
?>
<!doctype html>
<html>
  <head>
    <meta charset="UTF-8">
    <script src="js/jquery-3.3.1.js"></script>
    <script src="js/materialize.min.js"></script>
    <script src="js/jquery.dataTables.min.js"></script>
    <script src="js/main.js"></script>
    <link rel="stylesheet" href="css/materialize.min.css">
    <link rel="stylesheet" href="css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="libs/FPDF/fpdf.css">
    <title>SivieroEdilizia</title>
  </head>
  <body>
    <?php
      $page = 'login';    //stringa che verrà visualizzata nel campo della query string della richiesta get

      if($logged) {
        $page = 'home';

        if(isset($_GET['page'])) {
          $page = $_GET['page'];    //Se la pagina è settata => si, prendi il contenuto di page mettilo nel get
        }

        include('./template/menu.php'); //NAviga nelle altre cartelle
        include('./pages/'.$page.'.php');
        include('./template/footer.php');
      }
      else {
        include('./pages/'.$page.'.php');
      }
    ?>
  </body>
</html>
