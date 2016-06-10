<?php
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'dbUsername');
define('DB_PASSWORD', 'aVeryVeryVerySecurePassword');
define('DB_DATABASE', 'NameOfDataBase');

class DataBase{
  private $db;

  private function OpenConnection(){
    $this->db = new mysqli(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_DATABASE);
  }
  private function CloseConection(){
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

  private function TryToExecute( $query ){
    if ($this->db->connect_errno) {
      printf("Something went wrong: %s\n", $this->db->connect_error);
      exit();
    }
    return $this->db->query( $query );
  }
  private function Execute( $query ){
    $this->OpenConnection();
    $result = null;
    $rows = array();
    try{
      $result = $this->TryToExecute( $query );
      if( !($result instanceof bool) && $result != true ){
        var_dump($result);
        echo "  Something went wrong <br>Error: " . $this->db->error . "<br>";
      }
      if($result instanceof mysqli_result){
        while( $row = $result->fetch_assoc() ){
        	$rows[] = $row;
        }
        $result = $rows;
      }
    }catch( Exception $e ){
      var_dump($result);
      echo "  Something went wrong <br>Error: " . $this->db->error . "<br>";
    }finally{
      $this->CloseConection();
    }
    return $result;
  }

  public function Create(  $table, $col, $val ){
    $_col = $this->ReduceQuo( $col );
    $_val = $this->Reduce( $val );
    $sql  = "INSERT INTO $table ($_col) VALUES ( $_val )";
    return $this->Execute( $sql );
  }
  public function Delete( $table, $options ){
    $sql = "DELETE FROM $table WHERE {$options}";
    return $this->Execute( $sql );
  }
  public function Select( $table, $options = null ){
    $sql = "SELECT * FROM $table ";
    if( $options != null ){
      $sql = $sql." WHERE $options";
    }
    return $this->Execute( $sql );
  }

  public function Count( $table, $options = null ){
    $sql = "SELECT count(*) FROM $table ";
    if( $options != null ){
      $sql = $sql." WHERE $options";
    }
    $responce = $this->Execute( $sql );
    if( is_array($responce) ){
      return $responce[0]['count(*)'];
    }
    return $responce;
  }

  public function Update( $table, $val, $options = null ){
    $sql = "UPDATE $table SET $val";
    if( $options != null ){
      $sql = $sql." WHERE $options";
    }
    return $this->Execute( $sql );
  }

}

?>
