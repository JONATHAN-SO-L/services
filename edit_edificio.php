<?php
session_start();

if( $_SESSION['nombre']!="" && $_SESSION['clave']!="" && $_SESSION['tipo']=="admin"){
  include './services/assets/admin/navbar.php';
  include './services/assets/admin/links.php';

  function mensaje_ayuda(){
        echo '
            <div class="alert alert-success alert-dismissible fade in col-sm-3 animated bounceInDown" role="alert" style="position:fixed; top:70px; right:10px; z-index:10;"> 
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
            <h4 class="text-center"><strong>REGISTRO ACTUALIZADO</strong></h4>
            <p class="text-center">
            Se modificó exitosamente el edificio.
            </p>
            </div>
            ';
    }

    function mensaje_error() {
        echo '
            <div class="alert alert-danger alert-dismissible fade in col-sm-3 animated bounceInDown" role="alert" style="position:fixed; top:70px; right:10px; z-index:10;"> 
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
            <h4 class="text-center"><strong>OCURRIÓ UN ERROR</strong></h4>
            <p class="text-center">
            <u>No se logró recibir información correctamente, por favor, inténtalo de nuevo o contácta al Soporte Técnico.
            </p>
            </div>
            ';
    }
?>
<script src="./services/assets/css/main.css"></script>
<section id="content">
  <header id="content-header">
  
  <table>  
    <td>
		<tr>
       <a href="edificio.php" ><button  type="submit" value="Regresar" name="" class="btn btn-primary" style="text-align:center"><i class="fa fa-reply"></i>&nbsp;&nbsp;Regresar</button></a>
	   </tr>
	</td>
	   </table>
		<!--************************************ Page content******************************-->
		<div class="container">
          <div class="row">
            <div class="col-sm-12">
              <div class="page-header2">
                <h1 class="animated lightSpeedIn">Editar Edificio</h1>
                <span class="label label-danger"></span>
                <p class="pull-right text-primary">
               </p>
              </div>
            </div>
          </div>
        </div>
		<!--************************************ Page content******************************-->
<div class="container">
  <div class="row">
    <div class="col-sm-8">
      <div class="panel panel-warning">
        <div class="panel-heading text-center"><strong>Edite la información que se muestra a continuación, evite usar acentos.</strong></div>
        <div class="panel-body">
            <form role="form" action="" method="POST">
		
<?php
include './lib/class_mysql.php';
include './lib/config.php';
include ("conexi.php");
 
if(isset($_POST['descripcion']) && isset($_POST['calle']) && isset($_POST['municipio'])){
  require './services/functions/conex.php';
  $build = 'edificio';
  $log = 'auditlog';

  if (isset($_POST['modificar_edificio'])) {
    // Recopilación de datos
    $id_edit = $_POST['id_edit'];
    $empresa_id = $_POST['empresa_id'];
    $descripcion = $_POST['descripcion'];
    $calle = $_POST['calle'];
    $numero_exterior = $_POST['numero_exterior'];
    $numero_interior = $_POST['numero_interior'];
    $colonia = $_POST['colonia'];
    $municipio = $_POST['municipio'];
    $entidad_federativa = $_POST['entidad_federativa'];
    $codigo_postal = $_POST['codigo_postal'];
    $pais = $_POST['pais'];
    $direccion_gps = $_POST['direccion_gps'];
    $contacto_nombre = $_POST['contacto_nombre'];
    $contacto_apellido = $_POST['contacto_apellido'];
    $contacto_puesto = $_POST['contacto_puesto'];
    $contacto_email = $_POST['contacto_email'];
    $contacto_telefono = $_POST['contacto_telefono'];
    $requisitos_acceso = $_POST['requisitos_acceso'];

    // Información para auditlog
    $tecnico = $_SESSION['nombre_completo'];
    require './services/assets/timezone.php';
    $fecha_hora_carga = date("d/m/Y H:i:s");

    // Carga de información en DDBB
    $update_build = $con->prepare("UPDATE $build
                                            SET descripcion = ?, calle = ?, numero_exterior = ?, numero_interior = ?, colonia = ?,
                                                municipio = ?, entidad_federativa = ?, codigo_postal = ?, pais = ?, direccion_gps = ?,
                                                contacto_nombre = ?, contacto_apellido = ?, contacto_puesto = ?, contacto_email = ?, contacto_telefono = ?,
                                                requisitos_acceso = ?
                                          WHERE id_edificio = ?");

    $val_update_build = $update_build -> execute([$descripcion, $calle, $numero_exterior, $numero_interior, $colonia,
                                                  $municipio, $entidad_federativa, $codigo_postal, $pais, $direccion_gps,
                                                  $contacto_nombre, $contacto_apellido, $contacto_puesto, $contacto_email, $contacto_telefono,
                                                  $requisitos_acceso, $id_edit]);

    if ($val_update_build) {
      require './services/functions/drop_con.php';

      // Registro en auditlog empresa
      require './services/functions/conex_serv.php';
      $movimiento = utf8_decode('El usuario '.$tecnico.' modifica el edificio '.$descripcion.' el '.$fecha_hora_carga.'');
      $url = $_SERVER['PHP_SELF'];
      $database = 'veco_sims_devecchi';
      $save_move = $con->prepare("INSERT INTO $log (movimiento, link, ddbb, usuario_movimiento, fecha_hora)
                        VALUES (?, ?, ?, ?, ?)");
      $val_save_move = $save_move->execute([$movimiento, $url, $database, $tecnico, $fecha_hora_carga]);

      if ($val_save_move) {
        require './services/functions/drop_con.php';
        mensaje_ayuda();
      } else {
        require './services/functions/drop_con.php';
        mensaje_error();
      }

    } else {
      mensaje_error();
    }

  } else {
    mensaje_error();
  }
}
$id = MysqlQuery::RequestGet('id');
	$sql = Mysql::consulta("SELECT * FROM edificio WHERE id_edificio= '$id'");
	$reg=mysqli_fetch_array($sql, MYSQLI_ASSOC);

?>						
	  <fieldset>

                                <input class="form-control" type="hidden" readonly="" name="id_edit" readonly="" value="<?php echo $reg['id_edificio']?>">
						
                        <div class="form-group">
                            <label class="col-sm-222 control-label"><i class="fa fa-building-o" aria-hidden="true"></i> Empresa</label>
                             <div class="col-sm-110">
                              <div class='input-group'>
                                <?php
                                $sql2 = Mysql::consulta("SELECT * FROM edificio inner join empresas on edificio.empresa_id=empresas.id WHERE id_edificio= '$id'");
                                $registro=mysqli_fetch_array($sql2, MYSQLI_ASSOC);
                                ?>
                                <input type="text" class="form-control" readonly="" name="empresa_id" value="<?php echo utf8_decode($registro['razon_social'])?>">
                                <span class="input-group-addon"><i class="fa fa-eye"></i></span>
                              </div>
                          </div>
                        </div>
						
						<div class="form-group">
                            <label class="col-sm-222 control-label"><i class="fa fa-building" aria-hidden="true"></i> Descripción</label>
                             <div class="col-sm-110">
                              <div class='input-group'>
                                <input type="text" class="form-control" required="" name="descripcion" maxlength="30" value="<?php echo utf8_decode($reg['descripcion'])?>" placeholder="Por ejemplo: Planta Morelos">
                                <span class="input-group-addon"><i class="fa fa-eye"></i></span>
                              </div>
                          </div>
                        </div>
						
						<div class="form-group">
                            <label class="col-sm-222 control-label"><i class="fa fa-road" aria-hidden="true"></i> Calle</label>
                            <div class='col-sm-110'>
                                <div class="input-group">
                                   <input class="form-control" type="text" name="calle" value="<?php echo utf8_decode($reg['calle'])?>" placeholder="Por ejemplo: 13 Este">
								   <span class="input-group-addon"><i class="fa fa-pencil-square-o"></i></span>
                                </div>
                            </div>
                        </div>

						<div class="form-group">
                          <label  class="col-sm-222 control-label"><i class="fa fa-location-arrow" aria-hidden="true"></i> Número Exterior</label>
                          <div class="col-sm-110">
                              <div class='input-group'>
                                  <input type="text" class="form-control"  name="numero_exterior" value="<?php echo utf8_decode($reg['numero_exterior'])?>" placeholder="Por ejemplo: 116">
								  <span class="input-group-addon"><i class="fa fa-pencil-square-o"></i></span>
                              </div>
                          </div>
                        </div>
						
                        <div class="form-group">
                          <label  class="col-sm-222 control-label"><i class="fa fa-location-arrow" aria-hidden="true"></i> Número Interior <u>Opcional</u>*</label>
                          <div class="col-sm-110">
                              <div class='input-group'>
                                  <input type="text" class="form-control"  name="numero_interior" value="<?php echo utf8_decode($reg['numero_interior'])?>">
								  <span class="input-group-addon"><i class="fa fa-pencil-square-o"></i></span>
                              </div>
                          </div>
                        </div>
						
						<div class="form-group">
                            <label class="col-sm-222 control-label"><i class="fa fa-map" aria-hidden="true"></i> Colonia</label>
                             <div class="col-sm-110">
                              <div class='input-group'>
                                <input type="text" class="form-control" name="colonia" value="<?php echo utf8_decode($reg['colonia'])?>" placeholder="Por ejemplo: CIVAC">
                                <span class="input-group-addon"><i class="fa fa-pencil-square-o"></i></span>
                              </div>
                          </div>
                        </div>
						
						<div class="form-group">
                            <label class="col-sm-222 control-label"><i class="fa fa-map-o" aria-hidden="true"></i> Municipio</label>
                             <div class="col-sm-110">
                              <div class='input-group'>
                                <input type="text" class="form-control" name="municipio" value="<?php echo utf8_decode($reg['municipio'])?>" placeholder="Por ejemplo: Jiutepec">
                                <span class="input-group-addon"><i class="fa fa-pencil-square-o"></i></span>
                              </div>
                          </div>
                        </div>

                        <div class="form-group">
                          <label class="col-sm-222 control-label"><i class="fa fa-street-view" aria-hidden="true"></i> Entidad Federativa</label>
                          <div class="col-sm-110">
                              <div class='input-group'>
                                  <input class="form-control" name="entidad_federativa" value="<?php echo utf8_decode($reg['entidad_federativa'])?>" placeholder="Por ejemplo: Morelos">
								  <span class="input-group-addon"><i class="fa fa-pencil-square-o"></i></span>
                              </div> 
                          </div>
                        </div>

                        <div class="form-group">
                          <label  class="col-sm-222 control-label"><i class="fa fa-map-signs" aria-hidden="true"></i> Código Postal</label>
                          <div class="col-sm-110">
                              <div class='input-group'>
                                  <input type="text" class="form-control"  name="codigo_postal" value="<?php echo utf8_decode($reg['codigo_postal'])?>" placeholder="Por ejemplo: 62578">
								  <span class="input-group-addon"><i class="fa fa-pencil-square-o"></i></span>
                              </div> 
                          </div>
                        </div>
						
						<div class="form-group">
                          <label  class="col-sm-222 control-label"><i class="fa fa-globe" aria-hidden="true"></i> País</label>
                          <div class="col-sm-110">
                              <div class='input-group'>
                                  <input type="text" class="form-control" name="pais" value="<?php echo utf8_decode($reg['pais'])?>" placeholder="Por ejemplo: Mexico">
								  <span class="input-group-addon"><i class="fa fa-pencil-square-o"></i></span>
                              </div> 
                          </div>
                        </div>

                        <div class="form-group">
                          <label  class="col-sm-222 control-label"><i class="fa fa-map-marker" aria-hidden="true"></i> Dirección GPS</label>
                          <div class="col-sm-110">
                              <div class='input-group'>
                                  <input type="text" class="form-control" name="direccion_gps" value="<?php echo utf8_decode($reg['direccion_gps'])?>" placeholder="Por ejemplo: https://maps.app.goo.gl/qfgrHj5aq96Su9Ba9">
								  <span class="input-group-addon"><i class="fa fa-pencil-square-o"></i></span>
                              </div> 
                          </div>
                        </div>
						
						<div class="form-group">
                            <label class="col-sm-222 control-label"><i class="fa fa-user" aria-hidden="true"></i> Nombre del Contacto</label>
                             <div class="col-sm-110">
                              <div class='input-group'>
                                <input type="text" class="form-control" name="contacto_nombre" maxlength="30" value="<?php echo utf8_decode($reg['contacto_nombre'])?>" placeholder="Por ejemplo: Angel Francisco">
                                <span class="input-group-addon"><i class="fa fa-pencil-square-o"></i></span>
                              </div>
                          </div>
                        </div>
						
						<div class="form-group">
                            <label class="col-sm-222 control-label"><i class="fa fa-user-o" aria-hidden="true"></i> Apellido del Contacto</label>
                             <div class="col-sm-110">
                              <div class='input-group'>
                                <input type="text" class="form-control" name="contacto_apellido" maxlength="30" value="<?php echo utf8_decode( $reg['contacto_apellido'])?>" placeholder="Por ejemplo: De Vecchi Flores">
                                <span class="input-group-addon"><i class="fa fa-pencil-square-o"></i></span>
                              </div>
                          </div>
                        </div>
						
						<div class="form-group">
                            <label class="col-sm-222 control-label"><i class="fa fa-sitemap" aria-hidden="true"></i> Puesto del Contacto</label>
                             <div class="col-sm-110">
                              <div class='input-group'>
                                <input type="text" class="form-control" name="contacto_puesto" maxlength="30" value="<?php echo utf8_decode($reg['contacto_puesto'])?>" placeholder="Por ejemplo: Direccion General">
                                <span class="input-group-addon"><i class="fa fa-pencil-square-o"></i></span>
                              </div>
                          </div>
                        </div>
						
						<div class="form-group">
                            <label class="col-sm-222 control-label"><i class="fa fa-envelope" aria-hidden="true"></i> Email del Contacto</label>
                             <div class="col-sm-110">
                              <div class='input-group'>
                                <input type="email" class="form-control" name="contacto_email" value="<?php echo utf8_decode($reg['contacto_email'])?>" placeholder="Por ejemplo: email@email.com">
                                <span class="input-group-addon"><i class="fa fa-pencil-square-o"></i></span>
                              </div>
                          </div>
                        </div>
						
						<div class="form-group">
                            <label class="col-sm-222 control-label"><i class="fa fa-phone-square" aria-hidden="true"></i> Teléfono del Contacto</label>
                             <div class="col-sm-110">
                              <div class='input-group'>
                                <input type="text" class="form-control" name="contacto_telefono" maxlength="50" value="<?php echo utf8_decode($reg['contacto_telefono'])?>" placeholder="Por ejemplo: 01-800-333-11-11">
                                <span class="input-group-addon"><i class="fa fa-pencil-square-o"></i></span>
                              </div>
                          </div>
                        </div>
						
						<div class="form-group">
                          <label  class="col-sm-222 control-label"><i class="fa fa-shield" aria-hidden="true"></i> Requisitos de acceso</label>
                          <div class="col-sm-110">
                              <div class="col-sm-10" style="padding-left: 1px;">
                            <textarea class="form-control" rows="2" name="requisitos_acceso" value="<?php echo utf8_decode($reg['requisitos_acceso'])?>" style="width: 717px;" placeholder="Por ejemplo: Calzado de seguridad, cofia, cubrebocas y tapones auditivos"></textarea>
                          </div>
                          </div>
                        </div>
			</fieldset>			
                        <div class="form-group">
                          <div class="col-sm-offset-2 col-sm-10 text-center"><br>
                              <button type="submit" name="modificar_edificio" class="btn btn-danger">Modificar</button>
                          </div>
                        </div>
                    <br>
            </form>
            </div><!--col-md-12-->
          </div><!--container-->
		</div>
	  </div>
      </div>
    </div>
	  <?php
}else{
	header('Location: index.php');
}
?>
</body>
</html>