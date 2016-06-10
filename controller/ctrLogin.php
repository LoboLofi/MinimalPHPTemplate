<?php
require_once('../model/Usuario.php');

class ctrLogin{
  private $userName;
  private $password;
  private $user;
  private function Encrypt( $pass ){
    //in case of need to php Encrypt the password before insert it in the data base.
    return $pass;
  }

  public function Login( $user, $password, $file ){
    $this->userName  =  $user;
    $this->password  =  $password;
    if( $this->ExistsInADataBase() ) {
      return $this->CreateDataSession();
    }
    return array( 'code' => 0 );
  }

  private function CreateDataSession(){
    $rtnArr = array();
    $rtnArr['code'] = 1;
    $rtnArr['id']        = $this->user[0]['id'];
    $rtnArr['userClass'] = $this->user[0]['userClass'];
    $rtnArr['userName']  = $this->user[0]['userName'];
    session_start();
    $_SESSION['u_']  = $rtnArr;
    return $rtnArr;
  }
}

$ControladorDeSesion =  new ctrLogin;
$sess = $ControladorDeSesion->Login( $_POST['name'], $_POST['pw'], null );

if( $sess['code'] === 1 ){
  header('Location: ../view/ExampleView.php', true, 303);
  die();
}else{
  header('Location: ../view/inicio.php', true, 303);
  die();
}

?>
