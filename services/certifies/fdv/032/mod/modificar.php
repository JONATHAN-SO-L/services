<?php
session_start();
include '../../../assets/layout2.php';

if ($_SESSION['nombre'] != '' && $_SESSION['tipo'] == 'devecchi' || $_SESSION['tipo'] == 'admin') {
section(); ?>
    
    <div class="container">
        <table>
        <tr>
        <a href="../index.php"><button type="submit" value="Volver" name="" class="btn btn-primary" style="text-align:center"><i class="fa fa-reply"></i>&nbsp;&nbsp;Volver</button></a><br><br>
        </tr>
        </td>
        </table>

            <div class="row">
                <div class="col-sm-8">
                    <div class="panel panel-danger">
                        <div class="panel-heading text-center"><strong>Selecciona el objeto que quieres modificar</strong></div>
                            <div class="panel-body">
                                <form role="form" action="form.php" method="POST" enctype="multipart/form-data">
                                        <center>
                                        <div class="container">
                                            <div class="col-sm-8">
                                                <a class="btn btn-sm btn-primary" href="mod_build.php"><i class="fa fa-building" aria-hidden="true"></i> Empresa</a>
                                                <a class="btn btn-sm btn-primary" href="mod_accountant.php"><i class="fa fa-flask" aria-hidden="true"></i> Modelo de Contador de Partículas</a>
                                                <a class="btn btn-sm btn-primary" href="mod_form.php"><i class="fa fa-folder-open" aria-hidden="true"></i> Parámetros de calibración</a><br><br>
                                            </div>

                                            <div class="col-sm-8">
                                                <a class="btn btn-sm btn-success" href="mod_electronic_measure.php"><i class="fa fa-bolt" aria-hidden="true"></i> Mediciones Electrónicas</a>
                                                <a class="btn btn-sm btn-success" href="mod_behaviour.php"><i class="fa fa-star-half-o" aria-hidden="true"></i> Comportamiento</a>
                                                <a class="btn btn-sm btn-success" href="mod_instruments.php"><i class="fa fa-bar-chart" aria-hidden="true"></i> Estándar de Trazabilidad</a><br><br>
                                            </div>

                                            <div class="col-sm-8">
                                                <a class="btn btn-sm btn-danger" href="mod_standar_particles.php"><i class="fa fa-check-square" aria-hidden="true"></i> Partículas Estándar</a>
                                            </div>
                                        </div>
                                        </center>
                                </form>
                            </div>
                    </div>
                </div>
            </div>
        </div>

<?php
end_section();
} else {
    header('Location: ../../../../../index.php');
}
?>