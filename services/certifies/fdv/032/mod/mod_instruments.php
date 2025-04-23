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
                <h1>Instrumentos | Certificado: #</h1>
                <span class="label label-danger"></span> 		 
                <p class="pull-right text-warninng"></p>
            </div>
        </div>
    </div>
</div>

<div class="container">
    <div class="row">
        <div class="col-sm-8">
            <div class="panel panel-warning">
                <div class="panel-heading text-center"><strong>Selecciona correctamente los instrumentos a utilizar</strong></div>
                    <div class="panel-body">
                        <form role="form" action="mod_val_instruments.php" method="POST" enctype="multipart/form-data">
                            <div>
                                <label><i class="fa fa-info-circle" aria-hidden="true"></i>&nbsp;DMM <i>(Modelo)</i>:</label>
                                <select class="form-control" name="contador">
                                <option value=""> - Selecciona el modelo - </option>
                                <option value="Valor por defecto">Valor por defecto</option>
                                </select><br>

                                <label><i class="fa fa-info-circle" aria-hidden="true"></i>&nbsp;PHA <i>(Modelo)</i>:</label>
                                <select class="form-control" name="contador">
                                <option value=""> - Selecciona el modelo - </option>
                                <option value="Valor por defecto">Valor por defecto</option>
                                </select><br>

                                <label><i class="fa fa-info-circle" aria-hidden="true"></i>&nbsp;Medidor de flujo de masa <i>(Modelo)</i>:</label>
                                <select class="form-control" name="contador">
                                <option value=""> - Selecciona el modelo - </option>
                                <option value="Valor por defecto">Valor por defecto</option>
                                </select><br>

                                <label><i class="fa fa-info-circle" aria-hidden="true"></i>&nbsp;RH/TEMP SENSOR <i>(Modelo)</i>:</label>
                                <select class="form-control" name="contador">
                                <option value=""> - Selecciona el modelo - </option>
                                <option value="Valor por defecto">Valor por defecto</option>
                                </select><br>

                                <label><i class="fa fa-info-circle" aria-hidden="true"></i>&nbsp;Balómetro <i>(Modelo)</i>:</label>
                                <select class="form-control" name="contador">
                                <option value=""> - Selecciona el modelo - </option>
                                <option value="Valor por defecto">Valor por defecto</option>
                                </select><br>

                                <center><input class="btn btn-sm btn-danger" type="submit" value="Guardar" name="guardar_contador"></center>
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