<?php
session_start();

if( $_SESSION['nombre']!="" && $_SESSION['clave']!="" && $_SESSION['tipo']=="admin"){
  include '../../assets/admin/navbar2.php';
  include '../../assets/admin/links2.php';
  require '../../functions/conex_serv.php';
  $log = 'auditlog';
?>
<script src="../../assets/css/main.css"></script>
<section id="content">
    <header id="content-header">
        <table>
			<td>
                <tr>
                    <button onClick="document.location.reload();" type="submit" class="btn btn-warning" data-toggle="tooltip" data-placement="top" title="Click Actualizar Datos" HSPACE="10" VSPACE="10"><i class="fa fa-refresh fa-spin  fa-fw"></i>
                    <span class="sr-only">Loading...</span></button>
                </tr>
            </td>
        </table>

		<div class="container">
          <div class="row" style="width: 1270px;">
            <div class="col-sm-12">
           <div class="page-header2">
                <h1 class="animated lightSpeedIn">AuditLog</h1>
                <h4 class="animated lightSpeedIn">Aquí puedes visualizar todos los movimientos realizados en el sistema desde el más reciente al más anitguo</h4>
                <span class="label label-danger"></span> 		 
				<p class="pull-right text-primary"></p>
		   </div>
            </div>
          </div>
        </div>

        <div class="pull-left col-sm-3">
            <form action="" method="POST">
                <label>Buscar por Usuario:</label>
                <!--input type="search" name="palabra_clave" class="form-control" placeholder="Buscar por Formato"-->
                <select name="usuario_movimiento" class="form-control" required>
                    <option value=""> - Selecciona usuario que ha interactuado - </option>
                    <?php
                    // Búsqueda de los formatos disponibles
                    $b_user_move = $con -> prepare("SELECT usuario_movimiento FROM $log GROUP BY usuario_movimiento ORDER BY usuario_movimiento ASC");
                    $b_user_move->setFetchMode(PDO::FETCH_OBJ);
                    $b_user_move->execute();

                    $f_user_move = $b_user_move->fetchAll();

                    if ($b_user_move -> rowCount() > 0) {
                        foreach ($f_user_move as $usuario) {
                            $usuario_movimiento = $usuario -> usuario_movimiento;
                            echo '<option value="'.$usuario_movimiento.'">'.$usuario_movimiento.'</option>';
                        }
                    } else {
                        echo 'No se encontraron usuarios con movimientos en el sistema';
                    }
                    ?>
                </select>
                <br>
                <input type="submit" name="buscar_usuario" class="btn btn-sm btn-success" value="Buscar">
            </form><br>
        </div>

        <div class="pull-left col-sm-2">
            <form action="" method="POST">
                <label>Buscar por Rango de Fechas:</label>
                <input type="date" name="fecha1" class="form-control">
                <input type="date" name="fecha2" class="form-control">
                <br>
                <input type="submit" name="buscar_fechas" class="btn btn-sm btn-success" value="Buscar">
            </form><br>
        </div>

        <?php
        if (isset($_POST['buscar_usuario'])) {
            // Recepción de usuario
            $usuario_movimiento = $_POST['usuario_movimiento'];

            // Conteo de resultados
            $count_by_user = $con->prepare("SELECT COUNT(*) FROM $log WHERE usuario_movimiento = :usuario_movimiento");
            $count_by_user->bindValue(':usuario_movimiento', $usuario_movimiento);
            $count_by_user->execute();
            $num_total_results = $count_by_user->fetchColumn();

            // Consulta
            $s_by_user = $con->prepare("SELECT * FROM $log WHERE usuario_movimiento = :usuario_movimiento ORDER BY fecha_hora DESC");
            $s_by_user->bindValue(':usuario_movimiento', $usuario_movimiento);
            $s_by_user->setFetchMode(PDO::FETCH_OBJ);
            $s_by_user->execute();

            $f_by_user = $s_by_user->fetchAll();

            if ($s_by_user -> rowCount() > 0) {
                echo '
                <div class="table-responsive">
                <a href="index.php"><button type="submit" value="Volver" class="btn btn-primary" style="text-align:center"><i class="fa fa-reply"></i>&nbsp;&nbsp;Volver</button></a>
                <br><br>
                <p><strong>Búsqueda realizada: </strong><span class="badge bg-success">'.$usuario_movimiento.'</span></p>
                <p><strong>Registros encontrados: </strong><span class="badge bg-success">'.$num_total_results.'</span></p>

                <table class="table table-responsive table-hover table-striped table-bordered">
                <th>ID</th>
                <th>Fecha y Hora del Movimiento</th>
                <th>Usuario Movimiento</th>
                <th>Movimiento</th>
                <th>Link</th>
                <tr>';
                foreach ($f_by_user as $format) {
                    $id_movimiento = $format -> id_movimiento;
                    $movimiento = $format -> movimiento;
                    $link = $format -> link;
                    $usuario_movimiento = $format -> usuario_movimiento;
                    $fecha_hora = $format -> fecha_hora;

                    echo '
                    <td>'.$id_movimiento.'</td>
                    <td>'.$fecha_hora.'</td>
                    <td>'.$usuario_movimiento.'</td>
                    <td>'.utf8_encode($movimiento).'</td>
                    <td>'.$link.'</td>
                    <tr>';
                }
                echo '</table>
                </div>';
            } else {
                echo '<br><br><br><br><br><br><br>
                <center><h2 class="pull-left">No se encontraron registros en el sistema</h2></center>';
            }

        } elseif (isset($_POST['buscar_fechas'])) {
            // Recepción de fechas
            $fecha1 = $_POST['fecha1'];
            $fecha1=  date("d/m/Y", strtotime($fecha1));
            $fecha2 = $_POST['fecha2'];
            $fecha2=  date("d/m/Y", strtotime($fecha2));

            // Conteo de resultados
            $count_by_datetime = $con->prepare("SELECT COUNT(*) FROM $log WHERE fecha_hora BETWEEN :fecha1 AND :fecha2");
            $count_by_datetime->bindValue(':fecha1', $fecha1);
            $count_by_datetime->bindValue(':fecha2', $fecha2);
            $count_by_datetime->execute();
            $num_total_results = $count_by_datetime->fetchColumn();

            // Consulta
            $s_by_datetime = $con->prepare("SELECT * FROM $log WHERE fecha_hora BETWEEN :fecha1 AND :fecha2 ORDER BY id_movimiento DESC");
            $s_by_datetime->bindValue(':fecha1', $fecha1);
            $s_by_datetime->bindValue(':fecha2', $fecha2);
            $s_by_datetime->setFetchMode(PDO::FETCH_OBJ);
            $s_by_datetime->execute();

            $f_by_datetime = $s_by_datetime->fetchAll();

            if ($s_by_datetime -> rowCount() > 0) {
                echo '
                <div class="table-responsive">
                <a href="index.php"><button type="submit" value="Volver" class="btn btn-primary" style="text-align:center"><i class="fa fa-reply"></i>&nbsp;&nbsp;Volver</button></a>
                <br><br>
                <p><strong>Rango de fechas buscado: </strong><span class="badge bg-success">'.$fecha1.'</span> - </strong><span class="badge bg-success">'.$fecha2.'</span></p>
                <p><strong>Registros encontrados: </strong><span class="badge bg-success">'.$num_total_results.'</span></p>

                <table class="table table-responsive table-hover table-striped table-bordered">
                <th>ID</th>
                <th>Fecha y Hora del Movimiento</th>
                <th>Usuario Movimiento</th>
                <th>Movimiento</th>
                <th>Link</th>
                <tr>';
                foreach ($f_by_datetime as $format) {
                    $id_movimiento = $format -> id_movimiento;
                    $movimiento = $format -> movimiento;
                    $link = $format -> link;
                    $usuario_movimiento = $format -> usuario_movimiento;
                    $fecha_hora = $format -> fecha_hora;

                    echo '
                    <td>'.$id_movimiento.'</td>
                    <td>'.$fecha_hora.'</td>
                    <td>'.$usuario_movimiento.'</td>
                    <td>'.utf8_encode($movimiento).'</td>
                    <td>'.$link.'</td>
                    <tr>';
                }
                echo '</table>
                </div>';
            } else {
                echo '<br><br><br><br><br><br><br>
                <a href="index.php"><button type="submit" value="Volver" class="btn btn-primary" style="text-align:center"><i class="fa fa-reply"></i>&nbsp;&nbsp;Volver</button></a>
                <br><br>
                <center><h2 class="pull-left">No se encontraron registros en el sistema</h2></center>';
            }

        } else {
            // Consulta de información
            $s_formats = $con -> prepare("SELECT * FROM $log ORDER BY id_movimiento DESC");
            $s_formats->setFetchMode(PDO::FETCH_OBJ);
            $s_formats->execute();

            $f_formats = $s_formats->fetchAll();

            if ($s_formats -> rowCount() > 0) {
                echo '<table class="table table-responsive table-hover table-striped table-bordered">
                <th>ID</th>
                <th>Fecha y Hora del Movimiento</th>
                <th>Usuario Movimiento</th>
                <th>Movimiento</th>
                <th>Link</th>
                <tr>';
                foreach ($f_formats as $format) {
                    $id_movimiento = $format -> id_movimiento;
                    $movimiento = $format -> movimiento;
                    $link = $format -> link;
                    $usuario_movimiento = $format -> usuario_movimiento;
                    $fecha_hora = $format -> fecha_hora;

                    echo '
                    <td>'.$id_movimiento.'</td>
                    <td>'.$fecha_hora.'</td>
                    <td>'.$usuario_movimiento.'</td>
                    <td>'.utf8_decode($movimiento).'</td>
                    <td>'.$link.'</td>
                    <tr>';
                }
                echo '</table>';
            } else {
                echo '<br><br><br><br><br><br><br><br><br>
                <center><h2 class="pull-left">No se encontraron registros en el sistema</h2></center>';
            }
        }
        require '../../functions/drop_con.php';
}else{
	header('Location: ../../../index.php');
}
?>
</body>
</html>