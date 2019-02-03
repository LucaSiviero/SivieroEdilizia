<?php

class CRUDL {
  public $db = null;
  public $table = null;
  public $database = null;
  public $columns = Array();
  public $uri = null;
  public $load = false;
  public $load_data = Array();
  public $primary_key = null;
  public $references = Array();

  function __construct($db, $database, $table, $uri, $primary_key, $references) {
    $this->db = $db;
    $this->table = $table;
    $this->database = $database;
    $this->uri = $uri;
    $this->primary_key = $primary_key;
    $this->references = $references;
    $this->getTableInfo();
  }

  function getTableInfo() {
    $sql = "SHOW COLUMNS FROM ".$this->table;
    $query = mysql_query($sql, $this->db);

    $columns = Array();

    while($column = mysql_fetch_assoc($query)) {
      $sql = "SELECT COLUMN_COMMENT FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '".$this->database."' AND TABLE_NAME = '".$this->table."' AND COLUMN_NAME = '".$column['Field']."'";

      $data = mysql_fetch_assoc(mysql_query($sql, $this->db));

      $columns[] = Array(
        'Field' => $column['Field'],
        'Type' => $column['Type'],
        'Comment' => $data['COLUMN_COMMENT']
      );
    }

    $this->columns = $columns;
  }

  function Core($data) {
    $action = $data['action'];
    unset($data['action']);

    foreach(array_keys($data) as $key) {
      $data[$key] = addslashes($data[$key]);
    }

    if($action == 'load_edit') {
      $this->load = true;
      $this->load_data = Array($data['key'] => $data['value']);
    }
    if($action == 'delete') {
        $sql = "DELETE FROM ".$this->table." WHERE ".$data['key']." = '".$data['value']."'";
    }
    if($action == 'edit') {
      $q = Array();

      foreach(array_keys($data) as $key) {
        if($key != $this->primary_key) {
          $q[] = $key." = '".$data[$key]."' ";
        }
      }

      $q = implode(',', $q);

      $sql = "UPDATE ".$this->table." SET ".$q." WHERE ".$this->primary_key." = '".$data[$this->primary_key]."'";
    }
    if($action == 'create') {

      $sql = "INSERT INTO ".$this->table." (".implode(',', array_keys($data)).") VALUES ('".implode("', '", array_values($data))."')";
    }

    mysql_query($sql);
  }

  function Table($title) {
    $key_id = $this->primary_key;
    $this->startTable($title);

    $sql = "SELECT * FROM ".$this->table." WHERE 1";
    $query = mysql_query($sql);

    $keysR = Array();
    foreach($this->references as $reference) {
      $keysR[] = array_keys($reference)[0];
    }

    while($data = mysql_fetch_assoc($query)) {
      echo '<tr>';
      foreach(array_keys($data) as $key) {
        if(in_array($key, $keysR)) {
          $ref = $this->references[array_search($key, $keysR)];
          $ref_data = explode('.', $ref[$key]);
          $ref_table = $ref_data[0];
          $ref_field = $ref_data[1];

          $sql = "SELECT $ref_field FROM $ref_table WHERE $key = '".$data[$key]."'";
          $q_ref = mysql_query($sql);
          $ref_result = mysql_fetch_assoc($q_ref);
          $newValue = $ref_result[$ref_field];
          $data[$key] = $newValue;
        }
        ?>
          <td>
            <?php echo $data[$key]; ?>
          </td>
        <?php
      }
      $str = $key_id.'='.$data[$key_id];

      echo '<td>
      <form method="POST" action="'.$this->uri.'">
      <input type="hidden" name="action" value="delete">
      <input type="hidden" name="key" value="'.$key_id.'">
      <input type="hidden" name="value" value="'.$data[$key_id].'">
      <input type="submit" value="Elimina">
      </form>

      <form method="POST" action="'.$this->uri.'">
      <input type="hidden" name="action" value="load_edit">
      <input type="hidden" name="key" value="'.$key_id.'">
      <input type="hidden" name="value" value="'.$data[$key_id].'">
      <input type="submit" value="Modifica">
      </form>

      </td>
      </tr>';
    }

    $this->endTable();
  }

