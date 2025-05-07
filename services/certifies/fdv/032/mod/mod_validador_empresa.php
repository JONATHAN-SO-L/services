<?php
session_start();

if ($_SESSION['nombre'] != '' && $_SESSION['tipo'] == 'devecchi' || $_SESSION['tipo'] == 'admin') {
    include '../../../assets/layout2.php';
    section();
?>

<table>
        <tr>
        <a href="mod_build.php"><button type="submit" value="Volver" name="" class="btn btn-primary" style="text-align:center"><i class="fa fa-reply"></i>&nbsp;&nbsp;Volver</button></a>
        </tr>
        </td>
        </table>

        <div class="container" style="width: 1030px;">
            <div class="row" style="width: 770px;">
                <div class="col-sm-12">
                    <div class="page-header2">
                        <h1 class="animated lightSpeedIn">Validación de Empresa</h1>
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
                        <div class="panel-heading text-center"><strong>Valida que la ionformación del contador sea la correcta</strong></div>
                            <div class="panel-body">
                                <form role="form" action="modificar.php" method="POST" enctype="multipart/form-data">
                                    <div>
                                        <div class="col-sm-12">
                                        <label><i class="fa fa-building" aria-hidden="true"></i>&nbsp;Razón Social:</label>
                                        <input class="form-control" type="text" name="razon_social" id="razon_social" value="VECO" readonly><br>
                                        </div>

                                        <div class="col-sm-6">
                                        <label><i class="fa fa-id-badge" aria-hidden="true"></i>&nbsp;RFC:</label>
                                        <input class="form-control" type="text" name="rfc" id="rfc" value="VEC831119793" readonly>
                                        </div>

                                        <div class="col-sm-6">
                                        <label><i class="fa fa-map-marker" aria-hidden="true"></i>&nbsp;Sede:</label>
                                        <select class="form-control" name="sede" required>
                                            <option value=""> - Selecciona la sede - </option>
                                            <option value="Oficinas CDMX">Oficinas CDMX</option>
                                            <option value="Planta Morelos">Planta Morelos</option>
                                        </select><br>
                                        </div>

                                        <center><input class="btn btn-sm btn-danger" type="submit" value="Modificar" name="modificar"></center>
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