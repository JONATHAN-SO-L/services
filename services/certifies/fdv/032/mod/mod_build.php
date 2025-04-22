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
                <h1 class="animated lightSpeedIn">Modificación de la Empresa | #</h1>
                <span class="label label-danger"></span> 		 
                <p class="pull-right text-warning"></p>
            </div>
        </div>
    </div>
</div>

<div class="container">
    <div class="row">
        <div class="col-sm-8">
            <div class="panel panel-warning">
                <div class="panel-heading text-center"><strong>Aquí puedes modificar la información de la empresa</strong></div>
                    <div class="panel-body">
                        <form role="form" action="#" method="POST" enctype="multipart/form-data">
                            <div>
                                <label><i class="fa fa-building-o"></i>&nbsp;Empresa:</label>
                                <input class="form-control" type="text" name="empresa" id="empresa" placeholder="Por ejemplo: VECO">
                                <br>

                                <label><i class="fa fa-map-marker"></i>&nbsp;Dirección:</label>
                                <input class="form-control" type="text" name="direccion_empresa" id="direccion_empresa" placeholder="Por ejemplo: 13 Este 116">
                                <br>

                                <center><input class="btn btn-sm btn-danger" type="submit" value="Modificar" name="guadar_empresa"></center>
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