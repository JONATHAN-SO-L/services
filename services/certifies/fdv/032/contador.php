<?php
header('Content-Type: text/html; charset=UTF-8');
    session_start();
if ($_SESSION['nombre'] != '' && $_SESSION['tipo'] == 'devecchi' || $_SESSION['nombre'] != '' && $_SESSION['tipo'] == 'admin') { ?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
  <link rel="stylesheet" href="../../../assets/css/main.css">
  <?php include "../../assets/links2.php"; ?> 
  <?php include '../../assets/navbar.php'; ?>
</head>

<body>

<section id="content">	 
    <header id="content-header">
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

    </header>
</section>

</body>
</html>

<?php } else {
    header('Location: ../../../../index.php');
}
?>