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
                No fue posible crear el usuario, por favor inténtalo de nuevo o contacta al Soporte Técnico.
            </p>
        </div>
    '; 
    }

    function usuario_existente() {
    echo'
        <div class="alert alert-danger alert-dismissible fade in col-sm-3 animated bounceInDown" role="alert" style="position:fixed; top:70px; right:10px; z-index:10;"> 
            <a href="empresa.php"><button type="button" class="close"  data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
            <h4 class="text-center">OCURRIÓ UN ERROR</h4>
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

    if (isset($_POST['crear_usuario'])) {
        require '../../functions/conex.php';
        $sis = 'usuario_sis';
        $log = 'auditlog';

        //* Recepción de datos
        $usuario = $_POST['usuario'];
        $clave = $_POST['clave'];
        $nombre_completo = $_POST['nombre_completo'];
        $email = $_POST['email'];
        $tipo_usuario = $_POST['tipo_usuario'];

        //Obtención del archivo
        $size_max = 15728640; // Definición de tamaño máximo (15 MB)
        $carpeta_save = '../users/sign'; // Se define directorio donde se guarda el certificado del instrumento en el servidor
        $firma = $_FILES['firma']['name']; // Nombre del archivo a guardar
        $tipo_archivo = $_FILES['firma']['type']; // Tipo de archivo
        $tamano_archivo = $_FILES['firma']['size']; // Tamaño del archivo
        $temp_file = $_FILES['firma']['tmp_name']; // Asignación de memoria para procesamiento
        $nombre_save = $carpeta_save.'/'.$firma; // Nombre del archivo para guardar
        $almacenamiento_certificado = '../../services/admin/users/'.$firma;

        // Información para auditlog
		$admin = $_SESSION['nombre_completo'];
		require '../../assets/timezone.php';
		$fecha_hora_registro = date("d/m/Y H:i:s");

        //! Validación de que el usuario no exista
        $s_user = $con->prepare("SELECT * FROM $sis WHERE usuario = :usuario");
        $s_user->bindValue(':user', $usuario);
        $s_user->setFetchMode(PDO::FETCH_OBJ);
        $s_user->execute();

        $f_user = $s_user->fecthAll();

        if ($s_user -> rowCount() > 0) {
            //! Si el usuario ya existe se MATA EL PROCESO y se REDIRIGE al inicio
            usuario_existente();
            redirect_failed();
            die();
        } else {
            //* Si no existe el usuario procede con las validaciones
            $min_user = strlen($usuario)
            if ($min_user >= 10) { //! Se valida que el usuario cumpla con el mínimo de caracteres (10)
                $min_pass = strlen($clave);
                if ($min_pass >= 8) { //! Se valida que la contraseña cumpla con el mínimo de caracteres (8)
                    $min_name = strlen($nombre_completo);
                    if ($min_name >= 15) { //! Se valida que el nombre cumpla con el mínimo de caracteres (15)
                        if ($firma != NULL) { //! Se valida si se cargó una firma digital
                            # code...
                        } else {
                            //* Si no se cargó una firma se hace la captura sin la firma
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
       <a href="usuarios_sis.php" ><button type="submit" value="Regresar"class="btn btn-primary" style="text-align:center"><i class="fa fa-reply"></i>&nbsp;&nbsp;Volver</button></a>
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
      <div class="panel panel-danger">
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
                                   <input class="form-control" required placeholder="Por ejemplo: Omar Courtz" type="text" name="nombre_completo" pattern=".{15,}" maxlength="50">
								   <span class="input-group-addon"><i class="fa fa-pencil-square-o"></i></span>
                                </div>
                            </div>
                        </div>
						
						 <div class="form-group">
                            <label class="col-sm-222 control-label"><i class="fa fa-envelope" aria-hidden="true"></i> Correo Electrónico:</label>
                            <div class='col-sm-110'>
                                <div class="input-group">
                                   <input class="form-control" required type="email" placeholder="Por ejemplo: o.courtz@veco.mx" name="email">
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

                        <div class="form-group">
                            <label class="col-sm-222 control-label"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> *Firma Digital: <i>(Solo se admiten archivos en <u>formato PNG</u> con un tamaño <u>máximo de 15 MB</u>)</i></label>
                            <div class='col-sm-110'>
                                <div class="input-group">
                                   <input class="form-control" accept="image/png" placeholder="Nombre de Administrador" type="file" name="firma" maxlength="30">
								   <span class="input-group-addon"><i class="fa fa-pencil-square-o"></i></span>
                                </div>
                            </div>
                        </div>

                        <label class="col-sm-222 control-label">* <i>Campos opcionales</i></label>
						
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