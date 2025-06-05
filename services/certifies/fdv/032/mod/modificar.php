<?php
session_start();
include '../../../assets/layout2.php';

if ($_SESSION['nombre'] != '' && $_SESSION['tipo'] == 'devecchi' || $_SESSION['tipo'] == 'admin') {
    section();
    
    $id_documentos = $_SERVER['QUERY_STRING'];

    function mensaje_error() {
        echo '
            <div class="alert alert-danger alert-dismissible fade in col-sm-3 animated bounceInDown" role="alert" style="position:fixed; top:70px; right:10px; z-index:10;"> 
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
            <h4 class="text-center"><strong>OCURRIÓ UN ERROR</strong></h4>
            <p class="text-center">
            No se logró recibir información de parte del sistema, por favor, inténtalo de nuevo o contácta al Soporte Técnico.
            </p>
            </div>
            ';
    }

    // Busqueda del registro en la tabla de certificados
    require '../../../../functions/conex_serv.php';
    $certified = 'fdv_s_032';

    $s_reg = $con->prepare("SELECT empresa, id_documento, fa_esperado FROM $certified WHERE id_documento = :id_documento");
    $s_reg->bindValue(':id_documento', $id_documentos);
    $s_reg->setFetchMode(PDO::FETCH_OBJ);
    $s_reg->execute();
    $f_reg = $s_reg->fetchAll();

    if ($s_reg -> rowCount() > 0) {
        foreach ($f_reg as $data_certified) {
            $empresa = $data_certified -> empresa;
            $id_documento = $data_certified -> id_documento;
            $fa_esperado = $data_certified -> fa_esperado;
        }
    } else {
        mensaje_error();
    }
?>
    
    <div class="container">
        <table>
        <tr>
        <a href="../index.php"><button type="submit" value="Volver" name="" class="btn btn-primary" style="text-align:center"><i class="fa fa-reply"></i>&nbsp;&nbsp;Volver</button></a><br><br>
        </tr>
        </table>

        <div class="container" style="width: 1030px;">
            <div class="row" style="width: 770px;">
                <div class="col-sm-12">
                    <div class="page-header2">
                        <h3 class="animated lightSpeedIn">
                            <p><strong>Documento o Certificado:</strong> <i><?php echo $id_documento; ?></i></p>
                            <p><strong>Compañía:</strong> <i><?php echo $empresa; ?></i></p>
                        </h3>
                        <span class="label label-danger"></span> 		 
                        <p class="pull-right text-primary"></p>
                    </div>
                </div>
            </div>
        </div>

            <div class="row">
                <div class="col-sm-8">
                    <div class="panel panel-danger">
                        <div class="panel-heading text-center"><strong>Selecciona el objeto que quieres modificar</strong></div>
                            <div class="panel-body">
                                <form role="form" action="form.php" method="POST" enctype="multipart/form-data">
                                        <center>
                                        <div class="container">
                                            <div class="col-sm-8">
                                                <?php
                                                echo '
                                                <a class="btn btn-sm btn-primary" href="mod_build.php?'.$id_documento.'"><i class="fa fa-building" aria-hidden="true"></i> Empresa</a>
                                                <a class="btn btn-sm btn-primary" href="mod_accountant.php?'.$id_documento.'"><i class="fa fa-flask" aria-hidden="true"></i> Modelo de Contador de Partículas</a>
                                                <a class="btn btn-sm btn-primary" href="mod_form.php?'.$id_documento.'"><i class="fa fa-folder-open" aria-hidden="true"></i> Parámetros de calibración</a><br><br>
                                            </div>

                                            <div class="col-sm-8">
                                                <a class="btn btn-sm btn-success" href="mod_electronic_measure.php?'.$id_documento.'"><i class="fa fa-bolt" aria-hidden="true"></i> Mediciones Electrónicas</a>';

                                                switch ($fa_esperado) {
                                                    case $fa_esperado < 100:
                                                        echo '<a class="btn btn-sm btn-success" href="mod_behaviour.php?'.$id_documento.'"><i class="fa fa-star-half-o" aria-hidden="true"></i> Comportamiento</a>';
                                                    break;
                                                    case $fa_esperado >= 100:
                                                        echo '<a class="btn btn-sm btn-success" href="mod_behaviour2.php?'.$id_documento.'"><i class="fa fa-star-half-o" aria-hidden="true"></i> Comportamiento</a>';
                                                    break;
                                                }
                                                
                                                echo '<a class="btn btn-sm btn-success" href="mod_instruments.php?'.$id_documento.'"><i class="fa fa-bar-chart" aria-hidden="true"></i> Estándar de Trazabilidad</a><br><br>
                                            </div>
                                                ';
                                                ?>

                                            <!--div class="col-sm-8">
                                                <a class="btn btn-sm btn-danger" href="mod_standar_particles.php"><i class="fa fa-check-square" aria-hidden="true"></i> Partículas Estándar</a>
                                            </div-->
                                        </div>
                                        </center>
                                </form>
                            </div>
                    </div>
                </div>
            </div>

            <div class="btn-group">
                <button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown"><i class="fa fa-file-archive-o" aria-hidden="true"></i> Certificados<span class="caret"></span></button>
                <ul class="dropdown-menu" role="menu">
                    <li><a href="#"><i class="fa fa-certificate" aria-hidden="true"></i> DMM</a></li>
                    <li><a href="#"><i class="fa fa-certificate" aria-hidden="true"></i> PHA</a></li>
                    <li><a href="#"><i class="fa fa-certificate" aria-hidden="true"></i> Medidor de flujo de masa</a></li>
                    <li><a href="#"><i class="fa fa-certificate" aria-hidden="true"></i> RH/TEMP SENSOR</a></li>
                    <li><a href="#"><i class="fa fa-certificate" aria-hidden="true"></i> Balómetro</a></li>
                </ul>
            </div>

        </div>

<?php
end_section();
} else {
    header('Location: ../../../../../index.php');
}
?>