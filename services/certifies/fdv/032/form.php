<?php
header('Content-Type: text/html; charset=UTF-8');
    session_start();
if ($_SESSION['nombre'] != '' && $_SESSION['tipo'] == 'devecchi' || $_SESSION['tipo'] == 'admin') { ?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
  <link rel="stylesheet" href="../../../assets/css/main.css">
  <?php include "../../assets/links2.php"; ?> 
  <?php include '../../assets/navbar.php'; ?>
</head>

<body>

<section id="content">	 
    <header id="content-header">
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
                        <h1 class="animated lightSpeedIn">Certificado: #</h1>
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
                        <div class="panel-heading text-center"><strong>Para poder crear un nuevo certificado es necesario llenar los todos campos</strong></div>
                            <div class="panel-body">
                                <form role="form" action="mediciones_electronicas.php" method="POST" enctype="multipart/form-data">
                                    <div>
                                        <label><i class="fa fa-barcode"></i>&nbsp;ID del documento:</label>
                                        <input class="form-control" type="text" name="id_documento" id="id_documento" value="001" readonly>
                                        <br>

                                        <label><i class="fa fa-user-circle"></i>&nbsp;Técnico Certificado:</label>
                                        <input class="form-control" type="text" name="tecnico" id="tecnico" readonly value="<?php echo $_SESSION['nombre_completo']." ".$_SESSION['apellido']; ?>">
                                        <br>

                                        <label><i class="fa fa-star-half-o"></i>&nbsp;Condición física al recibir:</label>
                                        <select class="form-control" name="condicion_fisica" required>
                                            <option value=""> - Selecciona la condición adecuada - </option>
                                            <option value="No aplica">No aplica</option>
                                            <option value="Bueno">Bueno</option>
                                            <option value="Dañado">Dañado</option>
                                            <option value="Mal empacado">Mal empacado</option>
                                            <option value="Mal manejo">Mal manejo</option>
                                        </select>
                                        <br>

                                        <label><i class="fa fa-star-o"></i>&nbsp;Condición de calibración encontrada:</label>
                                        <select class="form-control" name="condicion_calibracion" required>
                                            <option value=""> - Selecciona la condición adecuada - </option>
                                            <option value="Unidad Nueva">Unidad Nueva</option>
                                            <option value="En tolerancia">En tolerancia</option>
                                            <option value="Fuera de tolerancia">Fuera de tolerancia</option>
                                        </select>
                                        <br>

                                        <label><i class="fa fa-certificate"></i>&nbsp;Condición de calibración final:</label>
                                        <select class="form-control" name="condicion_final" required>
                                        <option value=""> - Selecciona la condición adecuada - </option>
                                            <option value="Dentro de Especificaciones">Dentro de Especificaciones</option>
                                        </select>
                                        <br>

                                        <label><i class="fa fa-comments-o"></i>&nbsp;Comentarios: <i><u>(Opcional)</u></i></label><span class="badge bg-warning" style="margin: 5px;">Solo se permiten hasta 100 caracteres</span>
                                        <textarea class="form-control" name="comentarios" rows="2" maxlength="100" placeholder="Solo se permiten hasta 100 caracteres"></textarea>
                                        <br>

                                        <label><i class="fa fa-tachometer"></i>&nbsp;Modelo CI:</label>
                                        <input class="form-control" type="text" name="modelo_ci" id="modelo_ci" value="001" readonly>
                                        <br>

                                        <label><i class="fa fa-barcode"></i>&nbsp;Número de Serie:</label>
                                        <input class="form-control" type="text" name="numero_serie" id="numero_serie" readonly>
                                        <br>

                                        <label><i class="fa fa-calendar"></i>&nbsp;Fecha de Calibración:</label>
                                        <input class="form-control" type="date" name="fecha_calibracion" min='2025-05-01' required>
                                        <br>

                                        <label><i class="fa fa-user-o"></i>&nbsp;Identificación del Cliente:</label>
                                        <input class="form-control" type="text" name="identificacion_cliente" id="identificacion_cliente" readonly>
                                        <br>

                                        <label><i class="fa fa-tachometer"></i>&nbsp;Presión Barométrica:</label>
                                        <input class="form-control" type="number" min="0" step="0.01" name="presion_barometrica" id="presion_barometrica" required placeholder="Por ejemplo: 77.2 KPa">
                                        <br>

                                        <label><i class="fa fa-thermometer-three-quarters"></i>&nbsp;Temperatura (°C):</label>
                                        <input class="form-control" type="number" min="0" step="0.01" name="temperatura" id="temperatura" required placeholder="Por ejemplo: 26.3° C">
                                        <br>

                                        <label><i class="fa fa-thermometer-full"></i>&nbsp;Humedad Relativa:</label>
                                        <input class="form-control" type="number" min="0" step="0.01" name="humedad relativa" id="humedad relativa" required placeholder="Por ejemplo: 34.7% HR">
                                        <br>

                                        <center><input class="btn btn-sm btn-success" type="submit" value="Siguiente" name="guardar"></center>
                                    </div>
                                </form>
                            </div>
                    </div>
                </div>
            </div>
        </div>

    </header>
</section>

</body>
</html>

<?php } else {
    header('Location: ../../../../index.php');
}
?>