<?php
session_start();

if ($_SESSION['nombre'] != '' && $_SESSION['tipo'] == 'devecchi' || $_SESSION['tipo'] == 'admin') {
    include '../../assets/layout.php';
    section();

    $id_documento = $_SERVER['QUERY_STRING'];

    function mensaje_error() {
        echo '
            <div class="alert alert-danger alert-dismissible fade in col-sm-3 animated bounceInDown" role="alert" style="position:fixed; top:70px; right:10px; z-index:10;"> 
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
            <h4 class="text-center"><strong>OCURRIÓ UN ERROR</strong></h4>';

            switch ($_SESSION['tipo']) {
                case 'admin':
                    echo '<p class="text-center">
                    <u>No se logró recibir información de las partículas, por favor, agrega una partícula nueva.
                    </p>
                    <p class="text-center">
                    <a href="../../../../admin/particles/" class="btn btn-primary" style="text-align:center" target="_blank">Ver Partículas</a>
                    </p>';
                break;

                case 'devecchi':
                    echo '<p class="text-center">
                    <u>No se logró recibir información de las partículas, por favor, inténtalo de nuevo o contácta al Soporte Técnico.
                    </p>';
                break;
            }

        echo '</div>';
    }

    // Se obtiene información de las partículas estándar disponibles
    require '../../../functions/conex_serv.php';
    $particles = 'particulas';
?>

        <table>
        <tr>
        <button onClick="document.location.reload();" type="submit" class="btn btn-danger" data-toggle="tooltip" data-placement="top" title="Haz clic para reiniciar el formulario" HSPACE="10" VSPACE="10"><i class="fa fa-refresh fa-spin  fa-fw"></i>
        <span class="sr-only">Cargando...</span>Reiniciar formulario</button>
        </tr>
        </td>
        </table>

        <div class="container" style="width: 1030px;">
            <div class="row" style="width: 770px;">
                <div class="col-sm-12">
                    <div class="page-header2">
                        <h1 class="animated lightSpeedIn">Certificado: <strong><u><?php echo $id_documento; ?></u></strong> | Partículas Estándar</h1>
                        <span class="label label-danger"></span> 		 
                        <p class="pull-right text-primary"></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="container" style="margin-left: 5%;">
            <div class="row">
                <div class="col-sm-13">
                    <div class="panel panel-success">
                        <div class="panel-heading text-center"><strong>Para poder crear un nuevo certificado es necesario llenar los todos campos</strong></div>
                            <div class="panel-body">
                                <?php echo '<form role="form" action="../../../functions/add/standar_particles.php?'.$id_documento.'" method="POST" enctype="multipart/form-data">'; ?>
                                    <div>
                                        <div class="container">
                                            <table class="table table-responsive table-hover table-bordered table-striped table-primary" style="margin-left: -15px;">
                                                <thead>
                                                    <tr>
                                                    <th>MEDIDA NOMINAL</th>
                                                    <th>TAMAÑO REAL</th>
                                                    <th>DESVIACIÓN DE TAMAÑO</th>
                                                    <th>No. LOTE</th>
                                                    <th>EXP. FECHA</th>
                                                    <th>MEDIDA NOMINAL</th>
                                                    <th>TAMAÑO REAL</th>
                                                    <th>DESVIACIÓN DE TAMAÑO</th>
                                                    <th>No. LOTE</th>
                                                    <th>EXP. FECHA</th>
                                                    </tr>
                                                </thead>

                                                <!-- PRIMERA FILA -->
                                                <tbody>
                                                    <tr>
                                                    <td><strong>0.3 µm</strong></td>
                                                    <td>
                                                        <select class="form-control" name="tamano_real_03" required>
                                                            <option value=""></option>
                                                            <?php
                                                            /***************************
                                                            TAMAÑO REAL DE PARTÍCULA 0.3
                                                            ***************************/
                                                            $s_particle = $con->prepare("SELECT * FROM $particles WHERE tamano_nominal = '0.3' AND estado = 'Activo'");
                                                            $s_particle->setFetchMode(PDO::FETCH_OBJ);
                                                            $s_particle->execute();
                                                            $f_particle = $s_particle->fetchAll();
                                                            
                                                            if ($s_particle -> rowCount() > 0) {
                                                                foreach ($f_particle as $particula) {
                                                                    $tamano_real_03 = $particula -> tamano_real;

                                                                    echo '<option value="'.$tamano_real_03.'">'.$tamano_real_03.'</option>';
                                                                }
                                                            } else {
                                                                mensaje_error();
                                                            }
                                                            ?>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <select class="form-control" name="desviacion_tamano_03" required>
                                                            <option value=""></option>
                                                            <?php
                                                            /***********************
                                                            DESVIACIÓN DE TAMAÑO 0.3
                                                            ***********************/
                                                            $s_particle2 = $con->prepare("SELECT * FROM $particles WHERE tamano_nominal = '0.3' AND estado = 'Activo'");
                                                            $s_particle2->setFetchMode(PDO::FETCH_OBJ);
                                                            $s_particle2->execute();
                                                            $f_particle2 = $s_particle2->fetchAll();
                                                            
                                                            if ($s_particle2 -> rowCount() > 0) {
                                                                foreach ($f_particle2 as $particula2) {
                                                                    $desviacion_tamano_03 = $particula2 -> desviacion_tamano;

                                                                    echo '<option value="'.$desviacion_tamano_03.'">'.$desviacion_tamano_03.'</option>';
                                                                }
                                                            } else {
                                                                mensaje_error();
                                                            }
                                                            ?>
                                                        </select>±
                                                    </td>
                                                    <td>
                                                        <select class="form-control" name="no_lote_03" required>
                                                            <option value=""></option>
                                                            <?php
                                                            /**************
                                                            NO. DE LOTE 0.3
                                                            **************/
                                                            $s_particle3 = $con->prepare("SELECT * FROM $particles WHERE tamano_nominal = '0.3' AND estado = 'Activo'");
                                                            $s_particle3->setFetchMode(PDO::FETCH_OBJ);
                                                            $s_particle3->execute();
                                                            $f_particle3 = $s_particle3->fetchAll();
                                                            
                                                            if ($s_particle3 -> rowCount() > 0) {
                                                                foreach ($f_particle3 as $particula3) {
                                                                    $no_lote_03 = $particula3 -> no_lote;

                                                                    echo '<option value="'.$no_lote_03.'">'.$no_lote_03.'</option>';
                                                                }
                                                            } else {
                                                                mensaje_error();
                                                            }
                                                            ?>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <select class="form-control" name="exp_fecha_03" required>
                                                            <option value=""></option>
                                                            <?php
                                                            /**********************
                                                            FECHA DE EXPIRACIÓN 0.3
                                                            **********************/
                                                            $s_particle4 = $con->prepare("SELECT * FROM $particles WHERE tamano_nominal = '0.3' AND estado = 'Activo'");
                                                            $s_particle4->setFetchMode(PDO::FETCH_OBJ);
                                                            $s_particle4->execute();
                                                            $f_particle4 = $s_particle4->fetchAll();
                                                            
                                                            if ($s_particle4 -> rowCount() > 0) {
                                                                foreach ($f_particle4 as $particula4) {
                                                                    $exp_fecha_03 = $particula4 -> exp_fecha;

                                                                    echo '<option value="'.$exp_fecha_03.'">'.$exp_fecha_03.'</option>';
                                                                }
                                                            } else {
                                                                mensaje_error();
                                                            }
                                                            ?>
                                                        </select>
                                                    </td>

                                                    <td><strong>0.8 µm</strong></td>
                                                    <td>
                                                        <select class="form-control" name="tamano_real_08" required>
                                                            <option value=""></option>
                                                            <?php
                                                            /***************************
                                                            TAMAÑO REAL DE PARTÍCULA 0.8
                                                            ***************************/
                                                            $s_particle5 = $con->prepare("SELECT * FROM $particles WHERE tamano_nominal = '0.8' AND estado = 'Activo'");
                                                            $s_particle5->setFetchMode(PDO::FETCH_OBJ);
                                                            $s_particle5->execute();
                                                            $f_particle5 = $s_particle5->fetchAll();
                                                            
                                                            if ($s_particle5 -> rowCount() > 0) {
                                                                foreach ($f_particle5 as $particula5) {
                                                                    $tamano_real_08 = $particula5 -> tamano_real;

                                                                    echo '<option value="'.$tamano_real_08.'">'.$tamano_real_08.'</option>';
                                                                }
                                                            } else {
                                                                mensaje_error();
                                                            }
                                                            ?>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <select class="form-control" name="desviacion_tamano_08" required>
                                                            <option value=""></option>
                                                            <?php
                                                            /***************************
                                                            DESVIACIÓN DE TAMAÑO 0.8
                                                            ***************************/
                                                            $s_particle6 = $con->prepare("SELECT * FROM $particles WHERE tamano_nominal = '0.8' AND estado = 'Activo'");
                                                            $s_particle6->setFetchMode(PDO::FETCH_OBJ);
                                                            $s_particle6->execute();
                                                            $f_particle6 = $s_particle6->fetchAll();
                                                            
                                                            if ($s_particle6 -> rowCount() > 0) {
                                                                foreach ($f_particle6 as $particula6) {
                                                                    $desviacion_tamano_08 = $particula6 -> desviacion_tamano;

                                                                    echo '<option value="'.$desviacion_tamano_08.'">'.$desviacion_tamano_08.'</option>';
                                                                }
                                                            } else {
                                                                mensaje_error();
                                                            }
                                                            ?>
                                                        </select>±
                                                    </td>
                                                    <td>
                                                        <select class="form-control" name="no_lote_08" required>
                                                            <option value=""></option>
                                                            <?php
                                                            /***************************
                                                            NO DE LOTE 0.8
                                                            ***************************/
                                                            $s_particle7 = $con->prepare("SELECT * FROM $particles WHERE tamano_nominal = '0.8' AND estado = 'Activo'");
                                                            $s_particle7->setFetchMode(PDO::FETCH_OBJ);
                                                            $s_particle7->execute();
                                                            $f_particle7 = $s_particle7->fetchAll();
                                                            
                                                            if ($s_particle7 -> rowCount() > 0) {
                                                                foreach ($f_particle7 as $particula7) {
                                                                    $no_lote_08 = $particula7 -> no_lote;

                                                                    echo '<option value="'.$no_lote_08.'">'.$no_lote_08.'</option>';
                                                                }
                                                            } else {
                                                                mensaje_error();
                                                            }
                                                            ?>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <select class="form-control" name="exp_fecha_08" required>
                                                            <option value=""></option>
                                                            <?php
                                                            /***************************
                                                            FECHA DE EXPIRACIÓN 0.8
                                                            ***************************/
                                                            $s_particle8 = $con->prepare("SELECT * FROM $particles WHERE tamano_nominal = '0.8' AND estado = 'Activo'");
                                                            $s_particle8->setFetchMode(PDO::FETCH_OBJ);
                                                            $s_particle8->execute();
                                                            $f_particle8 = $s_particle8->fetchAll();
                                                            
                                                            if ($s_particle8 -> rowCount() > 0) {
                                                                foreach ($f_particle8 as $particula8) {
                                                                    $exp_fecha_08 = $particula8 -> exp_fecha;

                                                                    echo '<option value="'.$exp_fecha_08.'">'.$exp_fecha_08.'</option>';
                                                                }
                                                            } else {
                                                                mensaje_error();
                                                            }
                                                            ?>
                                                        </select>
                                                    </td>
                                                    </tr>
                                                </tbody>

                                                <!-- SEGUNDA FILA -->
                                                <tbody>
                                                    <tr>
                                                    <td><strong>0.4 µm</strong></td>
                                                    <td>
                                                        <select class="form-control" name="tamano_real_04" required>
                                                            <option value=""></option>
                                                            <?php
                                                            /***************************
                                                            TAMAÑO REAL DE PARTÍCULA 0.4
                                                            ***************************/
                                                            $s_particle9 = $con->prepare("SELECT * FROM $particles WHERE tamano_nominal = '0.4' AND estado = 'Activo'");
                                                            $s_particle9->setFetchMode(PDO::FETCH_OBJ);
                                                            $s_particle9->execute();
                                                            $f_particle9 = $s_particle9->fetchAll();
                                                            
                                                            if ($s_particle9 -> rowCount() > 0) {
                                                                foreach ($f_particle9 as $particula9) {
                                                                    $tamano_real_04 = $particula9 -> tamano_real;

                                                                    echo '<option value="'.$tamano_real_04.'">'.$tamano_real_04.'</option>';
                                                                }
                                                            } else {
                                                                mensaje_error();
                                                            }
                                                            ?>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <select class="form-control" name="desviacion_tamano_04" required>
                                                            <option value=""></option>
                                                            <?php
                                                            /***************************
                                                            DESVIACIÓN DE TAMAÑO 0.4
                                                            ***************************/
                                                            $s_particle10 = $con->prepare("SELECT * FROM $particles WHERE tamano_nominal = '0.4' AND estado = 'Activo'");
                                                            $s_particle10->setFetchMode(PDO::FETCH_OBJ);
                                                            $s_particle10->execute();
                                                            $f_particle10 = $s_particle10->fetchAll();
                                                            
                                                            if ($s_particle10 -> rowCount() > 0) {
                                                                foreach ($f_particle10 as $particula10) {
                                                                    $desviacion_tamano_04 = $particula10 -> desviacion_tamano;

                                                                    echo '<option value="'.$desviacion_tamano_04.'">'.$desviacion_tamano_04.'</option>';
                                                                }
                                                            } else {
                                                                mensaje_error();
                                                            }
                                                            ?>
                                                        </select>±
                                                    </td>
                                                    <td>
                                                        <select class="form-control" name="no_lote_04" required>
                                                            <option value=""></option>
                                                            <?php
                                                            /***************************
                                                            NO DE LOTE 0.4
                                                            ***************************/
                                                            $s_particle11 = $con->prepare("SELECT * FROM $particles WHERE tamano_nominal = '0.4' AND estado = 'Activo'");
                                                            $s_particle11->setFetchMode(PDO::FETCH_OBJ);
                                                            $s_particle11->execute();
                                                            $f_particle11 = $s_particle11->fetchAll();
                                                            
                                                            if ($s_particle11 -> rowCount() > 0) {
                                                                foreach ($f_particle11 as $particula11) {
                                                                    $no_lote_04 = $particula11 -> no_lote;

                                                                    echo '<option value="'.$no_lote_04.'">'.$no_lote_04.'</option>';
                                                                }
                                                            } else {
                                                                mensaje_error();
                                                            }
                                                            ?>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <select class="form-control" name="exp_fecha_04" required>
                                                            <option value=""></option>
                                                            <?php
                                                            /***************************
                                                            FECHA DE EXPIRACIÓN 0.4
                                                            ***************************/
                                                            $s_particle12 = $con->prepare("SELECT * FROM $particles WHERE tamano_nominal = '0.4' AND estado = 'Activo'");
                                                            $s_particle12->setFetchMode(PDO::FETCH_OBJ);
                                                            $s_particle12->execute();
                                                            $f_particle12 = $s_particle12->fetchAll();
                                                            
                                                            if ($s_particle12 -> rowCount() > 0) {
                                                                foreach ($f_particle12 as $particula12) {
                                                                    $exp_fecha_04 = $particula12 -> exp_fecha;

                                                                    echo '<option value="'.$exp_fecha_04.'">'.$exp_fecha_04.'</option>';
                                                                }
                                                            } else {
                                                                mensaje_error();
                                                            }
                                                            ?>
                                                        </select>
                                                    </td>

                                                    <td><strong>1.0 µm</strong></td>
                                                    <td>
                                                        <select class="form-control" name="tamano_real_10" required>
                                                            <option value=""></option>
                                                            <?php
                                                            /***************************
                                                            TAMAÑO REAL DE PARTÍCULA 1.0
                                                            ***************************/
                                                            $s_particle13 = $con->prepare("SELECT * FROM $particles WHERE tamano_nominal = '1.0' AND estado = 'Activo'");
                                                            $s_particle13->setFetchMode(PDO::FETCH_OBJ);
                                                            $s_particle13->execute();
                                                            $f_particle13 = $s_particle13->fetchAll();
                                                            
                                                            if ($s_particle13 -> rowCount() > 0) {
                                                                foreach ($f_particle13 as $particula13) {
                                                                    $tamano_real_10 = $particula13 -> tamano_real;

                                                                    echo '<option value="'.$tamano_real_10.'">'.$tamano_real_10.'</option>';
                                                                }
                                                            } else {
                                                                mensaje_error();
                                                            }
                                                            ?>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <select class="form-control" name="desviacion_tamano_10" required>
                                                            <option value=""></option>
                                                            <?php
                                                            /***************************
                                                            DESVIACIÓN DE TAMAÑO 1.0
                                                            ***************************/
                                                            $s_particle14 = $con->prepare("SELECT * FROM $particles WHERE tamano_nominal = '1.0' AND estado = 'Activo'");
                                                            $s_particle14->setFetchMode(PDO::FETCH_OBJ);
                                                            $s_particle14->execute();
                                                            $f_particle14 = $s_particle14->fetchAll();
                                                            
                                                            if ($s_particle14 -> rowCount() > 0) {
                                                                foreach ($f_particle14 as $particula14) {
                                                                    $desviacion_tamano_10 = $particula14 -> desviacion_tamano;

                                                                    echo '<option value="'.$desviacion_tamano_10.'">'.$desviacion_tamano_10.'</option>';
                                                                }
                                                            } else {
                                                                mensaje_error();
                                                            }
                                                            ?>
                                                        </select>±
                                                    </td>
                                                    <td>
                                                        <select class="form-control" name="no_lote_10" required>
                                                            <option value=""></option>
                                                            <?php
                                                            /***************************
                                                            NO DE LOTE 1.0
                                                            ***************************/
                                                            $s_particle15 = $con->prepare("SELECT * FROM $particles WHERE tamano_nominal = '1.0' AND estado = 'Activo'");
                                                            $s_particle15->setFetchMode(PDO::FETCH_OBJ);
                                                            $s_particle15->execute();
                                                            $f_particle15 = $s_particle15->fetchAll();
                                                            
                                                            if ($s_particle15 -> rowCount() > 0) {
                                                                foreach ($f_particle15 as $particula15) {
                                                                    $no_lote_10 = $particula15 -> no_lote;

                                                                    echo '<option value="'.$no_lote_10.'">'.$no_lote_10.'</option>';
                                                                }
                                                            } else {
                                                                mensaje_error();
                                                            }
                                                            ?>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <select class="form-control" name="exp_fecha_10" required>
                                                            <option value=""></option>
                                                            <?php
                                                            /***************************
                                                            FECHA DE EXPIRACIÓN 1.0
                                                            ***************************/
                                                            $s_particle16 = $con->prepare("SELECT * FROM $particles WHERE tamano_nominal = '1.0' AND estado = 'Activo'");
                                                            $s_particle16->setFetchMode(PDO::FETCH_OBJ);
                                                            $s_particle16->execute();
                                                            $f_particle16 = $s_particle16->fetchAll();
                                                            
                                                            if ($s_particle16 -> rowCount() > 0) {
                                                                foreach ($f_particle16 as $particula16) {
                                                                    $exp_fecha_10 = $particula16 -> exp_fecha;

                                                                    echo '<option value="'.$exp_fecha_10.'">'.$exp_fecha_10.'</option>';
                                                                }
                                                            } else {
                                                                mensaje_error();
                                                            }
                                                            ?>
                                                        </select>
                                                    </td>
                                                    </tr>
                                                </tbody>

                                                <!-- TERCERA FILA -->
                                                <tbody>
                                                    <tr>
                                                    <td><strong>0.5 µm</strong></td>
                                                    <td>
                                                        <select class="form-control" name="tamano_real_05" required>
                                                            <option value=""></option>
                                                            <?php
                                                            /***************************
                                                            TAMAÑO REAL DE PARTÍCULA 0.5
                                                            ***************************/
                                                            $s_particle17 = $con->prepare("SELECT * FROM $particles WHERE tamano_nominal = '0.5' AND estado = 'Activo'");
                                                            $s_particle17->setFetchMode(PDO::FETCH_OBJ);
                                                            $s_particle17->execute();
                                                            $f_particle17 = $s_particle17->fetchAll();
                                                            
                                                            if ($s_particle17 -> rowCount() > 0) {
                                                                foreach ($f_particle17 as $particula17) {
                                                                    $tamano_real_05 = $particula17 -> tamano_real;

                                                                    echo '<option value="'.$tamano_real_05.'">'.$tamano_real_05.'</option>';
                                                                }
                                                            } else {
                                                                mensaje_error();
                                                            }
                                                            ?>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <select class="form-control" name="desviacion_tamano_05" required>
                                                            <option value=""></option>
                                                            <?php
                                                            /***************************
                                                            DESVIACIÓN DE TAMAÑO 0.5
                                                            ***************************/
                                                            $s_particle18 = $con->prepare("SELECT * FROM $particles WHERE tamano_nominal = '0.5' AND estado = 'Activo'");
                                                            $s_particle18->setFetchMode(PDO::FETCH_OBJ);
                                                            $s_particle18->execute();
                                                            $f_particle18 = $s_particle18->fetchAll();
                                                            
                                                            if ($s_particle18 -> rowCount() > 0) {
                                                                foreach ($f_particle18 as $particula18) {
                                                                    $desviacion_tamano_05 = $particula18 -> desviacion_tamano;

                                                                    echo '<option value="'.$desviacion_tamano_05.'">'.$desviacion_tamano_05.'</option>';
                                                                }
                                                            } else {
                                                                mensaje_error();
                                                            }
                                                            ?>
                                                        </select>±
                                                    </td>
                                                    <td>
                                                        <select class="form-control" name="no_lote_05" required>
                                                            <option value=""></option>
                                                            <?php
                                                            /***************************
                                                            NO DE LOTE 0.5
                                                            ***************************/
                                                            $s_particle19 = $con->prepare("SELECT * FROM $particles WHERE tamano_nominal = '0.5' AND estado = 'Activo'");
                                                            $s_particle19->setFetchMode(PDO::FETCH_OBJ);
                                                            $s_particle19->execute();
                                                            $f_particle19 = $s_particle19->fetchAll();
                                                            
                                                            if ($s_particle19 -> rowCount() > 0) {
                                                                foreach ($f_particle19 as $particula19) {
                                                                    $no_lote_05 = $particula19 -> no_lote;

                                                                    echo '<option value="'.$no_lote_05.'">'.$no_lote_05.'</option>';
                                                                }
                                                            } else {
                                                                mensaje_error();
                                                            }
                                                            ?>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <select class="form-control" name="exp_fecha_05" required>
                                                            <option value=""></option>
                                                            <?php
                                                            /***************************
                                                            FECHA DE EXPIRACIÓN 0.5
                                                            ***************************/
                                                            $s_particle20 = $con->prepare("SELECT * FROM $particles WHERE tamano_nominal = '0.5' AND estado = 'Activo'");
                                                            $s_particle20->setFetchMode(PDO::FETCH_OBJ);
                                                            $s_particle20->execute();
                                                            $f_particle20 = $s_particle20->fetchAll();
                                                            
                                                            if ($s_particle20 -> rowCount() > 0) {
                                                                foreach ($f_particle20 as $particula20) {
                                                                    $exp_fecha_05 = $particula20 -> exp_fecha;

                                                                    echo '<option value="'.$exp_fecha_05.'">'.$exp_fecha_05.'</option>';
                                                                }
                                                            } else {
                                                                mensaje_error();
                                                            }
                                                            ?>
                                                        </select>
                                                    </td>

                                                    <td><strong>3.0 µm</strong></td>
                                                    <td>
                                                        <select class="form-control" name="tamano_real_30" required>
                                                            <option value=""></option>
                                                            <?php
                                                            /***************************
                                                            TAMAÑO REAL DE PARTÍCULA 3.0
                                                            ***************************/
                                                            $s_particle21 = $con->prepare("SELECT * FROM $particles WHERE tamano_nominal = '3.0' AND estado = 'Activo'");
                                                            $s_particle21->setFetchMode(PDO::FETCH_OBJ);
                                                            $s_particle21->execute();
                                                            $f_particle21 = $s_particle21->fetchAll();
                                                            
                                                            if ($s_particle21 -> rowCount() > 0) {
                                                                foreach ($f_particle21 as $particula21) {
                                                                    $tamano_real_30 = $particula21 -> tamano_real;

                                                                    echo '<option value="'.$tamano_real_30.'">'.$tamano_real_30.'</option>';
                                                                }
                                                            } else {
                                                                mensaje_error();
                                                            }
                                                            ?>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <select class="form-control" name="desviacion_tamano_30" required>
                                                            <option value=""></option>
                                                            <?php
                                                            /***************************
                                                            DESVIACIÓN DE TAMAÑO 3.0
                                                            ***************************/
                                                            $s_particle22 = $con->prepare("SELECT * FROM $particles WHERE tamano_nominal = '3.0' AND estado = 'Activo'");
                                                            $s_particle22->setFetchMode(PDO::FETCH_OBJ);
                                                            $s_particle22->execute();
                                                            $f_particle22 = $s_particle22->fetchAll();
                                                            
                                                            if ($s_particle22 -> rowCount() > 0) {
                                                                foreach ($f_particle22 as $particula22) {
                                                                    $desviacion_tamano_30 = $particula22 -> desviacion_tamano;

                                                                    echo '<option value="'.$desviacion_tamano_30.'">'.$desviacion_tamano_30.'</option>';
                                                                }
                                                            } else {
                                                                mensaje_error();
                                                            }
                                                            ?>
                                                        </select>±
                                                    </td>
                                                    <td>
                                                        <select class="form-control" name="no_lote_30" required>
                                                            <option value=""></option>
                                                            <?php
                                                            /***************************
                                                            NO DE LOTE 3.0
                                                            ***************************/
                                                            $s_particle23 = $con->prepare("SELECT * FROM $particles WHERE tamano_nominal = '3.0' AND estado = 'Activo'");
                                                            $s_particle23->setFetchMode(PDO::FETCH_OBJ);
                                                            $s_particle23->execute();
                                                            $f_particle23 = $s_particle23->fetchAll();
                                                            
                                                            if ($s_particle23 -> rowCount() > 0) {
                                                                foreach ($f_particle23 as $particula23) {
                                                                    $no_lote_30 = $particula23 -> no_lote;

                                                                    echo '<option value="'.$no_lote_30.'">'.$no_lote_30.'</option>';
                                                                }
                                                            } else {
                                                                mensaje_error();
                                                            }
                                                            ?>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <select class="form-control" name="exp_fecha_30" required>
                                                            <option value=""></option>
                                                            <?php
                                                            /***************************
                                                            FECHA DE EXPIRACIÓN 3.0
                                                            ***************************/
                                                            $s_particle24 = $con->prepare("SELECT * FROM $particles WHERE tamano_nominal = '3.0' AND estado = 'Activo'");
                                                            $s_particle24->setFetchMode(PDO::FETCH_OBJ);
                                                            $s_particle24->execute();
                                                            $f_particle24 = $s_particle24->fetchAll();
                                                            
                                                            if ($s_particle24 -> rowCount() > 0) {
                                                                foreach ($f_particle24 as $particula24) {
                                                                    $exp_fecha_30 = $particula24 -> exp_fecha;

                                                                    echo '<option value="'.$exp_fecha_30.'">'.$exp_fecha_30.'</option>';
                                                                }
                                                            } else {
                                                                mensaje_error();
                                                            }
                                                            ?>
                                                        </select>
                                                    </td>
                                                    </tr>
                                                </tbody>

                                                <!-- CUARTA FILA -->
                                                <tbody>
                                                    <tr>
                                                    <td><strong>0.6 µm</strong></td>
                                                    <td>
                                                        <select class="form-control" name="tamano_real_06" required>
                                                            <option value=""></option>
                                                            <?php
                                                            /***************************
                                                            TAMAÑO REAL DE PARTÍCULA 0.6
                                                            ***************************/
                                                            $s_particle25 = $con->prepare("SELECT * FROM $particles WHERE tamano_nominal = '0.6' AND estado = 'Activo'");
                                                            $s_particle25->setFetchMode(PDO::FETCH_OBJ);
                                                            $s_particle25->execute();
                                                            $f_particle25 = $s_particle25->fetchAll();
                                                            
                                                            if ($s_particle25 -> rowCount() > 0) {
                                                                foreach ($f_particle25 as $particula25) {
                                                                    $tamano_real_06 = $particula25 -> tamano_real;

                                                                    echo '<option value="'.$tamano_real_06.'">'.$tamano_real_06.'</option>';
                                                                }
                                                            } else {
                                                                mensaje_error();
                                                            }
                                                            ?>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <select class="form-control" name="desviacion_tamano_06" required>
                                                            <option value=""></option>
                                                            <?php
                                                            /***************************
                                                            DESVIACIÓN DE TAMAÑO 0.6
                                                            ***************************/
                                                            $s_particle26 = $con->prepare("SELECT * FROM $particles WHERE tamano_nominal = '0.6' AND estado = 'Activo'");
                                                            $s_particle26->setFetchMode(PDO::FETCH_OBJ);
                                                            $s_particle26->execute();
                                                            $f_particle26 = $s_particle26->fetchAll();
                                                            
                                                            if ($s_particle26 -> rowCount() > 0) {
                                                                foreach ($f_particle26 as $particula26) {
                                                                    $desviacion_tamano_06 = $particula26 -> desviacion_tamano;

                                                                    echo '<option value="'.$desviacion_tamano_06.'">'.$desviacion_tamano_06.'</option>';
                                                                }
                                                            } else {
                                                                mensaje_error();
                                                            }
                                                            ?>
                                                        </select>±
                                                    </td>
                                                    <td>
                                                        <select class="form-control" name="no_lote_06" required>
                                                            <option value=""></option>
                                                            <?php
                                                            /***************************
                                                            NO DE LOTE 0.6
                                                            ***************************/
                                                            $s_particle27 = $con->prepare("SELECT * FROM $particles WHERE tamano_nominal = '0.6' AND estado = 'Activo'");
                                                            $s_particle27->setFetchMode(PDO::FETCH_OBJ);
                                                            $s_particle27->execute();
                                                            $f_particle27 = $s_particle27->fetchAll();
                                                            
                                                            if ($s_particle27 -> rowCount() > 0) {
                                                                foreach ($f_particle27 as $particula27) {
                                                                    $no_lote_06 = $particula27 -> no_lote;

                                                                    echo '<option value="'.$no_lote_06.'">'.$no_lote_06.'</option>';
                                                                }
                                                            } else {
                                                                mensaje_error();
                                                            }
                                                            ?>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <select class="form-control" name="exp_fecha_06" required>
                                                            <option value=""></option>
                                                            <?php
                                                            /***************************
                                                            FECHA DE EXPIRACIÓN 0.6
                                                            ***************************/
                                                            $s_particle28 = $con->prepare("SELECT * FROM $particles WHERE tamano_nominal = '0.6' AND estado = 'Activo'");
                                                            $s_particle28->setFetchMode(PDO::FETCH_OBJ);
                                                            $s_particle28->execute();
                                                            $f_particle28 = $s_particle28->fetchAll();
                                                            
                                                            if ($s_particle28 -> rowCount() > 0) {
                                                                foreach ($f_particle28 as $particula28) {
                                                                    $exp_fecha_06 = $particula28 -> exp_fecha;

                                                                    echo '<option value="'.$exp_fecha_06.'">'.$exp_fecha_06.'</option>';
                                                                }
                                                            } else {
                                                                mensaje_error();
                                                            }
                                                            ?>
                                                        </select>
                                                    </td>

                                                    <td><strong>5.0 µm</strong></td>
                                                    <td>
                                                        <select class="form-control" name="tamano_real_50" required>
                                                            <option value=""></option>
                                                            <?php
                                                            /***************************
                                                            TAMAÑO REAL DE PARTÍCULA 5.0
                                                            ***************************/
                                                            $s_particle29 = $con->prepare("SELECT * FROM $particles WHERE tamano_nominal = '5.0' AND estado = 'Activo'");
                                                            $s_particle29->setFetchMode(PDO::FETCH_OBJ);
                                                            $s_particle29->execute();
                                                            $f_particle29 = $s_particle29->fetchAll();
                                                            
                                                            if ($s_particle29 -> rowCount() > 0) {
                                                                foreach ($f_particle29 as $particula29) {
                                                                    $tamano_real_50 = $particula29 -> tamano_real;

                                                                    echo '<option value="'.$tamano_real_50.'">'.$tamano_real_50.'</option>';
                                                                }
                                                            } else {
                                                                mensaje_error();
                                                            }
                                                            ?>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <select class="form-control" name="desviacion_tamano_50" required>
                                                            <option value=""></option>
                                                            <?php
                                                            /***************************
                                                            DESVIACIÓN DE TAMAÑO 5.0
                                                            ***************************/
                                                            $s_particle30 = $con->prepare("SELECT * FROM $particles WHERE tamano_nominal = '5.0' AND estado = 'Activo'");
                                                            $s_particle30->setFetchMode(PDO::FETCH_OBJ);
                                                            $s_particle30->execute();
                                                            $f_particle30 = $s_particle30->fetchAll();
                                                            
                                                            if ($s_particle30 -> rowCount() > 0) {
                                                                foreach ($f_particle30 as $particula30) {
                                                                    $desviacion_tamano_50 = $particula30 -> desviacion_tamano;

                                                                    echo '<option value="'.$desviacion_tamano_50.'">'.$desviacion_tamano_50.'</option>';
                                                                }
                                                            } else {
                                                                mensaje_error();
                                                            }
                                                            ?>
                                                        </select>±
                                                    </td>
                                                    <td>
                                                        <select class="form-control" name="no_lote_50" required>
                                                            <option value=""></option>
                                                            <?php
                                                            /***************************
                                                            NO DE LOTE 5.0
                                                            ***************************/
                                                            $s_particle31 = $con->prepare("SELECT * FROM $particles WHERE tamano_nominal = '5.0' AND estado = 'Activo'");
                                                            $s_particle31->setFetchMode(PDO::FETCH_OBJ);
                                                            $s_particle31->execute();
                                                            $f_particle31 = $s_particle31->fetchAll();
                                                            
                                                            if ($s_particle31 -> rowCount() > 0) {
                                                                foreach ($f_particle31 as $particula31) {
                                                                    $no_lote_50 = $particula31 -> no_lote;

                                                                    echo '<option value="'.$no_lote_50.'">'.$no_lote_50.'</option>';
                                                                }
                                                            } else {
                                                                mensaje_error();
                                                            }
                                                            ?>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <select class="form-control" name="exp_fecha_50" required>
                                                            <option value=""></option>
                                                            <?php
                                                            /***************************
                                                            FECHA DE EXPIRACIÓN 5.0
                                                            ***************************/
                                                            $s_particle32 = $con->prepare("SELECT * FROM $particles WHERE tamano_nominal = '5.0' AND estado = 'Activo'");
                                                            $s_particle32->setFetchMode(PDO::FETCH_OBJ);
                                                            $s_particle32->execute();
                                                            $f_particle32 = $s_particle32->fetchAll();
                                                            
                                                            if ($s_particle32 -> rowCount() > 0) {
                                                                foreach ($f_particle32 as $particula32) {
                                                                    $exp_fecha_50 = $particula32 -> exp_fecha;

                                                                    echo '<option value="'.$exp_fecha_50.'">'.$exp_fecha_50.'</option>';
                                                                }
                                                            } else {
                                                                mensaje_error();
                                                            }
                                                            ?>
                                                        </select>
                                                    </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            
                                        </div><br>

                                        <center><input class="btn btn-sm btn-success" type="submit" value="Siguiente" name="guardar_particulas"></center>
                                    </div>
                                </form>
                            </div>
                    </div>
                </div>
            </div>
        </div>

<?php
end_section();
} else {
    header('Location: ../../../../index.php');
}
?>