  function startTable($title) {
    $html = '<div class="row">
      <div class="col m12 l12 s12">
      <div class="card blue-grey darken-1">
      <div class="card-content white-text">
      <span class="card-title">'.$title.'</span>

      <table class="datatable responsive-table black-text">
      <thead>
      <tr>';

    foreach($this->columns as $column) {
      $html .= '<th>'.$column['Comment'].'</th>';
    }
    $html .= '<th>Azioni</th></tr>
    </thead>
    <tbody>';

    echo $html;
  }

  function endTable() {
    $html = '</tbody>
              </table>

            </div>
          </div>
        </div>
    </div>';

    echo $html;
  }

  function startForm($title) {
    echo '<div class="row">
    <div class="col m12 l12 s12">
    <div class="card blue-grey darken-1">
    <div class="card-content white-text">
    <span class="card-title">'.$title.'</span>

    <div class="row">
    <form class="col s12" method="POST" action="'.$this->uri.'">';
  }


  function endForm($edit=false) {
    echo '
    <input type="hidden" name="action" value="'.($edit ? 'edit' : 'create').'">
    <div class="row">
    <div class="input-field col s12" align="center">
    <button class="btn waves-effect waves-light center" style="width:50%" type="submit">'.($edit ? 'Modifica' : 'Inserisci').'
    <i class="material-icons right">send</i>
    </button>
    </div>
    </div>
    </form>
    </div>
    </div>
    </div>
    </div>
    </div>';
    }

  function Create($title) {
    $this->startForm($title);

    foreach($this->columns as $column) {
      if(strpos($column['Type'], 'varchar') !== false || strpos($column['Type'], 'decimal') !== false) {
        $html = $this->formInput($column['Field'], $column['Comment']);
      }
      if($column['Type'] == 'text') {
        $html = $this->formTextarea($column['Field'], $column['Comment']);
      }
      if(strpos($column['Type'], 'enum') !== false) {
        $options = $column['Type'];
        $options = str_replace(Array('enum(', ')', "'"), Array('', '', ''), $options);

        $options = explode(',', $options);

        $html = $this->formSelect($column['Field'], $column['Comment'], $options);
      }

      echo $html;
    }

    $this->endForm();
  }

  function Edit($title) {
    if($this->load) {
      $parameters = $this->load_data;
      $sql = "SELECT * FROM ".$this->table." WHERE ".array_keys($parameters)[0]." = '".array_values($parameters)[0]."'";
      $query_data = mysql_query($sql);
      $data = mysql_fetch_assoc($query_data);

      $this->startForm($title);

      foreach($this->columns as $column) {
        if(strpos($column['Type'], 'varchar') !== false || strpos($column['Type'], 'decimal') !== false) {
          $html = $this->formInput($column['Field'], $column['Comment'], $data[$column['Field']]);
        }
        if($column['Type'] == 'text') {
          $html = $this->formTextarea($column['Field'], $column['Comment'], $data[$column['Field']]);
        }
        if(strpos($column['Type'], 'enum') !== false) {
          $options = $column['Type'];
          $options = str_replace(Array('enum(', ')', "'"), Array('', '', ''), $options, $options);

          $options = explode(',', $options);
          $html = $this->formSelect($column['Field'], $column['Comment'], $options, $data[$column['Field']]);
        }

        echo $html;
      }

      $this->endForm(true);
    }
  }

  function formInput($name, $comment, $value='') {
    $html = '<div class="row">
      <div class="input-field col s12">
        <input id="'.$name.'" name="'.$name.'" type="text" value="'.$value.'">
        <label for="'.$name.'">'.$comment.'</label>
      </div>
    </div>';

    return $html;
  }

  function formTextarea($name, $comment, $value='') {
    $html = '<div class="row">
      <div class="input-field col s12">
        <textarea id="'.$name.'" name="'.$name.'" >'.$value.'</textarea>
        <label for="'.$name.'">'.$comment.'</label>
      </div>
    </div>';

    return $html;
  }

  function formSelect($name, $comment, $options, $value='') {
    $html = '<div class="row">
      <div class="input-field col s12">
      <select id="'.$name.'" name="'.$name.'">';

    if($value != '') {
        $html .= '<option value="'.$value.'">'.$value.'</option>';
    }
    else {
      $html .= '<option value="" disabled selected>'.$comment.'</option>';
    }

    foreach($options as $option) {
        $html .= '<option value="'.$option.'">'.$option.'</option>';
    }

    $html .= '</select>
      <label for="'.$name.'">'.$comment.'</label>
      </div>
      </div>';

    return $html;
  }
}


?>
