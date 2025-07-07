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

    // Recuperar información del comportamiento 1
    require '../../../../functions/conex_serv.php';
    $certified = 'fdv_s_032';

    $s_behaviour2 = $con->prepare("SELECT amplitud_esperada_05_100, tolerancia_05_100, como_encuentra_05_100, pasa_05_100, condicion_final_05_100,
                                            amplitud_esperada_10_100, tolerancia_10_100, como_encuentra_10_100, pasa_10_100, condicion_final_10_100,
                                            amplitud_esperada_30_100, tolerancia_30_100, como_encuentra_30_100, pasa_30_100, condicion_final_30_100,
                                            amplitud_esperada_50_100, tolerancia_50_100, como_encuentra_50_100, pasa_50_100, condicion_final_50_100
                                    FROM $certified WHERE id_documento = :id_documento");
    $s_behaviour2->bindValue(':id_documento', $id_documento);
    $s_behaviour2->setFetchMode(PDO::FETCH_OBJ);
    $s_behaviour2->execute();

    $f_behaviour2 = $s_behaviour2->fetchAll();

    if ($s_behaviour2 -> rowCount() > 0) {
        foreach ($f_behaviour2 as $comportamiento) {
            $amplitud_esperada_05_100 = $comportamiento -> amplitud_esperada_05_100;
            $tolerancia_05_100 = $comportamiento -> tolerancia_05_100;
            $como_encuentra_05_100 = $comportamiento -> como_encuentra_05_100;
            $pasa_05_100 = $comportamiento -> pasa_05_100;
            $condicion_final_05_100 = $comportamiento -> condicion_final_05_100;
            $amplitud_esperada_10_100 = $comportamiento -> amplitud_esperada_10_100;
            $tolerancia_10_100 = $comportamiento -> tolerancia_10_100;
            $como_encuentra_10_100 = $comportamiento -> como_encuentra_10_100;
            $pasa_10_100 = $comportamiento -> pasa_10_100;
            $condicion_final_10_100 = $comportamiento -> condicion_final_10_100;
            $amplitud_esperada_30_100 = $comportamiento -> amplitud_esperada_30_100;
            $tolerancia_30_100 = $comportamiento -> tolerancia_30_100;
            $como_encuentra_30_100 = $comportamiento -> como_encuentra_30_100;
            $pasa_30_100 = $comportamiento -> pasa_30_100;
            $condicion_final_30_100 = $comportamiento -> condicion_final_30_100;
            $amplitud_esperada_50_100 = $comportamiento -> amplitud_esperada_50_100;
            $tolerancia_50_100 = $comportamiento -> tolerancia_50_100;
            $como_encuentra_50_100 = $comportamiento -> como_encuentra_50_100;
            $pasa_50_100 = $comportamiento -> pasa_50_100;
            $condicion_final_50_100 = $comportamiento -> condicion_final_50_100;
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
                <h1 class="animated lightSpeedIn">Certificado: <strong><u><?php echo $id_documento; ?></u></strong> | Comportamiento</h1>
                <span class="label label-danger"></span> 		 
                <p class="pull-right text-warning"></p>
            </div>
        </div>
    </div>
</div>

<div class="container" style="margin-left: 5%;">
    <div class="row">
        <div class="col-sm-13">
            <div class="panel panel-warning">
                <div class="panel-heading text-center"><strong>Verifica que los nuevos datos son correctos</strong></div>
                    <div class="panel-body">
                        <?php echo '<form role="form" action="../../../../functions/mod/behaviour.php?'.$id_documento.'" method="POST" enctype="multipart/form-data">'; ?>
                            <div>
                                <div class="container">
                                    <table class="table table-responsive table-hover table-bordered table-striped table-warning" style="margin-left: -15px;">
                                    <thead>
                                    <tr>
                                    <th>TAMAÑO DE PARTÍCULA NOMINAL</th>
                                    <th>0.5 µm</th>
                                    <th>1.0 µm</th>
                                    <th>3.0 µm</th>
                                    <th>5.0 µm</th>
                                    </tr>
                                    </thead>

                                    <!-- PRIMERA FILA -->
                                    <tbody>
                                    <tr>
                                    <td><strong>AMPLITUD ESPERADA (desde la última calibración)</strong></td>
                                    <td><input class="form-control" type="number" name="amplitud_esperada_05" step="0.01" min="0" placeholder="Por ejemplo: 317" value="<?php echo $amplitud_esperada_05_100; ?>">  mV</td>
                                    <td><input class="form-control" type="number" name="amplitud_esperada_10" step="0.01" min="0" placeholder="Por ejemplo: 275" value="<?php echo $amplitud_esperada_10_100; ?>"> mV</td>
                                    <td><input class="form-control" type="number" name="amplitud_esperada_30" step="0.01" min="0" placeholder="Por ejemplo: 1414" value="<?php echo $amplitud_esperada_30_100; ?>"> mV</td>
                                    <td><input class="form-control" type="number" name="amplitud_esperada_50" step="0.01" min="0" placeholder="Por ejemplo: 395" value="<?php echo $amplitud_esperada_50_100; ?>"> mV</td>
                                    </tr>
                                    </tbody>

                                    <!-- SEGUNDA FILA -->
                                    <tbody>
                                    <tr>
                                    <td><strong>TOLERANCIA</strong></td>
                                    <td>± <input class="form-control" type="number" name="tolerancia_05" readonly value="60">  mV</td>
                                    <td>± <input class="form-control" type="number" name="tolerancia_10" readonly value="30">  mV</td>
                                    <td>± <input class="form-control" type="number" name="tolerancia_30" readonly value="150">  mV</td>
                                    <td>± <input class="form-control" type="number" name="tolerancia_50" readonly value="50">  mV</td>
                                    </tr>
                                    </tbody>

                                    <!-- TERCERA FILA -->
                                    <tbody>
                                    <tr>
                                    <td><strong>COMO SE ENCUENTRA</strong></td>
                                    <td><input class="form-control" type="number" name="como_encuentra_05" step="0.01" min="0" placeholder="Por ejemplo: 312" value="<?php echo $como_encuentra_05_100; ?>">  mV</td>
                                    <td><input class="form-control" type="number" name="como_encuentra_10" step="0.01" min="0" placeholder="Por ejemplo: 296" value="<?php echo $como_encuentra_10_100; ?>">  mV</td>
                                    <td><input class="form-control" type="number" name="como_encuentra_30" step="0.01" min="0" placeholder="Por ejemplo: 1598" value="<?php echo $como_encuentra_30_100; ?>">  mV</td>
                                    <td><input class="form-control" type="number" name="como_encuentra_50" step="0.01" min="0" placeholder="Por ejemplo: 407" value="<?php echo $como_encuentra_50_100; ?>">  mV</td>
                                    </tr>
                                    </tbody>

                                    <!-- CUARTA FILA -->
                                    <tbody>
                                    <tr>
                                    <td><strong>PASA (S/N)</strong></td>
                                    <td>
                                    <select class="form-control" name="pasa_05">
                                    <?php echo '<option value="'.$pasa_05_100.'">'.$pasa_05_100.' - (Actual seleccionado)</option>'; ?>
                                    <option value="SI">SI</option>
                                    <option value="NO">NO</option>
                                    </select>
                                    </td>
                                    <td>
                                    <select class="form-control" name="pasa_10">
                                    <?php echo '<option value="'.$pasa_10_100.'">'.$pasa_10_100.' - (Actual seleccionado)</option>'; ?>
                                    <option value="SI">SI</option>
                                    <option value="NO">NO</option>
                                    </select>
                                    </td>
                                    <td>
                                    <select class="form-control" name="pasa_30">
                                    <?php echo '<option value="'.$pasa_30_100.'">'.$pasa_30_100.' - (Actual seleccionado)</option>'; ?>
                                    <option value="SI">SI</option>
                                    <option value="NO">NO</option>
                                    </select>
                                    </td>
                                    <td>
                                    <select class="form-control" name="pasa_50">
                                    <?php echo '<option value="'.$pasa_50_100.'">'.$pasa_50_100.' - (Actual seleccionado)</option>'; ?>
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
                                    <td><input class="form-control" type="number" name="condicion_final_05" step="0.01" min="0" placeholder="Por ejemplo: 300" value="<?php echo $condicion_final_05_100; ?>">  mV</td>
                                    <td><input class="form-control" type="number" name="condicion_final_10" step="0.01" min="0" placeholder="Por ejemplo: 300" value="<?php echo $condicion_final_10_100; ?>">  mV</td>
                                    <td><input class="form-control" type="number" name="condicion_final_30" step="0.01" min="0" placeholder="Por ejemplo: 1618" value="<?php echo $condicion_final_30_100; ?>">  mV</td>
                                    <td><input class="form-control" type="number" name="condicion_final_50" step="0.01" min="0" placeholder="Por ejemplo: 405" value="<?php echo $condicion_final_50_100; ?>">  mV</td>
                                    </tr>
                                    </tbody>
                                    </table>

                                </div><br>

                                <center><input class="btn btn-sm btn-danger" type="submit" value="Modificar" name="modificar_comportamiento2"></center>
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