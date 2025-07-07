<?php
session_start();

if( $_SESSION['nombre']!="" && $_SESSION['clave']!="" && $_SESSION['tipo']=="admin"){
  include './services/assets/admin/navbar.php';
  include './services/assets/admin/links.php';
?>
<script src="./services/assets/css/main.css"></script>
<section id="content">
  <header id="content-header">
  <table>  
    <td>
	</td>
	   </table>
		<!--************************************ Page content******************************-->
		<div class="container">
          <div class="row">
            <div class="col-sm-12">
              <div class="page-header2">
                <h1 class="animated lightSpeedIn">Cambiar Contraseña</h1>
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
        <div class="panel-heading text-center"><strong>Edite la información que se muestra a continuación</strong></div>
        <div class="panel-body">
            <form role="form" action="" method="POST">
			
			<?php
			if(isset($_POST['editar'])){
			
			$usuario = "veco_dvi";
	$password = "Vec83Dv19iSa@";
	$servidor = "localhost";
	$basededatos = "veco_sims_devecchi";
	
	// creación de la conexión a la base de datos con mysql_connect()
	$conexion = mysqli_connect( $servidor, $usuario, $password ) or die ("No se ha podido conectar al servidor de Base de datos");
	$conexion ->set_charset('utf8');
	$conexion->query("SET NAMES UTF8");
    $conexion->query("SET CHARACTER SET utf8");
	
	// Selección de la base de datos
	$db = mysqli_select_db( $conexion, $basededatos ) or die ( "Upps! Pues va a ser que no se ha podido conectar a la base de datos" );
	$mysqli = new mysqli('localhost', "veco", "Vec83Dv19iSa@", 'veco_sims_devecchi');
			
			$passActual = $mysqli->real_escape_string($_POST['passActual']);
			$pass1 = $mysqli->real_escape_string($_POST['pass1']);
			$pass2 = $mysqli->real_escape_string($_POST['pass2']);
			
			$passActual = md5($passActual);
			$pass1 = md5($pass1);
			$pass2 = md5($pass2);
			
			$sqlA = $mysqli->query("SELECT clave FROM administrador WHERE nombre_admin ='".$_SESSION['nombre']."' ");			
			$rowA = $sqlA ->fetch_array();
			
			if($rowA['clave'] == $passActual){
				if($pass1 == $pass2){
					$update = $mysqli->query("update administrador set clave = '$pass1' WHERE nombre_admin ='".$_SESSION['nombre']."' ");
					if($update){
						echo "Se ha actualizado la contraseña";
					}
				}else{
				echo "Las contraseñas no coinciden";
				}
			}else{
			echo "Tu contraseña actual no coincide";
			}
		}
?>
			 
		<div class="form-group">
               <label class="col-sm-222 control-label">Contraseña Actual</label>
                <div class="col-sm-110">
                    <div class='input-group'>
                        <input type="password" class="form-control" name="passActual"  placeholder="Password Actual" required="">
                        <span class="input-group-addon"><i class="fa fa-pencil-square-o"></i></span>
                    </div>
                </div>
        </div>
						
		<div class="form-group">
               <label class="col-sm-222 control-label">Contraseña Nueva</label>
                <div class="col-sm-110">
                    <div class='input-group'>
                        <input type="password" class="form-control" name="pass1"  placeholder="Nueva Password" required="">
                        <span class="input-group-addon"><i class="fa fa-pencil-square-o"></i></span>
                    </div>
                </div>
        </div>
		
		<div class="form-group">
               <label class="col-sm-222 control-label">Repetir Contraseña Nueva</label>
                <div class="col-sm-110">
                    <div class='input-group'>
                        <input type="password" class="form-control" name="pass2"  placeholder="Confirmar Password" required="">
                        <span class="input-group-addon"><i class="fa fa-pencil-square-o"></i></span>
                    </div>
                </div>
        </div>
       
            <div class="form-group">
			<div class="col-sm-offset-2 col-sm-10 text-center">
                <input class="btn btn-info" type="submit" name="editar" value="Cambiar Password">
            </div>
			</div>
        </form>
    </div>
	</div><!--col-md-12-->
          </div><!--container-->
		</div>
	  </div>
      </div>
	   <?php
}else{
	header('Location: index.php');
}
?>

<footer></footer>

  <script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>

    <script src="js/index.js"></script>
</body>
</html>