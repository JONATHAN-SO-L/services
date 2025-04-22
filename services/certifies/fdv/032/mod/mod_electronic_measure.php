<?php
session_start();
include '../../../assets/layout2.php';

if ($_SESSION['nombre'] != '' && $_SESSION['tipo'] == 'devecchi' || $_SESSION['tipo'] == 'admin') {
section(); ?>

<table>
    <tr>
        <a href="modificar.php"><button type="submit" value="Volver" class="btn btn-primary" style="text-align:center"><i class="fa fa-reply"></i>&nbsp;&nbsp;Volver</button></a>
    </tr>
</table>

<div class="container" style="width: 1030px;">
    <div class="row" style="width: 770px;">
        <div class="col-sm-12">
            <div class="page-header2">
                <h1 class="animated lightSpeedIn">Certificado: # | Mediciones Electrónicas</h1>
                <span class="label label-danger"></span> 		 
                <p class="pull-right text-primary"></p>
            </div>
        </div>
    </div>
</div>

<div class="container" style="margin-left: 5%;">
    <div class="row">
        <div class="col-sm-13">
            <div class="panel panel-warning">
                <div class="panel-heading text-center"><strong>Para poder crear un nuevo certificado es necesario llenar los todos campos</strong></div>
                    <div class="panel-body">
                        <form role="form" action="modificar.php" method="POST" enctype="multipart/form-data">
                            <div>
                                <div class="container">
                                    <table class="table table-responsive table-hover table-bordered table-striped table-warning" style="margin-left: -15px;">
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
                                    <td><input class="form-control" type="number" name="esperado_voltaje" step="0.01" min="0" placeholder="Por ejemplo: 1.570">  Vdc †</td>
                                    <td>(Valor de referencia)</td>
                                    <td><input class="form-control" type="number" name="condicion_encontrada_voltaje" step="0.01" min="0" placeholder="Por ejemplo: 1.746"> Vdc</td>
                                    <td>
                                    <select class="form-control" name="pasa_voltaje">
                                    <option value=""> - Selecciona la opción correcta - </option>
                                    <option value="SI">SI</option>
                                    <option value="NO">NO</option>
                                    <option value="N/A">N/A</option>
                                    </select>
                                    </td>
                                    <td><input class="form-control" type="number" name="condicion_final_voltaje" step="0.01" min="0" placeholder="Por ejemplo: 1.570"> Vdc</td>
                                    </tr>
                                    </tbody>

                                    <!-- SEGUNDA FILA -->
                                    <tbody>
                                    <tr>
                                    <td><strong>FLUJO DE AIRE</strong></td>
                                    <td><input class="form-control" type="number" name="esperado_flujo" step="0.01" min="0" placeholder="Por ejemplo: 28.3"> LPM</td>
                                    <td>± 1.4 LPM</td>
                                    <td><input class="form-control" type="number" name="condicion_encontrada_flujo" step="0.01" min="0" placeholder="Por ejemplo: 28.4"> LPM<i><strong>*</strong></i></td>
                                    <td>
                                    <select class="form-control" name="pasa_flujo">
                                    <option value=""> - Selecciona la opción correcta - </option>
                                    <option value="SI">SI</option>
                                    <option value="NO">NO</option>
                                    <option value="N/A">N/A</option>
                                    </select>
                                    </td>
                                    <td><input class="form-control" type="number" name="condicion_final_flujo" step="0.01" min="0" placeholder="Por ejemplo: 28.4"> LPM<i><strong>*</strong></i></td>
                                    </tr>
                                    </tbody>

                                    <!-- TERCERA FILA -->
                                    <tbody>
                                    <tr>
                                    <td><strong>RUIDO MÁXIMO</strong></td>
                                    <td>< 200 mV</td>
                                    <td>(Valor de referencia)</td>
                                    <td><input class="form-control" type="number" name="condicion_esperada_ruido" step="0.001" min="0" placeholder="Por ejemplo: 105.5"> mV</td>
                                    <td>
                                    <select class="form-control" name="pasa_ruido">
                                    <option value=""> - Selecciona la opción correcta - </option>
                                    <option value="SI">SI</option>
                                    <option value="NO">NO</option>
                                    <option value="N/A">N/A</option>
                                    </select>
                                    </td>
                                    <td><input class="form-control" type="number" name="condicion_final_ruido" step="0.01" min="0" placeholder="Por ejemplo: 103.5"> mV</td>
                                    </tr>
                                    </tbody>
                                    </table>

                                    <p><strong>±</strong> Valor inicial; el voltaje aumenta a medida que el diodo láser se desgasta.</p>

                                    <label><i class="fa fa-tachometer"></i>&nbsp;<i><strong>*</strong></i> Las lecturas del medidor de flujo volumétrico y reflejan una compensación correctiva de:</label>
                                    <input class="form mt-3" type="number" name="flujo_volumétrico" step="0.01" min="0" placeholder="Por ejemplo: 0"> LPM.

                                </div><br>

                                <center><input class="btn btn-sm btn-danger" type="submit" value="Guardar" name="guardar"></center>
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