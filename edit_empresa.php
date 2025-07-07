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
            Se modificó exitosamente la empresa.
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
       <a href="empresa.php" ><button  type="submit" value="Regresar" name="" class="btn btn-primary" style="text-align:center"><i class="fa fa-reply"></i>&nbsp;&nbsp;Regresar</button></a>
	   </tr>
	</td>
	   </table>
		<!--************************************ Page content******************************-->
		<div class="container">
          <div class="row">
            <div class="col-sm-12">
              <div class="page-header2">
                <h1 class="animated lightSpeedIn">Editar Empresa</h1>
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
        <div class="panel-heading text-center"><strong>Edite la información que se muestra a continuación</strong></div>
        <div class="panel-body">
            <form role="form" action="" method="POST">
		
    <?php
	include './lib/class_mysql.php';
include './lib/config.php';

include ("conexi.php");
 
 if(isset($_POST['rfc']) && isset($_POST['razon_social']) && isset($_POST['nombre_corto'])){
  require './services/functions/conex.php';
  $comapny = 'empresas';
	$log = 'auditlog';

  // Recepción de datos
  $id_edit = $_POST['id_edit'];
  $rfc = $_POST['rfc'];
  $nombre_corto = $_POST['nombre_corto'];
  $razon_social = $_POST['razon_social'];
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

  // Información para auditlog
  $tecnico = $_SESSION['nombre_completo'];
  require './services/assets/timezone.php';
  $fecha_hora_carga = date("d/m/Y H:i:s");

  $update_company = $con->prepare("UPDATE $comapny
                                          SET razon_social = ?, calle = ?, numero_exterior = ?, numero_interior = ?, colonia = ?,
                                              municipio = ?, entidad_federativa = ?, codigo_postal = ?, pais = ?, direccion_gps = ?,
                                              contacto_nombre = ?, contacto_apellido = ?, contacto_puesto = ?, contacto_email = ?, contacto_telefono = ?
                                          WHERE id = ?");

  $val_update_company = $update_company->execute([$razon_social, $calle, $numero_exterior, $numero_interior, $colonia,
                                                  $municipio, $entidad_federativa, $codigo_postal, $pais, $direccion_gps,
                                                  $contacto_nombre, $contacto_apellido, $contacto_puesto, $contacto_email, $contacto_telefono,
                                                  $id_edit]);

  if ($val_update_company) {
    require './services/functions/drop_con.php';

				// Registro en auditlog empresa
				require './services/functions/conex_serv.php';
				$movimiento = utf8_decode('El usuario '.$tecnico.' modificó la empresa '.$razon_social.' con RFC: '.$rfc.' el '.$fecha_hora_carga.'');
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

}
$id = MysqlQuery::RequestGet('id');
	$sql = Mysql::consulta("SELECT * FROM empresas WHERE id= '$id'");
	$reg=mysqli_fetch_array($sql, MYSQLI_ASSOC);

?>						
	  <fieldset>
      
    <input class="form-control" type="hidden" readonly name="id_edit" readonly="" value="<?php echo $reg['id']?>">
						
                        <div class="form-group">
                            <label class="col-sm-222 control-label"><i class="fa fa-building" aria-hidden="true"></i> RFC</label>
                             <div class="col-sm-110">
                              <div class='input-group'>
                                <input type="text" class="form-control" readonly="" name="rfc" maxlength="20" value="<?php echo utf8_decode($reg['rfc'])?>">
                                <span class="input-group-addon"><i class="fa fa-eye"></i></span>
                              </div>
                          </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-222 control-label"><i class="fa fa-user-circle-o" aria-hidden="true"></i> Nombre Usuario</label>
                             <div class="col-sm-110">
                              <div class='input-group'>
                                <input type="text" class="form-control" required="" name="nombre_corto" value="<?php echo utf8_decode($reg['nombre_corto'])?>" readonly>
                                <span class="input-group-addon"><i class="fa fa-eye"></i></span>
                              </div>
                          </div>
                        </div>
						
						<div class="form-group">
                            <label class="col-sm-222 control-label"><i class="fa fa-building-o" aria-hidden="true"></i> Razón Social</label>
                             <div class="col-sm-110">
                              <div class='input-group'>
                                <input type="text" class="form-control" required="" name="razon_social" value="<?php echo utf8_decode($reg['razon_social'])?>">
                                <span class="input-group-addon"><i class="fa fa-pencil-square-o"></i>
                          </div>
                        </div>
						
						<div class="form-group"><br>
                            <label class="col-sm-222 control-label"><i class="fa fa-road" aria-hidden="true"></i> Calle</label>
                            <div class='col-sm-110'>
                                <div class="input-group">
                                   <input class="form-control" type="text" required="" name="calle" value="<?php echo utf8_decode($reg['calle'])?>">
								   <span class="input-group-addon"><i class="fa fa-pencil-square-o"></i></span>
                                </div>
                            </div>
                        </div>

						<div class="form-group">
                          <label  class="col-sm-222 control-label"><i class="fa fa-location-arrow" aria-hidden="true"></i> Número Exterior</label>
                          <div class="col-sm-110">
                              <div class='input-group'>
                                  <input type="text" class="form-control" required="" name="numero_exterior" value="<?php echo utf8_decode($reg['numero_exterior'])?>">
								  <span class="input-group-addon"><i class="fa fa-pencil-square-o"></i></span>
                              </div>
                          </div>
                        </div>
						
                        <div class="form-group">
                          <label  class="col-sm-222 control-label"><i class="fa fa-location-arrow" aria-hidden="true"></i> Número Interior</label>
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
                                <input type="text" class="form-control" required="" name="colonia" value="<?php echo utf8_decode($reg['colonia'])?>">
                                <span class="input-group-addon"><i class="fa fa-pencil-square-o"></i></span>
                              </div>
                          </div>
                        </div>
						
						<div class="form-group">
                            <label class="col-sm-222 control-label"><i class="fa fa-map-o" aria-hidden="true"></i> Municipio</label>
                             <div class="col-sm-110">
                              <div class='input-group'>
                                <input type="text" class="form-control" required="" name="municipio" value="<?php echo utf8_decode($reg['municipio'])?>">
                                <span class="input-group-addon"><i class="fa fa-pencil-square-o"></i></span>
                              </div>
                          </div>
                        </div>

                        <div class="form-group">
                          <label class="col-sm-222 control-label"><i class="fa fa-street-view" aria-hidden="true"></i> Entidad Federativa</label>
                          <div class="col-sm-110">
                              <div class='input-group'>
                                  <input class="form-control" required="" name="entidad_federativa" value="<?php echo utf8_decode($reg['entidad_federativa'])?>">
								  <span class="input-group-addon"><i class="fa fa-pencil-square-o"></i></span>
                              </div> 
                          </div>
                        </div>

                        <div class="form-group">
                          <label  class="col-sm-222 control-label"><i class="fa fa-map-signs" aria-hidden="true"></i> Código Postal</label>
                          <div class="col-sm-110">
                              <div class='input-group'>
                                  <input type="text" class="form-control"  required="" name="codigo_postal" value="<?php echo utf8_decode($reg['codigo_postal'])?>">
								  <span class="input-group-addon"><i class="fa fa-pencil-square-o"></i></span>
                              </div> 
                          </div>
                        </div>
						
						<div class="form-group">
                          <label  class="col-sm-222 control-label"><i class="fa fa-globe" aria-hidden="true"></i> País</label>
                          <div class="col-sm-110">
                              <div class='input-group'>
                                  <input type="text" class="form-control" required="" name="pais" value="<?php echo utf8_decode($reg['pais'])?>">
								  <span class="input-group-addon"><i class="fa fa-pencil-square-o"></i></span>
                              </div> 
                          </div>
                        </div>

                        <div class="form-group">
                          <label  class="col-sm-222 control-label"><i class="fa fa-map-marker" aria-hidden="true"></i> Dirección GPS</label>
                          <div class="col-sm-110">
                              <div class='input-group'>
                                  <input type="text" class="form-control" name="direccion_gps" value="<?php echo utf8_decode($reg['direccion_gps'])?>">
								  <span class="input-group-addon"><i class="fa fa-pencil-square-o"></i></span>
                              </div> 
                          </div>
                        </div>
						
						<div class="form-group">
                            <label class="col-sm-222 control-label"><i class="fa fa-user" aria-hidden="true"></i> Nombre del Contacto</label>
                             <div class="col-sm-110">
                              <div class='input-group'>
                                <input type="text" class="form-control" required="" name="contacto_nombre" maxlength="30" value="<?php echo utf8_decode($reg['contacto_nombre'])?>">
                                <span class="input-group-addon"><i class="fa fa-pencil-square-o"></i></span>
                              </div>
                          </div>
                        </div>
						
						<div class="form-group">
                            <label class="col-sm-222 control-label"><i class="fa fa-user-o" aria-hidden="true"></i> Apellido del Contacto</label>
                             <div class="col-sm-110">
                              <div class='input-group'>
                                <input type="text" class="form-control" required="" name="contacto_apellido" maxlength="30" value="<?php echo utf8_decode( $reg['contacto_apellido'])?>">
                                <span class="input-group-addon"><i class="fa fa-pencil-square-o"></i></span>
                              </div>
                          </div>
                        </div>
						
						<div class="form-group">
                            <label class="col-sm-222 control-label"><i class="fa fa-sitemap" aria-hidden="true"></i> Puesto del Contacto</label>
                             <div class="col-sm-110">
                              <div class='input-group'>
                                <input type="text" class="form-control" required="" name="contacto_puesto" maxlength="30" value="<?php echo utf8_decode($reg['contacto_puesto'])?>">
                                <span class="input-group-addon"><i class="fa fa-pencil-square-o"></i></span>
                              </div>
                          </div>
                        </div>
						
						<div class="form-group">
                            <label class="col-sm-222 control-label"><i class="fa fa-envelope" aria-hidden="true"></i> Email del Contacto</label>
                             <div class="col-sm-110">
                              <div class='input-group'>
                                <input type="email" class="form-control" required="" name="contacto_email" value="<?php echo utf8_decode($reg['contacto_email'])?>">
                                <span class="input-group-addon"><i class="fa fa-pencil-square-o"></i></span>
                              </div>
                          </div>
                        </div>
						
						<div class="form-group">
                            <label class="col-sm-222 control-label"><i class="fa fa-phone-square" aria-hidden="true"></i> Telefono del Contacto</label>
                             <div class="col-sm-110">
                              <div class='input-group'>
                                <input type="text" class="form-control" required="" name="contacto_telefono" maxlength="50" value="<?php echo utf8_decode($reg['contacto_telefono'])?>">
                                <span class="input-group-addon"><i class="fa fa-pencil-square-o"></i></span>
                              </div>
                          </div>
                        </div>
			</fieldset>			
                        <div class="form-group">
                          <div class="col-sm-offset-2 col-sm-10 text-center"><br>
                              <button type="submit" class="btn btn-danger">Guardar</button>
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

<footer></footer>

  <script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>

    <script src="js/index.js"></script>
</body>
</html>