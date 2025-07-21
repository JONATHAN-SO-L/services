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
                        No fue posible agregar el contador, valida si el equipo YA EXISTE o YA ESTÁ REGISTRADO, por favor inténtalo de nuevo o contacta al Soporte Técnico.
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
            Se registró correctamente el contador de partículas.
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
    
    if (isset($_POST['guardar_contador'])) {
        require '../../functions/conex_serv.php';

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
        $fecha_hora_carga = date("d/m/Y H:i:s");

        // Validación de que no existe en sistema
        $accountant = 'contadores';
        $log = 'auditlog';

        $s_counter = $con -> prepare("SELECT numero_serie FROM $accountant WHERE numero_serie = :serie");
        $s_counter->bindValue(':serie', $serie);
        $s_counter->setFetchMode(PDO::FETCH_OBJ);
        $s_counter->execute();

        $f_counter = $s_counter->fetchAll();

        if ($s_counter -> rowCount() > 0) {
            mensaje_error();
            redirect_failed();
        } else {
            // Registro de información
            $save_accountant = $con -> prepare("INSERT INTO $accountant (descripcion_nombre, modelo_ci, numero_serie, identificacion_cliente,
                                                                        numero_control, rango, frecuencia_calibracion, vigencia_inicial,
                                                                        vigencia_final, estado, area_asignada, empresa_vinculada,
                                                                        comentarios, registra_data, fecha_hora_registro)
                                                                 VALUES (?, ?, ?, ?,
                                                                        ?, ?, ?, ?,
                                                                        ?, ?, ?, ?,
                                                                        ?, ?, ?)");
            
            $val_save_accountant = $save_accountant->execute([$descripcion, $modelo, $serie, $id_cliente,
                                                            $no_control, $rango, $frecuencia_cal, $vigencia_ini,
                                                            $vigencia_fin, $estado, $area_asignada, $empresa_vinculada,
                                                            $comentarios, $tecnico, $fecha_hora_carga]);

            if ($val_save_accountant) {
                // Registro en auditlog
                $movimiento = utf8_decode('El usuario '.$tecnico.' agregó un(a) nuevo(a) '.$descripcion.' con número de serie '.$serie.' y no. control '.$no_control.' el '.$fecha_hora_carga.'');
                $url = $_SERVER['PHP_SELF'];
                $database = 'SIS';
                $save_move = $con->prepare("INSERT INTO $log (movimiento, link, ddbb, usuario_movimiento, fecha_hora)
                                    VALUES (?, ?, ?, ?, ?)");
                $val_save_move = $save_move->execute([$movimiento, $url, $database, $tecnico, $fecha_hora_carga]);

                if ($val_save_move) {
                    // Aignación del contador en la tabla "edificio"
                    require '../../functions/conex.php';
                    $building = 'edificio';
                    $save_building = $con->prepare("UPDATE $building SET serie_contador = ?
                                                                    WHERE id_edificio = ?");
                    
                    $val_save_building = $save_building->execute([$serie, $empresa_vinculada]);

                    if ($val_save_building) {
                      require '../../functions/conex_serv.php';
                      // Registro en auditlog
                      $movimiento2 = utf8_decode('El usuario '.$tecnico.' agrega '.$descripcion.' con numero de serie '.$serie.' y no. control '.$no_control.' en el edificio de la empresa con id '.$empresa_vinculada.' el '.$fecha_hora_carga.'');
                      $url2 = $_SERVER['PHP_SELF'];
                      $database = 'veco_sims_devecchi';
                      $save_move2 = $con->prepare("INSERT INTO $log (movimiento, link, ddbb, usuario_movimiento, fecha_hora)
                                          VALUES (?, ?, ?, ?, ?)");
                      $val_save_move2 = $save_move2->execute([$movimiento2, $url2, $database, $tecnico, $fecha_hora_carga]);

                      if ($val_save_move2) {
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
                <h1 class="animated lightSpeedIn">Nuevo Contador</h1>
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
        <div class="panel-heading text-center"><strong>Para poder crear un nuevo contador es necesario llenar los siguientes campos.</strong></div>
        <div class="panel-body">
            <form role="form" action="" method="POST" enctype="multipart/form-data">
						
						<div class="form-group">
                            <label class="col-sm-222 control-label"><i class="fa fa-tachometer" aria-hidden="true"></i> Descripción</label>
                             <div class="col-sm-110">
                              <div class='input-group'>
							      <input type="text" class="form-control" name="descripcion" maxlength="30" placeholder="Por ejemplo: Contador de Particulas" value="Contador de Particulas" required>
                                <span class="input-group-addon"><i class="fa fa-pencil-square-o"></i></span>
                              </div>
                          </div>
                        </div>
                    
                        <div class="form-group">
                            <label class="col-sm-222 control-label"><i class="fa fa-info-circle" aria-hidden="true"></i> Modelo del Contador</label>
                            <div class='col-sm-110'>
                                <div class="input-group">
                                   <input class="form-control" type="text" name="modelo" placeholder="Por ejemplo: CI-750t-01" required>
								   <span class="input-group-addon"><i class="fa fa-pencil-square-o"></i></span>
                                </div>
                            </div>
                        </div>
						<div class="form-group">
                          <label  class="col-sm-222 control-label"><i class="fa fa-barcode" aria-hidden="true"></i> Número de Serie</label>
                          <div class="col-sm-110">
                              <div class='input-group'>
                                  <input type="text" class="form-control" name="serie" placeholder="Por ejemplo: 132194" required>
								  <span class="input-group-addon"><i class="fa fa-pencil-square-o"></i></span>
                              </div>
                          </div>
                        </div>
						
						<div class="form-group">
                            <label class="col-sm-222 control-label"><i class="fa fa-user" aria-hidden="true"></i> Identificación del Cliente</label>
                             <div class="col-sm-110">
                              <div class='input-group'>
                                <input type="text" class="form-control" name="id_cliente" placeholder="Por ejemplo: CPS-01" required>
                                <span class="input-group-addon"><i class="fa fa-pencil-square-o"></i></span>
                              </div>
                          </div>
                        </div>
						
						<div class="form-group">
                            <label class="col-sm-222 control-label"><i class="fa fa-address-card" aria-hidden="true"></i> Número de Control</label>
                             <div class="col-sm-110">
                              <div class='input-group'>
                                <input type="text" class="form-control" name="no_control" placeholder="Por ejemplo: CPS-01" required>
                                <span class="input-group-addon"><i class="fa fa-pencil-square-o"></i></span>
                              </div>
                          </div>
                        </div>

                        <div class="form-group">
                          <label class="col-sm-222 control-label"><i class="fa fa-arrows-h" aria-hidden="true"></i> Rango</label>
                          <div class="col-sm-110">
                              <div class='input-group'>
                                  <input class="form-control" name="rango" placeholder="Por ejemplo: 0.3 - 5" required>
								  <span class="input-group-addon"><i class="fa fa-pencil-square-o"></i></span>
                              </div> 
                          </div>
                        </div>

                        <div class="form-group">
                          <label  class="col-sm-222 control-label"><i class="fa fa-sliders" aria-hidden="true"></i> Frecuencia de Calibración</label>
                          <div class="col-sm-110">
                              <div class='input-group'>
                                  <input type="text" class="form-control"  name="frecuencia_cal" placeholder="Por ejemplo: 12 Meses" required>
								  <span class="input-group-addon"><i class="fa fa-pencil-square-o"></i></span>
                              </div> 
                          </div>
                        </div>
						
						<div class="form-group">
                          <label  class="col-sm-222 control-label"><i class="fa fa-sort-numeric-asc" aria-hidden="true"></i> Vigencia Inicial</label>
                          <div class="col-sm-110">
                              <div class='input-group'>
                                  <input type="date" class="form-control" name="vigencia_ini" required>
								  <span class="input-group-addon"><i class="fa fa-pencil-square-o"></i></span>
                              </div> 
                          </div>
                        </div>
						
						<div class="form-group">
                            <label class="col-sm-222 control-label"><i class="fa fa-sort-numeric-desc" aria-hidden="true"></i> Vigencia Final</label>
                             <div class="col-sm-110">
                              <div class='input-group'>
                                <input type="date" class="form-control" name="vigencia_fin" required>
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
                                    <option value=""></option>
                                    <?php
                                    require '../../functions/drop_con.php';
                                    require '../../functions/conex.php';
                                    // Consulta de los edificios/empresas disponibles a asignar
                                    $company = 'empresas';
                                    $s_company = $con -> prepare("SELECT id, razon_social FROM $company");
                                    $s_company -> setFetchMode(PDO::FETCH_OBJ);
                                    $s_company -> execute();

                                    $f_company = $s_company->fetchAll();

                                    if ($s_company -> rowCount() > 0) {
                                      foreach ($f_company as $compania) {
                                        $id_empresa = $compania -> id;
                                        $razon_social = $compania -> razon_social;

                                        // Consulta de edificio con la información de la empresa obtenida
                                        $build = 'edificio';
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
                                        }
                                      }
                                    } else {
                                      mensaje_error();
                                      redirect_failed();
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
                                <textarea name="comentarios" class="form-control" maxlength="255" placeholder="Anota aquí las observaciones"></textarea>
                                <span class="input-group-addon"><i class="fa fa-pencil-square-o"></i></span>
                              </div>
                          </div>
                        </div>

                        <div class="form-group">
                          <div class="col-sm-offset-2 col-sm-10 text-center"><br>
                              <button type="submit" name="guardar_contador" class="btn btn-success">Guardar</button>
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