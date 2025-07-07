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
                        No fue posible agregar el instrumento, valida si el archivo o el formulario cumple los requerimientos, por favor inténtalo de nuevo o contacta al Soporte Técnico.
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
            Se registró correctamente el instrumento.
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
    
    if (isset($_POST['guardar_instrumento'])) {
        require '../../functions/conex_serv.php';

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
		$fecha_hora_carga = date("d/m/Y H:i:s");

        $instruments = 'instrumentos';
        $log = 'auditlog';

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
                    /**********************
                    ALMACENAMIENTO DE DATOS
                    **********************/
                    move_uploaded_file($temp_file, $nombre_save);

                    $save_certified_instrument = $con->prepare("INSERT INTO $instruments (activo, modelo, numero_serie, numero_control,
                                                                                        rango, frecuencia_calibracion, fecha_calibracion, fecha_proxima_calibracion,
                                                                                        estado, area_asignada, tipo_instrumento, comentarios,
                                                                                        certificado, registra_data, fecha_hora_registro)
                                                                                  VALUES (?, ?, ?, ?,
                                                                                          ?, ?, ?, ?,
                                                                                          ?, ?, ?, ?,
                                                                                          ?, ?, ?)");

                    $val_save_certified_instrument = $save_certified_instrument->execute([$activo, $modelo, $serie, $no_control,
                                                                                          $rango, $frecuencia_cal, $fecha_calibracion, $fecha_proxima_calibracion,
                                                                                          $estado, $area_asignada, $tipo_instrumento, $comentarios,
                                                                                          $almacenamiento_certificado ,$tecnico, $fecha_hora_carga]);

                    if ($val_save_certified_instrument) {
                        // Registro en auditlog
                        $movimiento = utf8_decode('El usuario '.$tecnico.' agregó un instrumento '.$activo.' con identificador '.$activo.', número de serie '.$serie.' y no. control '.$no_control.' el '.$fecha_hora_carga.'');
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
                <h1 class="animated lightSpeedIn">Nuevo Instrumento</h1>
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
        <div class="panel-heading text-center"><strong>Para poder crear un nuevo instrumento es necesario llenar los siguientes campos.</strong></div>
        <div class="panel-body">
            <form role="form" action="" method="POST" enctype="multipart/form-data">
						
						<div class="form-group">
                            <label class="col-sm-222 control-label"><i class="fa fa-id-badge" aria-hidden="true"></i> Activo <i>(Identificación VECO)</i></label>
                             <div class="col-sm-110">
                              <div class='input-group'>
							      <input type="text" class="form-control" name="activo" maxlength="30" placeholder="Por ejemplo: BAS-01" required>
                                <span class="input-group-addon"><i class="fa fa-pencil-square-o"></i></span>
                              </div>
                          </div>
                        </div>
                    
                        <div class="form-group">
                            <label class="col-sm-222 control-label"><i class="fa fa-info-circle" aria-hidden="true"></i> Modelo</label>
                            <div class='col-sm-110'>
                                <div class="input-group">
                                   <input class="form-control" type="text" name="modelo" placeholder="Por ejemplo: ADM-880C" required>
								   <span class="input-group-addon"><i class="fa fa-pencil-square-o"></i></span>
                                </div>
                            </div>
                        </div>
						<div class="form-group">
                          <label  class="col-sm-222 control-label"><i class="fa fa-barcode" aria-hidden="true"></i> Número de Serie</label>
                          <div class="col-sm-110">
                              <div class='input-group'>
                                  <input type="text" class="form-control" name="serie" placeholder="Por ejemplo: 11371" required>
								  <span class="input-group-addon"><i class="fa fa-pencil-square-o"></i></span>
                              </div>
                          </div>
                        </div>
						
						<div class="form-group">
                            <label class="col-sm-222 control-label"><i class="fa fa-address-card" aria-hidden="true"></i> Número de Control</label>
                             <div class="col-sm-110">
                              <div class='input-group'>
                                <input type="text" class="form-control" name="no_control" placeholder="Por ejemplo: FVF-DVI-1428" required>
                                <span class="input-group-addon"><i class="fa fa-pencil-square-o"></i></span>
                              </div>
                          </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-222 control-label"><i class="fa fa-arrows-h" aria-hidden="true"></i> Rango</label>
                             <div class="col-sm-110">
                              <div class='input-group'>
                                <input type="text" class="form-control" name="rango" placeholder="Por ejemplo: 100 a 3000 m3/h" required>
                                <span class="input-group-addon"><i class="fa fa-pencil-square-o"></i></span>
                              </div>
                          </div>
                        </div>

                        <div class="form-group">
                          <label  class="col-sm-222 control-label"><i class="fa fa-sliders" aria-hidden="true"></i> Frecuencia de Calibración</label>
                          <div class="col-sm-110">
                              <div class='input-group'>
                                  <input type="text" class="form-control" name="frecuencia_cal" placeholder="Por ejemplo: 12 Meses" required>
								  <span class="input-group-addon"><i class="fa fa-pencil-square-o"></i></span>
                              </div> 
                          </div>
                        </div>
						
						<div class="form-group">
                          <label  class="col-sm-222 control-label"><i class="fa fa-calendar" aria-hidden="true"></i> Fecha de Calibración</label>
                          <div class="col-sm-110">
                              <div class='input-group'>
                                  <input type="date" class="form-control" name="fecha_calibracion" required>
								  <span class="input-group-addon"><i class="fa fa-pencil-square-o"></i></span>
                              </div> 
                          </div>
                        </div>
						
						<div class="form-group">
                            <label class="col-sm-222 control-label"><i class="fa fa-calendar" aria-hidden="true"></i> Fecha de Próxima Calibración</label>
                             <div class="col-sm-110">
                              <div class='input-group'>
                                <input type="date" class="form-control" name="fecha_proxima_calibracion" required>
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
                                <select class="form-control" name="area_asignada" required>
                                    <option value=""></option>
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
                                <select class="form-control" name="tipo_instrumento" required>
                                    <option value=""></option>
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
                                <textarea name="comentarios" class="form-control" maxlength="255" placeholder="Anota aquí las observaciones"></textarea>
                                <span class="input-group-addon"><i class="fa fa-pencil-square-o"></i></span>
                              </div>
                          </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-222 control-label"><i class="fa fa-certificate" aria-hidden="true"></i> Certificado <i>(Solo se admiten archivos en <u>formato PDF</u> con un tamaño <u>máximo de 15 MB</u>)</i></label>
                             <div class="col-sm-110">
                              <div class='input-group'>
                                <input type="file" accept="application/pdf" class="form-control" name="certificate_instrument" required>
                                <span class="input-group-addon"><i class="fa fa-pencil-square-o"></i></span>
                              </div>
                          </div>
                        </div>

                        <div class="form-group">
                          <div class="col-sm-offset-2 col-sm-10 text-center"><br>
                              <button type="submit" name="guardar_instrumento" class="btn btn-success">Guardar</button>
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