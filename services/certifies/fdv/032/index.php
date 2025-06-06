<?php
session_start();

if ($_SESSION['nombre'] != '' && $_SESSION['tipo'] == 'devecchi' || $_SESSION['tipo'] == 'admin') {
  include '../../assets/layout.php';
  include '../../../functions/delete/modal_delete.php';
  section();

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

  function mensaje_busqueda() {
    echo '
            <div class="alert alert-success alert-dismissible fade in col-sm-3 animated bounceInDown" role="alert" style="position:fixed; top:70px; right:10px; z-index:10;"> 
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
            <h4 class="text-center"><strong>Búsqueda exitosa</strong></h4>
            <p class="text-center">
            Por favor visualiza la información encontrada.
            </p>
            </div>
            ';
  }
?>

    <table>
      <a href="../../../index.php"><button type="submit" value="Volver" class="btn btn-primary" style="text-align:center"><i class="fa fa-reply"></i>&nbsp;&nbsp;Volver</button></a>
    <a href="empresa.php"><button type="submit" value="Nuevo Certificado" name="" class="btn btn-success" style="text-align:center"><i class="fa fa-plus"></i>&nbsp;&nbsp;Nuevo Certificado</button></a>

    <div class="btn-group">
      <button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown">
      <i class="fa fa-file-pdf-o" aria-hidden="true"></i>&nbsp;&nbsp;Plantillas SGC <span class="caret"></span></button>
      <ul class="dropdown-menu" role="menu">
        <li><a href="../../../formats/fdv032/FDV-S-032_SGC.php" target="_blank">FDV-S-032 (Menor a 100 LPM)</a></li>
        <li><a href="../../../formats/fdv032/FDV-S-032_SGC-100.php" target="_blank">FDV-S-032 (100 LPM)</a></li>
      </ul>
    </div>

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
      <form action="" method="POST" enctype="multipart/form-data">
        <label>Buscar</label>
        <input class="form-control" type="search" name="words" placeholder="Puedes buscar por: Compañía, Modelo CI ó Número de Seie de Contador" alt="Puedes buscar por: Técnico, Compañía, Modelo CI ó Contador de Partículas">
        <input class="btn btn-sm btn-success form-control-inline" type="submit" name="buscar" value="Buscar">
      </form><br>
    </div>

    <div class="container" style="margin-left: 10%;">
      <div class="row">
        <div class="col-sm-10">
          
          <!-----------------------------------------------------------------
          TABLA CON TODOS LOS CERTIFICADOS EXPEDIDOS | LÍMITE DE 30 REGISTROS
          ------------------------------------------------------------------>
          <?php
          // Conexión a la DDBB
          require '../../../functions/conex_serv.php';
          $certified = 'fdv_s_032';
          $tecnico = $_SESSION['nombre_completo'];

          if (isset($_POST['buscar'])) {
            // Se realiza la búsqueda de información
            $word = $_POST['words'];

            $s_register = $con->prepare("SELECT * FROM $certified 
                                        WHERE tecnico = :tecnico 
                                        AND empresa LIKE '%$word%' OR modelo_contador LIKE '%$word%' OR numero_serie LIKE '%$word%'
                                        ORDER BY id_documento DESC LIMIT 30");
            $s_register->bindValue(':tecnico', $tecnico);
            $s_register->setFetchMode(PDO::FETCH_OBJ);
            $s_register->execute();
            $f_register = $s_register->fetchAll();

            // Se realiza conteo de resultados
            $total_registers = $con->prepare("SELECT COUNT(*) FROM $certified
                                              WHERE tecnico = '$tecnico'
                                              AND empresa LIKE '%$word%' OR modelo_contador LIKE '%$word%' OR numero_serie LIKE '%$word%'");
            $total_registers->execute();
            $num_total_results = $total_registers->fetchColumn();

            if ($s_register -> rowCount() > 0) {
              echo
              '<div class="table-responsive">

              <a href="index.php"><button type="submit" value="Volver" class="btn btn-primary" style="text-align:center"><i class="fa fa-reply"></i>&nbsp;&nbsp;Volver</button></a>
              <br><br>
              <p><strong>Búsqueda realizada: </strong><span class="badge bg-success">'.$word.'</span></p>
              <p><strong>Registros encontrados: </strong><span class="badge bg-success">'.$num_total_results.'</span></p>

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
                  </thead>';

                  foreach ($f_register as $registro) {
                    $id_documento = $registro -> id_documento;
                    $fecha_documento = $registro -> fecha_documento;
                    $empresa = $registro -> empresa;
                    $modelo_contador = $registro -> modelo_contador;
                    $numero_serie = $registro -> numero_serie;
                    $fecha_calibracion = $registro -> fecha_calibracion;
                    $fa_esperado = $registro -> fa_esperado;
                    $tecnico_certificado = $registro -> tecnico;
                    $fecha_hora_cierre = $registro -> fecha_hora_cierre;

                    echo '
                      <tbody>
                        <td class="text-center">';

                        switch ($fa_esperado) {
                          case $fa_esperado < 100:
                            echo '<a href="../../../formats/fdv032/fdv-s-032.php?'.$id_documento.'"" target="_blank" class="btn btn-sm btn-primary" title="Ver PDF"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></a>';
                          break;

                          case $fa_esperado >= 100:
                            echo '<a href="../../../formats/fdv032/fdv-s-032-100.php?'.$id_documento.'"" target="_blank" class="btn btn-sm btn-primary" title="Ver PDF"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></a>';
                          break;
                        }
                          
                    echo'<a href="./mod/modificar.php?'.$id_documento.'" class="btn btn-sm btn-warning" title="Modificar"><i class="fa fa-pencil-square" aria-hidden="true"></i></a>
                          <button href="" class="btn btn-sm btn-danger" title="Eliminar" data-toggle="modal" data-target="#Delete"><i class="fa fa-trash" aria-hidden="true"></i></button>
                        </td>
                        <td class="text-center">'.$id_documento.'</td>
                        <td class="text-center">'.$fecha_documento.'</td>
                        <td class="text-center">'.$empresa.'</td>
                        <td class="text-center">'.$modelo_contador.'</td>
                        <td class="text-center">'.$numero_serie.'</td>
                        <td class="text-center">'.$fecha_calibracion.'</td>
                        <td class="text-center">'.$tecnico_certificado.'</td>
                        <td class="text-center">'.$fecha_hora_cierre.'</td>

                        <tr></tr>
                      </tbody>
                    ';
                    mensaje_busqueda();
                  }

                echo '</table>
              </div>';
            } else {
              mensaje_error();
              echo '<a href="index.php"><button type="submit" value="Volver" class="btn btn-primary" style="text-align:center"><i class="fa fa-reply"></i>&nbsp;&nbsp;Volver</button></a>';
              echo '<center><h2>No se encontraron certificados en el sistema</h2></center><br>';
            }
          } else {
            // Se visualizan todos los registros
            $s_register = $con->prepare("SELECT * FROM $certified WHERE tecnico = :tecnico ORDER BY id_documento DESC LIMIT 30");
            $s_register->bindValue(':tecnico', $tecnico);
            $s_register->setFetchMode(PDO::FETCH_OBJ);
            $s_register->execute();
            $f_register = $s_register->fetchAll();

            // Se realiza conteo de resultados
            $total_registers = $con->prepare("SELECT COUNT(*) FROM $certified WHERE tecnico = '$tecnico'");
            $total_registers->execute();
            $num_total_results = $total_registers->fetchColumn();

            if ($s_register -> rowCount() > 0) {
              echo
              '<div class="table-responsive">
                <table class="table table-hover table-striped table-bordered">
                <p><strong>Registros Totales: </strong><span class="badge bg-success">'.$num_total_results.'</span></p>
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
                  </thead>';

                  foreach ($f_register as $registro) {
                    $id_documento = $registro -> id_documento;
                    $fecha_documento = $registro -> fecha_documento;
                    $empresa = $registro -> empresa;
                    $modelo_contador = $registro -> modelo_contador;
                    $numero_serie = $registro -> numero_serie;
                    $fecha_calibracion = $registro -> fecha_calibracion;
                    $fa_esperado = $registro -> fa_esperado;
                    $tecnico_certificado = $registro -> tecnico;
                    $fecha_hora_cierre = $registro -> fecha_hora_cierre;

                    echo '
                      <tbody>
                        <td class="text-center">';

                        switch ($fa_esperado) {
                          case $fa_esperado < 100:
                            echo '<a href="../../../formats/fdv032/fdv-s-032.php?'.$id_documento.'"" target="_blank" class="btn btn-sm btn-primary" title="Ver PDF"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></a>';
                          break;

                          case $fa_esperado >= 100:
                            echo '<a href="../../../formats/fdv032/fdv-s-032-100.php?'.$id_documento.'"" target="_blank" class="btn btn-sm btn-primary" title="Ver PDF"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></a>';
                          break;

                          case $fa_esperado == NULL:
                           echo '<a href="#"" target="_blank" class="btn btn-sm btn-primary disabled" title="Ver PDF"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></a>';
                          break;
                        }

                    echo'      <a href="./mod/modificar.php?'.$id_documento.'" class="btn btn-sm btn-warning" title="Modificar"><i class="fa fa-pencil-square" aria-hidden="true"></i></a>
                          <button href="" class="btn btn-sm btn-danger" title="Eliminar" data-toggle="modal" data-target="#Delete"><i class="fa fa-trash" aria-hidden="true"></i></button>
                        </td>
                        <td class="text-center">'.$id_documento.'</td>
                        <td class="text-center">'.$fecha_documento.'</td>
                        <td class="text-center">'.$empresa.'</td>
                        <td class="text-center">'.$modelo_contador.'</td>
                        <td class="text-center">'.$numero_serie.'</td>
                        <td class="text-center">'.$fecha_calibracion.'</td>
                        <td class="text-center">'.$tecnico_certificado.'</td>
                        <td class="text-center">'.$fecha_hora_cierre.'</td>

                        <tr></tr>
                      </tbody>
                    ';
                  }

                echo '</table>
              </div>';
            } else {
              echo '<center><h2>No se encontraron certificados en el sistema</h2></center>';
            }
          }
          ?>

        </div>
      </div>

      <?php include '../../../assets/footer.php'; ?>

    </div>

<?php
  end_section();
} else {
  header('Location: ../../../../index.php');
}