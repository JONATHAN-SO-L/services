<?php
session_start();

if ($_SESSION['nombre'] != '' && $_SESSION['tipo'] == 'devecchi' || $_SESSION['nombre'] != '' && $_SESSION['tipo'] == 'admin') {
    include '../../assets/layout.php';
    section();

    $id_documento = $_SERVER['QUERY_STRING'];

    function mensaje_ayuda(){
        echo '
            <div class="alert alert-success alert-dismissible fade in col-sm-3 animated bounceInDown" role="alert" style="position:fixed; top:70px; right:10px; z-index:10;"> 
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
            <h4 class="text-center"><strong>CONTADOR ENCONTRADO</strong></h4>
            <p class="text-center">
            Se encontró un modelo de contador de partículas que coincide, por favor, selecciona el número de serie adecuado.
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
                        <h1>Certificado: <strong><u><?php echo $id_documento; ?></u></strong></h1>
                        <span class="label label-danger"></span> 		 
                        <p class="pull-right text-primary"></p>
                    </div>
                </div>
            </div>
        </div>

        <?php
        // Validación de información registrada
        require '../../../functions/conex_serv.php';

        $accountant = 'contadores'; // Tabla de contadores
        $certified = 'fdv_s_032'; // Tabla de certificados

        $s_accountant = $con->prepare("SELECT modelo_ci, numero_serie, estado FROM $accountant WHERE estado = 'Calibrado'");
        $s_accountant->setFetchMode(PDO::FETCH_OBJ);
        $s_accountant->execute();
        $f_accountant = $s_accountant->fetchAll();

        if ($s_accountant -> rowCount() > 0) {
            foreach ($f_accountant as $contador) {
                $modelo_ci = $contador -> modelo_ci;
                $numero_serie = $contador -> numero_serie;
            }

            if (isset($_POST['guardar_contador'])) {
                // Se almacena el modelo del contador en la DDBB
                $save_model = $con->prepare("UPDATE $certified
                                                    SET modelo_contador= ?,
                                                    modelo_ci = ?
                                                    WHERE id_documento = $id_documento");
                $val_save_model = $save_model->execute([$modelo_ci, $modelo_ci]);

                if ($val_save_model) {
                    mensaje_ayuda();
                } else {
                    mensaje_error();
                }

                if ($numero_serie != '') { ?>
                    <div class="container">
                        <div class="row">
                            <div class="col-sm-8">
                                <div class="panel panel-success">
                                    <div class="panel-heading text-center"><strong>Para poder crear un nuevo certificado es necesario llenar los todos campos</strong></div>
                                        <div class="panel-body">
                                        <form role="form" action="validador_contador.php" method="POST" enctype="multipart/form-data">
                                            <div>
                                                <label><i class="fa fa-barcode" aria-hidden="true"></i>&nbsp;Número de serie del Contador de Partículas:</label>
                                                <select class="form-control" name="contador" required>
                                                <option value=""> - Selecciona el número de serie requerido - </option>
                                                <?php
                                                    $s_serie = $con->prepare("SELECT modelo_ci, numero_serie, estado FROM $accountant WHERE estado = 'Calibrado' AND modelo_ci = :modelo_ci");
                                                    $s_serie->bindValue(':modelo_ci', $modelo_ci);
                                                    $s_serie->setFetchMode(PDO::FETCH_OBJ);
                                                    $s_serie->execute();
                                                    $f_serie = $s_serie->fetchAll();

                                                    foreach ($f_serie as $serie) {
                                                        $numero_serie = $serie -> numero_serie;
                                                        echo '<option value="'.$numero_serie.'">'.$numero_serie.'</option>';
                                                    }
                                                ?>
                                                </select>
                                                <br>

                                                <center><input class="btn btn-sm btn-success" type="submit" value="Siguiente" name="guardar_serie"></center>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } else {
                    echo '<h2><center>No se encontraron registros en el sistema</center></h2>';
                    mensaje_error();
                }
            } else {
                if ($modelo_ci != '') { ?>
                    <div class="container">
                        <div class="row">
                            <div class="col-sm-8">
                                <div class="panel panel-success">
                                    <div class="panel-heading text-center"><strong>Para poder crear un nuevo certificado es necesario llenar los todos campos</strong></div>
                                        <div class="panel-body">
                                        <form role="form" action="" method="POST" enctype="multipart/form-data">
                                            <div>
                                                <label><i class="fa fa-tachometer"></i>&nbsp;Modelo del Contador de Partículas:</label>
                                                <select class="form-control" name="contador" required>
                                                <option value=""> - Selecciona el modelo requerido - </option>
                                                <?php
                                                    foreach ($f_accountant as $modelo) {
                                                        $modelo_ci = $modelo -> modelo_ci;
                                                        echo '<option value="'.$modelo_ci.'">'.$modelo_ci.'</option>';
                                                    }
                                                ?>
                                                </select>
                                                <br>

                                                <center><input class="btn btn-sm btn-success" type="submit" value="Siguiente" name="guardar_contador"></center>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } else {
                    echo '<h2><center>No se encontraron registros en el sistema</center></h2>';
                    mensaje_error();
                }
            }
        } else {
            echo '<h2><center>No se encontraron registros en el sistema</center></h2>';
            mensaje_error();
        }

end_section();
} else {
    header('Location: ../../../../index.php');
}
?>