<?php
session_start();

if( $_SESSION['nombre']!="" && $_SESSION['clave']!="" && $_SESSION['tipo']=="admin"){
  include '../../assets/admin/navbar2.php';
  include '../../assets/admin/links2.php';
?>
<script src="../../assets/css/main.css"></script>
<section id="content">
    <header id="content-header">
        <table>
		    <a href="add_accountant.php" ><button type="submit" class="btn btn-success" style="text-align:center"><i class="fa fa-plus"></i>&nbsp;&nbsp;Nuevo Contador</button></a>
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
                <h1 class="animated lightSpeedIn">Contadores de Partículas</h1>
                <span class="label label-danger"></span> 		 
				<p class="pull-right text-primary"></p>
		   </div>
            </div>
          </div>
        </div>

        <div class="pull-left col-sm-3">
            <form action="" method="POST">
                <label>Buscar:</label>
                <input type="search" name="palabra_clave" class="form-control" placeholder="Buscar por Modelo de CI, No. Control ó Estado">
                <br>
                <input type="submit" name="buscar_contador" class="btn btn-sm btn-success" value="Buscar">
            </form><br>
        </div>

        <div class="pull-rigth col-sm-2">
            <form action="" method="POST">
                <label>Buscar por empresa:</label>
                <select name="empresa_clave" class="form-control">
                    <option value=""> - Selecciona la empresa - </option>
                    <?php
                    require '../../functions/conex.php';
                    // Lista de las empresas disponibles para buscar
                    $empresas = 'empresas';
                    $buscar_empresas = $con->prepare("SELECT id, razon_social FROM $empresas");
                    $buscar_empresas->setFetchMode(PDO::FETCH_OBJ);
                    $buscar_empresas->execute();

                    $encontar_empresas = $buscar_empresas->fetchAll();

                    if ($buscar_empresas -> rowCount() > 0) {
                        foreach ($encontar_empresas as $empresas_disponibles) {
                            $id_empresa_dispo = $empresas_disponibles -> id;
                            $razon_empresa_dispo = $empresas_disponibles -> razon_social;

                            echo '<option value="'.$id_empresa_dispo.'">'.$razon_empresa_dispo.'</option>';
                        }
                    }
                    ?>
                </select>
                <br>
                <input type="submit" name="buscar_empresa" class="btn btn-sm btn-success" value="Buscar">
            </form><br>
        </div>

        <?php
        if (isset($_POST['buscar_contador'])) {
            // Recepción de palabra clave
            $palabra_clave = $_POST['palabra_clave'];

            $accountant = 'contadores';

            // Conteo de los resultados
            require '../../functions/conex_serv.php';
            $count_accountant = $con -> prepare("SELECT COUNT(*) FROM $accountant WHERE modelo_ci LIKE '%$palabra_clave%' OR numero_control LIKE '%$palabra_clave%' OR estado LIKE '%$palabra_clave%'");
            $count_accountant -> execute();
            $total_registros = $count_accountant -> fetchColumn();

            // Consulta de información
            $q_accountants = $con -> prepare("SELECT * FROM $accountant WHERE modelo_ci LIKE '%$palabra_clave%' OR numero_control LIKE '%$palabra_clave%' OR estado LIKE '%$palabra_clave%' ORDER BY numero_control ASC");
            $q_accountants->setFetchMode(PDO::FETCH_OBJ);
            $q_accountants->execute();

            $s_accountants = $q_accountants->fetchAll();

            if ($q_accountants -> rowCount() > 0) {
                echo '
                <a href="index.php"><button type="submit" value="Volver" class="btn btn-primary" style="text-align:center"><i class="fa fa-reply"></i>&nbsp;&nbsp;Volver</button></a>
                <br><br>
                <p><strong>Búsqueda realizada: </strong><span class="badge bg-success">'.$palabra_clave.'</span></p>
                <p><strong>Registros encontrados: </strong><span class="badge bg-success">'.$total_registros.'</span></p>';

                echo '<table class="table table-responsive table-hover table-striped table-bordered">
                <th>Acción</th>
                <th>ID</th>
                <th>No. Control</th>
                <th>Descripción</th>
                <th>Empresa Vinculada</th>
                <th>Modelo</th>
                <th>Número de Serie</th>
                <th>Estado</th>
                <th>Rango</th>
                <th>Frencuencia de Calibración</th>
                <th>Vigencia Inicial</th>
                <th>Vigencia Final</th>
                <th>Comentarios</th>
                <th>Área asignada</th>
                <tr>';
                foreach ($s_accountants as $counter) {
                    $id_contador = $counter -> id_contador;
                    $descripcion_nombre = $counter -> descripcion_nombre;
                    $modelo_ci = $counter -> modelo_ci;
                    $numero_serie = $counter -> numero_serie;
                    $numero_control = $counter -> numero_control;
                    $estado = $counter -> estado;
                    $rango = $counter -> rango;
                    $frecuencia_calibracion = $counter -> frecuencia_calibracion;
                    $vigencia_inicial = $counter -> vigencia_inicial;
                    $vigencia_final = $counter -> vigencia_final;
                    $comentarios = $counter -> comentarios;
                    $area_asignada = $counter -> area_asignada;
                    $empresa_vinculada = $counter -> empresa_vinculada;

                    require '../../functions/conex.php';
                    // Se busca el edificio asignado
                    $build = 'edificio';
                    $b_edificio = $con -> prepare("SELECT id_edificio, empresa_id FROM $build WHERE id_edificio = :id_edificio");
                    $b_edificio->bindValue('id_edificio', $empresa_vinculada);
                    $b_edificio->setFetchMode(PDO::FETCH_OBJ);
                    $b_edificio->execute();

                    $e_edificio = $b_edificio->fetchAll();

                    if ($b_edificio -> rowCount() > 0) {
                        foreach ($e_edificio as $edificio) {
                            $id_edificio = $edificio -> id_edificio;
                            $empresa_id = $edificio -> empresa_id;

                            // Se busca la empresa en base al edificio
                            $company = 'empresas';
                            $b_empresa = $con -> prepare("SELECT id, razon_social FROM $company WHERE id = :id");
                            $b_empresa->bindValue(':id', $empresa_id);
                            $b_empresa->setFetchMode(PDO::FETCH_OBJ);
                            $b_empresa->execute();

                            $e_empresa = $b_empresa->fetchAll();

                            if ($b_empresa -> rowCount() > 0) {
                                foreach ($e_empresa as $empresa) {
                                    $id_empresa = $empresa -> id;
                                    $razon_social = $empresa -> razon_social;
                                }
                            }
                        }
                    }

                    echo '<td class="text-center">
                    <a href="modify_accountant.php?'.$id_contador.'" class="btn btn-sm btn-warning"><i class="fa fa-pencil" aria-hidden="true"></i></a>
                    <a href="delte_accountant.php?'.$id_contador.'"class="btn btn-sm btn-danger"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
                    </td>
                    <td>'.$id_contador.'</td>
                    <td><strong>'.$numero_control.'</strong></td>
                    <td>'.$descripcion_nombre.'</td>
                    <td><strong>'.$razon_social.'</strong></td>
                    <td>'.$modelo_ci.'</td>
                    <td>'.$numero_serie.'</td>
                    <td><strong>'.$estado.'</strong></td>
                    <td>'.$rango.'</td>
                    <td>'.$frecuencia_calibracion.'</td>
                    <td>'.$vigencia_inicial.'</td>
                    <td>'.$vigencia_final.'</td>
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
        } elseif (isset($_POST['buscar_empresa'])) {
            // Recepción de empresa clave
            $empresa_clave = $_POST['empresa_clave'];
            require '../../functions/conex.php';

            // Obtener el edificio desde el ID obtenido por la empresa
            $builder = 'edificio';
            $buscar_edificio = $con -> prepare("SELECT id_edificio, empresa_id, descripcion FROM $builder WHERE empresa_id = :id_empresa");
            $buscar_edificio->bindValue(':id_empresa', $empresa_clave);
            $buscar_edificio->setFetchMode(PDO::FETCH_OBJ);
            $buscar_edificio->execute();

            $encontar_edificio = $buscar_edificio->fetchAll();

            if ($buscar_edificio -> rowCount() > 0) {
                foreach ($encontar_edificio as $edificio_encontrado) {
                    $id_edificio2 = $edificio_encontrado -> id_edificio;
                    $empresa_id2 = $edificio_encontrado -> empresa_id;
                    $descipcion2 = $edificio_encontrado -> descripcion;

                    // Obtener el nombre de la empresa en base al ID obtenido por el edificio
                    $companier = 'empresas';
                    $buscar_empresa_nombre = $con -> prepare("SELECT id, razon_social FROM $companier WHERE id = :empresa_id2");
                    $buscar_empresa_nombre->bindValue(':empresa_id2', $empresa_id2);
                    $buscar_empresa_nombre->setFetchMode(PDO::FETCH_OBJ);
                    $buscar_empresa_nombre->execute();

                    $encontar_empresa_nombre = $buscar_empresa_nombre->fetchAll();

                    if ($buscar_empresa_nombre -> rowCount() > 0) {
                        foreach ($encontar_empresa_nombre as $nombre_empresa_encontado) {
                            $id_empresa2 = $nombre_empresa_encontado -> id;
                            $razon_empresa2 = $nombre_empresa_encontado -> razon_social;
                        }
                    }
                }
            }

            $accountant = 'contadores';

            // Conteo de los resultados
            require '../../functions/conex_serv.php';
            $count_accountant2 = $con -> prepare("SELECT COUNT(*) FROM $accountant WHERE empresa_vinculada LIKE '%$id_edificio2%'");
            $count_accountant2 -> execute();
            $total_registros2 = $count_accountant2 -> fetchColumn();

            // Consulta de información
            $q_accountants2 = $con -> prepare("SELECT * FROM $accountant WHERE empresa_vinculada LIKE '%$id_edificio2%' ORDER BY numero_control ASC");
            $q_accountants2->setFetchMode(PDO::FETCH_OBJ);
            $q_accountants2->execute();

            $s_accountants2 = $q_accountants2->fetchAll();

            if ($q_accountants2 -> rowCount() > 0) {
                echo '
                <a href="index.php"><button type="submit" value="Volver" class="btn btn-primary" style="text-align:center"><i class="fa fa-reply"></i>&nbsp;&nbsp;Volver</button></a>
                <br><br>
                <p><strong>Búsqueda realizada: </strong><span class="badge bg-success">'.$razon_empresa2.'</span></p>
                <p><strong>Registros encontrados: </strong><span class="badge bg-success">'.$total_registros2.'</span></p>';

                echo '<table class="table table-responsive table-hover table-striped table-bordered">
                <th>Acción</th>
                <th>ID</th>
                <th>No. Control</th>
                <th>Descripción</th>
                <th>Empresa Vinculada</th>
                <th>Modelo</th>
                <th>Número de Serie</th>
                <th>Estado</th>
                <th>Rango</th>
                <th>Frencuencia de Calibración</th>
                <th>Vigencia Inicial</th>
                <th>Vigencia Final</th>
                <th>Comentarios</th>
                <th>Área asignada</th>
                <tr>';
                foreach ($s_accountants2 as $counter2) {
                    $id_contador = $counter2 -> id_contador;
                    $descripcion_nombre = $counter2 -> descripcion_nombre;
                    $modelo_ci = $counter2 -> modelo_ci;
                    $numero_serie = $counter2 -> numero_serie;
                    $numero_control = $counter2 -> numero_control;
                    $estado = $counter2 -> estado;
                    $rango = $counter2 -> rango;
                    $frecuencia_calibracion = $counter2 -> frecuencia_calibracion;
                    $vigencia_inicial = $counter2 -> vigencia_inicial;
                    $vigencia_final = $counter2 -> vigencia_final;
                    $comentarios = $counter2 -> comentarios;
                    $area_asignada = $counter2 -> area_asignada;
                    $empresa_vinculada = $counter2 -> empresa_vinculada;

                    require '../../functions/conex.php';
                    // Se busca el edificio asignado
                    $build = 'edificio';
                    $b_edificio = $con -> prepare("SELECT id_edificio, empresa_id FROM $build WHERE id_edificio = :id_edificio");
                    $b_edificio->bindValue('id_edificio', $empresa_vinculada);
                    $b_edificio->setFetchMode(PDO::FETCH_OBJ);
                    $b_edificio->execute();

                    $e_edificio = $b_edificio->fetchAll();

                    if ($b_edificio -> rowCount() > 0) {
                        foreach ($e_edificio as $edificio) {
                            $id_edificio = $edificio -> id_edificio;
                            $empresa_id = $edificio -> empresa_id;

                            // Se busca la empresa en base al edificio
                            $company = 'empresas';
                            $b_empresa = $con -> prepare("SELECT id, razon_social FROM $company WHERE id = :id");
                            $b_empresa->bindValue(':id', $empresa_id);
                            $b_empresa->setFetchMode(PDO::FETCH_OBJ);
                            $b_empresa->execute();

                            $e_empresa = $b_empresa->fetchAll();

                            if ($b_empresa -> rowCount() > 0) {
                                foreach ($e_empresa as $empresa) {
                                    $id_empresa = $empresa -> id;
                                    $razon_social = $empresa -> razon_social;
                                }
                            }
                        }
                    }

                    echo '<td class="text-center">
                    <a href="modify_accountant.php?'.$id_contador.'" class="btn btn-sm btn-warning"><i class="fa fa-pencil" aria-hidden="true"></i></a>
                    <a href="delte_accountant.php?'.$id_contador.'"class="btn btn-sm btn-danger"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
                    </td>
                    <td>'.$id_contador.'</td>
                    <td><strong>'.$numero_control.'</strong></td>
                    <td>'.$descripcion_nombre.'</td>
                    <td><strong>'.$razon_social.'</strong></td>
                    <td>'.$modelo_ci.'</td>
                    <td>'.$numero_serie.'</td>
                    <td><strong>'.$estado.'</strong></td>
                    <td>'.$rango.'</td>
                    <td>'.$frecuencia_calibracion.'</td>
                    <td>'.$vigencia_inicial.'</td>
                    <td>'.$vigencia_final.'</td>
                    <td>'.$comentarios.'</td>
                    <td>'.$area_asignada.'</td>
                    <tr>';
                }
                echo '</table>';
            } else {
                echo '<br><br><br><br><br><br><br>
                <a href="index.php" style="padding-left:15%;"><button type="submit" value="Volver" class="btn btn-primary" style="text-align:center"><i class="fa fa-reply"></i>&nbsp;&nbsp;Volver</button></a>
                <br><br>
                <center><h2 class="pull-left" style="padding-left:15%;">No se encontraron resultados para: <strong><u>'.$razon_empresa2.'</u></strong></h2></center>';
            }
        } else {
            // Consulta de información
            require '../../functions/conex_serv.php';
            $accountant = 'contadores';
            $q_accountants = $con -> prepare("SELECT * FROM $accountant ORDER BY numero_control ASC");
            $q_accountants->setFetchMode(PDO::FETCH_OBJ);
            $q_accountants->execute();

            $s_accountants = $q_accountants->fetchAll();

            if ($q_accountants -> rowCount() > 0) {
                echo '<table class="table table-responsive table-hover table-striped table-bordered">
                <th>Acción</th>
                <th>ID</th>
                <th>No. Control</th>
                <th>Descripción</th>
                <th>Empresa Vinculada</th>
                <th>Modelo</th>
                <th>Número de Serie</th>
                <th>Estado</th>
                <th>Rango</th>
                <th>Frencuencia de Calibración</th>
                <th>Vigencia Inicial</th>
                <th>Vigencia Final</th>
                <th>Comentarios</th>
                <th>Área asignada</th>
                <tr>';
                foreach ($s_accountants as $counter) {
                    $id_contador = $counter -> id_contador;
                    $descripcion_nombre = $counter -> descripcion_nombre;
                    $modelo_ci = $counter -> modelo_ci;
                    $numero_serie = $counter -> numero_serie;
                    $numero_control = $counter -> numero_control;
                    $estado = $counter -> estado;
                    $rango = $counter -> rango;
                    $frecuencia_calibracion = $counter -> frecuencia_calibracion;
                    $vigencia_inicial = $counter -> vigencia_inicial;
                    $vigencia_final = $counter -> vigencia_final;
                    $comentarios = $counter -> comentarios;
                    $area_asignada = $counter -> area_asignada;
                    $empresa_vinculada = $counter -> empresa_vinculada;

                    require '../../functions/conex.php';
                    // Se busca el edificio asignado
                    $build = 'edificio';
                    $b_edificio = $con -> prepare("SELECT id_edificio, empresa_id FROM $build WHERE id_edificio = :id_edificio");
                    $b_edificio->bindValue('id_edificio', $empresa_vinculada);
                    $b_edificio->setFetchMode(PDO::FETCH_OBJ);
                    $b_edificio->execute();

                    $e_edificio = $b_edificio->fetchAll();

                    if ($b_edificio -> rowCount() > 0) {
                        foreach ($e_edificio as $edificio) {
                            $id_edificio = $edificio -> id_edificio;
                            $empresa_id = $edificio -> empresa_id;

                            // Se busca la empresa en base al edificio
                            $company = 'empresas';
                            $b_empresa = $con -> prepare("SELECT id, razon_social FROM $company WHERE id = :id");
                            $b_empresa->bindValue(':id', $empresa_id);
                            $b_empresa->setFetchMode(PDO::FETCH_OBJ);
                            $b_empresa->execute();

                            $e_empresa = $b_empresa->fetchAll();

                            if ($b_empresa -> rowCount() > 0) {
                                foreach ($e_empresa as $empresa) {
                                    $id_empresa = $empresa -> id;
                                    $razon_social = $empresa -> razon_social;
                                }
                            }
                        }
                    }

                    echo '<td class="text-center">
                    <a href="modify_accountant.php?'.$id_contador.'" class="btn btn-sm btn-warning"><i class="fa fa-pencil" aria-hidden="true"></i></a>
                    <a href="delte_accountant.php?'.$id_contador.'"class="btn btn-sm btn-danger"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
                    </td>
                    <td>'.$id_contador.'</td>
                    <td><strong>'.$numero_control.'</strong></td>
                    <td>'.$descripcion_nombre.'</td>
                    <td><strong>'.$razon_social.'</strong></td>
                    <td>'.$modelo_ci.'</td>
                    <td>'.$numero_serie.'</td>
                    <td><strong>'.$estado.'</strong></td>
                    <td>'.$rango.'</td>
                    <td>'.$frecuencia_calibracion.'</td>
                    <td>'.$vigencia_inicial.'</td>
                    <td>'.$vigencia_final.'</td>
                    <td>'.$comentarios.'</td>
                    <td>'.$area_asignada.'</td>
                    <tr>';
                }
                echo '</table>';
            } else {
                echo '<br><br><br><br><br><br><br>
                <center><h2 class="pull-left">No se encontraron contadores cargados en el sistema</h2></center>';
            }
        }
        require '../../functions/drop_con.php';
}else{
	header('Location: ../../../index.php');
}
?>
</body>
</html>