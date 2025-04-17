<?php
session_start();

if ($_SESSION['nombre'] != '' && $_SESSION['tipo'] == 'devecchi' || $_SESSION['tipo'] == 'admin') {
  include '../../assets/layout.php';
  section();
?>

    <table>
    <a href="empresa.php"><button type="submit" value="Nuevo Certificado" name="" class="btn btn-success" style="text-align:center"><i class="fa fa-plus"></i>&nbsp;&nbsp;Nuevo Certificado</button></a>
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
          
          <!----------------------------------------
          TABLA CON TODOS LOS CERTIFICADOS EXPEDIDOS
          ----------------------------------------->
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
                <td class="text-center"></td>
                <td class="text-center"></td>
                <td class="text-center"></td>
                <td class="text-center"></td>
                <td class="text-center"></td>
                <td class="text-center"></td>
                <td class="text-center"></td>
                <td class="text-center"></td>
                <td class="text-center"></td>
                </tr>

                <td class="text-center"></td>
                <td class="text-center"></td>
                <td class="text-center"></td>
                <td class="text-center"></td>
                <td class="text-center"></td>
                <td class="text-center"></td>
                <td class="text-center"></td>
                <td class="text-center"></td>
                <td class="text-center"></td>
                </tr>
                </tr>
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