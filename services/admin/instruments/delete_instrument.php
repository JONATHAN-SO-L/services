<?php
session_start();

if( $_SESSION['nombre']!="" && $_SESSION['clave']!="" && $_SESSION['tipo']=="admin"){
  include '../../assets/admin/navbar2.php';
  include '../../assets/admin/links2.php';
  require '../../functions/conex_serv.php';
  $instrument = $_SERVER['QUERY_STRING'];
  
  function mensaje_error() {
    echo'
                <div class="alert alert-danger alert-dismissible fade in col-sm-3 animated bounceInDown" role="alert" style="position:fixed; top:70px; right:10px; z-index:10;"> 
                    <a href="empresa.php"><button type="button" class="close"  data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                    <h4 class="text-center">OCURRIÓ UN ERROR</h4>
                    <p class="text-center">
                        No fue posible eliminar el instrumento, por favor inténtalo de nuevo o contacta al Soporte Técnico.
                    </p>
                </div>
            '; 
  }

  function mensaje_ayuda(){
        echo '
            <div class="alert alert-success alert-dismissible fade in col-sm-3 animated bounceInDown" role="alert" style="position:fixed; top:70px; right:10px; z-index:10;"> 
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
            <h4 class="text-center"><strong>ELIMINACIÓN EXITOSA</strong></h4>
            <p class="text-center">
            Se eliminó correctamente el instrumento.
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

    $instruments = 'instrumentos';
    $log = 'auditlog';

    // Recuperación de información del instrumento
    $s_instrument = $con->prepare("SELECT * FROM $instruments WHERE id_instrumento = :instrument");
    $s_instrument->bindValue(':instrument', $instrument);
    $s_instrument->setFetchMode(PDO::FETCH_OBJ);
    $s_instrument->execute();
    
    $f_instrument = $s_instrument->fetchAll();

    if ($s_instrument -> rowCount() > 0) {
       foreach ($f_instrument as $instrumento) {
        $id_instrumento = $instrumento -> id_instrumento;
        $activo = $instrumento -> activo;
        $modelo = $instrumento -> modelo;
        $numero_serie = $instrumento -> numero_serie;
        $numero_control = $instrumento -> numero_control;
        $rango = $instrumento -> rango;
        $frecuencia_calibracion = $instrumento -> frecuencia_calibracion;

        $fecha_calibracion = $instrumento -> fecha_calibracion;
        $fecha_calibracion = date("Y-d-m", strtotime($fecha_calibracion));

        $fecha_proxima_calibracion = $instrumento -> fecha_proxima_calibracion;
        $fecha_proxima_calibracion = date("Y-d-m", strtotime($fecha_proxima_calibracion));

        $estado = $instrumento -> estado;
        $area_asignada = $instrumento -> area_asignada;
        $tipo_instrumento = $instrumento -> tipo_instrumento;
        $comentarios = $instrumento -> comentarios;
        $certificado = $instrumento -> certificado;
       }
    } else {
        mensaje_error();
        redirect_failed();
        die();
    }
    
    if (isset($_POST['eliminar_instrumento'])) {
        // Recpción de datos
        $activo = $_POST['activo'];
        $serie = $_POST['serie'];
        $no_control = $_POST['no_control'];

        // Información para auditlog
		$tecnico = $_SESSION['nombre_completo'];
		require '../../assets/timezone.php';
		$fecha_hora_eliminacion = date("d/m/Y H:i:s");

        // Eliminación de información
        $delete_instrument = $con->prepare("DELETE FROM $instruments WHERE id_instrumento = ?");
        $val_delete_instrument = $delete_instrument->execute([$instrument]);

        if ($val_delete_instrument) {
            // Se elimina certificado
            unlink('../'.$certificado);

            // Registro en auditlog
            $movimiento = utf8_decode('El usuario '.$tecnico.' eliminó '.$activo.' con número de serie '.$serie.' y no. control '.$no_control.' el '.$fecha_hora_eliminacion.'');
            $url = $_SERVER['PHP_SELF'].'?'.$instrument;
            $database = 'SIS';
            $save_move = $con->prepare("INSERT INTO $log (movimiento, link, ddbb, usuario_movimiento, fecha_hora)
                                VALUES (?, ?, ?, ?, ?)");
            $val_save_move = $save_move->execute([$movimiento, $url, $database, $tecnico, $fecha_hora_eliminacion]);

            if ($val_save_move) {
                require '../../functions/drop_con.php';
                mensaje_ayuda();
                redirect_success();
                die();
            } else {
                require '../../functions/drop_con.php';
                mensaje_error();
                redirect_failed();
                die();
            }
        } else {
            mensaje_error();
            redirect_failed();
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
                <h1 class="animated lightSpeedIn">Eliminar Instrumento: <strong><?php echo $instrument; ?></strong></h1>
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
        <div class="panel-heading text-center"><strong>Por favor valida que toda la información a continuación será eliminada permanentemente.</strong></div>
        <div class="panel-body">
            <form role="form" action="" method="POST" enctype="multipart/form-data">
						
						<div class="form-group">
                            <label class="col-sm-222 control-label"><i class="fa fa-id-badge" aria-hidden="true"></i> Activo <i>(Identificación VECO)</i></label>
                             <div class="col-sm-110">
                              <div class='input-group'>
							      <input type="text" class="form-control" name="activo" maxlength="30" value="<?php echo $activo; ?>" readonly>
                                <span class="input-group-addon"><i class="fa fa-pencil-square-o"></i></span>
                              </div>
                          </div>
                        </div>
                    
                        <div class="form-group">
                            <label class="col-sm-222 control-label"><i class="fa fa-info-circle" aria-hidden="true"></i> Modelo</label>
                            <div class='col-sm-110'>
                                <div class="input-group">
                                   <input class="form-control" type="text" name="modelo" placeholder="Por ejemplo: ADM-880C" value="<?php echo $modelo; ?>" readonly>
								   <span class="input-group-addon"><i class="fa fa-pencil-square-o"></i></span>
                                </div>
                            </div>
                        </div>
						<div class="form-group">
                          <label  class="col-sm-222 control-label"><i class="fa fa-barcode" aria-hidden="true"></i> Número de Serie</label>
                          <div class="col-sm-110">
                              <div class='input-group'>
                                  <input type="text" class="form-control" name="serie" placeholder="Por ejemplo: 11371" value="<?php echo $numero_serie; ?>" readonly>
								  <span class="input-group-addon"><i class="fa fa-pencil-square-o"></i></span>
                              </div>
                          </div>
                        </div>
						
						<div class="form-group">
                            <label class="col-sm-222 control-label"><i class="fa fa-address-card" aria-hidden="true"></i> Número de Control</label>
                             <div class="col-sm-110">
                              <div class='input-group'>
                                <input type="text" class="form-control" name="no_control" placeholder="Por ejemplo: FVF-DVI-1428" value="<?php echo $numero_control; ?>" readonly>
                                <span class="input-group-addon"><i class="fa fa-pencil-square-o"></i></span>
                              </div>
                          </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-222 control-label"><i class="fa fa-arrows-h" aria-hidden="true"></i> Rango</label>
                             <div class="col-sm-110">
                              <div class='input-group'>
                                <input type="text" class="form-control" name="rango" placeholder="Por ejemplo: 100 a 3000 m3/h" value="<?php echo $rango; ?>" readonly>
                                <span class="input-group-addon"><i class="fa fa-pencil-square-o"></i></span>
                              </div>
                          </div>
                        </div>

                        <div class="form-group">
                          <label  class="col-sm-222 control-label"><i class="fa fa-sliders" aria-hidden="true"></i> Frecuencia de Calibración</label>
                          <div class="col-sm-110">
                              <div class='input-group'>
                                  <input type="text" class="form-control" name="frecuencia_cal" placeholder="Por ejemplo: 12 Meses" value="<?php echo $frecuencia_calibracion; ?>" readonly>
								  <span class="input-group-addon"><i class="fa fa-pencil-square-o"></i></span>
                              </div> 
                          </div>
                        </div>
						
						<div class="form-group">
                          <label  class="col-sm-222 control-label"><i class="fa fa-calendar" aria-hidden="true"></i> Fecha de Calibración</label>
                          <div class="col-sm-110">
                              <div class='input-group'>
                                  <input type="date" class="form-control" name="fecha_calibracion" value="<?php echo $fecha_calibracion; ?>" readonly>
								  <span class="input-group-addon"><i class="fa fa-pencil-square-o"></i></span>
                              </div> 
                          </div>
                        </div>
						
						<div class="form-group">
                            <label class="col-sm-222 control-label"><i class="fa fa-calendar" aria-hidden="true"></i> Fecha de Próxima Calibración</label>
                             <div class="col-sm-110">
                              <div class='input-group'>
                                <input type="date" class="form-control" name="fecha_proxima_calibracion" value="<?php echo $fecha_proxima_calibracion; ?>" readonly>
                                <span class="input-group-addon"><i class="fa fa-pencil-square-o"></i></span>
                              </div>
                          </div>
                        </div>
						
						<div class="form-group">
                            <label class="col-sm-222 control-label"><i class="fa fa-star-half-o" aria-hidden="true"></i> Estado</label>
                             <div class="col-sm-110">
                              <div class='input-group'>
                                <input type="text" class="form-control" name="estado" value="<?php echo $estado; ?>" readonly>
                                <span class="input-group-addon"><i class="fa fa-pencil-square-o"></i></span>
                              </div>
                          </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-222 control-label"><i class="fa fa-users" aria-hidden="true"></i> Área Asignada</label>
                             <div class="col-sm-110">
                              <div class='input-group'>
                                <input type="text" class="form-control" name="area_asignada" value="<?php echo $area_asignada; ?>" readonly>
                                <span class="input-group-addon"><i class="fa fa-pencil-square-o"></i></span>
                              </div>
                          </div>
                        </div>
						
						<div class="form-group">
                            <label class="col-sm-222 control-label"><i class="fa fa-wrench" aria-hidden="true"></i> Tipo de Instrumento</label>
                             <div class="col-sm-110">
                              <div class='input-group'>
                                <input type="text" class="form-control" name="tipo_instrumento" value="<?php echo $tipo_instrumento; ?>" readonly>
                                <span class="input-group-addon"><i class="fa fa-pencil-square-o"></i></span>
                              </div>
                          </div>
                        </div>
						
						<div class="form-group">
                            <label class="col-sm-222 control-label"><i class="fa fa-comments" aria-hidden="true"></i> Comentarios <u>(Opcional)</u>*</label>
                             <div class="col-sm-110">
                              <div class='input-group'>
                                <input type="text" name="comentarios" class="form-control" maxlength="255" placeholder="Anota aquí las observaciones" value="<?php echo $comentarios; ?>" readonly>
                                <span class="input-group-addon"><i class="fa fa-pencil-square-o"></i></span>
                              </div>
                          </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-222 control-label"><i class="fa fa-certificate" aria-hidden="true"></i> Certificado</label><br>
                            <a href="../<?php echo $certificado; ?>" class="btn btn-sm btn-primary" target="_blank">Ver Certificado Activo</a><br>
                        </div>

                        <div class="form-group">
                          <div class="col-sm-offset-2 col-sm-10 text-center"><br>
                              <button type="submit" name="eliminar_instrumento" class="btn btn-danger">Eliminar</button>
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