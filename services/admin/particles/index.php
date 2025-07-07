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
            <a href="add_particles.php" ><button type="submit" class="btn btn-success" style="text-align:center"><i class="fa fa-plus"></i>&nbsp;&nbsp;Nueva Partícula</button></a>
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
                <h1 class="animated lightSpeedIn">Partículas Estándar</h1>
                <span class="label label-danger"></span> 		 
				<p class="pull-right text-primary"></p>
		   </div>
            </div>
          </div>
        </div>

        <div class="pull-left col-sm-2">
            <form action="" method="POST">
                <label>Buscar:</label>
                <input type="search" name="palabra_clave" class="form-control" placeholder="Buscar por Tamaño Nominal o Estado">
                <br>
                <input type="submit" name="buscar_particula" class="btn btn-sm btn-success" value="Buscar">
            </form><br>
        </div>

        <?php
        if (isset($_POST['buscar_particula'])) {
            // Recepción de la plabra clave
            $palabra_clave = $_POST['palabra_clave'];

            $particles = 'particulas';

            // Conteo de los resultados
            $count_particles = $con -> prepare("SELECT COUNT(*) FROM $particles WHERE tamano_nominal LIKE '%$palabra_clave%' OR estado LIKE '%$palabra_clave%'");
            $count_particles->execute();
            $total_registros = $count_particles->fetchColumn();

            // Consulta de información
            $q_particles = $con -> prepare("SELECT * FROM $particles WHERE tamano_nominal LIKE '%$palabra_clave%' OR estado LIKE '%$palabra_clave%' ORDER BY tamano_nominal ASC");
            $q_particles->setFetchMode(PDO::FETCH_OBJ);
            $q_particles->execute();

            $s_particles = $q_particles->fetchAll();

            if ($q_particles -> rowCount() > 0) {
                echo '
                <a href="index.php"><button type="submit" value="Volver" class="btn btn-primary" style="text-align:center"><i class="fa fa-reply"></i>&nbsp;&nbsp;Volver</button></a>
                <br><br>
                <p><strong>Búsqueda realizada: </strong><span class="badge bg-success">'.$palabra_clave.'</span></p>
                <p><strong>Registros encontrados: </strong><span class="badge bg-success">'.$total_registros.'</span></p>';

                echo '<table class="table table-responsive table-hover table-striped table-bordered">
                <th>Acción</th>
                <th>ID</th>
                <th>Tamaño Nominal</th>
                <th>Tamaño Real</th>
                <th>Desviación de Tamaño</th>
                <th>No. Lote</th>
                <th>Estado</th>
                <th>Fecha de Expiración</th>
                <tr>';
                foreach ($s_particles as $particle) {
                    $id_particula = $particle -> id_particula;
                    $tamano_nominal = $particle -> tamano_nominal;
                    $tamano_real = $particle -> tamano_real;
                    $desviacion_tamano = $particle -> desviacion_tamano;
                    $no_lote = $particle -> no_lote;
                    $exp_fecha = $particle -> exp_fecha;
                    $estado = $particle -> estado;

                    echo '<td class="text-center">
                    <a href="modify_particles.php?'.$id_particula.'" class="btn btn-sm btn-warning"><i class="fa fa-pencil" aria-hidden="true"></i></a>
                    <a href="delete_particles.php?'.$id_particula.'"class="btn btn-sm btn-danger"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
                    </td>
                    <td>'.$id_particula.'</td>
                    <td><strong>'.$tamano_nominal.'</strong></td>
                    <td>'.$tamano_real.'</td>
                    <td>'.$desviacion_tamano.'</td>
                    <td>'.$no_lote.'</td>
                    <td><strong>'.$estado.'</strong></td>
                    <td>'.$exp_fecha.'</td>
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
            $particles = 'particulas';
            $q_particles = $con -> prepare("SELECT * FROM $particles ORDER BY tamano_nominal ASC");
            $q_particles->setFetchMode(PDO::FETCH_OBJ);
            $q_particles->execute();

            $s_particles = $q_particles->fetchAll();

            if ($q_particles -> rowCount() > 0) {
                echo '<table class="table table-responsive table-hover table-striped table-bordered">
                <th>Acción</th>
                <th>ID</th>
                <th>Tamaño Nominal</th>
                <th>Tamaño Real</th>
                <th>Desviación de Tamaño</th>
                <th>No. Lote</th>
                <th>Estado</th>
                <th>Fecha de Expiración</th>
                <tr>';
                foreach ($s_particles as $particle) {
                    $id_particula = $particle -> id_particula;
                    $tamano_nominal = $particle -> tamano_nominal;
                    $tamano_real = $particle -> tamano_real;
                    $desviacion_tamano = $particle -> desviacion_tamano;
                    $no_lote = $particle -> no_lote;
                    $exp_fecha = $particle -> exp_fecha;
                    $estado = $particle -> estado;

                    echo '<td class="text-center">
                    <a href="modify_particles.php?'.$id_particula.'" class="btn btn-sm btn-warning"><i class="fa fa-pencil" aria-hidden="true"></i></a>
                    <a href="delete_particles.php?'.$id_particula.'"class="btn btn-sm btn-danger"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
                    </td>
                    <td>'.$id_particula.'</td>
                    <td><strong>'.$tamano_nominal.'</strong></td>
                    <td>'.$tamano_real.'</td>
                    <td>'.$desviacion_tamano.'</td>
                    <td>'.$no_lote.'</td>
                    <td><strong>'.$estado.'</strong></td>
                    <td>'.$exp_fecha.'</td>
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