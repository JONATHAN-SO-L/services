<?php
session_start();

if ($_SESSION['nombre'] != '' && $_SESSION['tipo'] == 'devecchi' || $_SESSION['tipo'] == 'admin') {
    include '../../assets/layout.php';
    section();
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
                                        <label><i class="fa fa-clock-o" aria-hidden="true"></i>&nbsp;Intervalo máximo de calibración recomendado (meses):</label>
                                        <input class="form-control" type="number" min="1" name="intervalo_calibracion" id="intervalo_calibracion" placeholder="Por ejemplo: 12" require>
                                        <br>

                                        <label><i class="fa fa-barcode"></i>&nbsp;ID del documento:</label>
                                        <input class="form-control" type="text" name="id_documento" id="id_documento" value="001" readonly>
                                        <br>

                                        <div class="col-sm-4">
                                        <label><i class="fa fa-star-half-o"></i>&nbsp;Condición física al recibir:</label>
                                        <select class="form-control" name="condicion_fisica" required>
                                            <option value=""> - Selecciona la condición adecuada - </option>
                                            <option value="No aplica">No aplica</option>
                                            <option value="Bueno">Bueno</option>
                                            <option value="Dañado">Dañado</option>
                                            <option value="Mal empacado">Mal empacado</option>
                                            <option value="Mal manejo">Mal manejo</option>
                                        </select>
                                        </div>

                                        <div class="col-sm-4">
                                        <label><i class="fa fa-star-o"></i>&nbsp;Condición de calibración encontrada:</label>
                                        <select class="form-control" name="condicion_calibracion" required>
                                            <option value=""> - Selecciona la condición adecuada - </option>
                                            <option value="Unidad Nueva">Unidad Nueva</option>
                                            <option value="En tolerancia">En tolerancia</option>
                                            <option value="Fuera de tolerancia">Fuera de tolerancia</option>
                                        </select>
                                        </div>

                                        <div class="col-sm-4">
                                        <label><i class="fa fa-certificate"></i>&nbsp;Condición de calibración final:</label>
                                        <select class="form-control" name="condicion_final" required>
                                        <option value=""> - Selecciona la condición adecuada - </option>
                                            <option value="Dentro de Especificaciones">Dentro de Especificaciones</option>
                                        </select><br>
                                        </div>

                                        <label><i class="fa fa-comments-o"></i>&nbsp;Comentarios: <i><u>(Opcional)</u></i></label><span class="badge bg-warning" style="margin: 5px;">Solo se permiten hasta 255 caracteres</span>
                                        <textarea class="form-control" name="comentarios" rows="2" maxlength="255" placeholder="Solo se permiten hasta 100 caracteres"></textarea>

                                        <hr>

                                        <div class="col-sm-4">
                                        <label><i class="fa fa-tachometer"></i>&nbsp;Modelo CI:</label>
                                        <input class="form-control" type="text" name="modelo_ci" id="modelo_ci" value="001" readonly>
                                        </div>

                                        <div class="col-sm-4">
                                        <label><i class="fa fa-barcode"></i>&nbsp;Número de Serie:</label>
                                        <input class="form-control" type="text" name="numero_serie" id="numero_serie" readonly>
                                        </div>

                                        <div class="col-sm-4">
                                        <label><i class="fa fa-calendar"></i>&nbsp;Fecha de Calibración:</label>
                                        <input class="form-control" type="date" name="fecha_calibracion" required><br>
                                        </div>
                                        
                                        <div class="col-sm-6">
                                        <label><i class="fa fa-user-o"></i>&nbsp;Identificación del Cliente:</label>
                                        <input class="form-control" type="text" name="identificacion_cliente" id="identificacion_cliente" readonly><hr>
                                        </div>

                                        <div class="col-sm-6">
                                        <label><i class="fa fa-user-circle"></i>&nbsp;Técnico Certificado:</label>
                                        <input class="form-control" type="text" name="tecnico" id="tecnico" readonly value="<?php echo $_SESSION['nombre_completo']." ".$_SESSION['apellido']; ?>"><hr>
                                        </div>

                                        <div class="col-sm-4">
                                        <label><i class="fa fa-tachometer"></i>&nbsp;Presión Barométrica:</label>
                                        <input class="form-control" type="number" min="0" step="0.01" name="presion_barometrica" id="presion_barometrica" required placeholder="Por ejemplo: 77.2 KPa"><hr>
                                        </div>

                                        <div class="col-sm-4">
                                        <label><i class="fa fa-thermometer-three-quarters"></i>&nbsp;Temperatura (°C):</label>
                                        <input class="form-control" type="number" min="0" step="0.01" name="temperatura" id="temperatura" required placeholder="Por ejemplo: 26.3° C"><hr>
                                        </div>

                                        <div class="col-sm-4">
                                        <label><i class="fa fa-thermometer-full"></i>&nbsp;Humedad Relativa:</label>
                                        <input class="form-control" type="number" min="0" step="0.01" name="humedad relativa" id="humedad relativa" required placeholder="Por ejemplo: 34.7% HR"><hr><br>
                                        </div>

                                        <center><input class="btn btn-sm btn-success" type="submit" value="Siguiente" name="guardar"></center>
                                    </div>
                                </form>
                            </div>
                    </div>
                </div>
            </div>
        </div>
<br><br>

<?php
end_section();
} else {
    header('Location: ../../../../index.php');
}
?>