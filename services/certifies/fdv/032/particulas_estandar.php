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
                        <h1 class="animated lightSpeedIn">Certificado: # | Partículas Estándar</h1>
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
                                <form role="form" action="index.php" method="POST" enctype="multipart/form-data">
                                    <div>
                                        <div class="container">
                                            <table class="table table-responsive table-hover table-bordered table-striped table-primary" style="margin-left: -15px;">
                                                <thead>
                                                    <tr>
                                                    <th>MEDIDA NOMINAL</th>
                                                    <th>TAMAÑO REAL</th>
                                                    <th>DESVIACIÓN DE TAMAÑO</th>
                                                    <th>No. LOTE</th>
                                                    <th>EXP. FECHA</th>
                                                    <th>MEDIDA NOMINAL</th>
                                                    <th>TAMAÑO REAL</th>
                                                    <th>DESVIACIÓN DE TAMAÑO</th>
                                                    <th>No. LOTE</th>
                                                    <th>EXP. FECHA</th>
                                                    </tr>
                                                </thead>

                                                <!-- PRIMERA FILA -->
                                                <tbody>
                                                    <tr>
                                                    <td><strong>0.3 µm</strong></td>
                                                    <td><input class="form-control" type="number" name="tamano_real_03" step="0.01" min="0" placeholder="0.303"></td>
                                                    <td><input class="form-control" type="number" name="desviacion_tamano_03" step="0.01" min="0" placeholder="0.006">±</td>
                                                    <td><input class="form-control" type="number" name="no_lote_03" step="0.01" min="0" placeholder="248877"></td>
                                                    <td><input class="form-control" type="text" name="exp_fecha_03" placeholder="ENE 25"></td>

                                                    <td><strong>0.8 µm</strong></td>
                                                    <td><input class="form-control" type="number" name="tamano_real_08" step="0.01" min="0" placeholder="N/A"></td>
                                                    <td><input class="form-control" type="number" name="desviacion_tamano_08" step="0.01" min="0" placeholder="N/A">±</td>
                                                    <td><input class="form-control" type="number" name="no_lote_08" step="0.01" min="0" placeholder="N/A"></td>
                                                    <td><input class="form-control" type="text" name="exp_fecha_08" placeholder="N/A"></td>
                                                    </tr>
                                                </tbody>

                                                <!-- SEGUNDA FILA -->
                                                <tbody>
                                                    <tr>
                                                    <td><strong>0.4 µm</strong></td>
                                                    <td><input class="form-control" type="number" name="tamano_real_04" step="0.01" min="0" placeholder="N/A"></td>
                                                    <td><input class="form-control" type="number" name="desviacion_tamano_04" step="0.01" min="0" placeholder="N/A">±</td>
                                                    <td><input class="form-control" type="number" name="no_lote_04" step="0.01" min="0" placeholder="N/A"></td>
                                                    <td><input class="form-control" type="text" name="exp_fecha_04" placeholder="N/A"></td>

                                                    <td><strong>1.0 µm</strong></td>
                                                    <td><input class="form-control" type="number" name="tamano_real_08" step="0.01" min="0" placeholder="0.994"></td>
                                                    <td><input class="form-control" type="number" name="desviacion_tamano_08" step="0.01" min="0" placeholder="0.015">±</td>
                                                    <td><input class="form-control" type="number" name="no_lote_08" step="0.01" min="0" placeholder="247589"></td>
                                                    <td><input class="form-control" type="text" name="exp_fecha_08" placeholder="DIC 24"></td>
                                                    </tr>
                                                </tbody>

                                                <!-- TERCERA FILA -->
                                                <tbody>
                                                    <tr>
                                                    <td><strong>0.5 µm</strong></td>
                                                    <td><input class="form-control" type="number" name="tamano_real_05" step="0.01" min="0" placeholder="0.510"></td>
                                                    <td><input class="form-control" type="number" name="desviacion_tamano_05" step="0.01" min="0" placeholder="0.010">±</td>
                                                    <td><input class="form-control" type="number" name="no_lote_05" step="0.01" min="0" placeholder="250693"></td>
                                                    <td><input class="form-control" type="text" name="exp_fecha_05" placeholder="FEB 25"></td>

                                                    <td><strong>3.0 µm</strong></td>
                                                    <td><input class="form-control" type="number" name="tamano_real_30" step="0.01" min="0" placeholder="N/A"></td>
                                                    <td><input class="form-control" type="number" name="desviacion_tamano_30" step="0.01" min="0" placeholder="N/A">±</td>
                                                    <td><input class="form-control" type="number" name="no_lote_30" step="0.01" min="0" placeholder="N/A"></td>
                                                    <td><input class="form-control" type="text" name="exp_fecha_30" placeholder="N/A"></td>
                                                    </tr>
                                                </tbody>

                                                <!-- CUARTA FILA -->
                                                <tbody>
                                                    <tr>
                                                    <td><strong>0.6 µm</strong></td>
                                                    <td><input class="form-control" type="number" name="tamano_real_06" step="0.01" min="0" placeholder="N/A"></td>
                                                    <td><input class="form-control" type="number" name="desviacion_tamano_06" step="0.01" min="0" placeholder="N/A">±</td>
                                                    <td><input class="form-control" type="number" name="no_lote_06" step="0.01" min="0" placeholder="N/A"></td>
                                                    <td><input class="form-control" type="text" name="exp_fecha_06" placeholder="N/A"></td>

                                                    <td><strong>5.0 µm</strong></td>
                                                    <td><input class="form-control" type="number" name="tamano_real_50" step="0.01" min="0" placeholder="5.014"></td>
                                                    <td><input class="form-control" type="number" name="desviacion_tamano_50" step="0.01" min="0" placeholder="0.047">±</td>
                                                    <td><input class="form-control" type="number" name="no_lote_50" step="0.01" min="0" placeholder="259013"></td>
                                                    <td><input class="form-control" type="text" name="exp_fecha_50" placeholder="SEP 25"></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            
                                        </div><br>

                                        <center><input class="btn btn-sm btn-success" type="submit" value="Siguiente" name="guardar"></center>
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