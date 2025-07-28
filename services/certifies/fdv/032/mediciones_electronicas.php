<?php
session_start();

if ($_SESSION['nombre'] != '' && $_SESSION['tipo'] == 'devecchi' || $_SESSION['tipo'] == 'admin') {
    include '../../assets/layout.php';
    section();

    $id_documento = $_SERVER['QUERY_STRING'];
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
                        <h1 class="animated lightSpeedIn">Certificado: <strong><u><?php echo $id_documento; ?></u></strong> | Mediciones Electrónicas</h1>
                        <span class="label label-danger"></span> 		 
                        <p class="pull-right text-primary"></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="container" style="margin-left: 5%;">
            <div class="row">
                <div class="col-sm-13">
                    <div class="panel panel-success">
                        <div class="panel-heading text-center"><strong>Para poder crear un nuevo certificado es necesario llenar los todos campos</strong></div>
                            <div class="panel-body">
                                <?php echo '<form role="form" action="../../../functions/add/electronic_measure.php?'.$id_documento.'" method="POST" enctype="multipart/form-data">'; ?>
                                    <div>
                                        <div class="container">
                                            <table class="table table-responsive table-hover table-bordered table-striped table-primary" style="margin-left: -15px;">
                                                <thead>
                                                    <tr>
                                                    <th>PRUEBA</th>
                                                    <th>ESPERADO</th>
                                                    <th>TOLERANCIA</th>
                                                    <th>CONDICIÓN ENCONTRADA</th>
                                                    <th>PASA</th>
                                                    <th>CONDICIÓN FINAL</th>
                                                    </tr>
                                                </thead>

                                                <!-- PRIMERA FILA -->
                                                <tbody>
                                                    <tr>
                                                    <td><strong>VOLTAJE LÁSER</strong></td>
                                                    <td><input class="form-control" type="number" name="esperado_voltaje" step="any" min="0" placeholder="Por ejemplo: 1.570" required>  Vdc †</td>
                                                    <td>(Valor de referencia)</td>
                                                    <td><input class="form-control" type="number" name="condicion_encontrada_voltaje" step="any" min="0" placeholder="Por ejemplo: 1.746" required> Vdc</td>
                                                    <td>
                                                        <select class="form-control" name="pasa_voltaje" required>
                                                            <option value=""> - Selecciona la opción correcta - </option>
                                                            <option value="SI">SI</option>
                                                            <option value="NO">NO</option>
                                                            <option value="N/A">N/A</option>
                                                        </select>
                                                    </td>
                                                    <td><input class="form-control" type="number" name="condicion_final_voltaje" step="any" min="0" placeholder="Por ejemplo: 1.570" required> Vdc</td>
                                                    </tr>
                                                </tbody>

                                                <!-- SEGUNDA FILA -->
                                                <tbody>
                                                    <tr>
                                                    <td><strong>FLUJO DE AIRE</strong></td>
                                                    <td><input class="form-control" type="number" name="esperado_flujo" step="0.01" min="0" placeholder="Por ejemplo: 28.3" required> LPM</td>
                                                    <td>± 1.4 LPM</td>
                                                    <td><input class="form-control" type="number" name="condicion_encontrada_flujo" step="0.01" min="0" placeholder="Por ejemplo: 28.4" required> LPM<i><strong>*</strong></i></td>
                                                    <td>
                                                        <select class="form-control" name="pasa_flujo" required>
                                                            <option value=""> - Selecciona la opción correcta - </option>
                                                            <option value="SI">SI</option>
                                                            <option value="NO">NO</option>
                                                            <option value="N/A">N/A</option>
                                                        </select>
                                                    </td>
                                                    <td><input class="form-control" type="number" name="condicion_final_flujo" step="0.01" min="0" placeholder="Por ejemplo: 28.4" required> LPM<i><strong>*</strong></i></td>
                                                    </tr>
                                                </tbody>

                                                <!-- TERCERA FILA -->
                                                <tbody>
                                                    <tr>
                                                    <td><strong>RUIDO MÁXIMO</strong></td>
                                                    <td>< 200 mV</td>
                                                    <td>(Valor de referencia)</td>
                                                    <td><input class="form-control" type="number" name="condicion_esperada_ruido" step="0.001" min="0" placeholder="Por ejemplo: 105.5" required> mV</td>
                                                    <td>
                                                        <select class="form-control" name="pasa_ruido" required>
                                                            <option value=""> - Selecciona la opción correcta - </option>
                                                            <option value="SI">SI</option>
                                                            <option value="NO">NO</option>
                                                            <option value="N/A">N/A</option>
                                                        </select>
                                                    </td>
                                                    <td><input class="form-control" type="number" name="condicion_final_ruido" step="0.01" min="0" placeholder="Por ejemplo: 103.5" required> mV</td>
                                                    </tr>
                                                </tbody>
                                            </table>

                                            <p><strong>±</strong> Valor inicial; el voltaje aumenta a medida que el diodo láser se desgasta.</p>

                                            <label><i class="fa fa-tachometer"></i>&nbsp;<i><strong>*</strong></i> Las lecturas del medidor de flujo volumétrico y reflejan una compensación correctiva de:</label>
                                            <input class="form mt-3" type="number" name="flujo_volumetrico" step="0.01" min="0" placeholder="Por ejemplo: 0" required> LPM.
                                            
                                        </div><br>

                                        <center><input class="btn btn-sm btn-success" type="submit" value="Siguiente" name="guardar_mediciones"></center>
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