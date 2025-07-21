<?php
session_start();
include '../../../assets/layout2.php';

if ($_SESSION['nombre'] != '' && $_SESSION['tipo'] == 'devecchi' || $_SESSION['tipo'] == 'admin') {
    section();
    
    $id_documentos = $_SERVER['QUERY_STRING'];

    function mensaje_error() {
        echo '
            <div class="alert alert-danger alert-dismissible fade in col-sm-3 animated bounceInDown" role="alert" style="position:fixed; top:70px; right:10px; z-index:10;"> 
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
            <h4 class="text-center"><strong>OCURRIÓ UN ERROR</strong></h4>
            <p class="text-center">
            No se logró recibir información de parte del sistema, por favor, inténtalo de nuevo o contácta al Soporte Técnico.
            </p>
            </div>
            ';
    }

    // Busqueda del registro en la tabla de certificados
    require '../../../../functions/conex_serv.php';
    $certified = 'fdv_s_032';
    $instruments = 'instrumentos';

    $s_reg = $con->prepare("SELECT empresa, id_documento, fa_esperado, dmm_activo, pha_activo, mfm_activo, rh_activo, balometro_activo FROM $certified WHERE id_documento = :id_documento");
    $s_reg->bindValue(':id_documento', $id_documentos);
    $s_reg->setFetchMode(PDO::FETCH_OBJ);
    $s_reg->execute();
    $f_reg = $s_reg->fetchAll();

    if ($s_reg -> rowCount() > 0) {
        foreach ($f_reg as $data_certified) {
            $empresa = $data_certified -> empresa;
            $id_documento = $data_certified -> id_documento;
            $fa_esperado = $data_certified -> fa_esperado;
            $dmm_activo = $data_certified -> dmm_activo;
            $pha_activo = $data_certified -> pha_activo;
            $mfm_activo = $data_certified -> mfm_activo;
            $rh_activo = $data_certified -> rh_activo;
            $balometro_activo = $data_certified -> balometro_activo;
        }
    } else {
        mensaje_error();
    }
?>
    
    <div class="container">
        <table>
        <tr>
        <a href="../index.php"><button type="submit" value="Volver" name="" class="btn btn-primary" style="text-align:center"><i class="fa fa-reply"></i>&nbsp;&nbsp;Volver</button></a><br><br>
        </tr>
        </table>

        <div class="container" style="width: 1030px;">
            <div class="row" style="width: 770px;">
                <div class="col-sm-12">
                    <div class="page-header2">
                        <h3 class="animated lightSpeedIn">
                            <p><strong>Documento o Certificado:</strong> <i><?php echo $id_documento; ?></i></p>
                            <p><strong>Compañía:</strong> <i><?php echo $empresa; ?></i></p>
                        </h3>
                        <span class="label label-danger"></span> 		 
                        <p class="pull-right text-primary"></p>
                    </div>
                </div>
            </div>
        </div>

            <div class="row">
                <div class="col-sm-8">
                    <div class="panel panel-danger">
                        <div class="panel-heading text-center"><strong>Selecciona el objeto que quieres modificar</strong></div>
                            <div class="panel-body">
                                <form role="form" action="form.php" method="POST" enctype="multipart/form-data">
                                        <center>
                                        <div class="container">
                                            <div class="col-sm-8">
                                                <?php
                                                echo '
                                                <a class="btn btn-sm btn-primary" href="mod_build.php?'.$id_documento.'"><i class="fa fa-building" aria-hidden="true"></i> Empresa</a>
                                                <a class="btn btn-sm btn-primary" href="mod_accountant.php?'.$id_documento.'"><i class="fa fa-flask" aria-hidden="true"></i> Modelo de Contador de Partículas</a>
                                                <a class="btn btn-sm btn-primary" href="mod_form.php?'.$id_documento.'"><i class="fa fa-folder-open" aria-hidden="true"></i> Parámetros de calibración</a><br><br>
                                            </div>

                                            <div class="col-sm-8">
                                                <a class="btn btn-sm btn-success" href="mod_electronic_measure.php?'.$id_documento.'"><i class="fa fa-bolt" aria-hidden="true"></i> Mediciones Electrónicas</a>';

                                                switch ($fa_esperado) {
                                                    case $fa_esperado < 100:
                                                        echo '<a class="btn btn-sm btn-success" href="mod_behaviour.php?'.$id_documento.'"><i class="fa fa-star-half-o" aria-hidden="true"></i> Comportamiento</a>';
                                                    break;
                                                    case $fa_esperado >= 100:
                                                        echo '<a class="btn btn-sm btn-success" href="mod_behaviour2.php?'.$id_documento.'"><i class="fa fa-star-half-o" aria-hidden="true"></i> Comportamiento</a>';
                                                    break;
                                                }
                                                
                                                echo '<a class="btn btn-sm btn-success" href="mod_instruments.php?'.$id_documento.'"><i class="fa fa-bar-chart" aria-hidden="true"></i> Estándar de Trazabilidad</a><br><br>
                                            </div>
                                                ';

                                                echo '<div class="col-sm-8">
                                                <a class="btn btn-sm btn-danger" href="mod_standar_particles.php?'.$id_documento.'"><i class="fa fa-check-square" aria-hidden="true"></i> Partículas Estándar</a>
                                                </div>';
                                                ?>
                                        </div>
                                        </center>
                                </form>
                            </div>
                    </div>
                </div>
            </div>
            
            <div class="btn-group">
                <button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown"><i class="fa fa-file-archive-o" aria-hidden="true"></i> Certificados<span class="caret"></span></button>
                <ul class="dropdown-menu" role="menu">

                    <?php
                    // Listado de certificados
                    // Certificado DMM
                    $s_dmm = $con->prepare("SELECT activo, estado, tipo_instrumento, certificado FROM $instruments WHERE estado != 'Vencido' AND activo = :activo");
                    $s_dmm->bindValue(':activo', $dmm_activo);
                    $s_dmm->setFetchMode(PDO::FETCH_OBJ);
                    $s_dmm->execute();

                    $f_dmm = $s_dmm->fetchAll();

                    if ($s_dmm -> rowCount() > 0) {
                        foreach ($f_dmm as $dmm) {
                            $activo = $dmm -> activo;
                            $certificado = $dmm -> certificado;

                            echo '<li><a href="../../../'.$certificado.'" target="_blank"><i class="fa fa-certificate" aria-hidden="true"></i> '.$activo.' - (DMM)</a></li>';
                        }
                    } else {
                        echo '<li><i class="fa fa-certificate" aria-hidden="true"></i> - No se encontró certificado DMM - </li>';
                    }

                    // Certificado PHA
                    $s_pha = $con->prepare("SELECT activo, estado, tipo_instrumento, certificado FROM $instruments WHERE estado != 'Vencido' AND activo = :activo");
                    $s_pha->bindValue(':activo', $pha_activo);
                    $s_pha->setFetchMode(PDO::FETCH_OBJ);
                    $s_pha->execute();

                    $f_pha = $s_pha->fetchAll();

                    if ($s_pha -> rowCount() > 0) {
                        foreach ($f_pha as $pha) {
                            $activo = $pha -> activo;
                            $certificado = $pha -> certificado;

                            echo '<li><a href="../../../'.$certificado.'" target="_blank"><i class="fa fa-certificate" aria-hidden="true"></i> '.$activo.' - (PHA)</a></li>';
                        }
                    } else {
                        echo '<li><i class="fa fa-certificate" aria-hidden="true"></i> - No se encontró certificado PHA - </li>';
                    }

                    // Certificado MFM
                    $s_mfm = $con->prepare("SELECT activo, estado, tipo_instrumento, certificado FROM $instruments WHERE estado != 'Vencido' AND activo = :activo");
                    $s_mfm->bindValue(':activo', $mfm_activo);
                    $s_mfm->setFetchMode(PDO::FETCH_OBJ);
                    $s_mfm->execute();

                    $f_mfm = $s_mfm->fetchAll();

                    if ($s_mfm -> rowCount() > 0) {
                        foreach ($f_mfm as $mfm) {
                            $activo = $mfm -> activo;
                            $certificado = $mfm -> certificado;

                            echo '<li><a href="../../../'.$certificado.'" target="_blank"><i class="fa fa-certificate" aria-hidden="true"></i> '.$activo.' - (MFM)</a></li>';
                        }
                    } else {
                        echo '<li><i class="fa fa-certificate" aria-hidden="true"></i> - No se encontró certificado MFM - </li>';
                    }

                    // Certificado RH/TEMP
                    $s_rh = $con->prepare("SELECT activo, estado, tipo_instrumento, certificado FROM $instruments WHERE estado != 'Vencido' AND activo = :activo");
                    $s_rh->bindValue(':activo', $rh_activo);
                    $s_rh->setFetchMode(PDO::FETCH_OBJ);
                    $s_rh->execute();

                    $f_rh = $s_rh->fetchAll();

                    if ($s_rh -> rowCount() > 0) {
                        foreach ($f_rh as $rh) {
                            $activo = $rh -> activo;
                            $certificado = $rh -> certificado;

                            echo '<li><a href="../../../'.$certificado.'" target="_blank"><i class="fa fa-certificate" aria-hidden="true"></i> '.$activo.' - (RH/TEMP)</a></li>';
                        }
                    } else {
                        echo '<li><i class="fa fa-certificate" aria-hidden="true"></i> - No se encontró certificado RH/TEMP - </li>';
                    }

                    // Certificado Balómetro
                    $s_balo = $con->prepare("SELECT activo, estado, tipo_instrumento, certificado FROM $instruments WHERE estado != 'Vencido' AND activo = :activo");
                    $s_balo->bindValue(':activo', $balometro_activo);
                    $s_balo->setFetchMode(PDO::FETCH_OBJ);
                    $s_balo->execute();

                    $f_balo = $s_balo->fetchAll();

                    if ($s_balo -> rowCount() > 0) {
                        foreach ($f_balo as $balo) {
                            $activo = $balo -> activo;
                            $certificado = $balo -> certificado;

                            echo '<li><a href="../../../'.$certificado.'" target="_blank"><i class="fa fa-certificate" aria-hidden="true"></i> '.$activo.' - (Balómetro)</a></li>';
                        }
                    } else {
                        echo '<li><i class="fa fa-certificate" aria-hidden="true"></i> - No se encontró certificado Balómetro - </li>';
                    }
                    ?>
                </ul>
            </div>

        </div>

<?php
end_section();
} else {
    header('Location: ../../../../../index.php');
}
?>