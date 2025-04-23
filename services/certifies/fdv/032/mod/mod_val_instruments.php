<?php
session_start();

if ($_SESSION['nombre'] != '' && $_SESSION['tipo'] == 'devecchi' || $_SESSION['tipo'] == 'admin') {
    include '../../../assets/layout2.php';
    section();
?>

<table>
    <tr>
        <a href="mod_instruments.php"><button type="submit" value="Volver" class="btn btn-primary" style="text-align:center"><i class="fa fa-reply"></i>&nbsp;&nbsp;Volver</button></a>
    </tr>
</table>

<div class="container" style="width: 1030px;">
    <div class="row" style="width: 770px;">
        <div class="col-sm-12">
            <div class="page-header2">
                <h1 class="animated lightSpeedIn">Certificado: # | Estándard de Trazabilidad</h1>
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
                <div class="panel-heading text-center"><strong>Asegúrate que estén correctos todos los campos</strong></div>
                    <div class="panel-body">
                        <form role="form" action="modificar.php" method="POST" enctype="multipart/form-data">
                            <div>
                                <div class="container">
                                    <table class="table table-responsive table-hover table-bordered table-striped table-warning" style="margin-left: -15px;">
                                    <thead>
                                    <tr>
                                    <th>INSTRUMENTO</th>
                                    <th>ACTIVO</th>
                                    <th>MODELO</th>
                                    <th>NO. DE SERIE</th>
                                    <th>CONTROL No.</th>
                                    <th>FECHA CAL</th>
                                    <th>PROX. CAL</th>
                                    </tr>
                                    </thead>

                                    <!-- PRIMERA FILA -->
                                    <tbody>
                                    <tr>
                                    <td><strong>DMM</strong></td>
                                    <td><input class="form-control" type="text" name="dmm_activo" value="MAS-07" readonly></td>
                                    <td><input class="form-control" type="text" name="dmm_modelo" value="CM-9942G" readonly></td>
                                    <td><input class="form-control" type="text" name="dmm_no_serie" value="1.592373" readonly></td>
                                    <td><input class="form-control" type="text" name="dmm_control_no" value="MM-68467-2023" readonly></td>
                                    <td><input class="form-control" type="text" name="dmm_fecha_cal" value="2023-06-19" readonly></td>
                                    <td><input class="form-control" type="text" name="dmm_prox_cal" value="2024-06-19" readonly></td>
                                    </tr>
                                    </tbody>

                                    <!-- SEGUNDA FILA -->
                                    <tbody>
                                    <tr>
                                    <td><strong>PHA</strong></td>
                                    <td><input class="form-control" type="text" name="pha_activo" value="PMCAS-01" readonly></td>
                                    <td><input class="form-control" type="text" name="pha_modelo" value="MCA8000D" readonly></td>
                                    <td><input class="form-control" type="text" name="pha_no_serie" value="1069" readonly></td>
                                    <td><input class="form-control" type="text" name="pha_control_no" value="22-0844-P" readonly></td>
                                    <td><input class="form-control" type="text" name="pha_fecha_cal" value="2023-08-04" readonly></td>
                                    <td><input class="form-control" type="text" name="pha_prox_cal" value="2024-08-04" readonly></td>
                                    </tr>
                                    </tbody>

                                    <!-- TERCERA FILA -->
                                    <tbody>
                                    <tr>
                                    <td><strong>Medidor de flujo de masa</strong></td>
                                    <td><input class="form-control" type="text" name="mfm_activo" value="FJS-01" readonly></td>
                                    <td><input class="form-control" type="text" name="mfm_modelo" value="4040H" readonly></td>
                                    <td><input class="form-control" type="text" name="mfm_no_serie" value="40401723011" readonly></td>
                                    <td><input class="form-control" type="text" name="mfm_control_no" value="FV-DVI-4927" readonly></td>
                                    <td><input class="form-control" type="text" name="mfm_fecha_cal" value="2023-07-24" readonly></td>
                                    <td><input class="form-control" type="text" name="mfm_prox_cal" value="2043-07-24" readonly></td>
                                    </tr>
                                    </tbody>

                                    <!-- CUARTA FILA -->
                                    <tbody>
                                    <tr>
                                    <td><strong>RH/TEMP SENSOR</strong></td>
                                    <td><input class="form-control" type="text" name="rh_activo" value="THS-01" readonly></td>
                                    <td><input class="form-control" type="text" name="rh_modelo" value="635-2" readonly></td>
                                    <td><input class="form-control" type="text" name="rh_no_serie" value="10494555/802" readonly></td>
                                    <td><input class="form-control" type="text" name="rh_control_no" value="HR-DVI-7657 - T-DVI-17315" readonly></td>
                                    <td><input class="form-control" type="text" name="rh_fecha_cal" value="2024-02-16 -> 2024-02-15" readonly></td>
                                    <td><input class="form-control" type="text" name="rh_prox_cal" value="2025-02-16 -> 2025-02-15" readonly></td>
                                    </tr>
                                    </tbody>

                                    <!-- QUINTA FILA -->
                                    <tbody>
                                    <tr>
                                    <td><strong>Balómetro</strong></td>
                                    <td><input class="form-control" type="text" name="balometro_activo" value="BAS-01" readonly></td>
                                    <td><input class="form-control" type="text" name="balometro_modelo" value="ADM880 " readonly></td>
                                    <td><input class="form-control" type="text" name="balometro_no_serie" value="M11371" readonly></td>
                                    <td><input class="form-control" type="text" name="balometro_control_no" value="P-DVI-5673" readonly></td>
                                    <td><input class="form-control" type="text" name="balometro_fecha_cal" value="2023-08-08" readonly></td>
                                    <td><input class="form-control" type="text" name="balometro_prox_cal" value="2024-08-08" readonly></td>
                                    </tr>
                                    </tbody>
                                    </table>

                                </div><br>

                                <center><input class="btn btn-sm btn-danger" type="submit" value="Guardar" name="guardar"></center>
                            </div>
                        </form>
                    </div>
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