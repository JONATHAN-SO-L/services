<?php
session_start();

if ($_SESSION['nombre'] != '' && $_SESSION['tipo'] == 'devecchi' || $_SESSION['tipo'] == 'admin') {
    include '../../assets/layout.php';
    section();
?>

        <table>  
        <tr>
        <a href="index.php"><button type="submit" value="Volver" class="btn btn-primary" style="text-align:center"><i class="fa fa-reply"></i>&nbsp;&nbsp;Volver</button></a>
        </tr>
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
                        <h1 class="animated lightSpeedIn">Empresa | Certificado: #</h1>
                        <span class="label label-danger"></span> 		 
                        <p class="pull-right text-primary"></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="container">
            <div class="row">
                <div class="col-sm-8">
                    <div class="panel panel-primary">
                        <div class="panel-heading text-center"><strong>Para poder crear un nuevo certificado es necesario colocar la RAZON SOCIAL de la compañía a buscar</strong></div>
                            <div class="panel-body">
                                <form role="form" action="validador_empresa.php" method="POST" enctype="multipart/form-data">
                                    <div>
                                        <label><i class="fa fa-building" aria-hidden="true"></i>&nbsp;Empresa:</label>
                                        <input class="form-control" type="text" name="empresa" id="empresa" placeholder="Por ejemplo: VECO">
                                        <br>

                                        <label><i class="fa fa-map-marker"></i>&nbsp;RFC: <i>(Opcional)</i></label>
                                        <input class="form-control" type="text" name="rfc" id="rfc" placeholder="Por ejemplo: XAXX010101000">
                                        <br>

                                        <center><input class="btn btn-sm btn-primary" type="submit" value="Buscar" name="buscar_empresa"></center>
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