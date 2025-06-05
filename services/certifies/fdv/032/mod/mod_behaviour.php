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

    $s_behaviour1 = $con->prepare("SELECT amplitud_esperada_03, tolerancia_03, como_encuentra_03, pasa_03, condicion_final_03,
                                            amplitud_esperada_05, tolerancia_05, como_encuentra_05, pasa_05, condicion_final_05,
                                            amplitud_esperada_10, tolerancia_10, como_encuentra_10, pasa_10, condicion_final_10,
                                            amplitud_esperada_50, tolerancia_50, como_encuentra_50, pasa_50, condicion_final_50
                                    FROM $certified WHERE id_documento = :id_documento");
    $s_behaviour1->bindValue(':id_documento', $id_documento);
    $s_behaviour1->setFetchMode(PDO::FETCH_OBJ);
    $s_behaviour1->execute();

    $f_behaviour1 = $s_behaviour1->fetchAll();

    if ($s_behaviour1 -> rowCount() > 0) {
        foreach ($f_behaviour1 as $comportamiento) {
            $amplitud_esperada_03 = $comportamiento -> amplitud_esperada_03;
            $tolerancia_03 = $comportamiento -> tolerancia_03;
            $como_encuentra_03 = $comportamiento -> como_encuentra_03;
            $pasa_03 = $comportamiento -> pasa_03;
            $condicion_final_03 = $comportamiento -> condicion_final_03;
            $amplitud_esperada_05 = $comportamiento -> amplitud_esperada_05;
            $tolerancia_05 = $comportamiento -> tolerancia_05;
            $como_encuentra_05 = $comportamiento -> como_encuentra_05;
            $pasa_05 = $comportamiento -> pasa_05;
            $condicion_final_05 = $comportamiento -> condicion_final_05;
            $amplitud_esperada_10 = $comportamiento -> amplitud_esperada_10;
            $tolerancia_10 = $comportamiento -> tolerancia_10;
            $como_encuentra_10 = $comportamiento -> como_encuentra_10;
            $pasa_10 = $comportamiento -> pasa_10;
            $condicion_final_10 = $comportamiento -> condicion_final_10;
            $amplitud_esperada_50 = $comportamiento -> amplitud_esperada_50;
            $tolerancia_50 = $comportamiento -> tolerancia_50;
            $como_encuentra_50 = $comportamiento -> como_encuentra_50;
            $pasa_50 = $comportamiento -> pasa_50;
            $condicion_final_50 = $comportamiento -> condicion_final_50;
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
                                    <td><input class="form-control" type="number" name="amplitud_esperada_03" step="0.01" min="0" placeholder="Por ejemplo: 302" value="<?php echo $amplitud_esperada_03; ?>">  mV</td>
                                    <td><input class="form-control" type="number" name="amplitud_esperada_05" step="0.01" min="0" placeholder="Por ejemplo: 302" value="<?php echo $amplitud_esperada_05; ?>"> mV</td>
                                    <td><input class="form-control" type="number" name="amplitud_esperada_10" step="0.01" min="0" placeholder="Por ejemplo: 705" value="<?php echo $amplitud_esperada_10; ?>"> V</td>
                                    <td><input class="form-control" type="number" name="amplitud_esperada_50" step="0.01" min="0" placeholder="Por ejemplo: 409" value="<?php echo $amplitud_esperada_50; ?>"> mV</td>
                                    </tr>
                                    </tbody>

                                    <!-- SEGUNDA FILA -->
                                    <tbody>
                                    <tr>
                                    <td><strong>TOLERANCIA</strong></td>
                                    <td>± <input class="form-control" type="number" name="tolerancia_03" readonly value="<?php echo $tolerancia_03; ?>">  mV</td>
                                    <td>± <input class="form-control" type="number" name="tolerancia_05" readonly value="<?php echo $tolerancia_05; ?>">  mV</td>
                                    <td>± <input class="form-control" type="number" name="tolerancia_10" readonly value="<?php echo $tolerancia_10; ?>">  mV</td>
                                    <td>± <input class="form-control" type="number" name="tolerancia_50" readonly value="<?php echo $tolerancia_50; ?>">  mV</td>
                                    </tr>
                                    </tbody>

                                    <!-- TERCERA FILA -->
                                    <tbody>
                                    <tr>
                                    <td><strong>COMO SE ENCUENTRA</strong></td>
                                    <td><input class="form-control" type="number" name="como_encuentra_03" step="0.01" min="0" placeholder="Por ejemplo: 297" value="<?php echo $como_encuentra_03; ?>">  mV</td>
                                    <td><input class="form-control" type="number" name="como_encuentra_05" step="0.01" min="0" placeholder="Por ejemplo: 298" value="<?php echo $como_encuentra_05; ?>">  mV</td>
                                    <td><input class="form-control" type="number" name="como_encuentra_10" step="0.01" min="0" placeholder="Por ejemplo: 701" value="<?php echo $como_encuentra_10; ?>">  V</td>
                                    <td><input class="form-control" type="number" name="como_encuentra_50" step="0.01" min="0" placeholder="Por ejemplo: 399" value="<?php echo $como_encuentra_50; ?>">  mV</td>
                                    </tr>
                                    </tbody>

                                    <!-- CUARTA FILA -->
                                    <tbody>
                                    <tr>
                                    <td><strong>PASA (S/N)</strong></td>
                                    <td>
                                    <select class="form-control" name="pasa_03">
                                    <?php echo '<option value="'.$pasa_03.'">'.$pasa_03.' - (Actual seleccionado)</option>'; ?>
                                    <option value="SI">SI</option>
                                    <option value="NO">NO</option>
                                    </select>
                                    </td>
                                    <td>
                                    <select class="form-control" name="pasa_05">
                                    <?php echo '<option value="'.$pasa_05.'">'.$pasa_05.' - (Actual seleccionado)</option>'; ?>
                                    <option value="SI">SI</option>
                                    <option value="NO">NO</option>
                                    </select>
                                    </td>
                                    <td>
                                    <select class="form-control" name="pasa_10">
                                    <?php echo '<option value="'.$pasa_10.'">'.$pasa_10.' - (Actual seleccionado)</option>'; ?>
                                    <option value="SI">SI</option>
                                    <option value="NO">NO</option>
                                    </select>
                                    </td>
                                    <td>
                                    <select class="form-control" name="pasa_50">
                                        <?php echo '<option value="'.$pasa_50.'">'.$pasa_50.' - (Actual seleccionado)</option>'; ?>
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
                                    <td><input class="form-control" type="number" name="condicion_final_03" step="0.01" min="0" placeholder="Por ejemplo: 305" value="<?php echo $condicion_final_03; ?>">  mV</td>
                                    <td><input class="form-control" type="number" name="condicion_final_05" step="0.01" min="0" placeholder="Por ejemplo: 305" value="<?php echo $condicion_final_05; ?>">  mV</td>
                                    <td><input class="form-control" type="number" name="condicion_final_10" step="0.01" min="0" placeholder="Por ejemplo: 704" value="<?php echo $condicion_final_10; ?>">  V</td>
                                    <td><input class="form-control" type="number" name="condicion_final_50" step="0.01" min="0" placeholder="Por ejemplo: 401" value="<?php echo $condicion_final_50; ?>">  mV</td>
                                    </tr>
                                    </tbody>
                                    </table>

                                    </div><br>

                                <center><input class="btn btn-sm btn-danger" type="submit" value="Modificar" name="modificar_comportamiento1"></center>
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