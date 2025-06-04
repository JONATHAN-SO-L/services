<?php
session_start();
include '../../../assets/layout2.php';

if ($_SESSION['nombre'] != '' && $_SESSION['tipo'] == 'devecchi' || $_SESSION['tipo'] == 'admin') {
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

    if (isset($_POST['modificar_serie'])) {
        // Recepción de datos
        $numero_serie = $_POST['serie_contador'];

        require '../../../../functions/conex_serv.php';
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
    }
?>

<table>
    <tr>
        <?php echo '<a href="mod_accountant.php?'.$id_documento.'"><button type="submit" value="Volver" class="btn btn-primary" style="text-align:center"><i class="fa fa-reply"></i>&nbsp;&nbsp;Volver</button></a>'; ?>
    </tr>
    </td>
</table>

<div class="container" style="width: 1030px;">
        <div class="row" style="width: 770px;">
            <div class="col-sm-12">
                <div class="page-header2">
                    <h1 class="animated lightSpeedIn">Contador <strong><u><?php echo $id_documento; ?></u></strong> | Validación</h1>
                    <span class="label label-danger"></span> 		 
                    <p class="pull-right text-warning"></p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container">
    <div class="row">
        <div class="col-sm-8">
            <div class="panel panel-warning">
                <div class="panel-heading text-center"><strong>Valida que la información del contador sea la correcta</strong></div>
                    <div class="panel-body">
                        <?php echo '<form role="form" action="../../../../functions/mod/accountant.php?'.$id_documento.'" method="POST" enctype="multipart/form-data">'; ?>
                            <div>
                                <div class="col-sm-4">
                                    <label><i class="fa fa-tachometer"></i>&nbsp;Modelo CI:</label>
                                    <input class="form-control" type="text" name="modelo_ci" id="modelo_ci" value="<?php echo $modelo_ci; ?>" readonly>
                                </div>

                                <div class="col-sm-4">
                                    <label><i class="fa fa-barcode"></i>&nbsp;Número de Serie:</label>
                                    <input class="form-control" type="text" name="numero_serie" id="numero_serie" value="<?php echo $numero_serie; ?>" readonly>
                                </div>

                                <div class="col-sm-4">
                                    <label><i class="fa fa-crosshairs"></i>&nbsp;Control No.</label>
                                    <input class="form-control" type="text" name="control_no" id="control_no" value="<?php echo $numero_control; ?>" readonly> <br>
                                </div>

                            <center><input class="btn btn-sm btn-danger" type="submit" value="Guardar" name="modificar_contador"></center>
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