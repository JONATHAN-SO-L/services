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
				<a href="empresa.php" class="btn btn-sm btn-danger" style="margin-left: 15%">Regresar</a>
			</div>';
	}

	function redirect_success() {
		echo '
			<div class="container" style="margin-left: 40%">
				<img src="./services/assets/img/loading_dvi.gif" height="100%" weight="100%">
				<br>
				<a href="empresa.php" class="btn btn-sm btn-success" style="margin-left: 15%">Continuar</a>
			</div>';
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
                <h1 class="animated lightSpeedIn">Borrar Empresa</h1>
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
        <div class="panel-heading text-center"><strong>Verifique la información del resgistro a borrar</strong></div>
        <div class="panel-body">		
    <?php
	include './lib/class_mysql.php';
include './lib/config.php';
if(isset($_POST['rfc']) && isset($_POST['razon_social']) && isset($_POST['nombre_corto'])){
		$id_edit=MysqlQuery::RequestPost('id_edit');
		$rfc=  MysqlQuery::RequestPost('rfc');
		$razon=  MysqlQuery::RequestPost('razon_social');
		$nombre_corto=  MysqlQuery::RequestPost('nombre_corto');

	if(isset($_POST['rfc']) && isset($_POST['razon_social'])){
          $empresa_delete=MysqlQuery::RequestPost('rfc');
          $corto_delete=MysqlQuery::RequestPost('razon_social');
         
          $sql=Mysql::consulta("SELECT * FROM empresas WHERE rfc= '$empresa_delete' AND razon_social='$corto_delete'");

          if(mysqli_num_rows($sql)>=1){
             if (MysqlQuery::Eliminar("empresas", "rfc='$empresa_delete' and razon_social='$corto_delete'")) {
              mensaje_ayuda();
              redirect_success();
              die();
             } else {
              mensaje_error();
              redirect_failed();
              die();
             }
             // Información para auditlog
             $tecnico = $_SESSION['nombre_completo'];
             require './services/assets/timezone.php';
             $fecha_hora_carga = date("d/m/Y H:i:s");

             // Eliminación del usuario
              require './services/functions/conex.php';
                $user_s = 'usuario_sis';
                $drop_user = $con->prepare("DELETE FROM $user_s
                                            WHERE usuario = ?");
                $val_drop_user = $drop_user->execute([$nombre_corto]);

             // Registro borrado usuario
             require './services/functions/conex_serv.php';
                $log = 'auditlog';
                $url = $_SERVER['PHP_SELF'];
                $database = 'veco_sims_devecchi';

              $movimiento2 = utf8_decode('El usuario '.$tecnico.' eliminó el usuario '.$nombre_corto.' el '.$fecha_hora_carga.'');
             $save_move2 = $con->prepare("INSERT INTO $log (movimiento, link, ddbb, usuario_movimiento, fecha_hora)
                              VALUES (?, ?, ?, ?, ?)");
              $val_save_move2 = $save_move2->execute([$movimiento2, $url, $database, $tecnico, $fecha_hora_carga]);
                
              if ($val_save_move2 && $val_drop_user) {                
                // Registro borrado empresa
                $movimiento = utf8_decode('El usuario '.$tecnico.' eliminó la empresa '.$razon.' con RFC: '.$rfc.' el '.$fecha_hora_carga.'');
                $save_move = $con->prepare("INSERT INTO $log (movimiento, link, ddbb, usuario_movimiento, fecha_hora)
                                  VALUES (?, ?, ?, ?, ?)");
                  $val_save_move = $save_move->execute([$movimiento, $url, $database, $tecnico, $fecha_hora_carga]);

                  if ($val_save_move) {
                    require './services/functions/drop_con.php';
                    mensaje_ayuda();
                    redirect_success();
                    die();
                  } else {
                    require './services/functions/drop_con.php';
                    mensaje_error();
                    redirect_failed();
                    die();
                  }
              } else {
                require './services/functions/drop_con.php';
                mensaje_error();
                redirect_failed();
                die();
              }
          }else{
            mensaje_error();
            redirect_failed();
            die();
          }
        }
}
$id = MysqlQuery::RequestGet('id');
	$sql = Mysql::consulta("SELECT * FROM empresas WHERE id= '$id'");
	$reg=mysqli_fetch_array($sql, MYSQLI_ASSOC);

?>
					<form action="" method="POST" style="display: inline-block;">
	  <fieldset>
                		<input type="hidden" name="id_edit" value="<?php echo $reg['id']?>">
						
                        <div class="form-group">
                            <label class="col-sm-222 control-label">RFC</label>
                             <div class="col-sm-110">
                              <div class='input-group'>
                                <input type="text" class="form-control" readonly="" name="rfc" readonly="" value="<?php echo utf8_decode($reg['rfc'])?>">
                                <span class="input-group-addon"></span>
                              </div>
                          </div>
                        </div>
						
						<div class="form-group">
                            <label class="col-sm-222 control-label">Razón Social</label>
                             <div class="col-sm-110">
                              <div class='input-group'>
                                <input type="text" class="form-control" readonly="" name="razon_social" readonly="" value="<?php echo utf8_decode($reg['razon_social'])?>">
                                <span class="input-group-addon"></span>
                              </div>
                          </div>
                        </div>
                    
                        <div class="form-group">
                            <label class="col-sm-222 control-label">Nombre Corto</label>
                            <div class='col-sm-110'>
                                <div class="input-group">
                                   <input class="form-control" readonly="" type="text" name="nombre_corto" readonly="" value="<?php echo utf8_decode($reg['nombre_corto'])?>">
								   <span class="input-group-addon"></span>
                                </div>
                            </div>
                        </div>
						
                        <div class="form-group">
                            <label class="col-sm-222 control-label">calle</label>
                            <div class='col-sm-110'>
                                <div class="input-group">
                                   <input class="form-control" readonly="" type="text" name="calle" readonly="" value="<?php echo utf8_decode($reg['calle'])?>">
								   <span class="input-group-addon"></span>
                                </div>
                            </div>
                        </div>

						<div class="form-group">
                          <label  class="col-sm-222 control-label">Núm Exterior</label>
                          <div class="col-sm-110">
                              <div class='input-group'>
                                  <input type="text" readonly="" class="form-control"  name="numero_exterior" readonly="" value="<?php echo utf8_decode($reg['numero_exterior'])?>">
								  <span class="input-group-addon"></span>
                              </div>
                          </div>
                        </div>
						
                        <div class="form-group">
                          <label  class="col-sm-222 control-label">Núm Interior</label>
                          <div class="col-sm-110">
                              <div class='input-group'>
                                  <input type="text" readonly="" class="form-control"  name="numero_interior" readonly="" value="<?php echo utf8_decode($reg['numero_interior'])?>">
								  <span class="input-group-addon"></span>
                              </div>
                          </div>
                        </div>
						
						<div class="form-group">
                            <label class="col-sm-222 control-label">Colonia</label>
                             <div class="col-sm-110">
                              <div class='input-group'>
                                <input type="text" class="form-control" readonly="" name="colonia" readonly="" value="<?php echo utf8_decode($reg['colonia'])?>">
                                <span class="input-group-addon"></span>
                              </div>
                          </div>
                        </div>
						
						<div class="form-group">
                            <label class="col-sm-222 control-label">Municipio</label>
                             <div class="col-sm-110">
                              <div class='input-group'>
                                <input type="text" class="form-control" readonly="" name="municipio" readonly="" value="<?php echo utf8_decode($reg['municipio'])?>">
                                <span class="input-group-addon"></span>
                              </div>
                          </div>
                        </div>

                        <div class="form-group">
                          <label class="col-sm-222 control-label">Entidad Federativa</label>
                          <div class="col-sm-110">
                              <div class='input-group'>
                                  <input class="form-control" readonly=""  name="entidad_federativa" readonly="" value="<?php echo utf8_decode($reg['entidad_federativa'])?>">
								  <span class="input-group-addon"></span>
                              </div> 
                          </div>
                        </div>

                        <div class="form-group">
                          <label  class="col-sm-222 control-label">Codigo Postal</label>
                          <div class="col-sm-110">
                              <div class='input-group'>
                                  <input type="text" readonly="" class="form-control"  name="codigo_postal" readonly=""  value="<?php echo utf8_decode($reg['codigo_postal'])?>">
								  <span class="input-group-addon"></span>
                              </div> 
                          </div>
                        </div>
						
						<div class="form-group">
                          <label  class="col-sm-222 control-label">País</label>
                          <div class="col-sm-110">
                              <div class='input-group'>
                                  <input type="text" readonly="" class="form-control"  name="pais" readonly="" value="<?php echo utf8_decode($reg['pais'])?>">
								  <span class="input-group-addon"></span>
                              </div> 
                          </div>
                        </div>

                        <div class="form-group">
                          <label  class="col-sm-222 control-label">Dirección GPS</label>
                          <div class="col-sm-110">
                              <div class='input-group'>
                                  <input type="text" readonly="" class="form-control"  name="direccion_gps" readonly="" value="<?php echo utf8_decode($reg['direccion_gps'])?>">
								  <span class="input-group-addon"></span>
                              </div> 
                          </div>
                        </div>
						
						<div class="form-group">
                            <label class="col-sm-222 control-label">Nombre del Contacto</label>
                             <div class="col-sm-110">
                              <div class='input-group'>
                                <input type="text" class="form-control" readonly="" name="contacto_nombre" readonly="" value="<?php echo utf8_decode($reg['contacto_nombre'])?>">
                                <span class="input-group-addon"></span>
                              </div>
                          </div>
                        </div>
						
						<div class="form-group">
                            <label class="col-sm-222 control-label">Apellido del Contacto</label>
                             <div class="col-sm-110">
                              <div class='input-group'>
                                <input type="text" class="form-control" readonly="" name="contacto_apellido" readonly="" value="<?php echo utf8_decode($reg['contacto_apellido'])?>">
                                <span class="input-group-addon"></span>
                              </div>
                          </div>
                        </div>
						
						<div class="form-group">
                            <label class="col-sm-222 control-label">Puesto del Contacto</label>
                             <div class="col-sm-110">
                              <div class='input-group'>
                                <input type="text" class="form-control" readonly="" name="contacto_puesto" readonly="" value="<?php echo utf8_decode($reg['contacto_puesto'])?>">
                                <span class="input-group-addon"></span>
                              </div>
                          </div>
                        </div>
						
						<div class="form-group">
                            <label class="col-sm-222 control-label">Email del Contacto</label>
                             <div class="col-sm-110">
                              <div class='input-group'>
                                <input type="email" class="form-control" readonly="" name="contacto_email" readonly="" value="<?php echo utf8_decode($reg['contacto_email'])?>">
                                <span class="input-group-addon"></span>
                              </div>
                          </div>
                        </div>
						
						<div class="form-group">
                            <label class="col-sm-222 control-label">Telefono del Contacto</label>
                             <div class="col-sm-110">
                              <div class='input-group'>
                                <input type="text" class="form-control" readonly="" name="contacto_telefono" readonly="" value="<?php echo utf8_decode($reg['contacto_telefono'])?>">
                                <span class="input-group-addon"></span>
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
                <button type="submit" class="btn btn-md btn-danger"><i class="fa fa-trash-o" aria-hidden="true"> Borrar</i></button>
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

<footer></footer>

  <script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>

    <script src="js/index.js"></script>
</body>
</html>