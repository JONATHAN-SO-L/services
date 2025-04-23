<?php
session_start();
include '../../../assets/layout2.php';

if ($_SESSION['nombre'] != '' && $_SESSION['tipo'] == 'devecchi' || $_SESSION['tipo'] == 'admin') {
section(); ?>

<table>
    <tr>
        <a href="mod_accountant.php"><button type="submit" value="Volver" class="btn btn-primary" style="text-align:center"><i class="fa fa-reply"></i>&nbsp;&nbsp;Volver</button></a>
    </tr>
    </td>
</table>

<div class="container" style="width: 1030px;">
        <div class="row" style="width: 770px;">
            <div class="col-sm-12">
                <div class="page-header2">
                    <h1 class="animated lightSpeedIn">Contador # | Validación de Contador</h1>
                    <span class="label label-danger"></span> 		 
                    <p class="pull-right text-warning"></p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container">
    <div class="row">
        <div class="col-sm-8">
            <div class="panel panel-warning">
                <div class="panel-heading text-center"><strong>Valida que la información del contador sea la correcta</strong></div>
                    <div class="panel-body">
                        <form role="form" action="modificar.php" method="POST" enctype="multipart/form-data">
                            <div>
                                <div class="col-sm-4">
                                    <label><i class="fa fa-tachometer"></i>&nbsp;Modelo CI:</label>
                                    <input class="form-control" type="text" name="modelo_ci" id="modelo_ci" readonly>
                                </div>

                                <div class="col-sm-4">
                                    <label><i class="fa fa-barcode"></i>&nbsp;Número de Serie:</label>
                                    <input class="form-control" type="text" name="numero_serie" id="numero_serie" readonly>
                                </div>

                                <div class="col-sm-4">
                                    <label><i class="fa fa-crosshairs"></i>&nbsp;Control No.</label>
                                    <input class="form-control" type="text" name="control_no" id="control_no" readonly> <br>
                                </div>

                                <div class="col-sm-6">
                                    <label><i class="fa fa-user-o"></i>&nbsp;Identificación del Cliente:</label>
                                    <input class="form-control" type="text" name="identificacion_cliente" id="identificacion_cliente" readonly>
                                </div>

                                <div class="col-sm-6">
                                    <label><i class="fa fa-user-circle"></i>&nbsp;Técnico:</label>
                                    <input class="form-control" type="text" name="tecnico" id="tecnico" value="<?php echo $_SESSION['nombre_completo']." ".$_SESSION['apellido']; ?>" readonly> <br>
                                </div>

                            <center><input class="btn btn-sm btn-danger" type="submit" value="Guardar" name="continuar"></center>
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