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
                        <h1 class="animated lightSpeedIn">Certificado: <strong><u><?php echo $id_documento; ?></u></strong> | Comportamiento</h1>
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
                                <?php echo '<form role="form" action="../../../functions/add/behaviour.php?'.$id_documento.'" method="POST" enctype="multipart/form-data">'; ?>
                                    <div>
                                        <div class="container">
                                            <table class="table table-responsive table-hover table-bordered table-striped table-primary" style="margin-left: -15px;">
                                                <thead>
                                                    <tr>
                                                    <th>TAMAÑO DE PARTÍCULA NOMINAL</th>
                                                    <th>0.3 µm</th>
                                                    <th>0.5 µm</th>
                                                    <th>1.0 µm</th>
                                                    <th>5.0 µm</th>
                                                    </tr>
                                                </thead>

                                                <!-- PRIMERA FILA -->
                                                <tbody>
                                                    <tr>
                                                    <td><strong>AMPLITUD ESPERADA (desde la última calibración)</strong></td>
                                                    <td><input class="form-control" type="number" name="amplitud_esperada_03" step="0.01" min="0" placeholder="Por ejemplo: 302" required>  mV</td>
                                                    <td><input class="form-control" type="number" name="amplitud_esperada_05" step="0.01" min="0" placeholder="Por ejemplo: 302" required> mV</td>
                                                    <td><input class="form-control" type="number" name="amplitud_esperada_10" step="0.01" min="0" placeholder="Por ejemplo: 705" required> V</td>
                                                    <td><input class="form-control" type="number" name="amplitud_esperada_50" step="0.01" min="0" placeholder="Por ejemplo: 409" required> mV</td>
                                                    </tr>
                                                </tbody>

                                                <!-- SEGUNDA FILA -->
                                                <tbody>
                                                    <tr>
                                                    <td><strong>TOLERANCIA</strong></td>
                                                    <td>± <input class="form-control" type="number" name="tolerancia_03" value="60" readonly>  mV</td>
                                                    <td>± <input class="form-control" type="number" name="tolerancia_05" value="30" readonly>  mV</td>
                                                    <td>± <input class="form-control" type="number" name="tolerancia_10" value="165" readonly>  mV</td>
                                                    <td>± <input class="form-control" type="number" name="tolerancia_50" value="50" readonly>  mV</td>
                                                    </tr>
                                                </tbody>

                                                <!-- TERCERA FILA -->
                                                <tbody>
                                                    <tr>
                                                    <td><strong>COMO SE ENCUENTRA</strong></td>
                                                    <td><input class="form-control" type="number" name="como_encuentra_03" step="0.01" min="0" placeholder="Por ejemplo: 297" required>  mV</td>
                                                    <td><input class="form-control" type="number" name="como_encuentra_05" step="0.01" min="0" placeholder="Por ejemplo: 298" required>  mV</td>
                                                    <td><input class="form-control" type="number" name="como_encuentra_10" step="0.01" min="0" placeholder="Por ejemplo: 701" required>  V</td>
                                                    <td><input class="form-control" type="number" name="como_encuentra_50" step="0.01" min="0" placeholder="Por ejemplo: 399" required>  mV</td>
                                                    </tr>
                                                </tbody>

                                                <!-- CUARTA FILA -->
                                                <tbody>
                                                    <tr>
                                                    <td><strong>PASA (S/N)</strong></td>
                                                    <td>
                                                        <select class="form-control" name="pasa_03" required>
                                                            <option value=""> - Selecciona - </option>
                                                            <option value="SI">SI</option>
                                                            <option value="NO">NO</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <select class="form-control" name="pasa_05" required>
                                                            <option value=""> - Selecciona - </option>
                                                            <option value="SI">SI</option>
                                                            <option value="NO">NO</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <select class="form-control" name="pasa_10" required>
                                                            <option value=""> - Selecciona - </option>
                                                            <option value="SI">SI</option>
                                                            <option value="NO">NO</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <select class="form-control" name="pasa_50" required>
                                                            <option value=""> - Selecciona - </option>
                                                            <option value="SI">SI</option>
                                                            <option value="NO">NO</option>
                                                        </select>
                                                    </td>
                                                    </tr>
                                                </tbody>

                                                <!-- QUINTA FILA -->
                                                <tbody>
                                                    <tr>
                                                    <td><strong>CONDICIÓN FINAL</strong></td>
                                                    <td><input class="form-control" type="number" name="condicion_final_03" step="0.01" min="0" placeholder="Por ejemplo: 305" required>  mV</td>
                                                    <td><input class="form-control" type="number" name="condicion_final_05" step="0.01" min="0" placeholder="Por ejemplo: 305" required>  mV</td>
                                                    <td><input class="form-control" type="number" name="condicion_final_10" step="0.01" min="0" placeholder="Por ejemplo: 704" required>  V</td>
                                                    <td><input class="form-control" type="number" name="condicion_final_50" step="0.01" min="0" placeholder="Por ejemplo: 401" required>  mV</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            
                                        </div><br>

                                        <center><input class="btn btn-sm btn-success" type="submit" value="Siguiente" name="guardar_comportamiento"></center>
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