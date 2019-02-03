<?php
  include ('../dbconnect.php');

  if(isset($_REQUEST['email']) && isset($_REQUEST['password'])) { //Se sono settate le variabili email e psw di pages/login.php
    $email = addslashes($_REQUEST['email']  );    //Prendo l'email dalla request e le aggiungo gli / per evitare situazioni
    $password = addslashes($_REQUEST['password']);  //Stesso di sopra
//Assegno la query per il login a una stringa e la eseguo
    $sql = "SELECT * FROM users WHERE email = '$email' AND password = '$password'";
    $query = mysql_query($sql);

    if(mysql_num_rows($query) > 0) {    //Controlla nella tabella
      $user = mysql_fetch_assoc($query);    //Trasforma in un array associativo il risultato della query
      session_start();      
      $_SESSION = $user;
      $_SESSION['logged'] = true;
      header("Location: ../index.php");
    }
    else {
      session_destroy();
      header("Location: ../index.php");
    }
  }
?>
