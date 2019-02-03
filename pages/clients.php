
<?php
  include('dbconnect.php');
?>
<link rel="stylesheet" type="text/css" href="/css/bgcolor.css">

<div class="row">
  <div class="col m12 l12 s12">
    <div class="card blue-grey darken-1">
      <div class="card-content white-text">
        <span class="card-title">Clienti</span>

          <table class="datatable responsive-table black-text">
          <thead>
            <tr>
              <th>Codice Cliente</th>
              <th>C.F. Cliente</th>
              <th>P.IVA</th>
              <th>Azioni </th>
            </tr>
          </thead>
          <tbody>
            <?php
              $sql = "SELECT * FROM clients WHERE 1";
              $query = mysql_query($sql);


              while($data = mysql_fetch_assoc($query)) {
            ?>
              <tr>
                <td>
                  <?php echo $data['codcli']; ?>
                </td>
                <td>
                  <?php echo $data['codfis']; ?>
                </td>
                <td>
                  <?php echo $data['piva']; ?>
                </td>
                <td>
                  <a href="core/deleteclient.php?codcli=<?php echo $data['codcli']; ?>">Elimina</a>
                </td>
              </tr>
            <?php
              }
            ?>
          </tbody>
          </table>

        </div>
      </div>
    </div>
</div>



<div class="row">
  <div class="col m12 l12 s12">
    <div class="card blue-grey darken-1">
      <div class="card-content white-text">
        <span class="card-title">Nuovo cliente</span>

        <div class="row">
           <form class="col s12" method="POST" action="core/addclient.php">
             <div class="row">
               <div class="input-field col s12">
                 <input id="codice" name="codcli" type="text" class="validate">
                 <label for="codice">Codice cliente</label>
               </div>
             </div>

             <div class="row">
               <div class="input-field col s12">
                 <input id="cf" name="codfis" type="text" class="validate">
                 <label for="cf">C.F. Cliente</label>
               </div>
             </div>

             <div class="row">
               <div class="input-field col s12">
                 <input id="iva" name="piva" type="text" class="validate">
                 <label for="piva">P.IVA</label>
               </div>
             </div>

             <div class="row">
               <div class="input-field col s12">
                 <input id="heading" name="heading" type="text" class="validate" placeholder="Spett.le Domenico Siviero...">
                 <label for="heading">Informazioni aggiuntive</label>
               </div>
             </div>

             <div class="row">
               <div class="input-field col s12" align="center">
                <button class="btn waves-effect waves-light center" style="width:50%" type="submit">Inserisci
                  <i class="material-icons right">send</i>
                </button>
               </div>
             </div>

           </form>
         </div>
      </div>
    </div>
  </div>
</div>
