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
            <u>No se logró recibir información</u> de los <strong>Contadorres de Partículas</strong> en sistema, por favor, inténtalo de nuevo o contácta al Soporte Técnico.
            </p>
            </div>
            ';
    }

    // Recuperación de información almacenada en DDBB
    require '../../../../functions/conex_serv.php';
    $certified = 'fdv_s_032';

    $s_measures = $con->prepare("SELECT * FROM $certified WHERE id_documento = :id_documento");
    $s_measures->bindValue(':id_documento', $id_documento);
    $s_measures->setFetchMode(PDO::FETCH_OBJ);
    $s_measures->execute();

    $f_measures = $s_measures->fetchAll();

    if ($s_measures -> rowCount() > 0) {
        foreach ($f_measures as $mediciones) {
            $vl_esperado = $mediciones -> vl_esperado;
            $vl_condicion_encontrada = $mediciones -> vl_condicion_encontrada;
            $vl_pasa = $mediciones -> vl_pasa;
            $vl_condicion_final = $mediciones -> vl_condicion_final;
            $fa_esperado = $mediciones -> fa_esperado;
            $fa_condicion_encontrada = $mediciones -> fa_condicion_encontrada;
            $fa_pasa = $mediciones -> fa_pasa;
            $fa_condicion_final = $mediciones -> fa_condicion_final;
            $rm_condicion_encontrada = $mediciones -> rm_condicion_encontrada;
            $rm_pasa = $mediciones -> rm_pasa;
            $rm_condicion_final = $mediciones -> rm_condicion_final;
            $flujo_volumetrico = $mediciones -> flujo_volumetrico;
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
            <div class="panel panel-warning">
                <div class="panel-heading text-center"><strong>Para poder crear un nuevo certificado es necesario llenar los todos campos</strong></div>
                    <div class="panel-body">
                        <?php echo '<form role="form" action="../../../../functions/mod/electronic_measure.php?'.$id_documento.'" method="POST" enctype="multipart/form-data">'; ?>
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
                                    <td><input class="form-control" type="number" name="esperado_voltaje" step="any" min="0" placeholder="Por ejemplo: 1.570" value="<?php echo $vl_esperado; ?>">  Vdc †</td>
                                    <td>(Valor de referencia)</td>
                                    <td><input class="form-control" type="number" name="condicion_encontrada_voltaje" step="any" min="0" placeholder="Por ejemplo: 1.746" value="<?php echo $vl_condicion_encontrada; ?>"> Vdc</td>
                                    <td>
                                    <select class="form-control" name="pasa_voltaje">
                                    <?php echo '<option value="'.$vl_pasa.'">'.$vl_pasa.' - (Actual seleccionado)</option>'; ?>
                                    <option value="SI">SI</option>
                                    <option value="NO">NO</option>
                                    <option value="N/A">N/A</option>
                                    </select>
                                    </td>
                                    <td><input class="form-control" type="number" name="condicion_final_voltaje" step="any" min="0" placeholder="Por ejemplo: 1.570" value="<?php echo $vl_condicion_final; ?>"> Vdc</td>
                                    </tr>
                                    </tbody>

                                    <!-- SEGUNDA FILA -->
                                    <tbody>
                                    <tr>
                                    <td><strong>FLUJO DE AIRE</strong></td>
                                    <td><input class="form-control" type="number" name="esperado_flujo" step="0.01" min="0" placeholder="Por ejemplo: 28.3" value="<?php echo $fa_esperado; ?>"> LPM</td>
                                    <td>± 1.4 LPM</td>
                                    <td><input class="form-control" type="number" name="condicion_encontrada_flujo" step="0.01" min="0" placeholder="Por ejemplo: 28.4" value="<?php echo $fa_condicion_encontrada; ?>"> LPM<i><strong>*</strong></i></td>
                                    <td>
                                    <select class="form-control" name="pasa_flujo">
                                    <?php echo '<option value="'.$fa_pasa.'">'.$fa_pasa.' - (Actual seleccionado)</option>'; ?>
                                    <option value="SI">SI</option>
                                    <option value="NO">NO</option>
                                    <option value="N/A">N/A</option>
                                    </select>
                                    </td>
                                    <td><input class="form-control" type="number" name="condicion_final_flujo" step="0.01" min="0" placeholder="Por ejemplo: 28.4" value="<?php echo $fa_condicion_final; ?>"> LPM<i><strong>*</strong></i></td>
                                    </tr>
                                    </tbody>

                                    <!-- TERCERA FILA -->
                                    <tbody>
                                    <tr>
                                    <td><strong>RUIDO MÁXIMO</strong></td>
                                    <td>< 200 mV</td>
                                    <td>(Valor de referencia)</td>
                                    <td><input class="form-control" type="number" name="condicion_esperada_ruido" step="0.001" min="0" placeholder="Por ejemplo: 105.5" value="<?php echo $rm_condicion_encontrada; ?>"> mV</td>
                                    <td>
                                    <select class="form-control" name="pasa_ruido">
                                    <?php echo '<option value="'.$rm_pasa.'">'.$rm_pasa.' - (Actual seleccionado)</option>'; ?>
                                    <option value="SI">SI</option>
                                    <option value="NO">NO</option>
                                    <option value="N/A">N/A</option>
                                    </select>
                                    </td>
                                    <td><input class="form-control" type="number" name="condicion_final_ruido" step="0.01" min="0" placeholder="Por ejemplo: 103.5" value="<?php echo $rm_condicion_final; ?>"> mV</td>
                                    </tr>
                                    </tbody>
                                    </table>

                                    <p><strong>±</strong> Valor inicial; el voltaje aumenta a medida que el diodo láser se desgasta.</p>

                                    <label><i class="fa fa-tachometer"></i>&nbsp;<i><strong>*</strong></i> Las lecturas del medidor de flujo volumétrico y reflejan una compensación correctiva de:</label>
                                    <input class="form mt-3" type="number" name="flujo_volumetrico" step="0.01" min="0" placeholder="Por ejemplo: 0" value="<?php echo $flujo_volumetrico; ?>"> LPM.

                                </div><br>

                                <center><input class="btn btn-sm btn-danger" type="submit" value="Modificar" name="modificar_mediciones"></center>
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