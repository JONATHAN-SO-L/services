<?php
session_start();

if( $_SESSION['nombre']!="" && $_SESSION['clave']!="" && $_SESSION['tipo']=="admin"){
  include '../../assets/admin/navbar2.php';
  include '../../assets/admin/links2.php';
  require '../../functions/conex_serv.php';
  $formats = 'formatos';
?>
<script src="../../assets/css/main.css"></script>
<section id="content">
    <header id="content-header">
        <table>
            <a href="add_format.php" ><button type="submit" class="btn btn-success" style="text-align:center"><i class="fa fa-plus"></i>&nbsp;&nbsp;Nuevo Formato</button></a>
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
                <h1 class="animated lightSpeedIn">Formatos y REV.</h1>
                <span class="label label-danger"></span> 		 
				<p class="pull-right text-primary"></p>
		   </div>
            </div>
          </div>
        </div>

        <div class="pull-left col-sm-2">
            <form action="" method="POST">
                <label>Buscar:</label>
                <!--input type="search" name="palabra_clave" class="form-control" placeholder="Buscar por Formato"-->
                <select name="palabra_clave" class="form-control" required>
                    <option value=""> - Selecciona el formato - </option>
                    <?php
                    // Búsqueda de los formatos disponibles
                    $b_formato = $con -> prepare("SELECT * FROM $formats ORDER BY nombre_formato ASC");
                    $b_formato->setFetchMode(PDO::FETCH_OBJ);
                    $b_formato->execute();

                    $e_formato = $b_formato->fetchAll();

                    if ($b_formato -> rowCount() > 0) {
                        foreach ($e_formato as $formati) {
                            $formato = $formati -> formato;
                            $nombre_formato = $formati -> nombre_formato;

                            echo '<option value="'.$nombre_formato.'">'.$nombre_formato.'</option>';
                        }
                    } else {
                        echo 'No se encontraron formatos cargados en el sistema';
                    }
                    ?>
                </select>
                <br>
                <input type="submit" name="buscar_contador" class="btn btn-sm btn-success" value="Buscar">
            </form><br>
        </div>

        <?php
        if (isset($_POST['buscar_contador'])) {
            // Recepción de palabra clave
            $palabra_clave = $_POST['palabra_clave'];

            // Conteo de los resultados
            $count_format = $con -> prepare("SELECT COUNT(*) FROM $formats WHERE nombre_formato LIKE '%$palabra_clave%' ORDER BY nombre_formato ASC");
            $count_format -> execute();
            $total_registros = $count_format -> fetchColumn();

            // Consulta de información
            $q_format = $con -> prepare("SELECT * FROM $formats WHERE nombre_formato LIKE '%$palabra_clave%' ORDER BY nombre_formato ASC");
            $q_format->setFetchMode(PDO::FETCH_OBJ);
            $q_format->execute();

            $fo_format = $q_format->fetchAll();

            if ($q_format -> rowCount() > 0) {
                echo '
                <a href="index.php"><button type="submit" value="Volver" class="btn btn-primary" style="text-align:center"><i class="fa fa-reply"></i>&nbsp;&nbsp;Volver</button></a>
                <br><br>
                <p><strong>Búsqueda realizada: </strong><span class="badge bg-success">'.$palabra_clave.'</span></p>
                <p><strong>Registros encontrados: </strong><span class="badge bg-success">'.$total_registros.'</span></p>';

                echo '<table class="table table-responsive table-hover table-striped table-bordered">
                <th>Acción</th>
                <th>Nombre de Formato</th>
                <th>Revisión del Formato</th>
                <tr>';

                foreach ($fo_format as $formate) {
                    $id_formato = $formate -> id_formato;
                    $formato = $formate -> formato;
                    $nombre_formato = $formate -> nombre_formato;
                    $revision_formato = $formate -> revision_formato;

                    echo '<td class="text-center">
                    <a href="modify_format.php?'.$formato.'" class="btn btn-sm btn-warning"><i class="fa fa-pencil" aria-hidden="true"></i></a>
                    <!--a href="delete_format.php?'.$formato.'"class="btn btn-sm btn-danger"><i class="fa fa-trash-o" aria-hidden="true"></i></a-->
                    </td>
                    <td><strong>'.$nombre_formato.'</strong></td>
                    <td>'.$revision_formato.'</td>
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
            $s_formats = $con -> prepare("SELECT * FROM $formats ORDER BY nombre_formato ASC");
            $s_formats->setFetchMode(PDO::FETCH_OBJ);
            $s_formats->execute();

            $f_formats = $s_formats->fetchAll();

            if ($s_formats -> rowCount() > 0) {
                echo '<table class="table table-responsive table-hover table-striped table-bordered">
                <th>Acción</th>
                <th>Nombre de Formato</th>
                <th>Revisión del Formato</th>
                <tr>';
                foreach ($f_formats as $format) {
                    $id_formato = $format -> id_formato;
                    $formato = $format -> formato;
                    $nombre_formato = $format -> nombre_formato;
                    $revision_formato = $format -> revision_formato;

                    echo '<td class="text-center">
                    <a href="modify_format.php?'.$formato.'" class="btn btn-sm btn-warning"><i class="fa fa-pencil" aria-hidden="true"></i></a>
                    <!--a href="delete_format.php?'.$formato.'"class="btn btn-sm btn-danger"><i class="fa fa-trash-o" aria-hidden="true"></i></a-->
                    </td>
                    <td><strong>'.$nombre_formato.'</strong></td>
                    <td>'.$revision_formato.'</td>
                    <tr>';
                }
                echo '</table>';
            } else {
                echo '<br><br><br><br><br><br><br>
                <center><h2 class="pull-left">No se encontraron formatos cargados en el sistema</h2></center>';
            }
        }
        require '../../functions/drop_con.php';
}else{
	header('Location: ../../../index.php');
}
?>
</body>
</html>