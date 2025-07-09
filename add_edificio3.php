<?php
session_start();

if( $_SESSION['nombre']!="" && $_SESSION['clave']!="" && $_SESSION['tipo']=="admin"){

  function mensaje_ayuda(){
        echo '
            <div class="alert alert-success alert-dismissible fade in col-sm-3 animated bounceInDown" role="alert" style="position:fixed; top:70px; right:10px; z-index:10;"> 
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
            <h4 class="text-center"><strong>REGISTRO EXITOSO</strong></h4>
            <p class="text-center">
            Se registró correctamente la información en el sistema.
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

	function redirect_failed() {
		echo '
			<div class="container" style="margin-left: 40%">
				<img src="./services/assets/img/loading_dvi.gif" height="40%" weight="40%">
				<br>
				<a href="add_edificio2.php" class="btn btn-sm btn-danger" style="margin-left: 15%">Regresar</a>
			</div>';
	}

	function redirect_success() {
		echo '
			<div class="container" style="margin-left: 40%">
				<img src="./services/assets/img/loading_dvi.gif" height="40%" weight="40%">
				<br>
				<a href="edificio.php" class="btn btn-sm btn-success" style="margin-left: 15%">Continuar</a>
			</div>';
	}

  require './services/functions/conex.php';
  $builds = 'edificio';
	$log = 'auditlog';

	include './services/assets/admin/navbar.php';
	include './services/assets/admin/links.php';
  
  if(isset($_POST['guardar_edificio'])){
    // Recepción de datos
    $empresa_id = $_POST['empresa_id'];
    $descripcion = $_POST['descripcion'];
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
    $requisitos_acceso = $_POST['requisitos_acceso'];

    // Información para auditlog
		$tecnico = $_SESSION['nombre_completo'];
		require './services/assets/timezone.php';
		$fecha_hora_carga = date("d/m/Y H:i:s");

    // Validación de la existencia del edificio
    $s_build = $con->prepare("SELECT * FROM $builds WHERE empresa_id = :empresa_id AND descripcion = :descripcion");
    $s_build->bindValue(':empresa_id', $empresa_id);
    $s_build->bindValue(':descripcion', $descripcion);
    $s_build->setFetchMode(PDO::FETCH_OBJ);
    $s_build->execute();

    $f_build = $s_build->fetchAll();

    if ($s_build -> rowCount() > 0) {
      redirect_failed();
      mensaje_error();
    } else {
      $save_build = $con->prepare("INSERT INTO $builds (empresa_id, descripcion, calle, numero_exterior, numero_interior,
                                                      colonia, municipio, entidad_federativa, codigo_postal, pais,
                                                      direccion_gps, contacto_nombre, contacto_apellido, contacto_puesto, contacto_email,
                                                      contacto_telefono, requisitos_acceso, fecha, tecnico)
                                              VALUES (?, ?, ?, ?, ?,
                                                      ?, ?, ?, ?, ?,
                                                      ?, ?, ?, ?, ?,
                                                      ?, ?, ?, ?)");
    
      $val_save_build = $save_build->execute([$empresa_id, $descripcion, $calle, $numero_exterior, $numero_interior,
                                            $colonia, $municipio, $entidad_federativa, $codigo_postal, $pais,
                                            $direccion_gps, $contacto_nombre, $contacto_apellido, $contacto_puesto, $contacto_email,
                                            $contacto_telefono, $requisitos_acceso, $fecha_hora_carga, $tecnico]);

      if ($val_save_build) {
        require './services/functions/drop_con.php';

				// Registro en auditlog empresa
				require './services/functions/conex_serv.php';
				$movimiento = utf8_decode('El usuario '.$tecnico.' registra el edificio '.$descripcion.' el '.$fecha_hora_carga.'');
				$url = $_SERVER['PHP_SELF'];
				$database = 'veco_sims_devecchi';
				$save_move = $con->prepare("INSERT INTO $log (movimiento, link, ddbb, usuario_movimiento, fecha_hora)
													VALUES (?, ?, ?, ?, ?)");
				$val_save_move = $save_move->execute([$movimiento, $url, $database, $tecnico, $fecha_hora_carga]);

				if ($val_save_move) {
					require './services/functions/drop_con.php';
					mensaje_ayuda();
					redirect_success();
				} else {
					require './services/functions/drop_con.php';
					mensaje_error();
					redirect_failed();
				}
      } else {
        mensaje_error();
        redirect_failed();
      }
    }
  }
		?>
		
 <?php
}else{
	header('Location: index.php');
}
?>
</body>
</html>