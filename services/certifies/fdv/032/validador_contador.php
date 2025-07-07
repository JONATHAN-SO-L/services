<?php
session_start();

if ($_SESSION['nombre'] != '' && $_SESSION['tipo'] == 'devecchi' || $_SESSION['tipo'] == 'admin') {
    include '../../assets/layout.php';
    section();

    $id_documento = $_SERVER['QUERY_STRING'];

    function mensaje_ayuda(){
        echo '
            <div class="alert alert-success alert-dismissible fade in col-sm-3 animated bounceInDown" role="alert" style="position:fixed; top:70px; right:10px; z-index:10;"> 
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
            <h4 class="text-center"><strong>NÚMERO DE SERIE ENCONTRADO</strong></h4>
            <p class="text-center">
            Se encontró un número de serie del contador de partículas que coincide.
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
            <u>No se logró recibir información</u> del <strong>número de serie</strong> del contador de partículas, por favor, inténtalo de nuevo o contácta al Soporte Técnico.
            </p>
            </div>
            ';
    }

    if (isset($_POST['guardar_serie'])) {
        require '../../../functions/conex_serv.php';
        // Recepción de datos
        $numero_serie = $_POST['serie_contador'];
        $certified = 'fdv_s_032'; // Tabla de certificados

        // Se almacena el número de serie del contador en la DDBB
        $save_serie = $con->prepare("UPDATE $certified
                                            SET numero_serie = ?
                                            WHERE id_documento = ?");
        $val_save_serie = $save_serie->execute([$numero_serie, $id_documento]);

        if ($val_save_serie) {
            mensaje_ayuda();
             
            // Busqueda de información del certificado en base de datos de contadores
            $accountant = 'contadores'; // Tabla de contadores
            $s_certified = $con->prepare("SELECT modelo_ci, numero_serie, numero_control, identificacion_cliente FROM $accountant WHERE numero_serie = $numero_serie AND estado = 'Calibrado'");
            $s_certified->setFetchMode(PDO::FETCH_OBJ);
            $s_certified->execute();
            $f_certified = $s_certified->fetchAll();

            if ($s_certified -> rowCount() > 0) {
                foreach ($f_certified as $contador) {
                    $modelo_ci = $contador -> modelo_ci;
                    $numero_serie = $contador -> numero_serie;
                    $numero_control = $contador -> numero_control;
                    $identificacion_cliente = $contador -> identificacion_cliente;
                }
            } else {
                mensaje_error();
                die();
            }

        } else {
            mensaje_error();
        }

    } else {
        mensaje_error();
        die();
    }

?>

        <table>
        <tr>
        <a href="contador.php"><button type="submit" value="Volver" name="" class="btn btn-primary" style="text-align:center"><i class="fa fa-reply"></i>&nbsp;&nbsp;Volver</button></a>
        </tr>
        </td>
        </table>

        <div class="container" style="width: 1030px;">
            <div class="row" style="width: 770px;">
                <div class="col-sm-12">
                    <div class="page-header2">
                        <h1 class="animated lightSpeedIn">Contador <strong><u><?php echo $modelo_ci; ?></u></strong> | Validación</h1>
                        <span class="label label-danger"></span> 		 
                        <p class="pull-right text-primary"></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="container">
            <div class="row">
                <div class="col-sm-8">
                    <div class="panel panel-primary">
                        <div class="panel-heading text-center"><strong>Valida que la información del contador sea la correcta</strong></div>
                            <div class="panel-body">
                                <?php echo '<form role="form" action="../../../functions/add/accountant.php?'.$id_documento.'" method="POST" enctype="multipart/form-data">'; ?>
                                    <div>
                                        <div class="col-sm-4">
                                        <label><i class="fa fa-tachometer"></i>&nbsp;Modelo CI:</label>
                                        <input class="form-control" type="text" name="modelo_ci" value="<?php echo $modelo_ci; ?>" readonly>
                                        </div>

                                        <div class="col-sm-4">
                                        <label><i class="fa fa-barcode"></i>&nbsp;Número de Serie:</label>
                                        <input class="form-control" type="text" name="numero_serie" value="<?php echo $numero_serie; ?>" readonly>
                                        </div>

                                        <div class="col-sm-4">
                                        <label><i class="fa fa-crosshairs"></i>&nbsp;Control No.</label>
                                        <input class="form-control" type="text" name="control_no" value="<?php echo $numero_control; ?>" readonly> <br>
                                        </div>

                                        <div class="col-sm-6">
                                        <label><i class="fa fa-user-o"></i>&nbsp;Identificación del Cliente:</label>
                                        <input class="form-control" type="text" name="identificacion_cliente" value="<?php echo $identificacion_cliente; ?>" readonly>
                                        </div>

                                        <div class="col-sm-6">
                                        <label><i class="fa fa-user-circle"></i>&nbsp;Técnico:</label>
                                        <input class="form-control" type="text" name="tecnico" value="<?php echo $_SESSION['nombre_completo']; ?>" readonly> <br>
                                        </div>

                                        <center><input class="btn btn-sm btn-success" type="submit" value="Siguiente" name="guardar_contador"></center>
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