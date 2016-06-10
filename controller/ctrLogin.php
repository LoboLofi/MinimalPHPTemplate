<?php
require_once('../model/Usuario.php');

class ctrLogin{
  private $nombreUsuario;
  private $contrasenia;
  private $usuario;
  private function Encrypt( $pass ){
    return $pass;
  }

  public function Login( $user, $password, $file ){
    $this->nombreUsuario  =  $user;
    $this->contrasenia    =  $password;
    if( $this->ExisteEnBDMovil() || $this->ExisteEnOtraBD() ) {
      return $this->CrearDatosDeSesion();
    }
    return array( 'code' => 0 );
  }
  private function CrearDatosDeSesion(){
    $rtnArr = array();
    $rtnArr['code'] = 1;
    $rtnArr['idEstablecimiento'] = $this->usuario[0]['idEstablecimiento'];
    $rtnArr['tipoUsuario']       = $this->usuario[0]['tipoUsuario'];
    $rtnArr['nombreUsuario']     = $this->usuario[0]['nombreUsuario'];
    session_start();
    $_SESSION['u_']  = $rtnArr;
    return $rtnArr;
  }

  private function ExisteEnOtraBD(){
    //Agregar modelo o buscar una solución más general.
    return false;
  }

  private function ExisteEnBDMovil(){
    $usur   = new Usuario;
    $hsCont = $this->Encrypt( $this->contrasenia );
    $where  = "nombreUsuario='$this->nombreUsuario' AND contraseniaUsuario='$hsCont' ";
    $this->usuario =  $usur->Seleccionar($where);
    return is_array($this->usuario);
  }
}

$ControladorDeSesion =  new ctrLogin;
$sess = $ControladorDeSesion->Login( $_POST['nombre'], $_POST['pw'], null );

if( $sess['code'] === 1 ){
  header('Location: ../view/AdministracionAlertas.php', true, 303);
}else{
  header('Location: ../view/inicio.php', true, 303);
}

?>
