<?php
session_start();
 
if( $_SESSION['nombre']!="" && $_SESSION['clave']!="" && $_SESSION['tipo']=="admin"){

	function mensaje_ayuda(){
        echo '
            <div class="alert alert-success alert-dismissible fade in col-sm-3 animated bounceInDown" role="alert" style="position:fixed; top:70px; right:10px; z-index:10;"> 
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
            <h4 class="text-center"><strong>REGISTRO EXITOSO</strong></h4>
            <p class="text-center">
            Se registró correctamente la empresa en el sistema.
            </p>
			<p class="text-center">
            Crea ahora la <strong>Sede ó Edificio</strong> correspondiente a esta empresa.
            </p>
			<p class="text-center">
			<a href="add_edificio2.php" class="btn btn-sm btn-primary"><strong>AGREGAR EDIFICIO</strong></a>            
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
				<a href="add_empresa2.php" class="btn btn-sm btn-danger" style="margin-left: 15%">Regresar</a>
			</div>';
	}

	function redirect_success() {
		echo '
			<div class="container" style="margin-left: 40%">
				<img src="./services/assets/img/loading_dvi.gif" height="40%" weight="40%">
			</div>';
	}

	require './services/functions/conex.php';
	$company = 'empresas';
	$users_s = 'usuario_sis';
	$log = 'auditlog';

	include './services/assets/admin/navbar.php';
	include './services/assets/admin/links.php';

	if(isset($_POST['guardar_empresa'])){
		// Recepción de datos
		$rfc = $_POST['rfc'];
		$razon_social = $_POST['razon_social'];
		$nombre_corto = $_POST['nombre_corto'];
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

		// Información para auditlog
		$tecnico = $_SESSION['nombre_completo'];
		require './services/assets/timezone.php';
		$fecha_hora_carga = date("d/m/Y H:i:s");

		// Busqueda de empresa en base al rfc y razon social
		$s_company = $con->prepare("SELECT rfc, razon_social FROM $company WHERE rfc = :rfc OR razon_social = :razon_social");
		$s_company->bindValue(':rfc', $rfc);
		$s_company->bindValue(':razon_social', $razon_social);
		$s_company->setFetchMode(PDO::FETCH_OBJ);
		$s_company->execute();

		$f_company = $s_company->fetchAll();

		if ($s_company -> rowCount() > 0) {
			mensaje_error();
			redirect_failed();
		} else {
			$save_company = $con->prepare("INSERT INTO $company (rfc, razon_social, nombre_corto, calle, numero_exterior, numero_interior,
																colonia, municipio, entidad_federativa, codigo_postal, pais, direccion_gps,
																contacto_nombre, contacto_apellido, contacto_puesto, contacto_email, contacto_telefono,
																fecha, tecnico)
														VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

			$val_save_company = $save_company->execute([$rfc, $razon_social, $nombre_corto, $calle, $numero_exterior, $numero_interior,
										$colonia, $municipio, $entidad_federativa, $codigo_postal, $pais, $direccion_gps,
										$contacto_nombre, $contacto_apellido, $contacto_puesto, $contacto_email, $contacto_telefono,
										$fecha_hora_carga, $tecnico]);

			if ($val_save_company) {
				// Creación de usuario en usuario_sis
				$clave = $nombre_corto;
				$clave_parse = md5($clave);
				$create_user = $con->prepare("INSERT INTO $users_s (nombre_completo, usuario, clave, email, razon_social, tipo_usuario, registra_data)
															VALUES (?, ?, ?, ?, ?, ?, ?)");
				$val_create_user = $create_user->execute([$razon_social, $nombre_corto, $clave_parse, $contacto_email, $razon_social, 'C', $tecnico]);
				require './services/functions/drop_con.php';

				// Registro en auditlog empresa
				require './services/functions/conex_serv.php';
				$movimiento = utf8_decode('El usuario '.$tecnico.' registró la empresa '.$razon_social.' con RFC: '.$rfc.' el '.$fecha_hora_carga.'');
				$url = $_SERVER['PHP_SELF'];
				$database = 'veco_sims_devecchi';
				$save_move = $con->prepare("INSERT INTO $log (movimiento, link, ddbb, usuario_movimiento, fecha_hora)
													VALUES (?, ?, ?, ?, ?)");
				$val_save_move = $save_move->execute([$movimiento, $url, $database, $tecnico, $fecha_hora_carga]);

				// Registro en auditlog cuenta usuario
				$movimiento2 = utf8_decode('El usuario '.$tecnico.' registró el usuario '.$razon_social.' en la base de datos '.$users_s.' el '.$fecha_hora_carga.'');
				$url2 = $_SERVER['PHP_SELF'];
				$save_move2 = $con->prepare("INSERT INTO $log (movimiento, link, ddbb, usuario_movimiento, fecha_hora)
													VALUES (?, ?, ?, ?, ?)");
				$val_save_move2 = $save_move2->execute([$movimiento2, $url2, $database, $tecnico, $fecha_hora_carga]);

				if ($val_create_user && $val_save_move && $val_save_move2) {
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

}else{
	header('Location: index.php');
}
?>
</body>
</html>