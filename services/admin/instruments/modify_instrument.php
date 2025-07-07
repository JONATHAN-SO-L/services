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
                        No fue posible modificar el instrumento, valida si el archivo o el formulario cumple los requerimientos, por favor inténtalo de nuevo o contacta al Soporte Técnico.
                    </p>
                </div>
            '; 
  }

  function mensaje_ayuda(){
        echo '
            <div class="alert alert-success alert-dismissible fade in col-sm-3 animated bounceInDown" role="alert" style="position:fixed; top:70px; right:10px; z-index:10;"> 
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
            <h4 class="text-center"><strong>MODIFICACIÓN EXITOSA</strong></h4>
            <p class="text-center">
            Se actualizó correctamente el instrumento.
            </p>
            </div>
            ';
    }

	function redirect_failed() {
		echo '
			<div class="container" style="margin-left: 40%">
				<img src="../../assets/img/loading_dvi.gif" height="40%" weight="40%">
				<br>
				<a href="modify_instrument.php" class="btn btn-sm btn-danger" style="margin-left: 15%">Regresar</a>
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
    
    if (isset($_POST['modificar_instrumento'])) {
        // Recpción de datos
        $activo = $_POST['activo'];
        $modelo = $_POST['modelo'];
        $serie = $_POST['serie'];
        $no_control = $_POST['no_control'];
        $rango = $_POST['rango'];
        $frecuencia_cal = $_POST['frecuencia_cal'];

        $fecha_calibracion = $_POST['fecha_calibracion'];
        $fecha_calibracion = date("d/m/Y", strtotime($fecha_calibracion));

        $fecha_proxima_calibracion = $_POST['fecha_proxima_calibracion'];
        $fecha_proxima_calibracion = date("d/m/Y", strtotime($fecha_proxima_calibracion));

        $estado = $_POST['estado'];
        $area_asignada = $_POST['area_asignada'];
        $tipo_instrumento = $_POST['tipo_instrumento'];
        $comentarios = $_POST['comentarios'];

        // Información para auditlog
		$tecnico = $_SESSION['nombre_completo'];
		require '../../assets/timezone.php';
		$fecha_hora_modificacion = date("d/m/Y H:i:s");

        //Obtención del archivo
        $size_max = 15728640; // Definición de tamaño máximo (15 MB)
        $carpeta_save = '../../certifies/instruments'; // Se define directorio donde se guarda el certificado del instrumento en el servidor
        $certificate_instrument = $_FILES['certificate_instrument']['name']; // Nombre del archivo a guardar
        $tipo_archivo = $_FILES['certificate_instrument']['type']; // Tipo de archivo
        $tamano_archivo = $_FILES['certificate_instrument']['size']; // Tamaño del archivo
        $temp_file = $_FILES['certificate_instrument']['tmp_name']; // Asignación de memoria para procesamiento
        $nombre_save = $carpeta_save.'/'.$certificate_instrument; // Nombre del archivo para guardar
        $almacenamiento_certificado = '../../services/certifies/instruments/'.$certificate_instrument;

        // Validar que se haya ingresado algún dato en la variable de certificado
        if ($certificate_instrument != NULL) {
            if ($tamano_archivo <= $size_max) { // Valida que el tamaño del archivo sea el permitido
                //Valida que el tipo de archivo sea el permitido
                if ($tipo_archivo == 'application/pdf') {
                    /************************************
                    ELIMINACIÓN DEL ARCHIVO ACTUAL EN USO
                    ************************************/
                    unlink('../'.$certificado);

                    /**********************
                    ALMACENAMIENTO DE DATOS
                    **********************/
                    move_uploaded_file($temp_file, $nombre_save);

                    $update_certified_instrument = $con->prepare("UPDATE $instruments SET activo = ?, modelo = ?, numero_serie = ?, numero_control = ?,
                                                                                          rango = ?, frecuencia_calibracion = ?, fecha_calibracion = ?, fecha_proxima_calibracion = ?,
                                                                                          estado = ?, area_asignada = ?, tipo_instrumento = ?, comentarios = ?,
                                                                                          certificado = ?, modifica_data = ?, fecha_hora_modificacion = ?
                                                                                    WHERE id_instrumento = ?");

                    $val_update_certified_instrument = $update_certified_instrument->execute([$activo, $modelo, $serie, $no_control,
                                                                                              $rango, $frecuencia_cal, $fecha_calibracion, $fecha_proxima_calibracion,
                                                                                              $estado, $area_asignada, $tipo_instrumento, $comentarios,
                                                                                              $almacenamiento_certificado, $tecnico, $fecha_hora_modificacion,
                                                                                              $instrument]);

                    if ($val_update_certified_instrument) {
                        // Registro en auditlog
                        $movimiento = utf8_decode('El usuario '.$tecnico.' modificó el instrumento '.$activo.' con número de serie '.$serie.' y no. control '.$no_control.' el '.$fecha_hora_modificacion.'');
                        $url = $_SERVER['PHP_SELF'].'?'.$instrument;
                        $database = 'SIS';
                        $save_move = $con->prepare("INSERT INTO $log (movimiento, link, ddbb, usuario_movimiento, fecha_hora)
                                            VALUES (?, ?, ?, ?, ?)");
                        $val_save_move = $save_move->execute([$movimiento, $url, $database, $tecnico, $fecha_hora_modificacion]);

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
                        die();
                    }
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
        } else {
            /********************************************************
            ALMACENAMIENTO DE DATOS SIN ALTERAR EL CERTIFICADO ACTUAL
            ********************************************************/
            $update_certified_instrument = $con->prepare("UPDATE $instruments SET activo = ?, modelo = ?, numero_serie = ?, numero_control = ?,
                                                                                  rango = ?, frecuencia_calibracion = ?, fecha_calibracion = ?, fecha_proxima_calibracion = ?,
                                                                                  estado = ?, area_asignada = ?, tipo_instrumento = ?, comentarios = ?,
                                                                                  certificado = ?, modifica_data = ?, fecha_hora_modificacion = ?
                                                                            WHERE id_instrumento = ?");

            $val_update_certified_instrument = $update_certified_instrument->execute([$activo, $modelo, $serie, $no_control,
                                                                                      $rango, $frecuencia_cal, $fecha_calibracion, $fecha_proxima_calibracion,
                                                                                      $estado, $area_asignada, $tipo_instrumento, $comentarios,
                                                                                      $certificado, $tecnico, $fecha_hora_modificacion, $instrument]);

            if ($val_update_certified_instrument) {
                // Registro en auditlog
                $movimiento = utf8_decode('El usuario '.$tecnico.' modificó el instrumento '.$activo.' con número de serie '.$serie.' y no. control '.$no_control.' el '.$fecha_hora_modificacion.'');
                $url = $_SERVER['PHP_SELF'].'?'.$instrument;
                $database = 'SIS';
                $save_move = $con->prepare("INSERT INTO $log (movimiento, link, ddbb, usuario_movimiento, fecha_hora)
                                    VALUES (?, ?, ?, ?, ?)");
                $val_save_move = $save_move->execute([$movimiento, $url, $database, $tecnico, $fecha_hora_modificacion]);

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
                die();
            }
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
                <h1 class="animated lightSpeedIn">Modificar Instrumento: <strong><?php echo $instrument; ?></strong></h1>
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
        <div class="panel-heading text-center"><strong>Para poder modificar un instrumento es necesario llenar los siguientes campos.</strong></div>
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
                                   <input class="form-control" type="text" name="modelo" placeholder="Por ejemplo: ADM-880C" value="<?php echo $modelo; ?>">
								   <span class="input-group-addon"><i class="fa fa-pencil-square-o"></i></span>
                                </div>
                            </div>
                        </div>
						<div class="form-group">
                          <label  class="col-sm-222 control-label"><i class="fa fa-barcode" aria-hidden="true"></i> Número de Serie</label>
                          <div class="col-sm-110">
                              <div class='input-group'>
                                  <input type="text" class="form-control" name="serie" placeholder="Por ejemplo: 11371" value="<?php echo $numero_serie; ?>">
								  <span class="input-group-addon"><i class="fa fa-pencil-square-o"></i></span>
                              </div>
                          </div>
                        </div>
						
						<div class="form-group">
                            <label class="col-sm-222 control-label"><i class="fa fa-address-card" aria-hidden="true"></i> Número de Control</label>
                             <div class="col-sm-110">
                              <div class='input-group'>
                                <input type="text" class="form-control" name="no_control" placeholder="Por ejemplo: FVF-DVI-1428" value="<?php echo $numero_control; ?>">
                                <span class="input-group-addon"><i class="fa fa-pencil-square-o"></i></span>
                              </div>
                          </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-222 control-label"><i class="fa fa-arrows-h" aria-hidden="true"></i> Rango</label>
                             <div class="col-sm-110">
                              <div class='input-group'>
                                <input type="text" class="form-control" name="rango" placeholder="Por ejemplo: 100 a 3000 m3/h" value="<?php echo $rango; ?>">
                                <span class="input-group-addon"><i class="fa fa-pencil-square-o"></i></span>
                              </div>
                          </div>
                        </div>

                        <div class="form-group">
                          <label  class="col-sm-222 control-label"><i class="fa fa-sliders" aria-hidden="true"></i> Frecuencia de Calibración</label>
                          <div class="col-sm-110">
                              <div class='input-group'>
                                  <input type="text" class="form-control" name="frecuencia_cal" placeholder="Por ejemplo: 12 Meses" value="<?php echo $frecuencia_calibracion; ?>">
								  <span class="input-group-addon"><i class="fa fa-pencil-square-o"></i></span>
                              </div> 
                          </div>
                        </div>
						
						<div class="form-group">
                          <label  class="col-sm-222 control-label"><i class="fa fa-calendar" aria-hidden="true"></i> Fecha de Calibración</label>
                          <div class="col-sm-110">
                              <div class='input-group'>
                                  <input type="date" class="form-control" name="fecha_calibracion" value="<?php echo $fecha_calibracion; ?>">
								  <span class="input-group-addon"><i class="fa fa-pencil-square-o"></i></span>
                              </div> 
                          </div>
                        </div>
						
						<div class="form-group">
                            <label class="col-sm-222 control-label"><i class="fa fa-calendar" aria-hidden="true"></i> Fecha de Próxima Calibración</label>
                             <div class="col-sm-110">
                              <div class='input-group'>
                                <input type="date" class="form-control" name="fecha_proxima_calibracion" value="<?php echo $fecha_proxima_calibracion; ?>">
                                <span class="input-group-addon"><i class="fa fa-pencil-square-o"></i></span>
                              </div>
                          </div>
                        </div>
						
						<div class="form-group">
                            <label class="col-sm-222 control-label"><i class="fa fa-star-half-o" aria-hidden="true"></i> Estado</label>
                             <div class="col-sm-110">
                              <div class='input-group'>
                                <select class="form-control" name="estado">
                                    <option value="<?php echo $estado; ?>"><?php echo $estado; ?> - (Actual)</option>
                                    <option value="Vencido">Vencido</option>
                                    <option value="Por Vencer">Por Vencer</option>
                                    <option value="Calibrado">Calibrado</option>
                                </select>
                                <span class="input-group-addon"><i class="fa fa-pencil-square-o"></i></span>
                              </div>
                          </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-222 control-label"><i class="fa fa-users" aria-hidden="true"></i> Área Asignada</label>
                             <div class="col-sm-110">
                              <div class='input-group'>
                                <select class="form-control" name="area_asignada">
                                    <option value="<?php echo $area_asignada; ?>"><?php echo $area_asignada; ?> - (Actual)</option>
                                    <option value="Servicios">Servicios</option>
                                    <option value="Servicios / Ingenieria">Servicios / Ingenieria</option>
                                    <option value="Servicios / Validacion">Servicios / Validacion</option>
                                </select>
                                <span class="input-group-addon"><i class="fa fa-pencil-square-o"></i></span>
                              </div>
                          </div>
                        </div>
						
						<div class="form-group">
                            <label class="col-sm-222 control-label"><i class="fa fa-wrench" aria-hidden="true"></i> Tipo de Instrumento</label>
                             <div class="col-sm-110">
                              <div class='input-group'>
                                <select class="form-control" name="tipo_instrumento">
                                    <option value="<?php echo $tipo_instrumento; ?>"><?php echo $tipo_instrumento; ?> - (Actual)</option>
                                    <option value="DMM">Multiamperímetro (DMM)</option>
                                    <option value="PHA">Medidor de pulsos (PHA)</option>
                                    <option value="MFM">Medidor de flujo de masa (MFM)</option>
                                    <option value="RH/TEMP">RH / TEMP Sensor</option>
                                    <option value="Balometro">Balómetro</option>
                                </select>
                                <span class="input-group-addon"><i class="fa fa-pencil-square-o"></i></span>
                              </div>
                          </div>
                        </div>
						
						<div class="form-group">
                            <label class="col-sm-222 control-label"><i class="fa fa-comments" aria-hidden="true"></i> Comentarios <u>(Opcional)</u>*</label>
                             <div class="col-sm-110">
                              <div class='input-group'>
                                <input type="text" name="comentarios" class="form-control" maxlength="255" placeholder="Anota aquí las observaciones" value="<?php echo $comentarios; ?>">
                                <span class="input-group-addon"><i class="fa fa-pencil-square-o"></i></span>
                              </div>
                          </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-222 control-label"><i class="fa fa-certificate" aria-hidden="true"></i> Certificado <i>(Solo se admiten archivos en <u>formato PDF</u> con un tamaño <u>máximo de 15 MB</u>)</i></label><br><br>
                            <center><a href="../<?php echo $certificado; ?>" class="btn btn-sm btn-success" target="_blank">Ver Actual</a></center><br>
                             <div class="col-sm-110">
                              <div class='input-group'>
                                <input type="file" accept="application/pdf" class="form-control" name="certificate_instrument">
                                <span class="input-group-addon"><i class="fa fa-pencil-square-o"></i></span>
                              </div>
                          </div>
                        </div>

                        <div class="form-group">
                          <div class="col-sm-offset-2 col-sm-10 text-center"><br>
                              <button type="submit" name="modificar_instrumento" class="btn btn-danger">Modificar</button>
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