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
        </td>
        </table>

        <div class="container" style="width: 1030px;">
            <div class="row" style="width: 770px;">
                <div class="col-sm-12">
                    <div class="page-header2">
                        <h1>Certificado: #</h1>
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
                        <div class="panel-heading text-center"><strong>Para poder crear un nuevo certificado es necesario llenar los todos campos</strong></div>
                            <div class="panel-body">
                                <form role="form" action="validador_contador.php" method="POST" enctype="multipart/form-data">
                                    <div>
                                        <label><i class="fa fa-tachometer"></i>&nbsp;Modelo del Contador de Partículas:</label>
                                        <select class="form-control" name="contador" required>
                                            <option value=""> - Selecciona el modelo requerido - </option>
                                            <option value="Valor por defecto">Valor por defecto</option>
                                        </select>
                                        <br>

                                        <center><input class="btn btn-sm btn-success" type="submit" value="Siguiente" name="guardar_contador"></center>
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