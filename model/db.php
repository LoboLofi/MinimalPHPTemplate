<?php
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'movil');
define('DB_PASSWORD', ']Rm3+*=y?;8u');
define('DB_DATABASE', 'turismo');

class DataBase{
  private $db;

  private function AbrirConexion(){
    $this->db = new mysqli(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_DATABASE);
  }
  private function cerrarConexion(){
    $this->db->close();
  }

  private function Reduce( $arr ){
    $rtn = "'".$arr[0];
    for( $i = 1 ; $i<count( $arr ) ; $i++ ){
      $rtn = $rtn."','".$arr[$i];
    }
    return $rtn."'";
  }
  private function ReduceQuo( $arr ){
    $rtn = ''.$arr[0];
    for( $i = 1 ; $i<count( $arr ) ; $i++ ){
      $rtn = $rtn.','.$arr[$i];
    }
    return $rtn.'';
  }

  private function IntentarEjecutar( $query ){
    if ($this->db->connect_errno) {
      printf("Algo salio mal: %s\n", $this->db->connect_error);
      exit();
    }
    return $this->db->query( $query );
  }
  private function Ejecutar( $query ){
    $this->AbrirConexion();
    $resultado = null;
    $rows = array();
    try{
      $resultado = $this->IntentarEjecutar( $query );
      if( !($resultado instanceof bool) && $resultado != true ){
        var_dump($resultado);
        echo "  Algo salio mal <br>Error: " . $this->db->error . "<br>";
      }
      if($resultado instanceof mysqli_result){
        while( $row = $resultado->fetch_assoc() ){
        	$rows[] = $row;
        }
        $resultado = $rows;
      }
    }catch( Exception $e ){
      var_dump($resultado);
      echo "  Algo salio mal <br>Error: " . $this->db->error . "<br>";
    }finally{
      $this->CerrarConexion();
    }
    return $resultado;
  }

  public function Crear(  $tabla, $col, $val ){
    $_col = $this->ReduceQuo( $col );
    $_val = $this->Reduce( $val );
    $sql  = "INSERT INTO $tabla ($_col) VALUES ( $_val )";
    return $this->Ejecutar( $sql );
  }
  public function Borrar( $tabla, $opciones ){
    $sql = "DELETE FROM $tabla WHERE {$opciones}";
    return $this->Ejecutar( $sql );
  }
  public function Seleccionar( $tabla, $opciones = null ){
    $sql = "SELECT * FROM $tabla ";
    if( $opciones != null ){
      $sql = $sql." WHERE $opciones";
    }
    return $this->Ejecutar( $sql );
  }

  public function Contar( $tabla, $opciones = null ){
    $sql = "SELECT count(*) FROM $tabla ";
    if( $opciones != null ){
      $sql = $sql." WHERE $opciones";
    }
    $respuesta = $this->Ejecutar( $sql );
    if( is_array($respuesta) ){
      return $respuesta[0]['count(*)'];
    }
    return $respuesta;
  }

  public function Actualizar( $tabla, $val, $opciones = null ){
    $sql = "UPDATE $tabla SET $val";
    if( $opciones != null ){
      $sql = $sql." WHERE $opciones";
    }
    return $this->Ejecutar( $sql );
  }

}

?>
