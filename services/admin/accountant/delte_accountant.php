<?php
session_start();

if( $_SESSION['nombre']!="" && $_SESSION['clave']!="" && $_SESSION['tipo']=="admin"){
  include '../../assets/admin/navbar2.php';
  include '../../assets/admin/links2.php';
  require '../../functions/conex_serv.php';
  
  function mensaje_error() {
    echo'
                <div class="alert alert-danger alert-dismissible fade in col-sm-3 animated bounceInDown" role="alert" style="position:fixed; top:70px; right:10px; z-index:10;"> 
                    <a href="empresa.php"><button type="button" class="close"  data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                    <h4 class="text-center">OCURRIÓ UN ERROR</h4>
                    <p class="text-center">
                        No fue posible obtener información del contador, valida si el equipo YA EXISTE o YA ESTÁ REGISTRADO, por favor inténtalo de nuevo o contacta al Soporte Técnico.
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
            Se eliminó correctamente el contador de partículas.
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

    $counter = $_SERVER['QUERY_STRING'];
    $accountant = 'contadores';
    $log = 'auditlog';

    // Recuperación de información del contador
    $s_counter = $con->prepare("SELECT * FROM $accountant WHERE id_contador = :counter");
    $s_counter->bindValue(':counter', $counter);
    $s_counter->setFetchMode(PDO::FETCH_OBJ);
    $s_counter->execute();
    
    $f_counter = $s_counter->fetchAll();

    if ($s_counter -> rowCount() > 0) {
       foreach ($f_counter as $contador) {
        $id_contador = $contador -> id_contador;
        $descripcion_nombre = $contador -> descripcion_nombre;
        $modelo_ci = $contador -> modelo_ci;
        $numero_serie = $contador -> numero_serie;
        $identificacion_cliente = $contador -> identificacion_cliente;
        $numero_control = $contador -> numero_control;
        $rango = $contador -> rango;
        $frecuencia_calibracion = $contador -> frecuencia_calibracion;
        $vigencia_inicial = $contador -> vigencia_inicial;
        $vigencia_final = $contador -> vigencia_final;
        $estado = $contador -> estado;
        $area_asignada = $contador -> area_asignada;
        $empresa_vinculada = $contador -> empresa_vinculada;
        $comentarios = $contador -> comentarios;
       }
    } else {
        mensaje_error();
        redirect_failed();
        die();
    }
    
    if (isset($_POST['eliminar_contador'])) {
        // Recpción de datos
        $descripcion = $_POST['descripcion'];
        $serie = $_POST['serie'];
        $no_control = $_POST['no_control'];

        // Información para auditlog
		$tecnico = $_SESSION['nombre_completo'];
		require '../../assets/timezone.php';
		$fecha_hora_eliminacion = date("d/m/Y H:i:s");

        // Eliminación de información
        $delete_accountant = $con->prepare("DELETE FROM $accountant WHERE id_contador = ?");
        $val_delete_accountant = $delete_accountant->execute([$counter]);

        if ($val_delete_accountant) {
            // Registro en auditlog
            $movimiento = utf8_decode('El usuario '.$tecnico.' elimina '.$descripcion.' con numero de serie '.$serie.' y no. control '.$no_control.' el '.$fecha_hora_eliminacion.'');
            $url = $_SERVER['PHP_SELF'].'?'.$counter;
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
                <h1 class="animated lightSpeedIn">Eliminar Contador: <strong><?php echo $counter; ?></strong></h1>
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
                            <label class="col-sm-222 control-label"><i class="fa fa-tachometer" aria-hidden="true"></i> Descripción</label>
                             <div class="col-sm-110">
                              <div class='input-group'>
							      <input type="text" class="form-control" name="descripcion" maxlength="30" value="<?php echo $descripcion_nombre; ?>" readonly>
                                <span class="input-group-addon"><i class="fa fa-pencil-square-o"></i></span>
                              </div>
                          </div>
                        </div>
                    
                        <div class="form-group">
                            <label class="col-sm-222 control-label"><i class="fa fa-info-circle" aria-hidden="true"></i> Modelo del Contador</label>
                            <div class='col-sm-110'>
                                <div class="input-group">
                                   <input class="form-control" type="text" name="modelo" value="<?php echo $modelo_ci; ?>" readonly>
								   <span class="input-group-addon"><i class="fa fa-pencil-square-o"></i></span>
                                </div>
                            </div>
                        </div>
						<div class="form-group">
                          <label  class="col-sm-222 control-label"><i class="fa fa-barcode" aria-hidden="true"></i> Número de Serie</label>
                          <div class="col-sm-110">
                              <div class='input-group'>
                                  <input type="text" class="form-control" name="serie" value="<?php echo $numero_serie; ?>" readonly>
								  <span class="input-group-addon"><i class="fa fa-pencil-square-o"></i></span>
                              </div>
                          </div>
                        </div>
						
						<div class="form-group">
                            <label class="col-sm-222 control-label"><i class="fa fa-user" aria-hidden="true"></i> Identificación del Cliente</label>
                             <div class="col-sm-110">
                              <div class='input-group'>
                                <input type="text" class="form-control" name="id_cliente" value="<?php echo $identificacion_cliente ?>" readonly>
                                <span class="input-group-addon"><i class="fa fa-pencil-square-o"></i></span>
                              </div>
                          </div>
                        </div>
						
						<div class="form-group">
                            <label class="col-sm-222 control-label"><i class="fa fa-address-card" aria-hidden="true"></i> Número de Control</label>
                             <div class="col-sm-110">
                              <div class='input-group'>
                                <input type="text" class="form-control" name="no_control" value="<?php echo $numero_control; ?>" readonly>
                                <span class="input-group-addon"><i class="fa fa-pencil-square-o"></i></span>
                              </div>
                          </div>
                        </div>

                        <div class="form-group">
                          <label class="col-sm-222 control-label"><i class="fa fa-arrows-h" aria-hidden="true"></i> Rango</label>
                          <div class="col-sm-110">
                              <div class='input-group'>
                                  <input class="form-control" name="rango" value="<?php echo $rango; ?>" readonly>
								  <span class="input-group-addon"><i class="fa fa-pencil-square-o"></i></span>
                              </div> 
                          </div>
                        </div>

                        <div class="form-group">
                          <label  class="col-sm-222 control-label"><i class="fa fa-sliders" aria-hidden="true"></i> Frecuencia de Calibración</label>
                          <div class="col-sm-110">
                              <div class='input-group'>
                                  <input type="text" class="form-control" name="frecuencia_cal" value="<?php echo $frecuencia_calibracion; ?>" readonly>
								  <span class="input-group-addon"><i class="fa fa-pencil-square-o"></i></span>
                              </div> 
                          </div>
                        </div>
						
						<div class="form-group">
                          <label  class="col-sm-222 control-label"><i class="fa fa-sort-numeric-asc" aria-hidden="true"></i> Vigencia Inicial</label>
                          <div class="col-sm-110">
                              <div class='input-group'>
                                  <input type="text" class="form-control" name="vigencia_ini" value="<?php echo $vigencia_inicial; ?>" readonly>
								  <span class="input-group-addon"><i class="fa fa-pencil-square-o"></i></span>
                              </div> 
                          </div>
                        </div>
						
						<div class="form-group">
                            <label class="col-sm-222 control-label"><i class="fa fa-sort-numeric-desc" aria-hidden="true"></i> Vigencia Final</label>
                             <div class="col-sm-110">
                              <div class='input-group'>
                                <input type="text" class="form-control" name="vigencia_fin" value="<?php echo $vigencia_final; ?>" readonly>
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
                            <label class="col-sm-222 control-label"><i class="fa fa-building" aria-hidden="true"></i> Empresa Asignada</label>
                             <div class="col-sm-110">
                              <div class='input-group'>
                                    <?php
                                    require '../../functions/conex.php';
                                    $company = 'empresas';
                                    $build = 'edificio';

                                    // Mostrar edificio seleccionado
                                    $s_edificio = $con->prepare("SELECT id_edificio, empresa_id, descripcion FROM $build WHERE id_edificio = :id_edificio");
                                    $s_edificio->bindValue('id_edificio', $empresa_vinculada);
                                    $s_edificio->setFetchMode(PDO::FETCH_OBJ);
                                    $s_edificio->execute();

                                    $f_edificio = $s_edificio->fetchAll();

                                    if ($s_edificio -> rowCount() > 0) {
                                      foreach ($f_edificio as $building) {
                                        $id_edificio2 = $building -> id_edificio;
                                        $empresa_id2 = $building -> empresa_id;
                                        $descripcion2 = $building -> descripcion;

                                        // Consulta la empresa con la información del edificio obtenido
                                        $s_empresa = $con->prepare("SELECT id, razon_social FROM $company WHERE id = :empresa_id");
                                        $s_empresa->bindValue(':empresa_id', $empresa_id2);
                                        $s_empresa->setFetchMode(PDO::FETCH_OBJ);
                                        $s_empresa->execute();

                                        $f_empresa = $s_empresa->fetchAll();

                                        if ($s_empresa -> rowCount() > 0) {
                                          foreach ($f_empresa as $industry) {
                                            $id_empresa2 = $industry -> id;
                                            $razon_social2 = $industry -> razon_social;
                                            echo '<input type="text" class="form-control" name="empresa_vinculada" value="'.$razon_social2.' - '.$descripcion2.'" readonly>';
                                          }
                                        } else {
                                          echo '<input type="text" class="form-control" name="empresa_vinculada" value="Sin empresa asignada" readonly>';
                                        }
                                      }
                                    } else {
                                      echo '<input type="text" class="form-control" name="empresa_vinculada" value="Sin empresa asignada" readonly>';
                                    }
                                    ?>
                                <span class="input-group-addon"><i class="fa fa-pencil-square-o"></i></span>
                              </div>
                          </div>
                        </div>
						
						<div class="form-group">
                            <label class="col-sm-222 control-label"><i class="fa fa-comments" aria-hidden="true"></i> Comentarios <u>(Opcional)</u>*</label>
                             <div class="col-sm-110">
                              <div class='input-group'>
                                <input type="text" name="comentarios" class="form-control" maxlength="255" value="<?php echo $comentarios; ?>" readonly>
                                <span class="input-group-addon"><i class="fa fa-pencil-square-o"></i></span>
                              </div>
                          </div>
                        </div>

                        <div class="form-group">
                          <div class="col-sm-offset-2 col-sm-10 text-center"><br>
                              <button type="submit" name="eliminar_contador" class="btn btn-danger">Eliminar</button>
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