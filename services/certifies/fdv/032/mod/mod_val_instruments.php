<?php
session_start();

if ($_SESSION['nombre'] != '' && $_SESSION['tipo'] == 'devecchi' || $_SESSION['tipo'] == 'admin') {
    include '../../../assets/layout2.php';
    section();

    $id_documento = $_SERVER['QUERY_STRING'];

    function mensaje_ayuda() {
    echo '
            <div class="alert alert-success alert-dismissible fade in col-sm-3 animated bounceInDown" role="alert" style="position:fixed; top:70px; right:10px; z-index:10;"> 
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
            <h4 class="text-center"><strong>Búsqueda exitosa</strong></h4>
            <p class="text-center">
            Por favor visualiza la información encontrada.
            </p>
            </div>
            ';
  }

    function mensaje_error() {
        echo '
            <div class="alert alert-danger alert-dismissible fade in col-sm-3 animated bounceInDown" role="alert" style="position:fixed; top:70px; right:10px; z-index:10;"> 
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
            <h4 class="text-center"><strong>OCURRIÓ UN ERROR</strong></h4>
            <p class="text-center">
            <u>No se logró recibir información</u> de los <strong>Contadorres de Partículas</strong> en sistema, por favor, inténtalo de nuevo o contácta al Soporte Técnico.
            </p>
            </div>
            ';
    }

    // Búsqueda de los instrumentos solicitados
    require '../../../../functions/conex_serv.php';
    $certified = 'fdv_s_032';
    $instruments = 'instrumentos';

    // Se busca primero los activos guardados en la tabla de certificados
    $s_actives = $con->prepare("SELECT dmm_activo, pha_activo, mfm_activo, rh_activo, balometro_activo FROM $certified WHERE id_documento = :id_documento");
    $s_actives->bindValue(':id_documento', $id_documento);
    $s_actives->setFetchMode(PDO::FETCH_OBJ);
    $s_actives->execute();
    $f_actives = $s_actives->fetchAll();
    
    if ($s_actives -> rowCount() > 0) {
        foreach ($f_actives as $activos) {
            $dmm_activo = $activos -> dmm_activo;
            $pha_activo = $activos -> pha_activo;
            $mfm_activo = $activos -> mfm_activo;
            $rh_activo = $activos -> rh_activo;
            $balometro_activo = $activos -> balometro_activo;
        }
        mensaje_ayuda();
    } else {
        mensaje_error();
    }

?>

<table>
    <tr>
        <?php echo '<a href="mod_instruments.php?'.$id_documento.'"><button type="submit" value="Volver" class="btn btn-primary" style="text-align:center"><i class="fa fa-reply"></i>&nbsp;&nbsp;Volver</button></a>'; ?>
    </tr>
</table>

<div class="container" style="width: 1030px;">
    <div class="row" style="width: 770px;">
        <div class="col-sm-12">
            <div class="page-header2">
                <h1 class="animated lightSpeedIn">Certificado: <strong><u><?php echo $id_documento; ?></u></strong> | Estándard de Trazabilidad</h1>
                <span class="label label-danger"></span> 		 
                <p class="pull-right text-warning"></p>
            </div>
        </div>
    </div>
</div>

<div class="container" style="margin-left: 5%;">
    <div class="row">
        <div class="col-sm-13">
            <div class="panel panel-warning">
                <div class="panel-heading text-center"><strong>Asegúrate que estén correctos todos los campos</strong></div>
                    <div class="panel-body">
                        <?php echo '<form role="form" action="../../../../functions/mod/instruments.php?'.$id_documento.'" method="POST" enctype="multipart/form-data">'; ?>
                            <div>
                                <div class="container">
                                    <table class="table table-responsive table-hover table-bordered table-striped table-warning" style="margin-left: -15px;">
                                    <thead>
                                    <tr>
                                    <th>INSTRUMENTO</th>
                                    <th>ACTIVO</th>
                                    <th>MODELO</th>
                                    <th>NO. DE SERIE</th>
                                    <th>CONTROL No.</th>
                                    <th>FECHA CAL</th>
                                    <th>PROX. CAL</th>
                                    </tr>
                                    </thead>

                                    <!-- PRIMERA FILA -->
                                    <tbody>
                                    <tr>
                                    <td><strong>DMM</strong></td>
                                    <?php
                                    $s_dmm = $con->prepare("SELECT * FROM $instruments WHERE activo = :dmm_activo");
                                    $s_dmm->bindValue(':dmm_activo', $dmm_activo);
                                    $s_dmm->setFetchMode(PDO::FETCH_OBJ);
                                    $s_dmm->execute();

                                    $f_dmm = $s_dmm->fetchAll();

                                    if ($s_dmm -> rowCount() > 0) {
                                        foreach ($f_dmm as $dmm) {
                                            $dmm_activo = $dmm -> activo;
                                            $dmm_modelo = $dmm -> modelo;
                                            $dmm_numero_serie = $dmm -> numero_serie;
                                            $dmm_numero_control = $dmm -> numero_control;
                                            $dmm_fecha_calibracion = $dmm -> fecha_calibracion;
                                            $dmm_fecha_proxima_calibracion = $dmm -> fecha_proxima_calibracion;

                                            echo '
                                            <td><input class="form-control" type="text" name="dmm_activo" readonly value="'.$dmm_activo.'"></td>
                                            <td><input class="form-control" type="text" name="dmm_modelo" readonly value="'.$dmm_modelo.'"></td>
                                            <td><input class="form-control" type="text" name="dmm_no_serie" readonly value="'.$dmm_numero_serie.'"></td>
                                            <td><input class="form-control" type="text" name="dmm_control_no" readonly value="'.$dmm_numero_control.'"></td>
                                            <td><input class="form-control" type="text" name="dmm_fecha_cal" readonly value="'.$dmm_fecha_calibracion.'"></td>
                                            <td><input class="form-control" type="text" name="dmm_prox_cal" readonly value="'.$dmm_fecha_proxima_calibracion.'"></td>
                                            ';
                                        }
                                    } else {
                                        mensaje_error();
                                    }
                                    ?>
                                    </tr>
                                    </tbody>

                                    <!-- SEGUNDA FILA -->
                                    <tbody>
                                    <tr>
                                    <td><strong>PHA</strong></td>
                                    <?php
                                    $s_pha = $con->prepare("SELECT * FROM $instruments WHERE activo = :pha_activo");
                                    $s_pha->bindValue(':pha_activo', $pha_activo);
                                    $s_pha->setFetchMode(PDO::FETCH_OBJ);
                                    $s_pha->execute();

                                    $f_pha = $s_pha->fetchAll();

                                    if ($s_pha -> rowCount() > 0) {
                                        foreach ($f_pha as $pha) {
                                            $pha_activo = $pha -> activo;
                                            $pha_modelo = $pha -> modelo;
                                            $pha_numero_serie = $pha -> numero_serie;
                                            $pha_numero_control = $pha -> numero_control;
                                            $pha_fecha_calibracion = $pha -> fecha_calibracion;
                                            $pha_fecha_proxima_calibracion = $pha -> fecha_proxima_calibracion;

                                            echo '
                                            <td><input class="form-control" type="text" name="pha_activo" readonly value="'.$pha_activo.'"></td>
                                            <td><input class="form-control" type="text" name="pha_modelo" readonly value="'.$pha_modelo.'"></td>
                                            <td><input class="form-control" type="text" name="pha_no_serie" readonly value="'.$pha_numero_serie.'"></td>
                                            <td><input class="form-control" type="text" name="pha_control_no" readonly value="'.$pha_numero_control.'"></td>
                                            <td><input class="form-control" type="text" name="pha_fecha_cal" readonly value="'.$pha_fecha_calibracion.'"></td>
                                            <td><input class="form-control" type="text" name="pha_prox_cal" readonly value="'.$pha_fecha_proxima_calibracion.'"></td>
                                            ';
                                        }
                                    } else {
                                        mensaje_error();
                                    }
                                    ?>
                                    </tr>
                                    </tbody>

                                    <!-- TERCERA FILA -->
                                    <tbody>
                                    <tr>
                                    <td><strong>Medidor de flujo de masa</strong></td>
                                    <?php
                                    $s_mfm = $con->prepare("SELECT * FROM $instruments WHERE activo = :mfm_activo");
                                    $s_mfm->bindValue(':mfm_activo', $mfm_activo);
                                    $s_mfm->setFetchMode(PDO::FETCH_OBJ);
                                    $s_mfm->execute();

                                    $f_mfm = $s_mfm->fetchAll();

                                    if ($s_mfm -> rowCount() > 0) {
                                        foreach ($f_mfm as $mfm) {
                                            $mfm_activo = $mfm -> activo;
                                            $mfm_modelo = $mfm -> modelo;
                                            $mfm_numero_serie = $mfm -> numero_serie;
                                            $mfm_numero_control = $mfm -> numero_control;
                                            $mfm_fecha_calibracion = $mfm -> fecha_calibracion;
                                            $mfm_fecha_proxima_calibracion = $mfm -> fecha_proxima_calibracion;

                                            echo '
                                            <td><input class="form-control" type="text" name="mfm_activo" readonly value="'.$mfm_activo.'"></td>
                                            <td><input class="form-control" type="text" name="mfm_modelo" readonly value="'.$mfm_modelo.'"></td>
                                            <td><input class="form-control" type="text" name="mfm_no_serie" readonly value="'.$mfm_numero_serie.'"></td>
                                            <td><input class="form-control" type="text" name="mfm_control_no" readonly value="'.$mfm_numero_control.'"></td>
                                            <td><input class="form-control" type="text" name="mfm_fecha_cal" readonly value="'.$mfm_fecha_calibracion.'"></td>
                                            <td><input class="form-control" type="text" name="mfm_prox_cal" readonly value="'.$mfm_fecha_proxima_calibracion.'"></td>
                                            ';
                                        }
                                    } else {
                                        mensaje_error();
                                    }
                                    ?>
                                    </tr>
                                    </tbody>

                                    <!-- CUARTA FILA -->
                                    <tbody>
                                    <tr>
                                    <td><strong>RH/TEMP SENSOR</strong></td>
                                    <?php
                                    $s_rh = $con->prepare("SELECT * FROM $instruments WHERE activo = :rh_activo");
                                    $s_rh->bindValue(':rh_activo', $rh_activo);
                                    $s_rh->setFetchMode(PDO::FETCH_OBJ);
                                    $s_rh->execute();

                                    $f_rh = $s_rh->fetchAll();

                                    if ($s_rh -> rowCount() > 0) {
                                        foreach ($f_rh as $rh) {
                                            $rh_activo = $rh -> activo;
                                            $rh_modelo = $rh -> modelo;
                                            $rh_numero_serie = $rh -> numero_serie;
                                            $rh_numero_control = $rh -> numero_control;
                                            $rh_fecha_calibracion = $rh -> fecha_calibracion;
                                            $rh_fecha_proxima_calibracion = $rh -> fecha_proxima_calibracion;

                                            echo '
                                            <td><input class="form-control" type="text" name="rh_activo" readonly value="'.$rh_activo.'"></td>
                                            <td><input class="form-control" type="text" name="rh_modelo" readonly value="'.$rh_modelo.'"></td>
                                            <td><input class="form-control" type="text" name="rh_no_serie" readonly value="'.$rh_numero_serie.'"></td>
                                            <td><input class="form-control" type="text" name="rh_control_no" readonly value="'.$rh_numero_control.'"></td>
                                            <td><input class="form-control" type="text" name="rh_fecha_cal" readonly value="'.$rh_fecha_calibracion.'"></td>
                                            <td><input class="form-control" type="text" name="rh_prox_cal" readonly value="'.$rh_fecha_proxima_calibracion.'"></td>
                                            ';
                                        }
                                    } else {
                                        mensaje_error();
                                    }
                                    ?>
                                    </tr>
                                    </tbody>

                                    <!-- QUINTA FILA -->
                                    <tbody>
                                    <tr>
                                    <td><strong>Balómetro</strong></td>
                                    <?php
                                    $s_balo = $con->prepare("SELECT * FROM $instruments WHERE activo = :balometro_activo");
                                    $s_balo->bindValue(':balometro_activo', $balometro_activo);
                                    $s_balo->setFetchMode(PDO::FETCH_OBJ);
                                    $s_balo->execute();

                                    $f_balo = $s_balo->fetchAll();

                                    if ($s_balo -> rowCount() > 0) {
                                        foreach ($f_balo as $balo) {
                                            $balo_activo = $balo -> activo;
                                            $balo_modelo = $balo -> modelo;
                                            $balo_numero_serie = $balo -> numero_serie;
                                            $balo_numero_control = $balo -> numero_control;
                                            $balo_fecha_calibracion = $balo -> fecha_calibracion;
                                            $balo_fecha_proxima_calibracion = $balo -> fecha_proxima_calibracion;

                                            echo '
                                            <td><input class="form-control" type="text" name="balometro_activo" readonly value="'.$balo_activo.'"></td>
                                            <td><input class="form-control" type="text" name="balometro_modelo" readonly value="'.$balo_modelo.'"></td>
                                            <td><input class="form-control" type="text" name="balometro_no_serie" readonly value="'.$balo_numero_serie.'"></td>
                                            <td><input class="form-control" type="text" name="balometro_control_no" readonly value="'.$balo_numero_control.'"></td>
                                            <td><input class="form-control" type="text" name="balometro_fecha_cal" readonly value="'.$balo_fecha_calibracion.'"></td>
                                            <td><input class="form-control" type="text" name="balometro_prox_cal" readonly value="'.$balo_fecha_proxima_calibracion.'"></td>
                                            ';
                                        }
                                    } else {
                                        mensaje_error();
                                    }
                                    ?>
                                    </tr>
                                    </tbody>
                                    </table>

                                </div><br>

                                <center><input class="btn btn-sm btn-danger" type="submit" value="Modificar" name="guardar_cambios_instrumentos"></center>
                            </div>
                        </form>
                    </div>
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