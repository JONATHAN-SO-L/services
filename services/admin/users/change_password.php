<?php
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);   

if( $_SESSION['nombre']!="" && $_SESSION['clave']!="" && $_SESSION['tipo']=="admin"){
    include '../../assets/admin/navbar2.php';
    include '../../assets/admin/links2.php';
    
    function mensaje_error() {
        echo '<div class="alert alert-danger alert-dismissible fade in col-sm-3 animated bounceInDown" role="alert" style="position:fixed; top:70px; right:10px; z-index:10;">
            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
            <h4 class="text-center"><strong>OCURRIÓ UN ERROR</strong></h4>
            <p class="text-center">
                No fue posible cambiar la contraseña del usuario, por favor inténtalo de nuevo o contacta al Soporte Técnico.
            </p>
            </div>'; 
    }

    function mensaje_ayuda(){
        echo '
            <div class="alert alert-success alert-dismissible fade in col-sm-3 animated bounceInDown" role="alert" style="position:fixed; top:70px; right:10px; z-index:10;"> 
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
            <h4 class="text-center"><strong>MODIFICACIÓN EXITOSA</strong></h4>
            <p class="text-center">
            Se cambió correctamente la contraseña del usuario.
            </p>
            </div>
        ';
    }

    function redirect_failed() {
        echo '
        <div class="container" style="margin-left: 40%">
            <img src="../../assets/img/loading_dvi.gif" height="40%" weight="40%">
            <br>
            <a href="change_password.php" class="btn btn-sm btn-danger" style="margin-left: 15%">Regresar</a>
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

    require '../../functions/conex.php';
    $sis = 'usuario_sis';
    $dvi = 'usuario_devecchi';
    $adm = 'administrador';
    $log = 'auditlog';

    if (isset($_POST['cambiar_pass'])) {
        // Recepción de datos
        $usuario = $_POST['usuario'];
        $clave = $_POST['clave'];

        // Información para auditlog
		$admin = $_SESSION['nombre_completo'];
		require '../../assets/timezone.php';
		$fecha_hora_modificacion = date("d/m/Y H:i:s");

        //* Obtiene el correo del usuario para las notificaciones
        $s_user_unique = $con->prepare("SELECT usuario, nombre_completo, email, tipo_usuario FROM $sis WHERE usuario = :usuario");
        $s_user_unique->bindValue(':usuario', $usuario);
        $s_user_unique->setFetchMode(PDO::FETCH_OBJ);
        $s_user_unique->execute();

        $f_user_unique = $s_user_unique->fetchAll();

        if ($s_user_unique -> rowCount() > 0) {
            foreach ($f_user_unique as $usr_u_found) {
                $usuario_f = $usr_u_found -> usuario;
                $nombre_completo_f = $usr_u_found -> nombre_completo;
                $email_f = $usr_u_found -> email;
                $tipo_usuario_f = $usr_u_found -> tipo_usuario;
            }

            /****************
            SALIDA DE CORREOS
            ****************/
            //TODOS: Se lanza correo a usuario de cambio de contrasñe en sistema
            $website = 'https://veco.lat';
            $soporte = 'https://veco.lat/soporte.php';
            $cabecera = 'From: VecoLAT <no-reply@veco.lat>';
            $asunto = utf8_decode('VecoLAT | Cambio de Contraseña');
            $mensaje = utf8_decode("Estimado(a) ".$nombre_completo.", le informamos que su contraseña de la Plataforma VecoLAT fue modificada, a continuación le compartimos sus nuevas credenciales de acceso.\r\n\r\n
            Usuario: ".$usuario_f."\r\n
            Contraseña: ".$clave."\r\n
            Enlace: ".$website."\r\n\r\n
            Ante cualquier duda o pregunta, por favor contácte con el Área de Sistemas: ".$soporte."\r\n
            Saludos Cordiales.");

            //! Se encripta la nueva contraseña
            $pass_hash = md5($clave);

            switch ($tipo_usuario_f) {
                case 'A':
                    $update_pass_admin = $con->prepare("UPDATE $sis SET clave = ?, modifica_data = ?, fecha_hora_modificacion = ?
                                                                    WHERE usuario = ?");

                    $val_update_pass_admin = $update_pass_admin->execute([$pass_hash, $admin, $fecha_hora_modificacion, $usuario]);

                    if ($val_update_pass_admin) {
                        require '../../functions/drop_con.php';
                        require '../../functions/conex_serv.php';
                        // Registro en auditlog
                        $movimiento = utf8_encode('El usuario '.$admin.' modificó la contraseña del usuario: '.$usuario_f.' con el tipo de usuario '.$tipo_usuario.' el '.$fecha_hora_modificacion.'');
                        $url = $_SERVER['PHP_SELF'];
                        $database = 'veco_sims_devecchi';
                        $save_move = $con->prepare("INSERT INTO $log (movimiento, link, ddbb, usuario_movimiento, fecha_hora)
                                            VALUES (?, ?, ?, ?, ?)");
                        $val_save_move = $save_move->execute([$movimiento, $url, $database, $admin, $fecha_hora_modificacion]);

                        if ($val_save_move) {
                            mail($email_f, $asunto, $mensaje, $cabecera);
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
                break;

                case 'G':
                    $update_pass_manager = $con->prepare("UPDATE $sis SET clave = ?, modifica_data = ?, fecha_hora_modificacion = ?
                                                                    WHERE usuario = ?");

                    $val_update_pass_manager = $update_pass_manager->execute([$pass_hash, $admin, $fecha_hora_modificacion, $usuario]);

                    if ($val_update_pass_manager) {
                        require '../../functions/drop_con.php';
                        require '../../functions/conex_serv.php';
                        // Registro en auditlog
                        $movimiento = utf8_encode('El usuario '.$admin.' modificó la contraseña del usuario: '.$usuario_f.' con el tipo de usuario '.$tipo_usuario.' el '.$fecha_hora_modificacion.'');
                        $url = $_SERVER['PHP_SELF'];
                        $database = 'veco_sims_devecchi';
                        $save_move = $con->prepare("INSERT INTO $log (movimiento, link, ddbb, usuario_movimiento, fecha_hora)
                                            VALUES (?, ?, ?, ?, ?)");
                        $val_save_move = $save_move->execute([$movimiento, $url, $database, $admin, $fecha_hora_modificacion]);

                        if ($val_save_move) {
                            require '../../functions/drop_con.php';
                            require '../../functions/conex.php';
                             //! Se actualiza la contraseña de la tabla ADMIN en caso de que exista el usuario
                            $update_pass_manager_admin = $con->prepare("UPDATE $adm SET clave = ?
                                                                                    WHERE nombre_admin = ?");
                            
                            $val_pass_manager_admin = $update_pass_manager_admin->execute([$pass_hash, $usuario]);

                            if ($val_pass_manager_admin) {
                                require '../../functions/drop_con.php';
                                require '../../functions/conex_serv.php';
                                // Registro en auditlog
                                $movimiento = utf8_encode('El usuario '.$admin.' modificó la contraseña del usuario: '.$usuario_f.' con el tipo de usuario '.$tipo_usuario.' en la tabla de ADMIN el '.$fecha_hora_modificacion.'');
                                $url = $_SERVER['PHP_SELF'];
                                $database = 'veco_sims_devecchi';
                                $save_move2 = $con->prepare("INSERT INTO $log (movimiento, link, ddbb, usuario_movimiento, fecha_hora)
                                                    VALUES (?, ?, ?, ?, ?)");
                                $val_save_move2 = $save_move2->execute([$movimiento, $url, $database, $admin, $fecha_hora_modificacion]);

                                if ($val_save_move2) {
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
                                mail($email_f, $asunto, $mensaje, $cabecera);
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
                break;

                case 'T':
                    $update_pass_tech = $con->prepare("UPDATE $sis SET clave = ?, modifica_data = ?, fecha_hora_modificacion = ?
                                                                    WHERE usuario = ?");

                    $val_update_pass_tech = $update_pass_tech->execute([$pass_hash, $admin, $fecha_hora_modificacion, $usuario]);

                    if ($val_update_pass_tech) {
                        require '../../functions/drop_con.php';
                        require '../../functions/conex_serv.php';
                        // Registro en auditlog
                        $movimiento = utf8_encode('El usuario '.$admin.' modificó la contraseña del usuario: '.$usuario_f.' con el tipo de usuario '.$tipo_usuario.' el '.$fecha_hora_modificacion.'');
                        $url = $_SERVER['PHP_SELF'];
                        $database = 'veco_sims_devecchi';
                        $save_move = $con->prepare("INSERT INTO $log (movimiento, link, ddbb, usuario_movimiento, fecha_hora)
                                            VALUES (?, ?, ?, ?, ?)");
                        $val_save_move = $save_move->execute([$movimiento, $url, $database, $admin, $fecha_hora_modificacion]);

                        if ($val_save_move) {
                            require '../../functions/drop_con.php';
                            require '../../functions/conex.php';
                            //! Se actualiza la contraseña de la tabla DVI en caso de que exista el usuario
                            $update_pass_manager_admin = $con->prepare("UPDATE $dvi SET clave = ?
                                                                                    WHERE nombre_usuario = ?");
                            
                            $val_pass_manager_admin = $update_pass_manager_admin->execute([$pass_hash, $usuario]);

                            if ($val_pass_manager_admin) {
                                require '../../functions/drop_con.php';
                                require '../../functions/conex_serv.php';
                                // Registro en auditlog
                                $movimiento = utf8_encode('El usuario '.$admin.' modificó la contraseña del usuario: '.$usuario_f.' con el tipo de usuario '.$tipo_usuario.' en la tabla de DVI el '.$fecha_hora_modificacion.'');
                                $url = $_SERVER['PHP_SELF'];
                                $database = 'veco_sims_devecchi';
                                $save_move2 = $con->prepare("INSERT INTO $log (movimiento, link, ddbb, usuario_movimiento, fecha_hora)
                                                    VALUES (?, ?, ?, ?, ?)");
                                $val_save_move2 = $save_move2->execute([$movimiento, $url, $database, $admin, $fecha_hora_modificacion]);

                                if ($val_save_move2) {
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
                                mail($email_f, $asunto, $mensaje, $cabecera);
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
                break;

                case 'C':
                    $update_pass_client = $con->prepare("UPDATE $sis SET clave = ?, modifica_data = ?, fecha_hora_modificacion = ?
                                                                    WHERE usuario = ?");

                    $val_update_pass_client = $update_pass_client->execute([$pass_hash, $admin, $fecha_hora_modificacion, $usuario]);

                    if ($val_update_pass_client) {
                        require '../../functions/drop_con.php';
                        require '../../functions/conex_serv.php';
                        // Registro en auditlog
                        $movimiento = utf8_encode('El usuario '.$admin.' modificó la contraseña del usuario: '.$usuario_f.' con el tipo de usuario '.$tipo_usuario.' el '.$fecha_hora_modificacion.'');
                        $url = $_SERVER['PHP_SELF'];
                        $database = 'veco_sims_devecchi';
                        $save_move = $con->prepare("INSERT INTO $log (movimiento, link, ddbb, usuario_movimiento, fecha_hora)
                                            VALUES (?, ?, ?, ?, ?)");
                        $val_save_move = $save_move->execute([$movimiento, $url, $database, $admin, $fecha_hora_modificacion]);

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
                break;
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
       <a href="usuarios_sis.php"><button type="submit" value="Regresar"class="btn btn-primary" style="text-align:center"><i class="fa fa-reply"></i>&nbsp;&nbsp;Volver</button></a>
	   </tr>
	</td>
	   </table>
		<!--************************************ Page content******************************-->
		<div class="container">
          <div class="row">
            <div class="col-sm-12">
              <div class="page-header2">
                <h1 class="animated lightSpeedIn"><strong>Cambiar contraseña</strong></h1>
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
      <div class="panel panel-danger">
        <div class="panel-heading text-center"><strong>¡IMPORTANTE!</strong> Selecciona al usuario correcto al que se desea cambiar la contraseña</div>
        <div class="panel-body">
            <form role="form" action="" method="POST">
			       
                       
						<div class="form-group">
                          <label  class="col-sm-222 control-label"><i class="fa fa-user-circle" aria-hidden="true"></i> Nombre de Usuario:</label>
                          <div class="col-sm-110">
                              <div class='input-group'>
                                <select class="form-control" name="usuario" required>
                                    <option value=""> - Selecciona al usuario - </option>
                                    <?php
                                    $s_users = $con->prepare("SELECT usuario, nombre_completo, email FROM $sis ORDER BY usuario ASC");
                                    $s_users->setFetchMode(PDO::FETCH_OBJ);
                                    $s_users->execute();

                                    $f_users = $s_users->fetchAll();

                                    if ($s_users -> rowCount() > 0) {
                                        foreach ($f_users as $users_found) {
                                            $usuario = $users_found -> usuario;
                                            $nombre_completo = $users_found -> nombre_completo;
                                            $email = $users_found -> email;

                                            echo '<option value="'.$usuario.'">'.$usuario.' - '.$nombre_completo.'</option>';
                                        }
                                    } else {
                                        mensaje_error();
                                        redirect_failed();
                                        die();
                                    }
                                    ?>
                                </select>
								  <span class="input-group-addon"><i class="fa fa-eye" aria-hidden="true"></i></span>
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
                          <div class="col-sm-offset-2 col-sm-10 text-center">
                              <center><input type="submit" class="btn btn-danger" name="cambiar_pass" value="Cambiar"></center>
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