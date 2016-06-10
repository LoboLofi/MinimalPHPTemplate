<?php
require_once('db.php');

class model{
  public $columnas;
  public $NombreDeTabla;
  public $LlavePrimaria;
  public $_LlavePrimaria;

  public function ActualizarVal( $col, $NvoVal ){
    if( array_key_exists($col,$this->columnas) ){
      $this->columnas[$col] = $NvoVal;
      if( $col === $this->LlavePrimaria ){
        $this->_LlavePrimaria = $NvoVal;
      }
      return 1;
    }
    return -1;
  }

  public function Seleccionar( $opc = null ){
    $db =  new DataBase;
    return is_null($opc) ? $db->Seleccionar($this->NombreDeTabla) : $db->Seleccionar($this->NombreDeTabla,$opc) ;
  }
  public function Contar( $opc = null ){
    $db =  new DataBase;
    return is_null($opc) ? $db->Contar($this->NombreDeTabla) : $db->Contar($this->NombreDeTabla,$opc) ;
  }

  public function Guardar(){
    $db =  new DataBase;
    $_col   = null;
    $_val   = null;
    foreach( $this->columnas as $col => $val) {
      if( is_null($val) ) {
        continue;
      }
      $_col[]=$col;
      $_val[]=$val;
    }
    return $db->Crear($this->NombreDeTabla, $_col, $_val);
  }
  public function Actualizar( ){
    $db =  new DataBase;
    $_val   = '';
    foreach( $this->columnas as $col => $val) {
      if( $col === $this->LlavePrimaria || is_null($val) )
        continue;
      $_val= $_val.", $col='$val'";
    }
    $_val = substr( $_val, 1, -1 );
    $opc = "$this->LlavePrimaria='$this->_LlavePrimaria' ";
    return $db->Actualizar( $this->NombreDeTabla, $_val ,$opc);
  }
}
?>
