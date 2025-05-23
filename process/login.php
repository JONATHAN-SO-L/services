<?php
session_start();

require './services/functions/conex.php';

/**************************
Recepción de datos y parseo
**************************/
$user = strip_tags($_POST['nombre_login']);
$pass = md5($_POST['contrasena_login']);

/*******************************
Validación existencia de usuario
*******************************/
$val_user = $con->prepare("SELECT * FROM usuario_sis WHERE usuario = :user AND clave = :pass");
$val_user->bindValue(':user', $user);
$val_user->bindValue(':pass', $pass);
$val_user->setFetchMode(PDO::FETCH_OBJ);
$val_user->execute();

$validate = $val_user->fetchAll();

/******************
Asignación de datos
******************/
if ($val_user -> rowCount() > 0) {
    foreach ($validate as $value) {
        $id_usuario = $value->id_usuario;
        $nombre_completo = $value->nombre_completo;
        $usuario = $value->usuario;
        $email = $value->email;
        $clave = $value->clave;
        $razon_social = $value->$razon_social;
        $tipo_usuario = $value->tipo_usuario;
    }
} else {
    echo '
        <div class="alert alert-danger alert-dismissible fade in col-sm-3 animated bounceInDown" role="alert" style="position:fixed; top:70px; right:10px; z-index:10;"> 
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
        <h4 class="text-center">OCURRIÓ UN ERROR</h4>
        <p class="text-center">
        Nombre de usuario o contraseña incorrectos
        </p>
        </div>
        ';
}

/*************************************
Redirección en base al tipo de usuario
*************************************/
switch ($tipo_usuario) {
    case 'A': // Administrador
    $_SESSION['usuario'] = $usuario;
    $_SESSION['nombre_completo'] = $nombre_completo;
    $_SESSION['email'] = $email;
    $_SESSION['razon_social'] = $razon_social;
    $_SESSION['tipo_usuario'] = $tipo_usuario;

    header('Location: ./seccion.php');

    break;

    case 'G': // Gerente o Jefe
    $_SESSION['usuario'] = $usuario;
    $_SESSION['nombre_completo'] = $nombre_completo;
    $_SESSION['email'] = $email;
    $_SESSION['razon_social'] = $razon_social;
    $_SESSION['tipo_usuario'] = $tipo_usuario;

    // Validación de la exitencia del usuario en otra tabla
    $s_user = $con -> prepare("SELECT * FROM administrador WHERE nombre_admin = :nombre_admin AND clave = :clave");
    $s_user->bindValue(':nombre_admin', $usuario);
    $s_user->bindValue(':clave', $clave);
    $s_user->setFetchMode(PDO::FETCH_OBJ);
    $s_user->execute();

    $found_user = $s_user->fetchAll();

    if ($s_user -> rowCount() > 0) {
        foreach ($found_user as $value) {
            $_SESSION['tipo'] = 'admin';
        }
    } else {
        echo '
        <div class="alert alert-danger alert-dismissible fade in col-sm-3 animated bounceInDown" role="alert" style="position:fixed; top:70px; right:10px; z-index:10;"> 
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
        <h4 class="text-center">OCURRIÓ UN ERROR</h4>
        <p class="text-center">
        Nombre de usuario o contraseña incorrectos
        </p>
        </div>
        ';
    }

    header('Location: ./inicio.php');

    break;

    case 'T': // Técnico de Servicios
    $_SESSION['usuario'] = $usuario;
    $_SESSION['nombre_completo'] = $nombre_completo;
    $_SESSION['nombre'] = $nombre_completo;
    $_SESSION['email'] = $email;
    $_SESSION['clave'] = $clave;
    $_SESSION['razon_social'] = $razon_social;
    $_SESSION['tipo_usuario'] = $tipo_usuario;

    // Validación de la exitencia del usuario en otra tabla
    $s_user = $con -> prepare("SELECT * FROM usuario_devecchi WHERE nombre_usuario = :nombre_usuario AND clave = :clave");
    $s_user->bindValue(':nombre_usuario', $usuario);
    $s_user->bindValue(':clave', $clave);
    $s_user->setFetchMode(PDO::FETCH_OBJ);
    $s_user->execute();

    $found_user = $s_user->fetchAll();

    if ($s_user -> rowCount() > 0) {
        foreach ($found_user as $value) {
            $_SESSION['tipo'] = 'devecchi';
        }
    } else {
        echo '
        <div class="alert alert-danger alert-dismissible fade in col-sm-3 animated bounceInDown" role="alert" style="position:fixed; top:70px; right:10px; z-index:10;"> 
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
        <h4 class="text-center">OCURRIÓ UN ERROR</h4>
        <p class="text-center">
        Nombre de usuario o contraseña incorrectos
        </p>
        </div>
        ';
    }

    header('Location: ./seccion.php');

    break;

    case 'C': // Cliente
    $_SESSION['usuario'] = $usuario;
    $_SESSION['nombre_completo'] = $nombre_completo;
    $_SESSION['email'] = $email;
    $_SESSION['razon_social'] = $razon_social;
    $_SESSION['tipo_usuario'] = $tipo_usuario;

    $_SESSION['usuario'] = $usuario;
    $_SESSION['nombre_completo'] = $nombre_completo;
    $_SESSION['email'] = $email;
    $_SESSION['razon_social'] = $razon_social;
    $_SESSION['tipo_usuario'] = $tipo_usuario;

    // Validación de la exitencia del usuario en otra tabla
    $s_user = $con -> prepare("SELECT * FROM usuario WHERE nombre_usuario = :nombre_usuario AND clave = :clave");
    $s_user->bindValue(':nombre_usuario', $usuario);
    $s_user->bindValue(':clave', $clave);
    $s_user->setFetchMode(PDO::FETCH_OBJ);
    $s_user->execute();

    $found_user = $s_user->fetchAll();

    if ($s_user -> rowCount() > 0) {
        foreach ($found_user as $value) {
            $_SESSION['tipo'] = 'user';
        }
    } else {
        echo '
        <div class="alert alert-danger alert-dismissible fade in col-sm-3 animated bounceInDown" role="alert" style="position:fixed; top:70px; right:10px; z-index:10;"> 
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
        <h4 class="text-center">OCURRIÓ UN ERROR</h4>
        <p class="text-center">
        Nombre de usuario o contraseña incorrectos
        </p>
        </div>
        ';
    }

    header('Location: ./inicio_user.php');

    break;
    
    default:
    echo '<script>alert("Ocurrión en error al recuperar información del usuario en sistema, por favor inténtalo de nuevo o comunícate con Soporte Técnico a través del Soporte Devinsa")</script>';
    header('Location: ../index.php');
    die();
    break;
}

?>