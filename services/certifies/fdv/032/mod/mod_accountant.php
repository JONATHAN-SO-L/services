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
                <h1 class="animated lightSpeedIn">Certificado: #</h1>
                <span class="label label-warning"></span> 		 
                <p class="pull-right text-primary"></p>
            </div>
        </div>
    </div>
</div>

<div class="container">
    <div class="row">
        <div class="col-sm-8">
            <div class="panel panel-warning">
                <div class="panel-heading text-center"><strong>Selecciona el nuevo contador a utilizar</strong></div>
                    <div class="panel-body">
                        <form role="form" action="mod_val_accountant.php" method="POST" enctype="multipart/form-data">
                            <div>
                                <label><i class="fa fa-tachometer"></i>&nbsp;Modelo del Contador de Partículas:</label>
                                <select class="form-control" name="contador" required>
                                <option value=""> - Selecciona el modelo requerido - </option>
                                <option value="Valor por defecto">Valor por defecto</option>
                                </select>
                                <br>

                                <center><input class="btn btn-sm btn-danger" type="submit" value="Siguiente" name="guardar_contador"></center>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Vista al guardar el modelo -->

<div class="container">
            <div class="row">
                <div class="col-sm-8">
                    <div class="panel panel-warning">
                        <div class="panel-heading text-center"><strong>Selecciona correctamente el número de serie del contador</strong></div>
                            <div class="panel-body">
                                <form role="form" action="mod_val_accountant.php" method="POST" enctype="multipart/form-data">
                                    <div>
                                        <label><i class="fa fa-barcode" aria-hidden="true"></i>&nbsp;Número de serie del Contador de Partículas:</label>
                                        <select class="form-control" name="contador" required>
                                            <option value=""> - Selecciona el número de serie requerido - </option>
                                            <option value="Valor por defecto">132194</option>
                                        </select>
                                        <br>

                                        <center><input class="btn btn-sm btn-danger" type="submit" value="Siguiente" name="guardar_contador"></center>
                                    </div>
                                </form>
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