<?php
session_start();

if ($_SESSION['nombre'] != '' && $_SESSION['tipo'] == 'devecchi' || $_SESSION['tipo'] == 'admin') {
  include '../../assets/layout.php';
  include '../../../functions/delete/modal_delete.php';
  section();
?>

    <table>
    <a href="empresa.php"><button type="submit" value="Nuevo Certificado" name="" class="btn btn-success" style="text-align:center"><i class="fa fa-plus"></i>&nbsp;&nbsp;Nuevo Certificado</button></a>
    <a href="../../../formats/fdv032/FDV-S-032_SGC.php" target="_blank"><button type="submit" value="Plantilla SGC" class="btn btn-danger" style="text-align:center"><i class="fa fa-file-pdf-o" aria-hidden="true"></i>&nbsp;&nbsp;Plantilla SGC</button></a>
    <td>
    <tr>
    <button onClick="document.location.reload();" type="submit" class="btn btn-warning" data-toggle="tooltip" data-placement="top" title="Haz clic para actualizar los datos" HSPACE="10" VSPACE="10"><i class="fa fa-refresh fa-spin fa-fw"></i>
    <span class="sr-only">Cargando...</span></button>
    </tr>
    </td>
    </table>

    <div class="page-header2">
      <h1 class="animated lightSpeedIn">Certificados realizados FDV-S-032</h1>
    </div><br>

    <div class="container col-sm-4">
      <form action="">
        <label>Buscar</label>
        <input class="form-control" type="search" name="words" placeholder="Puedes buscar por: Técnico, Compañía, Modelo CI ó Contador de Partículas" alt="Puedes buscar por: Técnico, Compañía, Modelo CI ó Contador de Partículas">
        <input class="btn btn-sm btn-success form-control-inline" type="submit" name="buscar" value="Buscar">
      </form><br>
    </div>

    <div class="container" style="margin-left: 10%;">
      <div class="row">
        <div class="col-sm-10">
          
          <!-----------------------------------------------------------------
          TABLA CON TODOS LOS CERTIFICADOS EXPEDIDOS | LÍMITE DE 30 REGISTROS
          ------------------------------------------------------------------>
          <div class="table-responsive">
            <table class="table table-hover table-striped table-bordered">
              <thead>
                <tr>
                <th class="text-center">Acción</th>
                <th class="text-center">Folio / ID del documento</th>
                <th class="text-center">Fecha del documento</th>
                <th class="text-center">Compañía</th>
                <th class="text-center">Modelo CI</th>
                <th class="text-center">Contador de Partículas - No. Serie</th>
                <th class="text-center">Fecha de Calibración</th>
                <th class="text-center">Técnico Certificado</th>
                <th class="text-center">Fecha y Hora de Cierre</th>
                </tr>
              </thead>
              <tbody>
                <td class="text-center">
                  <a href="#" class="btn btn-sm btn-primary" title="Ver PDF"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></a>
                  <a href="./mod/modificar.php" class="btn btn-sm btn-warning" title="Modificar"><i class="fa fa-pencil-square" aria-hidden="true"></i></a>
                  <button href="" class="btn btn-sm btn-danger" title="Eliminar" data-toggle="modal" data-target="#Delete"><i class="fa fa-trash" aria-hidden="true"></i></button>
                </td>
                <td class="text-center">25-041701</td>
                <td class="text-center">17ABR25</td>
                <td class="text-center">Biosense Webster</td>
                <td class="text-center">150t-01</td>
                <td class="text-center">154501</td>
                <td class="text-center">19JUN24</td>
                <td class="text-center">Jonathan Sánchez</td>
                <td class="text-center">2025-04-17 14:01:25</td>

                <tr></tr>
              </tbody>
            </table>
          </div>
          <!----------------------------------------
          NO EXISTEN DATOS EN EL SISTEMA
          ----------------------------------------->
          <br>
          <center><h2>No se encontraron certificados en el sistema</h2></center>

        </div>
      </div>
    </div>

<?php
  end_section();
} else {
  header('Location: ../../../../index.php');
}