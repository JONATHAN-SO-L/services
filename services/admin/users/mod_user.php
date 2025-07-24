<?php
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);   

if( $_SESSION['nombre']!="" && $_SESSION['clave']!="" && $_SESSION['tipo']=="admin"){
    include '../../assets/admin/navbar2.php';
    include '../../assets/admin/links2.php';

    $id_usuario = $_SERVER['QUERY_STRING'];
    
    function mensaje_error() {
        echo '<div class="alert alert-danger alert-dismissible fade in col-sm-3 animated bounceInDown" role="alert" style="position:fixed; top:70px; right:10px; z-index:10;">
            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
            <h4 class="text-center"><strong>OCURRIÓ UN ERROR</strong></h4>
            <p class="text-center">
                No fue posible modificar el usuario, por favor inténtalo de nuevo o contacta al Soporte Técnico.
            </p>
            </div>'; 
    }

    function mensaje_ayuda(){
        echo '
            <div class="alert alert-success alert-dismissible fade in col-sm-3 animated bounceInDown" role="alert" style="position:fixed; top:70px; right:10px; z-index:10;"> 
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
            <h4 class="text-center"><strong>REGISTRO EXITOSO</strong></h4>
            <p class="text-center">
            Se modificó correctamente el usuario.
            </p>
            </div>
        ';
}

function redirect_failed($id_usuario) {
    echo '
    <div class="container" style="margin-left: 40%">
        <img src="../../assets/img/loading_dvi.gif" height="40%" weight="40%">
        <br>
        <a href="mod_user.php?'.$id_usuario.'" class="btn btn-sm btn-danger" style="margin-left: 15%">Regresar</a>
    </div>';
}

function redirect_failed1() {
    echo '
    <div class="container" style="margin-left: 40%">
        <img src="../../assets/img/loading_dvi.gif" height="40%" weight="40%">
        <br>
        <a href="usuarios_sis.php" class="btn btn-sm btn-danger" style="margin-left: 15%">Regresar</a>
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

// Recuperación de información del usuario
require '../../functions/conex.php';
$sis = 'usuario_sis';
$s_user = $con -> prepare("SELECT * FROM $sis WHERE id_usuario = :id_usuario");
$s_user->bindValue(':id_usuario', $id_usuario);
$s_user->setFetchMode(PDO::FETCH_OBJ);
$s_user->execute();

$f_user = $s_user->fetchAll();

if ($s_user -> rowCount() > 0) {
    foreach ($f_user as $user_found) {
        $id_usuario_recop = $user_found -> id_usuario;
        $nombre_completo = $user_found -> nombre_completo;
        $usuario = $user_found -> usuario;
        $clave = $user_found -> clave;
        $email = $user_found -> email;
        $tipo_usuario = $user_found -> tipo_usuario;

        switch ($tipo_usuario) {
            case 'A':
                $type_info = 'Administrador || (Permisos Globales Sistemas)';
            break;

            case 'G':
                $type_info = 'Gerente';
            break;

            case 'T':
                $type_info = 'Técnico de Servicios | Usuario Claves Tarjetas';
            break;

            case 'C':
                $type_info = 'Cliente';
            break;
        }
    }
} else {
    mensaje_error();
    redirect_failed1();
    die();
}

if (isset($_POST['modificar_usuario'])) {
    // Recepción de datos del formulario
    $usuario = $_POST['usuario'];
    $nombre_completo = $_POST['nombre_completo'];
    $email = $_POST['email'];
    $tipo_usuario = $_POST['tipo_usuario'];

    // Información para auditlog
    $dvi = 'usuario_devecchi';
    $admin_old = 'administrador';
    $log = 'auditlog';
    $admin = $_SESSION['nombre_completo'];
    require '../../assets/timezone.php';
    $fecha_hora_modificacion = date("d/m/Y H:i:s");

    //* Se contabilizan los caracteres de las variables ingresadas
    $min_user = strlen($usuario);
    $min_name = strlen($nombre_completo);

    if ($min_user >= 10) {
        if ($min_name >=15) {
            $update_user = $con->prepare("UPDATE $sis SET nombre_completo = ?, email = ?, tipo_usuario = ?, modifica_data = ?, fecha_hora_modificacion = ?
                                                    WHERE id_usuario = ?");

            $val_update_user = $update_user->execute([$nombre_completo, $email, $tipo_usuario, $admin, $fecha_hora_modificacion, $id_usuario]);

            if ($val_update_user) {
                switch ($tipo_usuario) {
                    case 'G':
                        //! Valida que el usuario ya exista en la tabla de ADMIN
                        $s_admin = $con->prepare("SELECT nombre_admin, nombre, email_admin FROM $admin_old WHERE nombre_admin = :usuario");
                        $s_admin->bindValue(':usuario', $usuario);
                        $s_admin->setFetchMode(PDO::FETCH_OBJ);
                        $s_admin->execute();
                        
                        $f_admin = $s_admin->fetchAll();

                        if ($s_admin -> rowCount() > 0) {
                            foreach ($f_admin as $admin_user) {
                                $nombre_admin = $admin_user -> nombre_admin;
                                $nombre = $admin_user -> nombre;
                                $email_admin = $admin_user -> email_admin;
                            }

                            $save_admin = $con->prepare("UPDATE $admin_old SET nombre = ?, email_admin = ?
                                                                        WHERE nombre_admin = ?");

                            $val_save_admin = $save_admin->execute([$nombre_completo, $email, $usuario]);

                            if ($val_save_admin) {
                                require '../../functions/drop_con.php';
                                require '../../functions/conex_serv.php';
                                // Registro en auditlog
                                $movimiento = utf8_encode('El usuario '.$admin.' modificó al usuario: '.$usuario.' con el tipo de usuario '.$tipo_usuario.' el '.$fecha_hora_modificacion.'');
                                $url = $_SERVER['PHP_SELF'].'?'.$id_usuario;
                                $database = 'veco_sims_devecchi';
                                $save_move = $con->prepare("INSERT INTO $log (movimiento, link, ddbb, usuario_movimiento, fecha_hora)
                                                    VALUES (?, ?, ?, ?, ?)");
                                $val_save_move = $save_move->execute([$movimiento, $url, $database, $admin, $fecha_hora_modificacion]);

                                if ($val_save_move) {
                                    require '../../functions/drop_con.php';
                                    mensaje_ayuda();
                                    redirect_success();
                                    die();
                                } else {
                                    require '../../functions/drop_con.php';
                                    mensaje_error();
                                    redirect_failed($id_usuario);
                                    die();
                                }
                            } else {
                                mensaje_error();
                                redirect_failed($id_usuario);
                                die();
                            }
                        } else {
                            //! Si el usuario no existe en la base de datos de ADMIN, se elimina si existe en la tabla de DVI
                            $s_dvi = $con->prepare("SELECT nombre_usuario, nombre, email_devecchi FROM $dvi WHERE nombre_usuario = :usuario");
                            $s_dvi->bindValue(':usuario', $usuario);
                            $s_dvi->setFetchMode(PDO::FETCH_OBJ);
                            $s_dvi->execute();
                            
                            $f_dvi = $s_dvi->fetchAll();

                            if ($s_dvi -> rowCount() > 0) {
                                foreach ($f_dvi as $found_dvi) {
                                    $nombre_usuario = $found_dvi -> nombre_usuario;
                                    $nombre = $found_dvi -> nombre;
                                    $email_devecchi = $found_dvi -> email_devecchi;
                                }
                                $delete_dvi = $con->prepare("DELETE FROM $dvi WHERE nombre_usuario = ?");

                                $val_delete_dvi = $delete_dvi->execute([$usuario]);

                                if ($val_delete_dvi) {
                                    //! Se crea el usuario en la tabla ADMIN
                                    $save_admin = $con->prepare("INSERT INTO $admin_old (nombre_admin, clave, nombre, email_admin)
                                                                                VALUES (?, ?, ?, ?)");

                                    $val_save_admin = $save_admin->execute([$usuario, $clave, $nombre_completo, $email]);

                                    if ($val_save_admin) {
                                        require '../../functions/drop_con.php';
                                        require '../../functions/conex_serv.php';
                                        // Registro en auditlog
                                        $movimiento = utf8_encode('El usuario '.$admin.' eliminó al usuario: '.$usuario.' con el tipo de usuario '.$tipo_usuario.' de la tabla de usuarios TÉCNICOS y lo dio de alta en la tabla de usuarios ADMIN el '.$fecha_hora_modificacion.'');
                                        $url = $_SERVER['PHP_SELF'].'?'.$id_usuario;
                                        $database = 'veco_sims_devecchi';
                                        $save_move = $con->prepare("INSERT INTO $log (movimiento, link, ddbb, usuario_movimiento, fecha_hora)
                                                            VALUES (?, ?, ?, ?, ?)");
                                        $val_save_move = $save_move->execute([$movimiento, $url, $database, $admin, $fecha_hora_modificacion]);

                                        if ($val_save_move) {
                                            require '../../functions/drop_con.php';
                                            mensaje_ayuda();
                                            redirect_success();
                                            die();
                                        } else {
                                            require '../../functions/drop_con.php';
                                            mensaje_error();
                                            redirect_failed($id_usuario);
                                            die();
                                        }
                                    } else {
                                        mensaje_error();
                                        redirect_failed($id_usuario);
                                    }
                                } else {
                                    mensaje_error();
                                    redirect_failed($id_usuario);
                                    die();
                                }
                            } else {
                                //! Se crea el usuario en la tabla ADMIN
                                $save_usr_adm = $con->prepare("INSERT INTO $admin_old (nombre_admin, clave, nombre, email_admin)
                                                                                VALUES (?, ?, ?, ?)");

                                $val_save_usr_adm = $save_usr_adm->execute([$usuario,$clave, $nombre_completo, $email]);

                                if ($val_save_usr_adm) {
                                    require '../../functions/drop_con.php';
                                    require '../../functions/conex_serv.php';
                                    // Registro en auditlog
                                    $movimiento = utf8_encode('El usuario '.$admin.' eliminó al usuario: '.$usuario.' con el tipo de usuario '.$tipo_usuario.' de la tabla de usuarios TÉCNICOS y lo dio de alta en la tabla de usuarios ADMIN el '.$fecha_hora_modificacion.'');
                                    $url = $_SERVER['PHP_SELF'].'?'.$id_usuario;
                                    $database = 'veco_sims_devecchi';
                                    $save_move = $con->prepare("INSERT INTO $log (movimiento, link, ddbb, usuario_movimiento, fecha_hora)
                                                        VALUES (?, ?, ?, ?, ?)");
                                    $val_save_move = $save_move->execute([$movimiento, $url, $database, $admin, $fecha_hora_modificacion]);

                                    if ($val_save_move) {
                                        require '../../functions/drop_con.php';
                                        mensaje_ayuda();
                                        redirect_success();
                                        die();
                                    } else {
                                        require '../../functions/drop_con.php';
                                        mensaje_error();
                                        redirect_failed($id_usuario);
                                        die();
                                    }
                                } else {
                                    mensaje_error();
                                    redirect_failed($id_usuario);
                                    die();
                                }
                            }
                        }
                    break;

                    case 'T':
                        //! Valida que el usuario ya exista en la tabla de DVI
                        $s_user_dvi = $con->prepare("SELECT nombre_usuario, nombre, email_devecchi FROM usuario_devecchi WHERE nombre_usuario = :usuario");
                        $s_user_dvi->bindValue(':usuario', $usuario);
                        $s_user_dvi->setFetchMode(PDO::FETCH_OBJ);
                        $s_user_dvi->execute();
                        
                        $f_user_dvi = $s_user_dvi->fetchAll();

                        if ($s_user_dvi -> rowCount() > 0) {
                            foreach ($f_user_dvi as $dvi_user) {
                                $nombre = $dvi_user -> nombre;
                                $email_devecchi = $dvi_user -> email_devecchi;
                            }

                            $update_user_keys = $con->prepare("UPDATE $dvi SET nombre = ?, email_devecchi = ?
                                                                    WHERE nombre_usuario = ?");

                            $val_update_user_keys = $update_user_keys->execute([$nombre_completo, $email, $usuario]);

                            if ($val_update_user_keys) {
                                require '../../functions/drop_con.php';
                                require '../../functions/conex_serv.php';
                                // Registro en auditlog
                                $movimiento = utf8_encode('El usuario '.$admin.' modificó al usuario: '.$usuario.' con el tipo de usuario '.$tipo_usuario.' el '.$fecha_hora_modificacion.' que de igual forma funciona para las claves de las tarjetas de equipos');
                                $url = $_SERVER['PHP_SELF'].'?'.$id_usuario;
                                $database = 'veco_sims_devecchi';
                                $save_move = $con->prepare("INSERT INTO $log (movimiento, link, ddbb, usuario_movimiento, fecha_hora)
                                                    VALUES (?, ?, ?, ?, ?)");
                                $val_save_move = $save_move->execute([$movimiento, $url, $database, $admin, $fecha_hora_modificacion]);

                                if ($val_save_move) {
                                    require '../../functions/drop_con.php';
                                    mensaje_ayuda();
                                    redirect_success();
                                    die();
                                } else {
                                    require '../../functions/drop_con.php';
                                    mensaje_error();
                                    redirect_failed($id_usuario);
                                    die();
                                }
                            } else {
                                mensaje_error();
                                redirect_failed($id_usuario);
                                die();
                            }
                        } else {
                            //! Si el usuario no existe en la base de datos de DVI, se elimina si existe en la tabla de ADMIN
                            $s_user_adm = $con->prepare("SELECT nombre_admin, nombre, email_admin FROM $admin_old WHERE nombre_admin = :usuario");
                            $s_user_adm->bindValue(':usuario', $usuario);
                            $s_user_adm->setFetchMode(PDO::FETCH_OBJ);
                            $s_user_adm->execute();
                            
                            $f_user_adm = $s_user_adm->fetchAll();

                            if ($s_user_adm -> rowCount() > 0) {
                                foreach ($f_user_adm as $adm_usr) {
                                    $nombre_admin = $adm_usr-> nombre_admin;
                                    $nombre = $adm_usr-> nombre;
                                    $email_admin = $adm_usr-> email_admin;
                                }

                                $delete_usr = $con->prepare("DELETE FROM $admin_old WHERE nombre_admin = ?");

                                $val_delete_usr = $delete_usr->execute([$nombre_admin]);

                                if ($val_delete_usr) {
                                    //! Se crea el usuario en la tabla de DVI
                                    $save_usr_dvi = $con->prepare("INSERT INTO $dvi (nombre_usuario, clave, nombre, email_devecchi)
                                                                            VALUES (?, ?, ?, ?)");

                                    $val_save_usr_dvi = $save_usr_dvi->execute([$usuario, $clave, $nombre_completo, $email]);

                                    if ($val_save_usr_dvi) {
                                        require '../../functions/drop_con.php';
                                        require '../../functions/conex_serv.php';
                                        // Registro en auditlog
                                        $movimiento = utf8_encode('El usuario '.$admin.' eliminó al usuario: '.$usuario.' con el tipo de usuario '.$tipo_usuario.' de la tabla de usuarios GERENTES y lo dio de alta en la tabla de usuarios DVI el '.$fecha_hora_modificacion.' que de igual forma funciona para las claves de las tarjetas de equipos');
                                        $url = $_SERVER['PHP_SELF'].'?'.$id_usuario;
                                        $database = 'veco_sims_devecchi';
                                        $save_move = $con->prepare("INSERT INTO $log (movimiento, link, ddbb, usuario_movimiento, fecha_hora)
                                                            VALUES (?, ?, ?, ?, ?)");
                                        $val_save_move = $save_move->execute([$movimiento, $url, $database, $admin, $fecha_hora_modificacion]);

                                        if ($val_save_move) {
                                            require '../../functions/drop_con.php';
                                            mensaje_ayuda();
                                            redirect_success();
                                            die();
                                        } else {
                                            require '../../functions/drop_con.php';
                                            mensaje_error();
                                            redirect_failed($id_usuario);
                                            die();
                                        }
                                    } else {
                                        mensaje_error();
                                        redirect_failed($id_usuario);
                                        die();
                                    }
                                } else {
                                    mensaje_error();
                                    redirect_failed($id_usuario);
                                    die();
                                }
                            } else {
                                //! Se crea el usuario en la tabla de DVI
                                $save_usr_dvi = $con->prepare("INSERT INTO $dvi (nombre_usuario, clave, nombre, email_devecchi)
                                                                        VALUES (?, ?, ?, ?)");

                                $val_save_usr_dvi = $save_usr_dvi->execute([$usuario, $clave, $nombre_completo, $email]);

                                if ($val_save_usr_dvi) {
                                    require '../../functions/drop_con.php';
                                    require '../../functions/conex_serv.php';
                                    // Registro en auditlog
                                    $movimiento = utf8_encode('El usuario '.$admin.' modificó y dio de alta en la tabla de usuarios DVI el usuario '.$usuario.' el '.$fecha_hora_modificacion.' que de igual forma funciona para las claves de las tarjetas de equipos');
                                    $url = $_SERVER['PHP_SELF'].'?'.$id_usuario;
                                    $database = 'veco_sims_devecchi';
                                    $save_move = $con->prepare("INSERT INTO $log (movimiento, link, ddbb, usuario_movimiento, fecha_hora)
                                                        VALUES (?, ?, ?, ?, ?)");
                                    $val_save_move = $save_move->execute([$movimiento, $url, $database, $admin, $fecha_hora_modificacion]);

                                    if ($val_save_move) {
                                        require '../../functions/drop_con.php';
                                        mensaje_ayuda();
                                        redirect_success();
                                        die();
                                    } else {
                                        require '../../functions/drop_con.php';
                                        mensaje_error();
                                        redirect_failed($id_usuario);
                                        die();
                                    }
                                } else {
                                    mensaje_error();
                                    redirect_failed($id_usuario);
                                    die();
                                }
                            }
                        }
                    break;
                    
                    default:
                        require '../../functions/drop_con.php';
                        require '../../functions/conex_serv.php';
                        // Registro en auditlog
                        $movimiento = utf8_encode('El usuario '.$admin.' modificó el usuario: '.$usuario.' con el tipo de usuario '.$tipo_usuario.' el '.$fecha_hora_modificacion.'');
                        $url = $_SERVER['PHP_SELF'];
                        $database = 'veco_sims_devecchi';
                        $save_move = $con->prepare("INSERT INTO $log (movimiento, link, ddbb, usuario_movimiento, fecha_hora)
                                            VALUES (?, ?, ?, ?, ?)");
                        $val_save_move = $save_move->execute([$movimiento, $url, $database, $admin, $fecha_hora_modificacion]);

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
                    break;
                }
            } else {
                mensaje_error();
                redirect_failed($id_usuario);
                die();
            }
        } else {
            mensaje_error();
            redirect_failed($id_usuario);
            die();
        }
    } else {
        mensaje_error();
        redirect_failed($id_usuario);
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
       <a href="usuarios_sis.php" ><button type="submit" value="Regresar"class="btn btn-primary" style="text-align:center"><i class="fa fa-reply"></i>&nbsp;&nbsp;Volver</button></a>
	   </tr>
	</td>
	   </table>
		<!--************************************ Page content******************************-->
		<div class="container">
          <div class="row">
            <div class="col-sm-12">
              <div class="page-header2">
                <h1 class="animated lightSpeedIn">Modificar Usuario: <strong><?php echo $usuario; ?></strong></h1>
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
      <div class="panel panel-warning">
        <div class="panel-heading text-center">Para modificar al usuario seleccionado es importante que valides la información debajo</div>
        <div class="panel-body">
            <form role="form" action="" method="POST">
			       
                       
						<div class="form-group">
                          <label  class="col-sm-222 control-label"><i class="fa fa-user-circle" aria-hidden="true"></i> Nombre de Usuario: <i>(Mínimo 10 caracteres)</i></label>
                          <div class="col-sm-110">
                              <div class='input-group'>
								  <input type="text" class="form-control" name="usuario" placeholder="Por ejemplo: OMARCRT" pattern=".{10,}" maxlength="11" required onkeyup="this.value = this.value.toUpperCase();" value="<?php echo $usuario; ?>" readonly>
								  <span class="input-group-addon"><i class="fa fa-pencil-square-o"></i></span>
                              </div>
                          </div>
                        </div>
						
						<div class="form-group">
                            <label class="col-sm-222 control-label"><i class="fa fa-user-circle-o" aria-hidden="true"></i> Nombre Completo: <i>(Utiliza mayúsculas para la primera letra, mínimo 15 caracteres)</i></label>
                            <div class='col-sm-110'>
                                <div class="input-group">
                                   <input class="form-control" placeholder="Por ejemplo: Omar Courtz" type="text" name="nombre_completo" pattern=".{15,}" maxlength="50" value="<?php echo $nombre_completo; ?>" required>
								   <span class="input-group-addon"><i class="fa fa-pencil-square-o"></i></span>
                                </div>
                            </div>
                        </div>
						
						 <div class="form-group">
                            <label class="col-sm-222 control-label"><i class="fa fa-envelope" aria-hidden="true"></i> Correo Electrónico:</label>
                            <div class='col-sm-110'>
                                <div class="input-group">
                                   <input class="form-control" type="email" placeholder="Por ejemplo: o.courtz@veco.mx" name="email" value="<?php echo $email; ?>" required>
								   <span class="input-group-addon"><i class="fa fa-pencil-square-o"></i></span>
                                </div>
                            </div>
                        </div>
						
						<div class="form-group">
                            <label class="col-sm-222 control-label"><i class="fa fa-user-secret" aria-hidden="true"></i> Tipo de Usuario:</label>
                            <div class='col-sm-110'>
                                <div class="input-group">
                                    <select class="form-control" name="tipo_usuario" required>
                                        <option value="<?php echo $tipo_usuario; ?>"><?php echo $type_info; ?> - (Actual seleccionado)</option>
                                        <option value="A">Administrador || (Permisos Globales Sistemas)</option>
                                        <option value="G">Gerente</option>
                                        <option value="T">Técnico de Servicios | Usuario Claves Tarjetas</option>
                                        <option value="C">Cliente</option>
                                    </select>
                                    <span class="input-group-addon"><i class="fa fa-pencil-square-o"></i></span>
                                </div>
                            </div>
                        </div>
						
						<div class="form-group">
                          <div class="col-sm-offset-2 col-sm-10 text-center">
                              <center><input type="submit" class="btn btn-danger" name="modificar_usuario" value="Modificar Usuario"></center>
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