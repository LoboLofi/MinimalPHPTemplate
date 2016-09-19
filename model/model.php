<?php
require_once('db.php');

class model{
  public $column;
  public $tableName;
  public $primaryKey;
  public $_primaryKey;

  public function UpdateValue( $col, $newVal ){
    if( array_key_exists($col,$this->column) ){
      $this->column[$col] = $newVal;
      if( $col === $this->primaryKey ){
        $this->_primaryKey = $newVal;
      }
      return 1;
    }
    return -1;
  }

  public function Select( $opc = null ){
    $db =  new DataBase;
    return is_null($opc) ? $db->Select($this->tableName) : $db->Select($this->tableName,$opc) ;
  }
  public function Count( $opc = null ){
    $db =  new DataBase;
    return is_null($opc) ? $db->Count($this->tableName) : $db->Count($this->tableName,$opc) ;
  }

  public function Create(){
    $db =  new DataBase;
    $_col   = null;
    $_val   = null;
    foreach( $this->column as $col => $val) {
      if( is_null($val) ) {
        continue;
      }
      $_col[]=$col;
      $_val[]=$val;
    }
    return $db->Create($this->tableName, $_col, $_val);
  }
  public function Update( ){
    $db =  new DataBase;
    $_val   = '';
    foreach( $this->column as $col => $val) {
      if( $col === $this->primaryKey || is_null($val) )
        continue;
      $_val= $_val.", $col='$val'";
    }
    $_val = substr( $_val, 1, -1 );
    $opc = "$this->primaryKey='$this->_primaryKey' ";
    return $db->Update( $this->tableName, $_val ,$opc);
  }
}
?>
