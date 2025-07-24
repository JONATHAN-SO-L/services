<?php
session_start(); 

if( $_SESSION['nombre']!="" && $_SESSION['clave']!="" && $_SESSION['tipo']=="admin"){
    include '../../assets/admin/navbar2.php';
    include '../../assets/admin/links2.php';
    
    function mensaje_error() {
        echo '<div class="alert alert-danger alert-dismissible fade in col-sm-3 animated bounceInDown" role="alert" style="position:fixed; top:70px; right:10px; z-index:10;">
            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
            <h4 class="text-center"><strong>OCURRIÓ UN ERROR</strong></h4>
            <p class="text-center">
                No fue posible crear el usuario, por favor inténtalo de nuevo o contacta al Soporte Técnico.
            </p>
            </div>'; 
    }

    function usuario_existente() {
    echo '<div class="alert alert-danger alert-dismissible fade in col-sm-3 animated bounceInDown" role="alert" style="position:fixed; top:70px; right:10px; z-index:10;">
            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
            <h4 class="text-center"><strong>OCURRIÓ UN ERROR</strong></h4>
            <p class="text-center">
                No fue posible crear el usuario debido a que este ya existe, por favor inténtalo de nuevo o contacta al Soporte Técnico.
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
            Se registró correctamente el usuario.
            </p>
            </div>
        ';
}

function redirect_failed() {
    echo '
    <div class="container" style="margin-left: 40%">
        <img src="../../assets/img/loading_dvi.gif" height="40%" weight="40%">
        <br>
        <a href="add_user.php" class="btn btn-sm btn-danger" style="margin-left: 15%">Regresar</a>
    </div>';
}

function redirect_success() {
    echo '
    <div class="container" style="margin-left: 40%">
        <img src="../../assets/img/loading_dvi.gif" height="40%" weight="40%">
        <br>
        <a href="usuarios_sis.php" class="btn btn-sm btn-success" style="margin-left: 15%">Continuar</a>
    </div>';
}

    if (isset($_POST['crear_usuario'])) {
        require '../../functions/conex.php';
        $sis = 'usuario_sis';
        $dvi = 'usuario_devecchi';
        $log = 'auditlog';

        //* Recepción de datos
        $usuario = strip_tags($_POST['usuario']);
        $clave = strip_tags($_POST['clave']);
        $nombre_completo = $_POST['nombre_completo'];
        $email = $_POST['email'];
        $tipo_usuario = $_POST['tipo_usuario'];

        //Obtención del archivo
        /*$size_max = 15728640; //! Definición de tamaño máximo (15 MB)
        $carpeta_save = '../users/sign'; // Se define directorio donde se guarda el certificado del instrumento en el servidor
        $firma_doc = $_FILES['firma_doc']['name']; // Nombre del archivo a guardar
        $tipo_archivo = $_FILES['firma_doc']['type']; // Tipo de archivo
        $tamano_archivo = $_FILES['firma_doc']['size']; // Tamaño del archivo
        $temp_file = $_FILES['firma_doc']['tmp_name']; // Asignación de memoria para procesamiento
        $nombre_save = $carpeta_save.'/'.$firma_doc; // Nombre del archivo para guardar
        $almacenamiento_firma = '../../services/admin/users/'.$firma_doc;*/

        // Información para auditlog
		$admin = $_SESSION['nombre_completo'];
		require '../../assets/timezone.php';
		$fecha_hora_registro = date("d/m/Y H:i:s");

        //* Se contabilizan los caracteres de las variables ingresadas
        $min_user = strlen($usuario);
        $min_pass = strlen($clave);
        $min_name = strlen($nombre_completo);

        //TODO: Se encripta la clave del usuario y el usuario se pasa a mayúsculas
        $usuario_mayus = strtoupper($usuario);
        $pass_hash = md5($clave);

        /****************
        SALIDA DE CORREOS
        ****************/
        //TODOS: Se lanza correo a usuario de alta en sistema
        $website = 'https://veco.lat';
        $soporte = 'https://veco.lat/soporte.php';
        $cabecera = 'From: VecoLAT <no-reply@veco.lat>';
        $asunto = 'VecoLAT | Alta en Plataforma';
        $mensaje = utf8_decode("Estimado(a) ".$nombre_completo.", nos alegra informarle que se le ha registrado en la Plataforma VecoLAT, a continuación le compartimos sus credenciales de acceso.\r\n\r\n
        Usuario: ".$usuario_mayus."\r\n
        Contraseña: ".$clave."\r\n
        Enlace: ".$website."\r\n\r\n
        Ante cualquier duda o pregunta, por favor contácte con el Área de Sistemas: ".$soporte."\r\n
        Saludos Cordiales.");

        //! Validación de que el usuario no exista
        $s_user = $con->prepare("SELECT * FROM $sis WHERE usuario = :usuario");
        $s_user->bindValue(':usuario', $usuario);
        $s_user->setFetchMode(PDO::FETCH_OBJ);
        $s_user->execute();

        $f_user = $s_user->fetchAll();

        if ($s_user -> rowCount() > 0) { //! Si el usuario existe lanza un mensaje de error
            usuario_existente();
            redirect_failed();
            die();
        } else {
            if ($min_user >= 10) { //! Valida que el NOMBRE DE USUARIO cumpla con los caracteres mínimos (10)
                if ($min_pass >= 8) { //! Valida que la CONTRASEÑA cumpla con los caracteres mínimos (8)
                    if ($min_name >= 15) { //! Valida que el NOMBRE COMPLETO cumpla con los caracteres mínimos (15)
                        /*if ($firma != NULL) { //! Valida si se cargó una firma para el usuario
                            if ($tamano_archivo <= $size_max) { // Valida que el tamaño del archivo no supere el máximo (15 MB)
                                if ($tipo_archivo == 'image/png') { // Valida que el tipo de archivo sea imagen en PNG
                                    move_uploaded_file($temp_file, $nombre_save);

                                    $save_user_sing = $con->prepare("INSERT INTO $sis (nombre_completo, usuario, clave, email,
                                                                                        tipo_usuario, sign, registra_data, fecha_hora_registro)
                                                                                VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                                    
                                    $val_save_user_sing = $save_user_sing->execute([$nombre_completo, $usuario_mayus, $pass_hash, $email, $tipo_usuario, $almacenamiento_firma, $admin, $fecha_hora_registro]);

                                    if ($val_save_user_sing) {
                                        //! Una vez validada la creación del nuevo usuario, en caso de que el TIPO DE USUARIO sea "T" se le agrega a la tabla de "usuario_devecchi"
                                        if ($tipo_usuario == 'T') {
                                            $save_user_keys = $con->prepare("INSERT INTO $dvi (nombre_usuario, clave, nombre, email_devecchi)
                                                                                VALUES (?, ?, ?, ?)");

                                            $val_sav_user_keys = $save_user_keys->execute([$usuario_mayus, $pass_hash, $nombre_completo, $email]);

                                            if ($val_sav_user_keys) {
                                                require '../../functions/drop_con.php';
                                                require '../../functions/conex_serv.php';
                                                // Registro en auditlog
                                                $movimiento = utf8_decode('El usuario '.$admin.' dio de alta al nuevo usuario '.$usuario_mayus.' con el tipo de usuario '.$tipo_usuario.' el '.$fecha_hora_registro.' que de igual forma funciona para las claves de las tarjetas de equipos');
                                                $url = $_SERVER['PHP_SELF'];
                                                $database = 'veco_sims_devecchi';
                                                $save_move = $con->prepare("INSERT INTO $log (movimiento, link, ddbb, usuario_movimiento, fecha_hora)
                                                                    VALUES (?, ?, ?, ?, ?)");
                                                $val_save_move = $save_move->execute([$movimiento, $url, $database, $admin, $fecha_hora_registro]);

                                                if ($val_save_move) {
                                                    mail($email, $asunto, $mensaje, $cabecera);
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
                                            require '../../functions/drop_con.php';
                                            require '../../functions/conex_serv.php';
                                            // Registro en auditlog
                                            $movimiento = utf8_decode('El usuario '.$admin.' dio de alta al nuevo usuario '.$usuario_mayus.' con el tipo de usuario '.$tipo_usuario.' el '.$fecha_hora_registro.'');
                                            $url = $_SERVER['PHP_SELF'];
                                            $database = 'veco_sims_devecchi';
                                            $save_move = $con->prepare("INSERT INTO $log (movimiento, link, ddbb, usuario_movimiento, fecha_hora)
                                                                VALUES (?, ?, ?, ?, ?)");
                                            $val_save_move = $save_move->execute([$movimiento, $url, $database, $admin, $fecha_hora_registro]);

                                            if ($val_save_move) {
                                                mail($email, $asunto, $mensaje, $cabecera);
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
                        } else {*/ //* Si nose cargó una firma, se capturan los datos del usuario sin esta
                            $save_user = $con->prepare("INSERT INTO $sis (nombre_completo, usuario, clave, email,
                                                                            tipo_usuario, registra_data, fecha_hora_registro)
                                                                    VALUES (?, ?, ?, ?, ?, ?, ?)");
                            
                            $val_save_user = $save_user->execute([$nombre_completo, $usuario_mayus, $pass_hash, $email, $tipo_usuario, $admin, $fecha_hora_registro]);

                            if ($val_save_user) {
                                //! Una vez validada la creación del nuevo usuario, en caso de que el TIPO DE USUARIO sea "T" se le agrega a la tabla de "usuario_devecchi"
                                if ($tipo_usuario == 'T') {
                                    $save_user_keys = $con->prepare("INSERT INTO $dvi (nombre_usuario, clave, nombre, email_devecchi)
                                                                                VALUES (?, ?, ?, ?)");

                                    $val_sav_user_keys = $save_user_keys->execute([$usuario_mayus, $pass_hash, $nombre_completo, $email]);

                                    if ($val_sav_user_keys) {
                                        require '../../functions/drop_con.php';
                                        require '../../functions/conex_serv.php';
                                        // Registro en auditlog
                                        $movimiento = utf8_decode('El usuario '.$admin.' dio de alta al nuevo usuario: '.$usuario_mayus.' con el tipo de usuario '.$tipo_usuario.' el '.$fecha_hora_registro.' que de igual forma funciona para las claves de las tarjetas de equipos');
                                        $url = $_SERVER['PHP_SELF'];
                                        $database = 'veco_sims_devecchi';
                                        $save_move = $con->prepare("INSERT INTO $log (movimiento, link, ddbb, usuario_movimiento, fecha_hora)
                                                            VALUES (?, ?, ?, ?, ?)");
                                        $val_save_move = $save_move->execute([$movimiento, $url, $database, $admin, $fecha_hora_registro]);

                                        if ($val_save_move) {
                                            mail($email, $asunto, $mensaje, $cabecera);
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
                                    require '../../functions/drop_con.php';
                                    require '../../functions/conex_serv.php';
                                    // Registro en auditlog
                                    $movimiento = utf8_decode('El usuario '.$admin.' dio de alta al nuevo usuario: '.$usuario_mayus.' con el tipo de usuario '.$tipo_usuario.' el '.$fecha_hora_registro.'');
                                    $url = $_SERVER['PHP_SELF'];
                                    $database = 'veco_sims_devecchi';
                                    $save_move = $con->prepare("INSERT INTO $log (movimiento, link, ddbb, usuario_movimiento, fecha_hora)
                                                        VALUES (?, ?, ?, ?, ?)");
                                    $val_save_move = $save_move->execute([$movimiento, $url, $database, $admin, $fecha_hora_registro]);

                                    if ($val_save_move) {
                                        mail($email, $asunto, $mensaje, $cabecera);
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
                                }
                            } else {
                                mensaje_error();
                                redirect_failed();
                                die();
                            }
                        //}
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
       <a href="usuarios_sis.php"><button type="submit" value="Regresar"class="btn btn-primary" style="text-align:center"><i class="fa fa-reply"></i>&nbsp;&nbsp;Volver</button></a>
	   </tr>
	</td>
	   </table>
		<!--************************************ Page content******************************-->
		<div class="container">
          <div class="row">
            <div class="col-sm-12">
              <div class="page-header2">
                <h1 class="animated lightSpeedIn">Nuevo Usuario</h1>
                <span class="label label-danger"></span>
                <p class="pull-right text-primary">
               </p>
              </div>
            </div>
          </div>
        </div>

<div class="container">
  <div class="row">
    <div class="col-sm-8">
      <div class="panel panel-primary">
        <div class="panel-heading text-center"><strong>¡IMPORTANTE!</strong> Los usuarios de tipo CLIENTE son generados automáticamente por el sistema al crear una nueva empresa</div>
        <div class="panel-body">
            <form role="form" action="" method="POST">
			       
                       
						<div class="form-group">
                          <label  class="col-sm-222 control-label"><i class="fa fa-user-circle" aria-hidden="true"></i> Nombre de Usuario: <i>(Mínimo 10 caracteres)</i></label>
                          <div class="col-sm-110">
                              <div class='input-group'>
								  <input type="text" class="form-control" name="usuario" placeholder="Por ejemplo: OMARCRT" pattern=".{10,}" maxlength="11" required onkeyup="this.value = this.value.toUpperCase();">
								  <span class="input-group-addon"><i class="fa fa-pencil-square-o"></i></span>
                              </div>
                          </div>
                        </div>

                        <div class="form-group">
                          <label  class="col-sm-222 control-label"><i class="fa fa-key" aria-hidden="true"></i> Contraseña: <i>(Mínimo 8 caracteres)</i></label>
                          <div class="col-sm-110">
                              <div class='input-group'>
								  <input type="text" class="form-control" name="clave" placeholder="Por ejemplo: ***************" pattern=".{8,15}" maxlength="15" required>
								  <span class="input-group-addon"><i class="fa fa-pencil-square-o"></i></span>
                              </div>
                          </div>
                        </div>
						
						<div class="form-group">
                            <label class="col-sm-222 control-label"><i class="fa fa-user-circle-o" aria-hidden="true"></i> Nombre Completo: <i>(Utiliza mayúsculas para la primera letra, mínimo 15 caracteres)</i></label>
                            <div class='col-sm-110'>
                                <div class="input-group">
                                   <input class="form-control" placeholder="Por ejemplo: Omar Courtz" type="text" name="nombre_completo" pattern=".{15,}" maxlength="50" required>
								   <span class="input-group-addon"><i class="fa fa-pencil-square-o"></i></span>
                                </div>
                            </div>
                        </div>
						
						 <div class="form-group">
                            <label class="col-sm-222 control-label"><i class="fa fa-envelope" aria-hidden="true"></i> Correo Electrónico:</label>
                            <div class='col-sm-110'>
                                <div class="input-group">
                                   <input class="form-control" type="email" placeholder="Por ejemplo: o.courtz@veco.mx" name="email" required>
								   <span class="input-group-addon"><i class="fa fa-pencil-square-o"></i></span>
                                </div>
                            </div>
                        </div>
						
						<div class="form-group">
                            <label class="col-sm-222 control-label"><i class="fa fa-user-secret" aria-hidden="true"></i> Tipo de Usuario:</label>
                            <div class='col-sm-110'>
                                <div class="input-group">
                                    <select class="form-control" name="tipo_usuario" required>
                                        <option value=""> - Selecciona el tipo de usuario correcto - </option>
                                        <option value="A">Administrador || (Permisos Globales Sistemas)</option>
                                        <option value="G">Gerente</option>
                                        <option value="T">Técnico de Servicios | Usuario Claves Tarjetas</option>
                                    </select>
                                    <span class="input-group-addon"><i class="fa fa-pencil-square-o"></i></span>
                                </div>
                            </div>
                        </div>

                        <!--div class="form-group">
                            <label class="col-sm-222 control-label"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> * Firma Digital: <i>(Solo se admiten archivos en <u>formato PNG</u> con un tamaño <u>máximo de 15 MB</u>)</i></label>
                            <div class='col-sm-110'>
                                <div class="input-group">
                                   <input type="file" accept="image/png" class="form-control" name="firma_doc">
								   <span class="input-group-addon"><i class="fa fa-pencil-square-o"></i></span>
                                </div>
                            </div>
                        </div>

                        <label class="col-sm-222 control-label">* <i>Campos opcionales</i></label-->
						
						<div class="form-group">
                          <div class="col-sm-offset-2 col-sm-10 text-center">
                              <center><input type="submit" class="btn btn-success" name="crear_usuario" value="Crear Usuario"></center>
                          </div>
                        </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<?php
 include '../../assets/footer.php';
}else{
	header('Location: index.php');
}
?>
</body>
</html>