<?php
session_start();

if( $_SESSION['nombre']!="" && $_SESSION['clave']!="" && $_SESSION['tipo_usuario']=="A"){
  include '../../assets/admin/navbar2.php';
  include '../../assets/admin/links2.php';

  function mensaje_error() {
    echo'
                <div class="alert alert-danger alert-dismissible fade in col-sm-3 animated bounceInDown" role="alert" style="position:fixed; top:70px; right:10px; z-index:10;">
                    <h4 class="text-center"><strong>OCURRIÓ UN ERROR</strong></h4>
                    <p class="text-center">
                        No fue posible obtener la información de los usuarios, asegúrate que la conexión con la base de datos esté funcionando o que haya usuarios registrados
                    </p>
                </div>
            '; 
  }
?>
<script src="../../assets/css/main.css"></script>
<section id="content">
  <header id="content-header">
  <script type="text/javascript">
  </script>
  
  <div class="container">
          <div class="row">
            <div class="col-sm-12">
           <div class="page-header2">
<a class="btn btn-sm btn-primary" href="../../../tabla_usuarios.php"><i class="fa fa-pencil"></i><strong>&nbsp;&nbsp;&nbsp;&nbsp;Clientes</strong> (Obsoleto)</a>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<a class="btn btn-sm btn-danger" href="../../../tabla_devecchi.php"><i class="fa fa-pencil"></i><strong>&nbsp;&nbsp;&nbsp;&nbsp;Claves de Tarjetas</strong></a>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<a class="btn btn-sm btn-warning" href="../../../tabla_admin.php"><i class="fa fa-pencil"></i><strong>&nbsp;&nbsp;&nbsp;&nbsp;Gerentes</strong> (Obsoleto)</a>

				</div>
            </div>
          </div>
        </div>
		
		<br>
		<br>
		
  <table>
  <td>
  <tr>
		    <a href="add_user.php" ><button type="submit" name="" class="btn btn-success" style="text-align:center"><i class="fa fa-plus"></i>&nbsp;&nbsp;Nuevo Usuario</button></a>
            <a href="change_password.php" ><button type="submit" name="" class="btn btn-danger" style="text-align:center"><i class="fa fa-unlock-alt" aria-hidden="true"></i>&nbsp;&nbsp;Cambiar contraseña de usuario</button></a>
			<td>
 <tr>
      <button onClick="document.location.reload();" type="submit" class="btn btn-warning" data-toggle="tooltip" data-placement="top" title="Click Actualizar Datos" HSPACE="10" VSPACE="10"><i class="fa fa-refresh fa-spin  fa-fw"></i>
<span class="sr-only">Loading...</span></button>
        </tr>
          </td>
			   
       </table>
		<!--************************************ Page content******************************-->
		<div class="container">
          <div class="row">
            <div class="col-sm-12">
              <div class="page-header2">
                <h1 class="animated lightSpeedIn"><strong>Usuarios</strong></h1>
                <span class="label label-danger"></span>
                <p class="pull-right text-primary">
               </p>
              </div>
			 </div>
          </div>
        </div>

        <div class="pull-left col-sm-2">
            <form action="" method="POST">
                <label>Buscar:</label>
                <input type="search" name="palabra_clave" class="form-control" placeholder="Buscar por Usuario o Nombre Completo">
                <br>
                <input type="submit" name="buscar_usuario" class="btn btn-sm btn-success" value="Buscar">
            </form><br>
        </div>


    <?php
    require '../../functions/conex.php';
    $sis = 'usuario_sis';

    //? Se define si existe una búsqueda o no
    if (isset($_POST['buscar_usuario'])) {
        //* Recepción de datos
        $palabra_clave = $_POST['palabra_clave'];

        //* Conteo de los resultados
        $count_users = $con -> prepare("SELECT COUNT(*) FROM $sis WHERE nombre_completo LIKE '%$palabra_clave%' OR usuario LIKE '%$palabra_clave%'");
        $count_users->execute();
        $total_registros = $count_users->fetchColumn();

        //? Consulta la información de todos los usuarios y los ordena por tipo de usuario
        $s_users = $con->prepare("SELECT id_usuario, nombre_completo, usuario, tipo_usuario FROM $sis WHERE nombre_completo LIKE '%$palabra_clave%' OR usuario LIKE '%$palabra_clave%' ORDER BY tipo_usuario ASC");
        $s_users->setFetchMode(PDO::FETCH_OBJ);
        $s_users->execute();

        $f_users = $s_users->fetchAll();

        if ($s_users -> rowCount() > 0) {
            echo '
            <a href="usuarios_sis.php"><button type="submit" value="Volver" class="btn btn-primary" style="text-align:center"><i class="fa fa-reply"></i>&nbsp;&nbsp;Volver</button></a>
            <br><br>
            <p><strong>Búsqueda realizada: </strong><span class="badge bg-success">'.$palabra_clave.'</span></p>
            <p><strong>Registros encontrados: </strong><span class="badge bg-success">'.$total_registros.'</span></p>';

            echo "<table class='table table-hover table-striped table-bordered'>
            <th WIDTH=25%>Acción</th>
            <th WIDTH=25%>Nombre Usuario</th>
            <th WIDTH=25%>Nombre Completo</th>
            <th WIDTH=25%>Tipo de Usuario</th>";

            foreach ($f_users as $users) {
                $id_usuario = $users -> id_usuario;
                $nombre_completo = $users -> nombre_completo;
                $usuario = $users -> usuario;
                $tipo_usuario = $users -> tipo_usuario;

                switch ($tipo_usuario) {
                    case 'A':
                        $type = 'Administrador';
                    break;

                    case 'G':
                        $type = 'Gerente';
                    break;

                    case 'T':
                        $type = 'Técnico de Servicios';
                    break;

                    case 'C':
                        $type = 'Cliente';
                    break;
                }

                echo '<tr>
                <td class="text-center">
                <a href="mod_user.php?'.$id_usuario.'" class="btn btn-sm btn-warning"><i class="fa fa-pencil" aria-hidden="true"></i></a>
                <a href="drop_user.php?'.$id_usuario.'" class="btn btn-sm btn-danger"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
                </td>

                <td><strong>'.$usuario.'</strong></td>
                <td>'.$nombre_completo.'</td>
                <td>'.$type.'</td>
                </tr>';
            }
            echo "</table>";
        } else {
            mensaje_error();
            echo '<br><br><br><br><br><br><br>
            <a href="usuarios_sis.php" style="padding-left:15%;"><button type="submit" value="Volver" class="btn btn-primary" style="text-align:center"><i class="fa fa-reply"></i>&nbsp;&nbsp;Volver</button></a>
            <br><br>
            <center><h2 class="pull-left" style="padding-left:15%;">No se encontraron resultados para: <strong><u>'.$palabra_clave.'</u></strong></h2></center>';
            echo '<br><br>';
        }

    } else {
        //* Conteo de los resultados
        $count_users = $con -> prepare("SELECT COUNT(*) FROM $sis");
        $count_users->execute();
        $total_registros = $count_users->fetchColumn();

        //? Consulta la información de todos los usuarios y los ordena por tipo de usuario
        $s_users = $con->prepare("SELECT id_usuario, nombre_completo, usuario, tipo_usuario FROM $sis ORDER BY tipo_usuario ASC");
        $s_users->setFetchMode(PDO::FETCH_OBJ);
        $s_users->execute();

        $f_users = $s_users->fetchAll();

        if ($s_users -> rowCount() > 0) {
            echo '<p><strong>Usuarios Totales: </strong><span class="badge bg-success">'.$total_registros.'</span></p>';

            echo "<table class='table table-hover table-striped table-bordered'>
            <th WIDTH=25%>Acción</th>
            <th WIDTH=25%>Nombre Usuario</th>
            <th WIDTH=25%>Nombre Completo</th>
            <th WIDTH=25%>Tipo de Usuario</th>";

            foreach ($f_users as $users) {
                $id_usuario = $users -> id_usuario;
                $nombre_completo = $users -> nombre_completo;
                $usuario = $users -> usuario;
                $tipo_usuario = $users -> tipo_usuario;

                switch ($tipo_usuario) {
                    case 'A':
                        $type = 'Administrador';
                    break;

                    case 'G':
                        $type = 'Gerente';
                    break;

                    case 'T':
                        $type = 'Técnico de Servicios';
                    break;

                    case 'C':
                        $type = 'Cliente';
                    break;
                }

                echo '<tr>
                <td class="text-center">
                <a href="mod_user.php?'.$id_usuario.'" class="btn btn-sm btn-warning"><i class="fa fa-pencil" aria-hidden="true"></i></a>
                <a href="drop_user.php?'.$id_usuario.'" class="btn btn-sm btn-danger"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
                </td>

                <td><strong>'.$usuario.'</strong></td>
                <td>'.$nombre_completo.'</td>
                <td>'.$type.'</td>
                </tr>';
            }
            echo "</table>";
        } else {
            mensaje_error();
            echo '<br><br><br><br><br><br><br>
            <a href="usuarios_sis.php" style="padding-left:15%;"><button type="submit" value="Volver" class="btn btn-primary" style="text-align:center"><i class="fa fa-reply"></i>&nbsp;&nbsp;Volver</button></a>
            <br><br>';
            echo '<h3>No se encontraron usuarios registrados en el sistema</h3>';
        }
    }

    ?>

     </div>
 <?php
 echo '<br><br>';
 include '../../assets/footer.php';
}else{
	header('Location: ../../../index.php');
	 }
?>

</body>
</html>