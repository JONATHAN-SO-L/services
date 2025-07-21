<?php
session_start();

if ($_SESSION['nombre'] != '' && $_SESSION['tipo'] == 'devecchi' || $_SESSION['nombre'] != '' && $_SESSION['tipo'] == 'admin') {
    include '../../assets/layout.php';
    section();

    $id_documento = $_SERVER['QUERY_STRING'];

    function mensaje_error() {
        echo '
            <div class="alert alert-danger alert-dismissible fade in col-sm-3 animated bounceInDown" role="alert" style="position:fixed; top:70px; right:10px; z-index:10;"> 
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
            <h4 class="text-center"><strong>OCURRIÓ UN ERROR</strong></h4>
            <p class="text-center">
            No se logró recibir información de los contadores en sistema, por favor, inténtalo de nuevo o contácta al Soporte Técnico.
            </p>
            </div>
            ';
    }

    require '../../../functions/conex_serv.php';
    $instruments = 'instrumentos';  // Tabla de instrumentos
?>

<table>
    <tr>
        <button onClick="document.location.reload();" type="submit" class="btn btn-danger" data-toggle="tooltip" data-placement="top" title="Haz clic para reiniciar el formulario" HSPACE="10" VSPACE="10"><i class="fa fa-refresh fa-spin  fa-fw"></i>
        <span class="sr-only">Cargando...</span>Reiniciar formulario</button>
    </tr>
</table>

<div class="container" style="width: 1030px;">
    <div class="row" style="width: 770px;">
        <div class="col-sm-12">
            <div class="page-header2">
                <h1>Instrumentos | Certificado: <strong><u><?php echo $id_documento; ?></u></strong></h1>
                <span class="label label-danger"></span> 		 
                <p class="pull-right text-primary"></p>
            </div>
        </div>
    </div>
</div>

<div class="container">
    <div class="row">
        <div class="col-sm-8">
            <div class="panel panel-success">
                <div class="panel-heading text-center"><strong>Selecciona correctamente los instrumentos a utilizar</strong></div>
                    <div class="panel-body">
                        <?php echo '<form role="form" action="../../../functions/add/instruments.php?'.$id_documento.'" method="POST" enctype="multipart/form-data">'; ?>
                            <div>
                                <label><i class="fa fa-info-circle" aria-hidden="true"></i>&nbsp;DMM <i>(Identificación VECO)</i>:</label>
                                <select class="form-control" name="dmm" required>
                                <option value=""> - Selecciona el instrumento requerido - </option>
                                <?php
                                    $s_instrument = $con->prepare("SELECT activo FROM $instruments WHERE estado != 'Vencido' AND tipo_instrumento = 'DMM'");
                                    $s_instrument->setFetchMode(PDO::FETCH_OBJ);
                                    $s_instrument->execute();
                                    $f_instrument = $s_instrument->fetchAll();

                                    if ($s_instrument -> rowCount() > 0) {
                                        foreach ($f_instrument as $dmm) {
                                        $activo = $dmm -> activo;
                                        echo '<option value="'.$activo.'">'.$activo.'</option>';
                                        }
                                    } else {
                                        mensaje_error();
                                    }
                                ?>
                                </select><br>

                                <label><i class="fa fa-info-circle" aria-hidden="true"></i>&nbsp;PHA <i>(Identificación VECO)</i>:</label>
                                <select class="form-control" name="pha" required>
                                <option value=""> - Selecciona el instrumento requerido - </option>
                                <?php
                                    $s_instrument = $con->prepare("SELECT activo FROM $instruments WHERE estado != 'Vencido' AND tipo_instrumento = 'PHA'");
                                    $s_instrument->setFetchMode(PDO::FETCH_OBJ);
                                    $s_instrument->execute();
                                    $f_instrument = $s_instrument->fetchAll();

                                    if ($s_instrument -> rowCount() > 0) {
                                        foreach ($f_instrument as $pha) {
                                        $activo = $pha -> activo;
                                        echo '<option value="'.$activo.'">'.$activo.'</option>';
                                        }
                                    } else {
                                        mensaje_error();
                                    }
                                ?>
                                </select><br>

                                <label><i class="fa fa-info-circle" aria-hidden="true"></i>&nbsp;Medidor de flujo de masa <i>(Identificación VECO)</i>:</label>
                                <select class="form-control" name="mfm" required>
                                <option value=""> - Selecciona el instrumento requerido - </option>
                                <?php
                                    $s_instrument = $con->prepare("SELECT activo FROM $instruments WHERE estado != 'Vencido' AND tipo_instrumento = 'MFM'");
                                    $s_instrument->setFetchMode(PDO::FETCH_OBJ);
                                    $s_instrument->execute();
                                    $f_instrument = $s_instrument->fetchAll();

                                    if ($s_instrument -> rowCount() > 0) {
                                        foreach ($f_instrument as $mfm) {
                                        $activo = $mfm -> activo;
                                        echo '<option value="'.$activo.'">'.$activo.'</option>';
                                        }
                                    } else {
                                        mensaje_error();
                                    }
                                ?>
                                </select><br>

                                <label><i class="fa fa-info-circle" aria-hidden="true"></i>&nbsp;RH/TEMP SENSOR <i>(Identificación VECO)</i>:</label>
                                <select class="form-control" name="rh_temp" required>
                                <option value=""> - Selecciona el instrumento requerido - </option>
                                <?php
                                    $s_instrument = $con->prepare("SELECT activo FROM $instruments WHERE estado != 'Vencido' AND tipo_instrumento = 'RH/TEMP'");
                                    $s_instrument->setFetchMode(PDO::FETCH_OBJ);
                                    $s_instrument->execute();
                                    $f_instrument = $s_instrument->fetchAll();

                                    if ($s_instrument -> rowCount() > 0) {
                                        foreach ($f_instrument as $rh_temp) {
                                        $activo = $rh_temp -> activo;
                                        echo '<option value="'.$activo.'">'.$activo.'</option>';
                                        }
                                    } else {
                                        mensaje_error();
                                    }
                                ?>
                                </select><br>

                                <label><i class="fa fa-info-circle" aria-hidden="true"></i>&nbsp;Balómetro <i>(Identificación VECO)</i>:</label>
                                <select class="form-control" name="balometro" required>
                                <option value=""> - Selecciona el instrumento requerido - </option>
                                <?php
                                    $s_instrument = $con->prepare("SELECT activo FROM $instruments WHERE estado != 'Vencido' AND tipo_instrumento = 'Balometro'");
                                    $s_instrument->setFetchMode(PDO::FETCH_OBJ);
                                    $s_instrument->execute();
                                    $f_instrument = $s_instrument->fetchAll();

                                    if ($s_instrument -> rowCount() > 0) {
                                        foreach ($f_instrument as $balometro) {
                                        $activo = $balometro -> activo;
                                        echo '<option value="'.$activo.'">'.$activo.'</option>';
                                        }
                                    } else {
                                        mensaje_error();
                                    }
                                ?>
                                </select><br>

                                <center><input class="btn btn-sm btn-success" type="submit" value="Siguiente" name="guardar_contador"></center>
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