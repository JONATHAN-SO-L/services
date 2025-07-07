<?php
session_start();

if( $_SESSION['nombre']!="" && $_SESSION['clave']!="" && $_SESSION['tipo']=="admin"){
  include '../../assets/admin/navbar2.php';
  include '../../assets/admin/links2.php';
  
  function mensaje_error() {
    echo'
                <div class="alert alert-danger alert-dismissible fade in col-sm-3 animated bounceInDown" role="alert" style="position:fixed; top:70px; right:10px; z-index:10;"> 
                    <a href="empresa.php"><button type="button" class="close"  data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                    <h4 class="text-center">OCURRIÓ UN ERROR</h4>
                    <p class="text-center">
                        No fue posible agregar la información de la partícula, por favor inténtalo de nuevo o contacta al Soporte Técnico.
                    </p>
                </div>
            '; 
  }

  function mensaje_ayuda(){
        echo '
            <div class="alert alert-success alert-dismissible fade in col-sm-3 animated bounceInDown" role="alert" style="position:fixed; top:70px; right:10px; z-index:10;"> 
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
            <h4 class="text-center"><strong>REGISTRO EXITOSO</strong></h4>
            <p class="text-center">
            Se registró correctamente la infromación de la partícula.
            </p>
            </div>
            ';
    }

	function redirect_failed() {
		echo '
			<div class="container" style="margin-left: 40%">
				<img src="../../assets/img/loading_dvi.gif" height="40%" weight="40%">
				<br>
				<a href="index.php" class="btn btn-sm btn-danger" style="margin-left: 15%">Regresar</a>
			</div>';
	}

	function redirect_success() {
		echo '
			<div class="container" style="margin-left: 40%">
				<img src="../../assets/img/loading_dvi.gif" height="40%" weight="40%">
				<br>
				<a href="index.php" class="btn btn-sm btn-success" style="margin-left: 15%">Continuar</a>
			</div>';
	}
    
    if (isset($_POST['guardar_particula'])) {
        require '../../functions/conex_serv.php';

        // Recpción de datos
        $tamano_nominal = $_POST['tamano_nominal'];
        $tamano_real = $_POST['tamano_real'];
        $desviacion_tamano = $_POST['desviacion_tamano'];
        $no_lote = $_POST['no_lote'];

        $fecha_exp = $_POST['fecha_exp'];

        $estado = $_POST['estado'];

        // Información para auditlog
		$tecnico = $_SESSION['nombre_completo'];
		require '../../assets/timezone.php';
		$fecha_hora_carga = date("d/m/Y H:i:s");

        $particles = 'particulas';
        $log = 'auditlog';

        // Carga de información en DDBB
        $save_particle = $con->prepare("INSERT INTO $particles (tamano_nominal, tamano_real, desviacion_tamano, no_lote,
                                                                exp_fecha, estado, registra_data, fecha_hora_registro)
                                                        VALUES (?, ?, ?, ?,
                                                                ?, ?, ?, ?)");

        $val_save_particles = $save_particle->execute([$tamano_nominal, $tamano_real, $desviacion_tamano, $no_lote,
                                                       $fecha_exp, $estado, $tecnico, $fecha_hora_carga]);

        if ($val_save_particles) {
            // Registro en auditlog
            $movimiento = utf8_decode('El usuario '.$tecnico.' agregó una nueva partícula '.$tamano_nominal.' con número de lote '.$no_lote.', estado '.$estado.' y fecha de expiración '.$fecha_exp.' el '.$fecha_hora_carga.'');
            $url = $_SERVER['PHP_SELF'];
            $database = 'SIS';
            $save_move = $con->prepare("INSERT INTO $log (movimiento, link, ddbb, usuario_movimiento, fecha_hora)
                                VALUES (?, ?, ?, ?, ?)");
            $val_save_move = $save_move->execute([$movimiento, $url, $database, $tecnico, $fecha_hora_carga]);

            if ($val_save_move) {
                require '../../functions/drop_con.php';
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
    }
?>
<script src="../../assets/css/main.css"></script>

<section id="content">
  <header id="content-header">
  
  <table>  
    <td>
		<tr>
       <a href="index.php" ><button  type="submit" class="btn btn-primary" style="text-align:center"><i class="fa fa-reply"></i>&nbsp;&nbsp;Regresar</button></a>
	   </tr>
	</td>
	   </table>
		<!--************************************ Page content******************************-->
		<div class="container">
          <div class="row">
            <div class="col-sm-12">
              <div class="page-header2">
                <h1 class="animated lightSpeedIn">Nueva Partícula</h1>
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
      <div class="panel panel-success">
        <div class="panel-heading text-center"><strong>Para poder crear una nueva partícula es necesario llenar los siguientes campos.</strong></div>
        <div class="panel-body">
            <form role="form" action="" method="POST" enctype="multipart/form-data">
						
						<div class="form-group">
                            <label class="col-sm-222 control-label"><i class="fa fa-exchange" aria-hidden="true"></i> Tamaño Nominal</label>
                             <div class="col-sm-110">
                              <div class='input-group'>
							      <input type="text" class="form-control" name="tamano_nominal" maxlength="30" placeholder="Por ejemplo: 0.3" required>
                                <span class="input-group-addon"><i class="fa fa-pencil-square-o"></i></span>
                              </div>
                          </div>
                        </div>
                    
                        <div class="form-group">
                            <label class="col-sm-222 control-label"><i class="fa fa-exchange" aria-hidden="true"></i> Tamaño Real</label>
                            <div class='col-sm-110'>
                                <div class="input-group">
                                   <input class="form-control" type="text" name="tamano_real" placeholder="Por ejemplo: 0.003" required>
								   <span class="input-group-addon"><i class="fa fa-pencil-square-o"></i></span>
                                </div>
                            </div>
                        </div>
						<div class="form-group">
                          <label  class="col-sm-222 control-label"><i class="fa fa-sort-amount-asc" aria-hidden="true"></i> Desviación de Tamaño</label>
                          <div class="col-sm-110">
                              <div class='input-group'>
                                  <input type="text" class="form-control" name="desviacion_tamano" placeholder="Por ejemplo: 0.0066" required>
								  <span class="input-group-addon"><i class="fa fa-pencil-square-o"></i></span>
                              </div>
                          </div>
                        </div>
						
						<div class="form-group">
                            <label class="col-sm-222 control-label"><i class="fa fa-hashtag" aria-hidden="true"></i> Número de Lote</label>
                             <div class="col-sm-110">
                              <div class='input-group'>
                                <input type="text" class="form-control" name="no_lote" placeholder="Por ejemplo: 287456" required>
                                <span class="input-group-addon"><i class="fa fa-pencil-square-o"></i></span>
                              </div>
                          </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-222 control-label"><i class="fa fa-calendar-times-o" aria-hidden="true"></i> Fecha de Expiración</label>
                             <div class="col-sm-110">
                              <div class='input-group'>
                                <input type="text" class="form-control" name="fecha_exp" required>
                                <span class="input-group-addon"><i class="fa fa-pencil-square-o"></i></span>
                              </div>
                          </div>
                        </div>
						
						<div class="form-group">
                            <label class="col-sm-222 control-label"><i class="fa fa-star-half-o" aria-hidden="true"></i> Estado</label>
                             <div class="col-sm-110">
                              <div class='input-group'>
                                <select class="form-control" name="estado" required>
                                    <option value=""></option>
                                    <option value="Activo">Activo</option>
                                    <option value="Inactivo">Inactivo</option>
                                </select>
                                <span class="input-group-addon"><i class="fa fa-pencil-square-o"></i></span>
                              </div>
                          </div>
                        </div>

                        <div class="form-group">
                          <div class="col-sm-offset-2 col-sm-10 text-center"><br>
                              <button type="submit" name="guardar_particula" class="btn btn-success">Guardar</button>
                          </div>
                        </div>
                    <br>
            </form>
            </div>
          </div>
	  
	  
		</div>
	  </div>
      </div>
    </div>

<?php
}else{
	header('Location: ../../../index.php');
}
?>
</body>
</html>