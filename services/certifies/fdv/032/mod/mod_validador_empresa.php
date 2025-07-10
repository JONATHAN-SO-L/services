<?php
session_start();

if ($_SESSION['nombre'] != '' && $_SESSION['tipo'] == 'devecchi' || $_SESSION['tipo'] == 'admin') {
    include '../../../assets/layout2.php';
    section();

    $id_documento = $_SERVER['QUERY_STRING'];

    function mensaje_ayuda() {
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

    function mensaje_error() {
        echo '
            <div class="alert alert-danger alert-dismissible fade in col-sm-3 animated bounceInDown" role="alert" style="position:fixed; top:70px; right:10px; z-index:10;"> 
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
            <h4 class="text-center"><strong>OCURRIÓ UN ERROR</strong></h4>
            <p class="text-center">
            <u>No se logró recibir información del registro, por favor, inténtalo de nuevo o contácta al Soporte Técnico.
            </p>
            </div>
            ';
    }

    if ($_POST['guadar_empresa']) {
        // Recepción de datos
        $empresa = $_POST['empresa'];
        $rfc = $_POST['rfc'];

        // Recuperar información de la empresa
        require '../../../../functions/conex.php';
        $company = 'empresas';
        $build = 'edificio';

        if ($empresa != '' && $rfc != '') {
            $s_company = $con->prepare("SELECT id, razon_social, rfc FROM $company WHERE razon_social LIKE '%$empresa%' AND rfc LIKE '%$rfc%'");
            $s_company->setFetchMode(PDO::FETCH_OBJ);
            $s_company->execute();
            $f_company = $s_company->fetchAll();

            if ($s_company -> rowCount() > 0) {
                foreach ($f_company as $enterprise) {
                    $empresa_id = $enterprise -> id;
                    $razon_social = $enterprise -> razon_social;
                    $rfc = $enterprise -> rfc;
                }
                mensaje_ayuda();
            } else {
                mensaje_error();
            }
        } elseif ($empresa != '') {
            $s_company = $con->prepare("SELECT id, razon_social, rfc FROM $company WHERE razon_social LIKE '%$empresa%'");
            $s_company->setFetchMode(PDO::FETCH_OBJ);
            $s_company->execute();
            $f_company = $s_company->fetchAll();

            if ($s_company -> rowCount() > 0) {
                foreach ($f_company as $enterprise) {
                    $empresa_id = $enterprise -> id;
                    $razon_social = $enterprise -> razon_social;
                    $rfc = $enterprise -> rfc;
                }
                mensaje_ayuda();
            } else {
                mensaje_error();
            }
        } elseif ($rfc != '') {
            $s_company = $con->prepare("SELECT id, razon_social, rfc FROM $company WHERE rfc LIKE '%$rfc%'");
            $s_company->setFetchMode(PDO::FETCH_OBJ);
            $s_company->execute();
            $f_company = $s_company->fetchAll();

            if ($s_company -> rowCount() > 0) {
                foreach ($f_company as $enterprise) {
                    $empresa_id = $enterprise -> id;
                    $razon_social = $enterprise -> razon_social;
                    $rfc = $enterprise -> rfc;
                }
                mensaje_ayuda();
            } else {
                mensaje_error();
            }
        } else {
            mensaje_error();
        }

    } else {
        echo '<script>alert("No se detectó el iniciador de la petición, por favor, inténtalo de nuevo o contacta al Soporte Técnico")</script>';
        echo '<meta http-equiv="refresh" content="0; url=mod_build.php?'.$id_documento.'">';
    }
?>

<table>
        <tr>
        <a href="<?php echo 'mod_build.php?'.$id_documento; ?>"><button type="submit" value="Volver" name="" class="btn btn-primary" style="text-align:center"><i class="fa fa-reply"></i>&nbsp;&nbsp;Volver</button></a>
        </tr>
        </td>
        </table>

        <div class="container" style="width: 1030px;">
            <div class="row" style="width: 770px;">
                <div class="col-sm-12">
                    <div class="page-header2">
                        <h1 class="animated lightSpeedIn">Validación de Empresa</h1>
                        <span class="label label-danger"></span> 		 
                        <p class="pull-right text-warning"></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="container">
            <div class="row">
                <div class="col-sm-8">
                    <div class="panel panel-warning">
                        <div class="panel-heading text-center"><strong>Valida que la ionformación del contador sea la correcta</strong></div>
                            <div class="panel-body">
                                <?php echo '<form role="form" action="../../../../functions/mod/company.php?'.$id_documento.'" method="POST" enctype="multipart/form-data">'; ?>
                                    <div>
                                        <div class="col-sm-12">
                                        <label><i class="fa fa-building" aria-hidden="true"></i>&nbsp;Razón Social:</label>
                                        <input class="form-control" type="text" name="razon_social" id="razon_social" value="<?php echo $empresa_id; ?>" readonly><br>
                                        </div>

                                        <div class="col-sm-6">
                                        <label><i class="fa fa-id-badge" aria-hidden="true"></i>&nbsp;RFC:</label>
                                        <input class="form-control" type="text" name="rfc" id="rfc" value="<?php echo $rfc; ?>" readonly>
                                        </div>

                                        <div class="col-sm-6">
                                        <label><i class="fa fa-map-marker" aria-hidden="true"></i>&nbsp;Sede:</label>
                                        <select class="form-control" name="sede" id="sede" required>
                                            <option value=""> - Selecciona la sede - </option>
                                            <?php
                                            $s_build = $con->prepare("SELECT id_edificio, empresa_id, descripcion FROM $build WHERE empresa_id = :empresa_id");
                                            $s_build->bindValue(':empresa_id', $empresa_id);
                                            $s_build->setFetchMode(PDO::FETCH_OBJ);
                                            $s_build->execute();
                                            
                                            $f_build = $s_build->fetchAll();

                                            if ($s_build -> rowCount() > 0) {
                                                foreach ($f_build as $builder) {
                                                    $id_edificio = $builder -> id_edificio;
                                                    $empresa_id = $builder -> empresa_id;
                                                    $descripcion = $builder -> descripcion;

                                                    echo '<option value="'.$id_edificio.'">'.$descripcion.'</option>';
                                                }
                                            } else {
                                                mensaje_error();
                                            }

                                            ?>
                                        </select><br>
                                        </div>

                                        <center><input class="btn btn-sm btn-danger" type="submit" value="Modificar" name="modificar_empresa"></center>
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