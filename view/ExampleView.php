<?php
  // session_start();
  // if( !isset( $_SESSION['u_'] ) ){
  //     header('Location: login.php', true, 303);
  //     die();
  // }
?>

<?php
require_once( '../model/Registro.php' );
require_once( '../model/Establecimiento.php' );
require_once( '../helpers/InspectorInconsistencias.php' );
$_EST_HOTEL_VERIFICADO_ = 1;

//Valor por defecto de constantes de paginación
$_LIMITE_REGISTROS_ = 10;
$_INICIO_REGISTROS_ = 10;

class AdministracionAlertas{
  private function ObtenerIncidenciasDeEstablecimientos( $hoteles ){
    $rtn = array();
    foreach ($hoteles as $key => $hotel) {
      $inconsistencias = InspectorInconsistencias::BuscarInsocnsistenciasDeHotel( $hotel );
      $auxMsn = '<ul>';
      foreach ($inconsistencias as $key => $inconsistencia) {
        if( $inconsistencia['Cantidad'] > 0 ){
          $auxMsn .=  '<li>'.$inconsistencia['Descripcion'].'</li>';
        }
      }
      $auxMsn.='</br>';
      $hotel['Mensaje']=$auxMsn;
      $rtn[]=$hotel;
    }
    return $rtn;
  }

//Functiones para obtener datos:
  public function HotelesModificados( ){
    $establsmto = new Establecimiento;
    $opc = "estatusEstablecimiento='w' LIMIT $_INICIO_REGISTROS_,$_LIMITE_REGISTROS_";
    return $establsmto->Seleccionar( $opc );
  }
  public function RegistrosInconsistentes( $pagina ){
    $establsmto = new Establecimiento;
    $opc = InspectorInconsistencias::CondicionesDeEstablecimientoIncosistente();
    $hoteles = $this->ObtenerIncidenciasDeEstablecimientos( $establsmto->Seleccionar( $opc ) );
    return $hoteles;
  }
  public function RegistroFueraDeFecha( ){
    $establsmto = new Establecimiento;
    $registro   = new Registro;
    $establecimientos  = $establsmto->Seleccionar( InspectorInconsistencias::CondicionesDeHotelesExtemporaneos() );
    $registros = $registro->Seleccionar( InspectorInconsistencias::CondicionesDeRegistrosExtemporaneos() );
    $hoteles = array();
    foreach( $establecimientos as $key => $hotel ){
      $hoteles[$hotel['idEstablecimiento']] = $hotel;
    }
    for( $i=0 ; $i < count($registros) ; $i++ ){
      $registros[$i]['NombreHotel'] = $hoteles[ $registros[$i]['idEstablecimiento'] ]['nombreEstablecimiento'];
    }
    return $registros;

  }
  public function RegistrosPendientes( ){
    $establsmto = new Establecimiento;
    $registro   = new Registro;
    $hoteles = $establsmto->Seleccionar( InspectorInconsistencias::CondicionesDeRegistrosPendientes() );
    foreach( $hoteles as $key => $hotel ) {
      $opc = InspectorInconsistencias::CondicionesRegistroEnTiempo( $hotel['idEstablecimiento'] );
      $hoteles[$key]['Total'] = $registro->Contar($opc);
      $opc = InspectorInconsistencias::CondicionRegistroMasReciente( $hotel['idEstablecimiento'] );
      $regAux = $registro->Seleccionar($opc);
      $hoteles[$key]['UltimoRegistro'] = $regAux[0]['fechaRegistro'];
    }
    return $hoteles;
  }

}
$test = new AdministracionAlertas;
$hoteles = $test->RegistrosInconsistentes();
// $registros = $test->RegistroFueraDeFecha();
// $hoteles = $test->RegistrosPendientes();
?>

<!-- <table style="width:100%">
  <tr>
    <th>NombreHotel</th>
    <th>fechaRegistro</th>
    <th>idRegistro</th>
    <th>estatusRegistro</th>
  </tr>
  <?php
    // foreach( $registros as $registro ){
    //   echo '<tr>';
    //   echo '<td>'.$registro['NombreHotel'].'</td>';
    //   echo '<td>'.$registro['fechaRegistro'].'</td>';
    //   echo '<td>'.$registro['idRegistro'].'</td>';
    //   echo '<td>'.$registro['estatusRegistro'].'</td>';
    //   echo '</tr>';
    // }
  ?>
</table> -->

<table style="width:100%">
  <tr>
    <th>idEstablecimiento</th>
    <th>nombreEstablecimiento</th>
    <th>razonSocial</th>
    <th>rfcEstablecimiento</th>
    <th>correoEstablecimiento</th>
    <th>estatusEstablecimiento</th>
    <th>cuartosRegistrados</th>
    <th>idCategoria</th>
    <th>idZona</th>
    <th>idDelegacion</th>
    <th>idDireccion</th>
    <th>Mensaje</th>
  </tr>
  <?php
    foreach( $hoteles as $hotel ){
      echo '<tr>';
      echo '<td>'.$hotel['idEstablecimiento'].'</td>';
      echo '<td>'.$hotel['nombreEstablecimiento'].'</td>';
      echo '<td>'.$hotel['razonSocial'].'</td>';
      echo '<td>'.$hotel['rfcEstablecimiento'].'</td>';
      echo '<td>'.$hotel['correoEstablecimiento'].'</td>';
      echo '<td>'.$hotel['estatusEstablecimiento'].'</td>';
      echo '<td>'.$hotel['cuartosRegistrados'].'</td>';
      echo '<td>'.$hotel['idCategoria'].'</td>';
      echo '<td>'.$hotel['idZona'].'</td>';
      echo '<td>'.$hotel['idDelegacion'].'</td>';
      echo '<td>'.$hotel['idDireccion'].'</td>';
      echo '<td>'.$hotel['Mensaje'].'</td>';
      echo '</tr>';
    }
  ?>
</table>
