<?php
  include('dbconnect.php');
?>

<link rel="stylesheet" type="text/css" href="/css/bgcolor.css">

<div class="row">
  <div class="col m12 l12 s12">
    <div class="card blue-grey darken-1">
      <div class="card-content white-text">
        <span class="card-title">Prodotti</span>

          <table class="datatable responsive-table black-text">
          <thead>
            <tr>
              <th>Codice</th>
              <th>Descrizione</th>
              <th>Prezzo</th>
              <th>Azioni</th>
            </tr>
          </thead>
          <tbody>
            <?php
              $sql = "SELECT * FROM products WHERE 1";
              $query = mysql_query($sql);


              while($data = mysql_fetch_assoc($query)) {
            ?>
              <tr>
                <td>
                  <?php echo $data['code']; ?>
                </td>
                <td>
                  <?php echo $data['name']; ?>
                </td>
                <td>
                  <?php echo $data['price']; ?>
                </td>
                <td>
                  <a href="core/deleteproduct.php?code=<?php echo $data['code']; ?>">Elimina</a>
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
        <span class="card-title">Nuovo prodotto</span>

        <div class="row">
           <form class="col s12" method="POST" action="core/addproduct.php">
             <div class="row">
               <div class="input-field col s12">
                 <input id="codice" name="code" type="text" class="validate">
                 <label for="codice">Codice</label>
               </div>
             </div>

             <div class="row">
               <div class="input-field col s12">
                 <input id="descrizione" name="name" type="text" class="validate">
                 <label for="descrizione">Descrizione</label>
               </div>
             </div>

             <div class="row">
               <div class="input-field col s12">
                   <select id="um" name="unit">
                     <option value="" disabled selected>Scegli UM</option>
                     <option value="PZ">PZ</option>
                     <option value="ML">ML</option>
                   </select>
                 <label for="um">UM</label>
               </div>
             </div>

             <div class="row">
               <div class="input-field col s12">
                 <input id="prezzo" name="price" type="text" class="validate">
                 <label for="prezzo">Prezzo</label>
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
