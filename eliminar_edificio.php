<?php
session_start();

if( $_SESSION['nombre']!="" && $_SESSION['clave']!="" && $_SESSION['tipo']=="admin"){
  include './services/assets/admin/navbar.php';
  include './services/assets/admin/links.php';

  function mensaje_error() {
    echo'
                <div class="alert alert-danger alert-dismissible fade in col-sm-3 animated bounceInDown" role="alert" style="position:fixed; top:70px; right:10px; z-index:10;"> 
                    <a href="empresa.php"><button type="button" class="close"  data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                    <h4 class="text-center">OCURRIÓ UN ERROR</h4>
                    <p class="text-center">
                        No fue posible eliminar la empresa, revisa si no hay edificios relacionados con ella o contacta al Soporte Técnico.
                    </p>
                </div>
            '; 
  }

  function mensaje_ayuda(){
        echo '
            <div class="alert alert-success alert-dismissible fade in col-sm-3 animated bounceInDown" role="alert" style="position:fixed; top:70px; right:10px; z-index:10;"> 
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
            <h4 class="text-center"><strong>BORRADO EXITOSO</strong></h4>
            <p class="text-center">
            Se eliminó correctamente la empresa y su usuario de acceso.
            </p>
            </div>
            ';
    }

	function redirect_failed() {
		echo '
			<div class="container" style="margin-left: 40%">
				<img src="./services/assets/img/loading_dvi.gif" height="100%" weight="100%">
				<br>
				<a href="edificio.php" class="btn btn-sm btn-danger" style="margin-left: 15%">Regresar</a>
			</div>';
	}

	function redirect_success() {
		echo '
			<div class="container" style="margin-left: 40%">
				<img src="./services/assets/img/loading_dvi.gif" height="100%" weight="100%">
				<br>
				<a href="edificio.php" class="btn btn-sm btn-success" style="margin-left: 15%">Continuar</a>
			</div>';
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
                <h1 class="animated lightSpeedIn">Borrar Edificio</h1>
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
      <div class="panel panel-danger">
        <div class="panel-heading text-center"><strong>Verifique información del resgistro a borrar</strong></div>
        <div class="panel-body">
            <form role="form" action="" method="POST">
		
    <?php
	include './lib/class_mysql.php';
include './lib/config.php';
include 'conexi.php';

// Información para auditlog
$tecnico = $_SESSION['nombre_completo'];
require './services/assets/timezone.php';
$fecha_hora_carga = date("d/m/Y H:i:s");

if(isset($_POST['descripcion']) && isset($_POST['calle'])){
		$id_edit=MysqlQuery::RequestPost('id_edit');
		$descripcion=  MysqlQuery::RequestPost('descripcion');
		$calle=  MysqlQuery::RequestPost('calle');

	if(isset($_POST['calle']) && isset($_POST['descripcion'])){
          $empresa_delete=MysqlQuery::RequestPost('calle');
          $corto_delete=MysqlQuery::RequestPost('descripcion');
         
          $sql=Mysql::consulta("SELECT * FROM edificio WHERE calle= '$empresa_delete' AND descripcion='$corto_delete'");

          if(mysqli_num_rows($sql)>=1){
             if (MysqlQuery::Eliminar("edificio", "calle='$empresa_delete' and descripcion='$corto_delete'")) {
              // Registro en auditlog
              $log = 'auditlog';
                $movimiento = utf8_decode('El usuario '.$tecnico.' elimina el edificio '.$_POST['descripcion'].' el '.$fecha_hora_carga.'');
                $url = $_SERVER['PHP_SELF'];
                $database = 'SIS';
                require './services/functions/conex_serv.php';
                $save_move = $con->prepare("INSERT INTO $log (movimiento, link, ddbb, usuario_movimiento, fecha_hora)
                                    VALUES (?, ?, ?, ?, ?)");
                $val_save_move = $save_move->execute([$movimiento, $url, $database, $tecnico, $fecha_hora_carga]);

                if ($val_save_move) {
                  mensaje_ayuda();
                  redirect_success();
                  die();
                } else {
                    mensaje_error();
                    redirect_failed();
                    die();
                }
             } else {
              mensaje_error();
              redirect_failed();
              die();
             }
             mensaje_ayuda();
             redirect_success();
             die();
          }else{
            mensaje_error();
            redirect_failed();
            die();
          }
        }
}

$id = MysqlQuery::RequestGet('id');
	$sql = Mysql::consulta("SELECT * FROM edificio WHERE id_edificio= '$id'");
	$reg=mysqli_fetch_array($sql, MYSQLI_ASSOC);

?>
					<form action="" method="POST" style="display: inline-block;">
	  <fieldset>
                                <input type="hidden" class="form-control" name="id_edit" readonly="" value="<?php echo $reg['id_edificio']?>">
						
                        <div class="form-group">
                            <label class="col-sm-222 control-label"><i class="fa fa-building-o" aria-hidden="true"></i> Empresa</label>
                             <div class="col-sm-110">
                              <div class='input-group'>
							  <?php
							  $sql2 = Mysql::consulta("SELECT * FROM edificio inner join empresas on edificio.empresa_id=empresas.id WHERE id_edificio= '$id'");
								$registro=mysqli_fetch_array($sql2, MYSQLI_ASSOC);
								?>
                                <input type="text" class="form-control" readonly="" name="empresa_id" value="<?php echo utf8_decode($registro['razon_social'])?>">
                                <span class="input-group-addon"></span>
                              </div>
                          </div>
                        </div>
						
						<div class="form-group">
                            <label class="col-sm-222 control-label"><i class="fa fa-building" aria-hidden="true"></i> Descripción</label>
                             <div class="col-sm-110">
                              <div class='input-group'>
                                <input type="text" class="form-control" readonly="" name="descripcion" readonly="" value="<?php echo utf8_decode($reg['descripcion'])?>">
                                <span class="input-group-addon"></span>
                              </div>
                          </div>
                        </div>
                    
                        <div class="form-group">
                            <label class="col-sm-222 control-label"><i class="fa fa-road" aria-hidden="true"></i> Calle</label>
                            <div class='col-sm-110'>
                                <div class="input-group">
                                   <input class="form-control" readonly="" type="text" name="calle" readonly="" value="<?php echo utf8_decode($reg['calle'])?>">
								   <span class="input-group-addon"></span>
                                </div>
                            </div>
                        </div>

						<div class="form-group">
                          <label  class="col-sm-222 control-label"><i class="fa fa-location-arrow" aria-hidden="true"></i> Número Exterior</label>
                          <div class="col-sm-110">
                              <div class='input-group'>
                                  <input type="text" readonly="" class="form-control"  name="numero_exterior" readonly="" value="<?php echo utf8_decode($reg['numero_exterior'])?>">
								  <span class="input-group-addon"></span>
                              </div>
                          </div>
                        </div>
						
                        <div class="form-group">
                          <label  class="col-sm-222 control-label"><i class="fa fa-location-arrow" aria-hidden="true"></i> Número Interior</label>
                          <div class="col-sm-110">
                              <div class='input-group'>
                                  <input type="text" readonly="" class="form-control"  name="numero_interior" readonly="" value="<?php echo utf8_decode($reg['numero_interior'])?>">
								  <span class="input-group-addon"></span>
                              </div>
                          </div>
                        </div>
						
						<div class="form-group">
                            <label class="col-sm-222 control-label"><i class="fa fa-map" aria-hidden="true"></i> Colonia</label>
                             <div class="col-sm-110">
                              <div class='input-group'>
                                <input type="text" class="form-control" readonly="" name="colonia" readonly="" value="<?php echo utf8_decode($reg['colonia'])?>">
                                <span class="input-group-addon"></span>
                              </div>
                          </div>
                        </div>
						
						<div class="form-group">
                            <label class="col-sm-222 control-label"><i class="fa fa-map-o" aria-hidden="true"></i> Municipio</label>
                             <div class="col-sm-110">
                              <div class='input-group'>
                                <input type="text" class="form-control" readonly="" name="municipio" readonly="" value="<?php echo utf8_decode($reg['municipio'])?>">
                                <span class="input-group-addon"></span>
                              </div>
                          </div>
                        </div>

                        <div class="form-group">
                          <label class="col-sm-222 control-label"><i class="fa fa-street-view" aria-hidden="true"></i> Entidad Federativa</label>
                          <div class="col-sm-110">
                              <div class='input-group'>
                                  <input class="form-control" readonly=""  name="entidad_federativa" readonly="" value="<?php echo utf8_decode($reg['entidad_federativa'])?>">
								  <span class="input-group-addon"></span>
                              </div> 
                          </div>
                        </div>

                        <div class="form-group">
                          <label  class="col-sm-222 control-label"><i class="fa fa-map-signs" aria-hidden="true"></i> Código Postal</label>
                          <div class="col-sm-110">
                              <div class='input-group'>
                                  <input type="text" readonly="" class="form-control"  name="codigo_postal" readonly=""  value="<?php echo utf8_decode($reg['codigo_postal'])?>">
								  <span class="input-group-addon"></span>
                              </div> 
                          </div>
                        </div>
						
						<div class="form-group">
                          <label  class="col-sm-222 control-label"><i class="fa fa-globe" aria-hidden="true"></i> País</label>
                          <div class="col-sm-110">
                              <div class='input-group'>
                                  <input type="text" readonly="" class="form-control"  name="pais" readonly="" value="<?php echo utf8_decode($reg['pais'])?>">
								  <span class="input-group-addon"></span>
                              </div> 
                          </div>
                        </div>

                        <div class="form-group">
                          <label  class="col-sm-222 control-label"><i class="fa fa-map-marker" aria-hidden="true"></i> Dirección GPS</label>
                          <div class="col-sm-110">
                              <div class='input-group'>
                                  <input type="text" readonly="" class="form-control"  name="direccion_gps" readonly="" value="<?php echo utf8_decode($reg['direccion_gps'])?>">
								  <span class="input-group-addon"></span>
                              </div> 
                          </div>
                        </div>
						
						<div class="form-group">
                            <label class="col-sm-222 control-label"><i class="fa fa-user" aria-hidden="true"></i> Nombre del Contacto</label>
                             <div class="col-sm-110">
                              <div class='input-group'>
                                <input type="text" class="form-control" readonly="" name="contacto_nombre" readonly="" value="<?php echo utf8_decode($reg['contacto_nombre'])?>">
                                <span class="input-group-addon"></span>
                              </div>
                          </div>
                        </div>
						
						<div class="form-group">
                            <label class="col-sm-222 control-label"><i class="fa fa-user-o" aria-hidden="true"></i> Apellido del Contacto</label>
                             <div class="col-sm-110">
                              <div class='input-group'>
                                <input type="text" class="form-control" readonly="" name="contacto_apellido" readonly="" value="<?php echo utf8_decode($reg['contacto_apellido'])?>">
                                <span class="input-group-addon"></span>
                              </div>
                          </div>
                        </div>
						
						<div class="form-group">
                            <label class="col-sm-222 control-label"><i class="fa fa-sitemap" aria-hidden="true"></i> Puesto del Contacto</label>
                             <div class="col-sm-110">
                              <div class='input-group'>
                                <input type="text" class="form-control" readonly="" name="contacto_puesto" readonly="" value="<?php echo utf8_decode($reg['contacto_puesto'])?>">
                                <span class="input-group-addon"></span>
                              </div>
                          </div>
                        </div>
						
						<div class="form-group">
                            <label class="col-sm-222 control-label"><i class="fa fa-envelope" aria-hidden="true"></i> Email del Contacto</label>
                             <div class="col-sm-110">
                              <div class='input-group'>
                                <input type="text" class="form-control" readonly="" name="contacto_email" readonly="" value="<?php echo utf8_decode($reg['contacto_email'])?>">
                                <span class="input-group-addon"></span>
                              </div>
                          </div>
                        </div>
						
						<div class="form-group">
                            <label class="col-sm-222 control-label"><i class="fa fa-phone-square" aria-hidden="true"></i> Teléfono del Contacto</label>
                             <div class="col-sm-110">
                              <div class='input-group'>
                                <input type="text" class="form-control" readonly="" name="contacto_telefono" readonly="" value="<?php echo utf8_decode($reg['contacto_telefono'])?>">
                                <span class="input-group-addon"></span>
                              </div>
                          </div>
                        </div>
						
						<div class="form-group">
                          <label  class="col-sm-222 control-label"><i class="fa fa-shield" aria-hidden="true"></i> Requisitos de Acceso</label>
						  <div class="col-sm-110">
                              <div class='input-group'>
                          <div class="col-sm-10" style="padding-left: 1px;">
                            <textarea style="width: 717px;" readonly="" class="form-control" rows="1"  name="requisitos_acceso" required=""><?php echo utf8_decode($reg['requisitos_acceso'])?></textarea>
                           </div>
                          </div>
                        </div>
						</div>
			</fieldset>								
						
									
                        <div class="form-group">
                          <div class="col-sm-offset-2 col-sm-10 text-center">
						  <div class="form-group">
                          <label  class="col-sm-222 control-label text-center"> *Confirme borrado de registro* </label>
                        </div>   
                <input type="hidden" name="id_edit" value="<?php echo $row['id']; ?>">
                <button type="submit" class="btn btn-sm btn-danger"><i class="fa fa-trash-o" aria-hidden="true"> Borrar</i></button>
				 </div>
                        </div>
            </form>
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