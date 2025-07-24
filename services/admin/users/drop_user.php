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
                No fue posible eliminar el usuario, por favor inténtalo de nuevo o contacta al Soporte Técnico.
            </p>
            </div>'; 
    }

    function mensaje_ayuda(){
        echo '
            <div class="alert alert-success alert-dismissible fade in col-sm-3 animated bounceInDown" role="alert" style="position:fixed; top:70px; right:10px; z-index:10;"> 
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
            <h4 class="text-center"><strong>BORRADO EXITOSO</strong></h4>
            <p class="text-center">
            Se eliminó correctamente el usuario.
            </p>
            </div>
        ';
}

function redirect_failed1() {
    echo '
    <div class="container" style="margin-left: 40%">
        <img src="../../assets/img/loading_dvi.gif" height="40%" weight="40%">
        <br>
        <a href="usuarios_sis.php" class="btn btn-sm btn-danger" style="margin-left: 15%">Regresar</a>
    </div>';
}

function redirect_failed($id_usuario) {
    echo '
    <div class="container" style="margin-left: 40%">
        <img src="../../assets/img/loading_dvi.gif" height="40%" weight="40%">
        <br>
        <a href="drop_user.php?'.$id_usuario.'" class="btn btn-sm btn-danger" style="margin-left: 15%">Regresar</a>
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

// Recopilación de información del usuario
require '../../functions/conex.php';
$sis = 'usuario_sis';
$log = 'auditlog';

$s_user = $con->prepare("SELECT * FROM $sis WHERE id_usuario = :id_usuario");
$s_user->bindValue(':id_usuario', $id_usuario);
$s_user->setFetchMode(PDO::FETCH_OBJ);
$s_user->execute();

$f_user = $s_user->fetchAll();

if ($s_user -> rowCount() > 0) {
    foreach ($f_user as $user_found) {
        $nombre_completo = $user_found -> nombre_completo;
        $usuario = $user_found -> usuario;
        $email = $user_found -> email;
        $tipo_usuario = $user_found -> tipo_usuario;
    }
} else {
    mensaje_error();
    redirect_failed1();
    die();
}

if (isset($_POST['eliminar_usuario'])) {
    require '../../functions/conex.php';
    $sis = 'usuario_sis';
    $admin = 'administrador';
    $dvi = 'usuario_devecchi';
    $log = 'auditlog';

    // Recepción del nombre y tipo de usuario
    $usuario = $_POST['usuario'];
    $tipo_usuario = $_POST['tipo_usuario'];

    // Información para auditlog
    $user_admin = $_SESSION['nombre_completo'];
    require '../../assets/timezone.php';
    $fecha_hora_registro = date("d/m/Y H:i:s");

    switch ($tipo_usuario) { //TODO: Dependiendo del tipo de usuario hará las eliminaciones correspondientes
        case 'A':
            $drop_admin = $con->prepare("DELETE FROM $sis WHERE id_usuario = ?");
            $val_drop_admin = $drop_admin->execute([$id_usuario]);

            if ($val_drop_admin) {
                require '../../functions/drop_con.php';
                require '../../functions/conex_serv.php';
                // Registro en auditlog
                $movimiento = utf8_encode('El usuario '.$user_admin.' eliminó al administrador: '.$usuario.' con el tipo de usuario '.$tipo_usuario.' el '.$fecha_hora_registro.'');
                $url = $_SERVER['PHP_SELF'].'?'.$id_usuario;
                $database = 'veco_sims_devecchi';
                $save_move = $con->prepare("INSERT INTO $log (movimiento, link, ddbb, usuario_movimiento, fecha_hora)
                                    VALUES (?, ?, ?, ?, ?)");
                $val_save_move = $save_move->execute([$movimiento, $url, $database, $admin, $fecha_hora_registro]);

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
        break;

        case 'G':
            $drop_manager = $con->prepare("DELETE FROM $sis WHERE id_usuario = ?");
            $val_drop_manager = $drop_manager->execute([$id_usuario]);

            if ($val_drop_manager) {
                //! Se elimina el usuario en tabla ADMIN en caso de existir
                $drop_manager_admin = $con->prepare("DELETE FROM $admin WHERE nombre_admin = ?");
                $val_drop_manager_admin = $drop_manager_admin->execute([$usuario]);

                if ($val_drop_manager_admin) {
                    require '../../functions/drop_con.php';
                    require '../../functions/conex_serv.php';
                    // Registro en auditlog
                    $movimiento = utf8_encode('El usuario '.$user_admin.' eliminó al gerente: '.$usuario.' con el tipo de usuario '.$tipo_usuario.' el '.$fecha_hora_registro.'');
                    $url = $_SERVER['PHP_SELF'].'?'.$id_usuario;
                    $database = 'veco_sims_devecchi';
                    $save_move = $con->prepare("INSERT INTO $log (movimiento, link, ddbb, usuario_movimiento, fecha_hora)
                                        VALUES (?, ?, ?, ?, ?)");
                    $val_save_move = $save_move->execute([$movimiento, $url, $database, $admin, $fecha_hora_registro]);

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
                    require '../../functions/drop_con.php';
                    require '../../functions/conex_serv.php';
                    // Registro en auditlog
                    $movimiento = utf8_encode('El usuario '.$user_admin.' eliminó al gerente: '.$usuario.' con el tipo de usuario '.$tipo_usuario.' el '.$fecha_hora_registro.', no contaba con otra cuenta en la tabla de ADMIN');
                    $url = $_SERVER['PHP_SELF'].'?'.$id_usuario;
                    $database = 'veco_sims_devecchi';
                    $save_move = $con->prepare("INSERT INTO $log (movimiento, link, ddbb, usuario_movimiento, fecha_hora)
                                        VALUES (?, ?, ?, ?, ?)");
                    $val_save_move = $save_move->execute([$movimiento, $url, $database, $admin, $fecha_hora_registro]);

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
                }
            } else {
                mensaje_error();
                redirect_failed($id_usuario);
                die();
            }
        break;

        case 'T':
            $drop_tech = $con->prepare("DELETE FROM $sis WHERE id_usuario = ?");
            $val_drop_tech = $drop_tech->execute([$id_usuario]);

            if ($val_drop_tech) {
                //! Se elimina el usuario en tabla DVI en caso de existir
                $drop_tech_dvi = $con->prepare("DELETE FROM $dvi WHERE nombre_usuario = ?");
                $val_drop_tech_dvi = $drop_tech_dvi->execute([$usuario]);

                if ($val_drop_tech_dvi) {
                    require '../../functions/drop_con.php';
                    require '../../functions/conex_serv.php';
                    // Registro en auditlog
                    $movimiento = utf8_encode('El usuario '.$user_admin.' eliminó al gerente: '.$usuario.' con el tipo de usuario '.$tipo_usuario.' el '.$fecha_hora_registro.'');
                    $url = $_SERVER['PHP_SELF'].'?'.$id_usuario;
                    $database = 'veco_sims_devecchi';
                    $save_move = $con->prepare("INSERT INTO $log (movimiento, link, ddbb, usuario_movimiento, fecha_hora)
                                        VALUES (?, ?, ?, ?, ?)");
                    $val_save_move = $save_move->execute([$movimiento, $url, $database, $admin, $fecha_hora_registro]);

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
                    require '../../functions/drop_con.php';
                    require '../../functions/conex_serv.php';
                    // Registro en auditlog
                    $movimiento = utf8_encode('El usuario '.$user_admin.' eliminó al gerente: '.$usuario.' con el tipo de usuario '.$tipo_usuario.' el '.$fecha_hora_registro.', no contaba con otra cuenta en la tabla de ADMIN');
                    $url = $_SERVER['PHP_SELF'].'?'.$id_usuario;
                    $database = 'veco_sims_devecchi';
                    $save_move = $con->prepare("INSERT INTO $log (movimiento, link, ddbb, usuario_movimiento, fecha_hora)
                                        VALUES (?, ?, ?, ?, ?)");
                    $val_save_move = $save_move->execute([$movimiento, $url, $database, $admin, $fecha_hora_registro]);

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
                }
            } else {
                mensaje_error();
                redirect_failed($id_usuario);
                die();
            }
        break;

        case 'C':
            $drop_client = $con->prepare("DELETE FROM $sis WHERE id_usuario = ?");
            $val_drop_client = $drop_client->execute([$id_usuario]);
            
            if ($val_drop_client) {
                require '../../functions/drop_con.php';
                require '../../functions/conex_serv.php';
                // Registro en auditlog
                $movimiento = utf8_encode('El usuario '.$user_admin.' eliminó al cliente: '.$usuario.' con el tipo de usuario '.$tipo_usuario.' el '.$fecha_hora_registro.'');
                $url = $_SERVER['PHP_SELF'].'?'.$id_usuario;
                $database = 'veco_sims_devecchi';
                $save_move = $con->prepare("INSERT INTO $log (movimiento, link, ddbb, usuario_movimiento, fecha_hora)
                                    VALUES (?, ?, ?, ?, ?)");
                $val_save_move = $save_move->execute([$movimiento, $url, $database, $admin, $fecha_hora_registro]);

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
        break;
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
                <h1 class="animated lightSpeedIn">Eliminar Usuario: <strong><?php echo $usuario; ?></strong></h1>
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
        <div class="panel-heading text-center"><strong>¡IMPORTANTE!</strong> Verifica los datos del usuario que estás por eliminar</div>
        <div class="panel-body">
            <form role="form" action="" method="POST">
			       
                       
						<div class="form-group">
                          <label  class="col-sm-222 control-label"><i class="fa fa-user-circle" aria-hidden="true"></i> Nombre de Usuario: <i>(Mínimo 10 caracteres)</i></label>
                          <div class="col-sm-110">
                              <div class='input-group'>
								  <input type="text" class="form-control" name="usuario" required readonly value="<?php echo $usuario; ?>">
								  <span class="input-group-addon"><i class="fa fa-eye" aria-hidden="true"></i></span>
                              </div>
                          </div>
                        </div>
						
						<div class="form-group">
                            <label class="col-sm-222 control-label"><i class="fa fa-user-circle-o" aria-hidden="true"></i> Nombre Completo: <i>(Utiliza mayúsculas para la primera letra, mínimo 15 caracteres)</i></label>
                            <div class='col-sm-110'>
                                <div class="input-group">
                                   <input class="form-control" type="text" name="nombre_completo" required readonly value="<?php echo $nombre_completo; ?>">
								   <span class="input-group-addon"><i class="fa fa-eye" aria-hidden="true"></i></span>
                                </div>
                            </div>
                        </div>
						
						 <div class="form-group">
                            <label class="col-sm-222 control-label"><i class="fa fa-envelope" aria-hidden="true"></i> Correo Electrónico:</label>
                            <div class='col-sm-110'>
                                <div class="input-group">
                                   <input class="form-control" type="email" name="email" required readonly value="<?php echo $email; ?>">
								   <span class="input-group-addon"><i class="fa fa-eye" aria-hidden="true"></i></span>
                                </div>
                            </div>
                        </div>
						
						<div class="form-group">
                            <label class="col-sm-222 control-label"><i class="fa fa-user-secret" aria-hidden="true"></i> Tipo de Usuario:</label>
                            <div class='col-sm-110'>
                                <div class="input-group">
                                    <input class="form-control" type="text" name="tipo_usuario" required readonly value="<?php echo $tipo_usuario; ?>">
                                    <span class="input-group-addon"><i class="fa fa-eye" aria-hidden="true"></i></span>
                                </div>
                            </div>
                        </div>
						
						<div class="form-group">
                          <div class="col-sm-offset-2 col-sm-10 text-center">
                              <center><input type="submit" class="btn btn-danger" name="eliminar_usuario" value="Eliminar Usuario"></center>
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