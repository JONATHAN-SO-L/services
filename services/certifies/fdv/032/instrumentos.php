<?php
session_start();

if ($_SESSION['nombre'] != '' && $_SESSION['tipo'] == 'devecchi' || $_SESSION['nombre'] != '' && $_SESSION['tipo'] == 'admin') {
    include '../../assets/layout.php';
    section();
?>

<table>
    <tr>
        <button onClick="document.location.reload();" type="submit" class="btn btn-danger" data-toggle="tooltip" data-placement="top" title="Haz clic para reiniciar el formulario" HSPACE="10" VSPACE="10"><i class="fa fa-refresh fa-spin  fa-fw"></i>
        <span class="sr-only">Cargando...</span>Reiniciar formulario</button>
    </tr>
</table>

<div class="container" style="width: 1030px;">
    <div class="row" style="width: 770px;">
        <div class="col-sm-12">
            <div class="page-header2">
                <h1>Instrumentos | Certificado: #</h1>
                <span class="label label-danger"></span> 		 
                <p class="pull-right text-primary"></p>
            </div>
        </div>
    </div>
</div>

<div class="container">
    <div class="row">
        <div class="col-sm-8">
            <div class="panel panel-success">
                <div class="panel-heading text-center"><strong>Selecciona correctamente los instrumentos a utilizar</strong></div>
                    <div class="panel-body">
                        <form role="form" action="estandard_trazabilidad.php" method="POST" enctype="multipart/form-data">
                            <div>
                                <label><i class="fa fa-info-circle" aria-hidden="true"></i>&nbsp;DMM <i>(Modelo)</i>:</label>
                                <select class="form-control" name="contador" required>
                                <option value=""> - Selecciona el modelo requerido - </option>
                                <option value="Valor por defecto">Valor por defecto</option>
                                </select><br>

                                <label><i class="fa fa-info-circle" aria-hidden="true"></i>&nbsp;PHA <i>(Modelo)</i>:</label>
                                <select class="form-control" name="contador" required>
                                <option value=""> - Selecciona el modelo requerido - </option>
                                <option value="Valor por defecto">Valor por defecto</option>
                                </select><br>

                                <label><i class="fa fa-info-circle" aria-hidden="true"></i>&nbsp;Medidor de flujo de masa <i>(Modelo)</i>:</label>
                                <select class="form-control" name="contador" required>
                                <option value=""> - Selecciona el modelo requerido - </option>
                                <option value="Valor por defecto">Valor por defecto</option>
                                </select><br>

                                <label><i class="fa fa-info-circle" aria-hidden="true"></i>&nbsp;RH/TEMP SENSOR <i>(Modelo)</i>:</label>
                                <select class="form-control" name="contador" required>
                                <option value=""> - Selecciona el modelo requerido - </option>
                                <option value="Valor por defecto">Valor por defecto</option>
                                </select><br>

                                <label><i class="fa fa-info-circle" aria-hidden="true"></i>&nbsp;Balómetro <i>(Modelo)</i>:</label>
                                <select class="form-control" name="contador" required>
                                <option value=""> - Selecciona el modelo requerido - </option>
                                <option value="Valor por defecto">Valor por defecto</option>
                                </select><br>

                                <center><input class="btn btn-sm btn-success" type="submit" value="Siguiente" name="guardar_contador"></center>
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