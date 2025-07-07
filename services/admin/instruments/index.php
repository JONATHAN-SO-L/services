<?php
session_start();

if( $_SESSION['nombre']!="" && $_SESSION['clave']!="" && $_SESSION['tipo']=="admin"){
  include '../../assets/admin/navbar2.php';
  include '../../assets/admin/links2.php';
  require '../../functions/conex_serv.php';
?>
<script src="../../assets/css/main.css"></script>
<section id="content">
    <header id="content-header">
        <table>
            <a href="add_instrument.php" ><button type="submit" class="btn btn-success" style="text-align:center"><i class="fa fa-plus"></i>&nbsp;&nbsp;Nuevo Instrumento</button></a>
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
                <h1 class="animated lightSpeedIn">Instrumentos</h1>
                <span class="label label-danger"></span> 		 
				<p class="pull-right text-primary"></p>
		   </div>
            </div>
          </div>
        </div>

        <div class="pull-left col-sm-3">
            <form action="" method="POST">
                <label>Buscar:</label>
                <input type="search" name="palabra_clave" class="form-control" placeholder="Buscar por Activo, Tipo de Instrumento, Estado ó No. Control">
                <br>
                <input type="submit" name="buscar_instrumento" class="btn btn-sm btn-success" value="Buscar">
            </form><br>
        </div>

        <?php
        if (isset($_POST['buscar_instrumento'])) {
            //Recepción de la palabra clave
            $palabra_clave = $_POST['palabra_clave'];

            $instruments = 'instrumentos';

            // Conteo de los resultados
            $count_instruments = $con -> prepare("SELECT COUNT(*) FROM $instruments WHERE activo LIKE '%$palabra_clave%' OR numero_control LIKE '%$palabra_clave%' OR tipo_instrumento LIKE '%$palabra_clave%' OR estado LIKE '%$palabra_clave%'");
            $count_instruments->execute();
            $total_registros = $count_instruments->fetchColumn();

            // Consulta de información
            $q_instruments = $con -> prepare("SELECT * FROM $instruments WHERE activo LIKE '%$palabra_clave%' OR numero_control LIKE '%$palabra_clave%' OR tipo_instrumento LIKE '%$palabra_clave%' OR estado LIKE '%$palabra_clave%' ORDER BY id_instrumento ASC");
            $q_instruments->setFetchMode(PDO::FETCH_OBJ);
            $q_instruments->execute();

            $s_instruments = $q_instruments->fetchAll();

            if ($q_instruments -> rowCount() > 0) {
                echo '
                <a href="index.php"><button type="submit" value="Volver" class="btn btn-primary" style="text-align:center"><i class="fa fa-reply"></i>&nbsp;&nbsp;Volver</button></a>
                <br><br>
                <p><strong>Búsqueda realizada: </strong><span class="badge bg-success">'.$palabra_clave.'</span></p>
                <p><strong>Registros encontrados: </strong><span class="badge bg-success">'.$total_registros.'</span></p>';

                echo '<table class="table table-responsive table-hover table-striped table-bordered">
                <th>Acción</th>
                <th>ID</th>
                <th>Activo</th>
                <th>No. Control</th>
                <th>Modelo</th>
                <th>Número de Serie</th>
                <th>Estado</th>
                <th>Tipo de Instrumento</th>
                <th>Rango</th>
                <th>Frecuencia de Calibración</th>
                <th>Fecha de Calibración</th>
                <th>Fecha Próxima Calibración</th>
                <th>Comentarios</th>
                <th>Área Asignada</th>
                <tr>';
                foreach ($s_instruments as $instrument) {
                    $id_instrumento = $instrument -> id_instrumento;
                    $activo = $instrument -> activo;
                    $modelo = $instrument -> modelo;
                    $numero_serie = $instrument -> numero_serie;
                    $numero_control = $instrument -> numero_control;
                    $estado = $instrument -> estado;
                    $tipo_instrumento = $instrument -> tipo_instrumento;
                    $rango = $instrument -> rango;
                    $frecuencia_calibracion = $instrument -> frecuencia_calibracion;
                    $fecha_calibracion = $instrument -> fecha_calibracion;
                    $fecha_proxima_calibracion = $instrument -> fecha_proxima_calibracion;
                    $comentarios = $instrument -> comentarios;
                    $area_asignada = $instrument -> area_asignada;
                    $certificado = $instrument -> certificado;

                    echo '<td class="text-center">';
                    if ($certificado != NULL) {
                        echo '
                        <a href="../'.$certificado.'" class="btn btn-sm btn-primary" title="Ver Certificado" target="_blank"><i class="fa fa-certificate" aria-hidden="true"></i></a>
                        ';
                    }
                    echo'<a href="modify_instrument.php?'.$id_instrumento.'" class="btn btn-sm btn-warning"><i class="fa fa-pencil" aria-hidden="true"></i></a>
                    <a href="delete_instrument.php?'.$id_instrumento.'"class="btn btn-sm btn-danger"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
                    </td>
                    <td>'.$id_instrumento.'</td>
                    <td><strong>'.$activo.'</strong></td>
                    <td>'.$numero_control.'</td>
                    <td>'.$modelo.'</td>
                    <td>'.$numero_serie.'</td>
                    <td><strong>'.$estado.'</strong></td>
                    <td>'.$tipo_instrumento.'</td>
                    <td>'.$rango.'</td>
                    <td>'.$frecuencia_calibracion.'</td>
                    <td>'.$fecha_calibracion.'</td>
                    <td>'.$fecha_proxima_calibracion.'</td>
                    <td>'.$comentarios.'</td>
                    <td>'.$area_asignada.'</td>
                    <tr>';
                }
                echo '</table>';
            } else {
                echo '<br><br><br><br><br><br><br>
                <a href="index.php" style="padding-left:15%;"><button type="submit" value="Volver" class="btn btn-primary" style="text-align:center"><i class="fa fa-reply"></i>&nbsp;&nbsp;Volver</button></a>
                <br><br>
                <center><h2 class="pull-left" style="padding-left:15%;">No se encontraron resultados para: <strong><u>'.$palabra_clave.'</u></strong></h2></center>';
            }
        } else {
            // Consulta de información
            $instruments = 'instrumentos';
            $q_instruments = $con -> prepare("SELECT * FROM $instruments ORDER BY id_instrumento ASC");
            $q_instruments->setFetchMode(PDO::FETCH_OBJ);
            $q_instruments->execute();

            $s_instruments = $q_instruments->fetchAll();

            if ($q_instruments -> rowCount() > 0) {
                echo '<table class="table table-responsive table-hover table-striped table-bordered">
                <th>Acción</th>
                <th>ID</th>
                <th>Activo</th>
                <th>No. Control</th>
                <th>Modelo</th>
                <th>Número de Serie</th>
                <th>Estado</th>
                <th>Tipo de Instrumento</th>
                <th>Rango</th>
                <th>Frecuencia de Calibración</th>
                <th>Fecha de Calibración</th>
                <th>Fecha Próxima Calibración</th>
                <th>Comentarios</th>
                <th>Área Asignada</th>
                <tr>';
                foreach ($s_instruments as $instrument) {
                    $id_instrumento = $instrument -> id_instrumento;
                    $activo = $instrument -> activo;
                    $modelo = $instrument -> modelo;
                    $numero_serie = $instrument -> numero_serie;
                    $numero_control = $instrument -> numero_control;
                    $estado = $instrument -> estado;
                    $tipo_instrumento = $instrument -> tipo_instrumento;
                    $rango = $instrument -> rango;
                    $frecuencia_calibracion = $instrument -> frecuencia_calibracion;
                    $fecha_calibracion = $instrument -> fecha_calibracion;
                    $fecha_proxima_calibracion = $instrument -> fecha_proxima_calibracion;
                    $comentarios = $instrument -> comentarios;
                    $area_asignada = $instrument -> area_asignada;
                    $certificado = $instrument -> certificado;

                    echo '<td class="text-center">';
                    if ($certificado != NULL) {
                        echo '
                        <a href="../'.$certificado.'" class="btn btn-sm btn-primary" title="Ver Certificado" target="_blank"><i class="fa fa-certificate" aria-hidden="true"></i></a>
                        ';
                    }
                    echo '<a href="modify_instrument.php?'.$id_instrumento.'" class="btn btn-sm btn-warning"><i class="fa fa-pencil" aria-hidden="true"></i></a>
                    <a href="delete_instrument.php?'.$id_instrumento.'"class="btn btn-sm btn-danger"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
                    </td>
                    <td>'.$id_instrumento.'</td>
                    <td><strong>'.$activo.'</strong></td>
                    <td>'.$numero_control.'</td>
                    <td>'.$modelo.'</td>
                    <td>'.$numero_serie.'</td>
                    <td><strong>'.$estado.'</strong></td>
                    <td>'.$tipo_instrumento.'</td>
                    <td>'.$rango.'</td>
                    <td>'.$frecuencia_calibracion.'</td>
                    <td>'.$fecha_calibracion.'</td>
                    <td>'.$fecha_proxima_calibracion.'</td>
                    <td>'.$comentarios.'</td>
                    <td>'.$area_asignada.'</td>
                    <tr>';
                }
                echo '</table>';
            } else {
                echo '<br><br><br><br><br><br>
                    <center><h2 class="pull-left">No se encontraron contadores cargados en el sistema</h2></center>';
            }
        }
}else{
	header('Location: ../../../index.php');
}
?>
</body>
</html>