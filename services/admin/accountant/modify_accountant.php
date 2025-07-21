<?php
session_start();

if( $_SESSION['nombre']!="" && $_SESSION['clave']!="" && $_SESSION['tipo']=="admin"){
  include '../../assets/admin/navbar2.php';
  include '../../assets/admin/links2.php';
  require '../../functions/conex_serv.php';
  $counter = $_SERVER['QUERY_STRING'];
  
  function mensaje_error() {
    echo'
                <div class="alert alert-danger alert-dismissible fade in col-sm-3 animated bounceInDown" role="alert" style="position:fixed; top:70px; right:10px; z-index:10;"> 
                    <a href="empresa.php"><button type="button" class="close"  data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                    <h4 class="text-center">OCURRIÓ UN ERROR</h4>
                    <p class="text-center">
                        No fue posible agregar el contador, valida si el equipo YA EXISTE o YA ESTÁ REGISTRADO, por favor inténtalo de nuevo o contacta al Soporte Técnico.
                    </p>
                </div>
            '; 
  }

  function mensaje_ayuda(){
        echo '
            <div class="alert alert-success alert-dismissible fade in col-sm-3 animated bounceInDown" role="alert" style="position:fixed; top:70px; right:10px; z-index:10;"> 
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
            <h4 class="text-center"><strong>MMODIFICACIÓN EXITOSA</strong></h4>
            <p class="text-center">
            Se modificó correctamente el contador de partículas.
            </p>
            </div>
            ';
    }

	function redirect_failed() {
		echo '
			<div class="container" style="margin-left: 40%">
				<img src="../../assets/img/loading_dvi.gif" height="40%" weight="40%">
				<br>
				<a href="modify_accountant.php?'.$counter.'" class="btn btn-sm btn-danger" style="margin-left: 15%">Regresar</a>
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
        $empresa_vinculada = $contador-> empresa_vinculada;
        $rango = $contador -> rango;
        $frecuencia_calibracion = $contador -> frecuencia_calibracion;

        $vigencia_inicial = $contador -> vigencia_inicial;
        $vigencia_inicial=  date("Y-d-m", strtotime($vigencia_inicial));

        $vigencia_final = $contador -> vigencia_final;
        $vigencia_final = date("Y-d-m", strtotime($vigencia_final));

        $estado = $contador -> estado;
        $area_asignada = $contador -> area_asignada;
        $comentarios = $contador -> comentarios;
       }
    } else {
        mensaje_error();
        redirect_failed();
        die();
    }
    
    if (isset($_POST['modificar_contador'])) {
        // Recpción de datos
        $descripcion = $_POST['descripcion'];
        $modelo = $_POST['modelo'];
        $serie = $_POST['serie'];
        $id_cliente = $_POST['id_cliente'];
        $no_control = $_POST['no_control'];
        $rango = $_POST['rango'];
        $frecuencia_cal = $_POST['frecuencia_cal'];

        $vigencia_ini = $_POST['vigencia_ini'];
        $vigencia_ini = date("d/m/Y", strtotime($vigencia_ini));

        $vigencia_fin = $_POST['vigencia_fin'];
        $vigencia_fin = date("d/m/Y", strtotime($vigencia_fin));

        $estado = $_POST['estado'];
        $area_asignada = $_POST['area_asignada'];
        $empresa_vinculada = $_POST['empresa_vinculada'];
        $comentarios = $_POST['comentarios'];

        // Información para auditlog
		$tecnico = $_SESSION['nombre_completo'];
		require '../../assets/timezone.php';
		$fecha_hora_modificacion = date("d/m/Y H:i:s");

        // Modificación de información
        $update_accountant = $con->prepare("UPDATE $accountant SET descripcion_nombre = ?, modelo_ci = ?, numero_serie = ?, identificacion_cliente = ?,
                                                                    numero_control = ?, rango = ?, frecuencia_calibracion = ?, vigencia_inicial = ?,
                                                                    vigencia_final = ?, estado = ?, area_asignada = ?, empresa_vinculada = ?,
                                                                    comentarios = ?, modifica_data = ?, fecha_hora_modificacion = ?
                                                                WHERE id_contador = ?");
        
        $val_update_accountant = $update_accountant->execute([$descripcion, $modelo, $serie, $id_cliente,
                                                            $no_control, $rango, $frecuencia_cal, $vigencia_ini,
                                                            $vigencia_fin, $estado, $area_asignada, $empresa_vinculada,
                                                            $comentarios, $tecnico, $fecha_hora_modificacion, $counter]);

        if ($val_update_accountant) {
            // Registro en auditlog
            $movimiento = utf8_decode('El usuario '.$tecnico.' modifica '.$descripcion.' con numero de serie '.$serie.' y no. control '.$no_control.' el '.$fecha_hora_modificacion.'');
            $url = $_SERVER['PHP_SELF'].'?'.$counter;
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
                <h1 class="animated lightSpeedIn">Modificar Contador: <strong><?php echo $counter; ?></strong></h1>
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
        <div class="panel-heading text-center"><strong>Para modificar un contador es necesario llenar los siguientes campos.</strong></div>
        <div class="panel-body">
            <form role="form" action="" method="POST" enctype="multipart/form-data">
						
						<div class="form-group">
                            <label class="col-sm-222 control-label"><i class="fa fa-tachometer" aria-hidden="true"></i> Descripción</label>
                             <div class="col-sm-110">
                              <div class='input-group'>
							      <input type="text" class="form-control" name="descripcion" maxlength="30" placeholder="Por ejemplo: Contador de Particulas" value="<?php echo $descripcion_nombre; ?>" readonly>
                                <span class="input-group-addon"><i class="fa fa-pencil-square-o"></i></span>
                              </div>
                          </div>
                        </div>
                    
                        <div class="form-group">
                            <label class="col-sm-222 control-label"><i class="fa fa-info-circle" aria-hidden="true"></i> Modelo del Contador</label>
                            <div class='col-sm-110'>
                                <div class="input-group">
                                   <input class="form-control" type="text" name="modelo" placeholder="Por ejemplo: CI-750t-01" value="<?php echo $modelo_ci; ?>">
								   <span class="input-group-addon"><i class="fa fa-pencil-square-o"></i></span>
                                </div>
                            </div>
                        </div>
						<div class="form-group">
                          <label  class="col-sm-222 control-label"><i class="fa fa-barcode" aria-hidden="true"></i> Número de Serie</label>
                          <div class="col-sm-110">
                              <div class='input-group'>
                                  <input type="text" class="form-control" name="serie" placeholder="Por ejemplo: 132194" value="<?php echo $numero_serie; ?>">
								  <span class="input-group-addon"><i class="fa fa-pencil-square-o"></i></span>
                              </div>
                          </div>
                        </div>
						
						<div class="form-group">
                            <label class="col-sm-222 control-label"><i class="fa fa-user" aria-hidden="true"></i> Identificación del Cliente</label>
                             <div class="col-sm-110">
                              <div class='input-group'>
                                <input type="text" class="form-control" name="id_cliente" placeholder="Por ejemplo: CPS-01" value="<?php echo $identificacion_cliente ?>">
                                <span class="input-group-addon"><i class="fa fa-pencil-square-o"></i></span>
                              </div>
                          </div>
                        </div>
						
						<div class="form-group">
                            <label class="col-sm-222 control-label"><i class="fa fa-address-card" aria-hidden="true"></i> Número de Control</label>
                             <div class="col-sm-110">
                              <div class='input-group'>
                                <input type="text" class="form-control" name="no_control" placeholder="Por ejemplo: CPS-01" value="<?php echo $numero_control; ?>">
                                <span class="input-group-addon"><i class="fa fa-pencil-square-o"></i></span>
                              </div>
                          </div>
                        </div>

                        <div class="form-group">
                          <label class="col-sm-222 control-label"><i class="fa fa-arrows-h" aria-hidden="true"></i> Rango</label>
                          <div class="col-sm-110">
                              <div class='input-group'>
                                  <input class="form-control" name="rango" placeholder="Por ejemplo: 0.3 - 5" value="<?php echo $rango; ?>">
								  <span class="input-group-addon"><i class="fa fa-pencil-square-o"></i></span>
                              </div> 
                          </div>
                        </div>

                        <div class="form-group">
                          <label  class="col-sm-222 control-label"><i class="fa fa-sliders" aria-hidden="true"></i> Frecuencia de Calibración</label>
                          <div class="col-sm-110">
                              <div class='input-group'>
                                  <input type="text" class="form-control"  name="frecuencia_cal" placeholder="Por ejemplo: 12 Meses" value="<?php echo $frecuencia_calibracion; ?>">
								  <span class="input-group-addon"><i class="fa fa-pencil-square-o"></i></span>
                              </div> 
                          </div>
                        </div>
						
						<div class="form-group">
                          <label  class="col-sm-222 control-label"><i class="fa fa-sort-numeric-asc" aria-hidden="true"></i> Vigencia Inicial</label>
                          <div class="col-sm-110">
                              <div class='input-group'>
                                  <input type="date" class="form-control" name="vigencia_ini" value="<?php echo $vigencia_inicial; ?>">
								  <span class="input-group-addon"><i class="fa fa-pencil-square-o"></i></span>
                              </div> 
                          </div>
                        </div>
						
						<div class="form-group">
                            <label class="col-sm-222 control-label"><i class="fa fa-sort-numeric-desc" aria-hidden="true"></i> Vigencia Final</label>
                             <div class="col-sm-110">
                              <div class='input-group'>
                                <input type="date" class="form-control" name="vigencia_fin" value="<?php echo $vigencia_final; ?>">
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
                                </select>
                                <span class="input-group-addon"><i class="fa fa-pencil-square-o"></i></span>
                              </div>
                          </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-222 control-label"><i class="fa fa-building" aria-hidden="true"></i> Empresa Asignada</label>
                             <div class="col-sm-110">
                              <div class='input-group'>
                                <select class="form-control" name="empresa_vinculada" required>
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
                                            echo '<option value="'.$id_edificio2.'">'.$razon_social2.' - '.$descripcion2.' - (Actual)</option>';
                                          }
                                        } else {
                                          mensaje_error();
                                          redirect_failed();
                                          die();
                                        }
                                      }
                                    } else {
                                      mensaje_error();
                                      redirect_failed();
                                    }

                                    // Consulta de los edificios/empresas disponibles a asignar
                                    $s_company = $con -> prepare("SELECT id, razon_social FROM $company");
                                    $s_company -> setFetchMode(PDO::FETCH_OBJ);
                                    $s_company -> execute();

                                    $f_company = $s_company->fetchAll();

                                    if ($s_company -> rowCount() > 0) {
                                      foreach ($f_company as $compania) {
                                        $id_empresa = $compania -> id;
                                        $razon_social = $compania -> razon_social;

                                        // Consulta de edificio con la información de la empresa obtenida
                                        $s_building = $con -> prepare("SELECT empresa_id, id_edificio, descripcion FROM $build WHERE empresa_id = :id_empresa ORDER BY empresa_id ASC");
                                        $s_building->bindValue(':id_empresa', $id_empresa);
                                        $s_building->setFetchMode(PDO::FETCH_OBJ);
                                        $s_building->execute();

                                        $f_building = $s_building->fetchAll();

                                        if ($s_building -> rowCount() > 0) {
                                          foreach ($f_building as $sede) {
                                            $empresa_id = $sede -> empresa_id;
                                            $id_edificio = $sede -> id_edificio;
                                            $descripcion_edificio = $sede -> descripcion;
                                            echo '<option value="'.$id_edificio.'">'.$razon_social.' - '.$descripcion_edificio.'</option>';
                                          }
                                        } else {
                                          mensaje_error();
                                          redirect_failed();
                                          die();
                                        }
                                      }
                                    } else {
                                      mensaje_error();
                                      redirect_failed();
                                      die();
                                    }
                                    ?>
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
                          <div class="col-sm-offset-2 col-sm-10 text-center"><br>
                              <button type="submit" name="modificar_contador" class="btn btn-danger">Modificar</button>
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