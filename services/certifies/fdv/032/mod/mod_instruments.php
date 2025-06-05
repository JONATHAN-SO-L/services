<?php
session_start();
include '../../../assets/layout2.php';

if ($_SESSION['nombre'] != '' && $_SESSION['tipo'] == 'devecchi' || $_SESSION['tipo'] == 'admin') {
    section();

    $id_documento = $_SERVER['QUERY_STRING'];

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

    // Recuperación de los activos almacenados en DDBB
    require '../../../../functions/conex_serv.php';
    $certified = 'fdv_s_032';

    $s_instruments = $con->prepare("SELECT dmm_activo, pha_activo, mfm_activo, rh_activo, balometro_activo FROM $certified WHERE id_documento = :id_documento");
    $s_instruments->bindValue(':id_documento', $id_documento);
    $s_instruments->setFetchMode(PDO::FETCH_OBJ);
    $s_instruments->execute();

    $f_instruments = $s_instruments->fetchAll();

    if ($s_instruments -> rowCount() > 0) {
        foreach ($f_instruments as $instrumentos) {
            $dmm_activo = $instrumentos -> dmm_activo;
            $pha_activo = $instrumentos -> pha_activo;
            $mfm_activo = $instrumentos -> mfm_activo;
            $rh_activo = $instrumentos -> rh_activo;
            $balometro_activo = $instrumentos -> balometro_activo;
        }
    } else {
        mensaje_error();
    }

    $instruments = 'instrumentos';  // Tabla de instrumentos

?>

<table>
    <tr>
        <?php echo '<a href="modificar.php?'.$id_documento.'"><button type="submit" value="Volver" class="btn btn-primary" style="text-align:center"><i class="fa fa-reply"></i>&nbsp;&nbsp;Volver</button></a>'; ?>
    </tr>
</table>

<div class="container" style="width: 1030px;">
    <div class="row" style="width: 770px;">
        <div class="col-sm-12">
            <div class="page-header2">
                <h1>Instrumentos | Certificado: <strong><u><?php echo $id_documento; ?></u></strong></h1>
                <span class="label label-danger"></span> 		 
                <p class="pull-right text-warninng"></p>
            </div>
        </div>
    </div>
</div>

<div class="container">
    <div class="row">
        <div class="col-sm-8">
            <div class="panel panel-warning">
                <div class="panel-heading text-center"><strong>Selecciona correctamente los instrumentos a utilizar</strong></div>
                    <div class="panel-body">
                        <?php echo '<form role="form" action="mod_val_instruments.php?'.$id_documento.'" method="POST" enctype="multipart/form-data">'; ?>
                            <div>
                                <label><i class="fa fa-info-circle" aria-hidden="true"></i>&nbsp;DMM <i>(Identificación VECO)</i>:</label>
                                <select class="form-control" name="dmm">
                                <?php
                                echo '<option value="'.$dmm_activo.'">'.$dmm_activo.' - (Actual seleccionado)</option>';

                                $s_dmm = $con->prepare("SELECT activo FROM $instruments WHERE estado = 'Calibrado' AND tipo_instrumento = 'DMM'");
                                $s_dmm->setFetchMode(PDO::FETCH_OBJ);
                                $s_dmm->execute();

                                $f_dmm = $s_dmm->fetchAll();

                                if ($s_dmm -> rowCount() > 0) {
                                    foreach ($f_dmm as $dmm) {
                                        $dmm_found = $dmm -> activo;
                                        echo '<option value="'.$dmm_found.'">'.$dmm_found.'</option>';
                                    }
                                } else {
                                    mensaje_error();
                                }
                                ?>
                                </select><br>

                                <label><i class="fa fa-info-circle" aria-hidden="true"></i>&nbsp;PHA <i>(Identificación VECO)</i>:</label>
                                <select class="form-control" name="pha">
                                <?php echo '<option value="'.$pha_activo.'">'.$pha_activo.' - (Actual seleccionado)</option>';

                                $s_pha = $con->prepare("SELECT activo FROM $instruments WHERE estado = 'Calibrado' AND tipo_instrumento = 'PHA'");
                                $s_pha->setFetchMode(PDO::FETCH_OBJ);
                                $s_pha->execute();

                                $f_pha = $s_pha->fetchAll();

                                if ($s_pha -> rowCount() > 0) {
                                    foreach ($f_pha as $pha) {
                                        $pha_found = $pha -> activo;
                                        echo '<option value="'.$pha_found.'">'.$pha_found.'</option>';
                                    }
                                } else {
                                    mensaje_error();
                                }
                                ?>
                                </select><br>

                                <label><i class="fa fa-info-circle" aria-hidden="true"></i>&nbsp;Medidor de flujo de masa <i>(Identificación VECO)</i>:</label>
                                <select class="form-control" name="mfm">
                                <?php echo '<option value="'.$mfm_activo.'">'.$mfm_activo.' - (Actual seleccionado)</option>';

                                $s_mfm = $con->prepare("SELECT activo FROM $instruments WHERE estado = 'Calibrado' AND tipo_instrumento = 'MFM'");
                                $s_mfm->setFetchMode(PDO::FETCH_OBJ);
                                $s_mfm->execute();

                                $f_mfm = $s_mfm->fetchAll();

                                if ($s_mfm -> rowCount() > 0) {
                                    foreach ($f_mfm as $mfm) {
                                        $mfm_found = $mfm -> activo;
                                        echo '<option value="'.$mfm_found.'">'.$mfm_found.'</option>';
                                    }
                                } else {
                                    mensaje_error();
                                }
                                ?>
                                </select><br>

                                <label><i class="fa fa-info-circle" aria-hidden="true"></i>&nbsp;RH/TEMP SENSOR <i>(Identificación VECO)</i>:</label>
                                <select class="form-control" name="rh_temp">
                                <?php echo '<option value="'.$rh_activo.'">'.$rh_activo.' - (Actual seleccionado)</option>';

                                $s_rh = $con->prepare("SELECT activo FROM $instruments WHERE estado = 'Calibrado' AND tipo_instrumento = 'RH/TEMP'");
                                $s_rh->setFetchMode(PDO::FETCH_OBJ);
                                $s_rh->execute();

                                $f_rh = $s_rh->fetchAll();

                                if ($s_rh -> rowCount() > 0) {
                                    foreach ($f_rh as $rh) {
                                        $rh_found = $rh -> activo;
                                        echo '<option value="'.$rh_found.'">'.$rh_found.'</option>';
                                    }
                                } else {
                                    mensaje_error();
                                }
                                ?>
                                </select><br>

                                <label><i class="fa fa-info-circle" aria-hidden="true"></i>&nbsp;Balómetro <i>(Identificación VECO)</i>:</label>
                                <select class="form-control" name="balometro">
                                <?php echo '<option value="'.$balometro_activo.'">'.$balometro_activo.' - (Actual seleccionado)</option>';

                                $s_balo = $con->prepare("SELECT activo FROM $instruments WHERE estado = 'Calibrado' AND tipo_instrumento = 'Balometro'");
                                $s_balo->setFetchMode(PDO::FETCH_OBJ);
                                $s_balo->execute();

                                $f_balo = $s_balo->fetchAll();

                                if ($s_balo -> rowCount() > 0) {
                                    foreach ($f_balo as $balo) {
                                        $balo_found = $balo -> activo;
                                        echo '<option value="'.$balo_found.'">'.$balo_found.'</option>';
                                    }
                                } else {
                                    mensaje_error();
                                }
                                ?>
                                </select><br>

                                <center><input class="btn btn-sm btn-primary" type="submit" value="Buscar" name="modificar_instrumentos"></center>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php end_section();
}else{
    header('Location: ../../../../../index.php');
}
?>