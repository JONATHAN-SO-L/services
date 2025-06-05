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
            No se logró recibir información de parte del sistema, por favor, inténtalo de nuevo o contácta al Soporte Técnico.
            </p>
            </div>
            ';
    }

  function mensaje_busqueda() {
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

    require '../../../../functions/conex_serv.php';
    $certified = 'fdv_s_032';

    // Recuperación de información almacenada en DDBB
    $s_data = $con->prepare("SELECT * FROM $certified WHERE id_documento = :id_documento");
    $s_data->bindValue(':id_documento', $id_documento);
    $s_data->setFetchMode(PDO::FETCH_OBJ);
    $s_data->execute();

    $f_data = $s_data->fetchAll();

    if ($s_data -> rowCount() > 0) {
        foreach ($f_data as $data) {
            $intervalo_calibracion = $data -> intervalo_calibracion;
            $id_documento_ddbb = $data -> id_documento;
            $condicion_recepcion = $data -> condicion_recepcion;
            $condicion_calibracion = $data -> condicion_calibracion;
            $condicion_calibracion_final = $data -> condicion_calibracion_final;
            $comentarios = $data -> comentarios;
            $modelo_ci = $data -> modelo_ci;
            $numero_serie = $data -> numero_serie;
            $fecha_calibracion = $data -> fecha_calibracion;
            $identificacion_cliente = $data -> identificacion_cliente;
            $tecnico = $data -> tecnico;
            $presion_barometrica = $data -> presion_barometrica;
            $temperatura = $data -> temperatura;
            $humedad_relativa = $data -> humedad_relativa;
        }
    } else {
        mensaje_error();
    }
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
                <h1 class="animated lightSpeedIn">Certificado: <strong><u><?php echo $id_documento; ?></u></strong></h1>
                <span class="label label-danger"></span> 		 
                <p class="pull-right text-primary"></p>
            </div>
        </div>
    </div>
</div>

<div class="container">
    <div class="row">
        <div class="col-sm-8">
            <div class="panel panel-warning">
                <div class="panel-heading text-center"><strong>Valida que toda la información sea correcta</strong></div>
                    <div class="panel-body">
                        <?php echo '<form role="form" action="../../../../functions/mod/form.php?'.$id_documento.'" method="POST" enctype="multipart/form-data">'; ?>
                            <div>
                                <label><i class="fa fa-clock-o" aria-hidden="true"></i>&nbsp;Intervalo máximo de calibración recomendado (meses):</label>
                                    <input class="form-control" type="number" min="1" name="intervalo_calibracion" id="intervalo_calibracion" placeholder="Por ejemplo: 12" value="<?php echo $intervalo_calibracion; ?>">
                                <br>

                                <label><i class="fa fa-barcode"></i>&nbsp;ID del documento:</label>
                                    <input class="form-control" type="text" name="id_documento" id="id_documento" value="<?php echo $id_documento_ddbb; ?>" readonly>
                                <br>

                                <div class="col-sm-4">
                                    <label><i class="fa fa-star-half-o"></i>&nbsp;Condición física al recibir:</label>
                                    <select class="form-control" name="condicion_fisica">
                                        <?php echo '<option value="'.$condicion_recepcion.'">'.$condicion_recepcion.' - (Actual seleccionado)</option>'; ?>
                                        <option value="No aplica">No aplica</option>
                                        <option value="Bueno">Bueno</option>
                                        <option value="Dañado">Dañado</option>
                                        <option value="Mal empacado">Mal empacado</option>
                                        <option value="Mal manejo">Mal manejo</option>
                                        <option value="Otros">Otros</option>
                                    </select>
                                </div>

                                <div class="col-sm-4">
                                    <label><i class="fa fa-star-o"></i>&nbsp;Condición de calibración encontrada:</label>
                                    <select class="form-control" name="condicion_calibracion">
                                        <?php echo '<option value="'.$condicion_calibracion.'">'.$condicion_calibracion.' - (Actual seleccionado)</option>'; ?>
                                        <option value="Unidad Nueva">Unidad Nueva</option>
                                        <option value="En tolerancia">En tolerancia</option>
                                        <option value="Fuera de tolerancia">Fuera de tolerancia</option>
                                    </select>
                                </div>

                                <div class="col-sm-4">
                                    <label><i class="fa fa-certificate"></i>&nbsp;Condición de calibración final:</label>
                                    <select class="form-control" name="condicion_final">
                                        <?php echo '<option value="'.$condicion_calibracion_final.'">'.$condicion_calibracion_final.' - (Actual seleccionado)</option>'; ?>
                                        <option value="Dentro de Especificaciones">Dentro de Especificaciones</option>
                                        <option value="Fuera de Especificaciones">Fuera de Especificaciones</option>
                                    </select><br>
                                </div>

                                <label><i class="fa fa-comments-o"></i>&nbsp;Comentarios: <i><u>(Opcional)</u></i></label><span class="badge bg-warning" style="margin: 5px;">Solo se permiten hasta 255 caracteres</span>
                                <input class="form-control" type="text" name="comentarios" id="comentarios" placeholder="Solo se permiten hasta 100 caracteres" value="<?php echo $comentarios; ?>">

                                <hr>

                                <div class="col-sm-4">
                                    <label><i class="fa fa-tachometer"></i>&nbsp;Modelo CI:</label>
                                    <input class="form-control" type="text" name="modelo_ci" id="modelo_ci" readonly value="<?php echo $modelo_ci; ?>">
                                </div>

                                <div class="col-sm-4">
                                    <label><i class="fa fa-barcode"></i>&nbsp;Número de Serie:</label>
                                    <input class="form-control" type="text" name="numero_serie" id="numero_serie" readonly value="<?php echo $numero_serie; ?>">
                                </div>

                                <div class="col-sm-4">
                                    <label><i class="fa fa-calendar"></i>&nbsp;Fecha de Calibración:</label>
                                    <input class="form-control" type="text" name="fecha_calibracion" readonly value="<?php echo $fecha_calibracion; ?>"><br>
                                </div>

                                <div class="col-sm-6">
                                    <label><i class="fa fa-user-o"></i>&nbsp;Identificación del Cliente:</label>
                                    <input class="form-control" type="text" name="identificacion_cliente" id="identificacion_cliente" readonly value="<?php echo $identificacion_cliente; ?>"><hr>
                                </div>

                                <div class="col-sm-6">
                                    <label><i class="fa fa-user-circle"></i>&nbsp;Técnico Certificado:</label>
                                    <input class="form-control" type="text" name="tecnico" id="tecnico" readonly value="<?php echo $tecnico; ?>"><hr>
                                </div>

                                <div class="col-sm-4">
                                    <label><i class="fa fa-tachometer"></i>&nbsp;Presión Barométrica:</label>
                                    <input class="form-control" type="number" min="0" step="0.01" name="presion_barometrica" id="presion_barometrica" placeholder="Por ejemplo: 77.2 KPa" value="<?php echo $presion_barometrica; ?>"><hr>
                                </div>

                                <div class="col-sm-4">
                                    <label><i class="fa fa-thermometer-three-quarters"></i>&nbsp;Temperatura (°C):</label>
                                    <input class="form-control" type="number" min="0" step="0.01" name="temperatura" id="temperatura" placeholder="Por ejemplo: 26.3° C" value="<?php echo $temperatura; ?>"><hr>
                                </div>

                                <div class="col-sm-4">
                                    <label><i class="fa fa-thermometer-full"></i>&nbsp;Humedad Relativa:</label>
                                    <input class="form-control" type="number" min="0" step="0.01" name="humedad_relativa" id="humedad_relativa" placeholder="Por ejemplo: 34.7% HR" value="<?php echo $humedad_relativa; ?>"><hr><br>
                                </div>

                            <center><input class="btn btn-sm btn-danger" type="submit" value="Modificar" name="modificar_form"></center>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<br><br>

<?php end_section();
}else{
    header('Location: ../../../../../index.php');
}
?>