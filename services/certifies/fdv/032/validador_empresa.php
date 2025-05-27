<?php
session_start();

if ($_SESSION['nombre'] != '' && $_SESSION['tipo'] == 'devecchi' || $_SESSION['tipo'] == 'admin') {
    include '../../assets/layout.php';
    section();

    function mensaje_error() {
        echo '
            <div class="alert alert-danger alert-dismissible fade in col-sm-3 animated bounceInDown" role="alert" style="position:fixed; top:70px; right:10px; z-index:10;"> 
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
            <h4 class="text-center"><strong>OCURRIÓ UN ERROR</strong></h4>
            <p class="text-center">
            <u>No se logró recibir información</u> de la <strong>Razón Social</strong> o <strong>RFC</strong>, por favor, inténtalo de nuevo o contácta al Soporte Técnico.
            </p>
            </div>
            ';
    }

    function mensaje_ayuda(){
        echo '
            <div class="alert alert-success alert-dismissible fade in col-sm-3 animated bounceInDown" role="alert" style="position:fixed; top:70px; right:10px; z-index:10;"> 
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
            <h4 class="text-center"><strong>EMPRESA ENCONTRADA</strong></h4>
            <p class="text-center">
            Se encontró una empresa que coincide, por favor, valida que sea correcta la información.
            </p>
            </div>
            ';
    }

    if (isset($_POST['buscar_empresa'])) {
        /*********************
        Búsqueda de la empresa
        *********************/
        // Obtención de valores
        $razon_social = $_POST['empresa'];
        $rfc = $_POST['rfc'];

        // Búsqueda de la empresa en la tabla empresas
        require '../../../functions/conex.php';
        $company = 'empresas'; // Tabla empresas
        $build = 'edificio'; // Tabla edificio

        if ($razon_social != '' && $rfc != '') { // Se reciben ambos campos
            $s_company = $con -> prepare("SELECT id, rfc, razon_social FROM $company WHERE razon_social = :razon_social AND rfc = :rfc");
            $s_company->bindValue(':razon_social', $razon_social);
            $s_company->bindValue(':rfc', $rfc);
            $s_company->setFetchMode(PDO::FETCH_OBJ);
            $s_company->execute();

            $f_company = $s_company->fetchAll();

            if ($s_company -> rowCount() > 0) {
                foreach ($f_company as $empresa) {
                    $id_empresa = $empresa -> id;
                    $rfc_val = $empresa -> rfc;
                    $compania_val = $empresa -> razon_social;

                    mensaje_ayuda();
                    }
            } else {
                mensaje_error();
            }

        } elseif ($razon_social != '') { // No se recibe RFC
            $s_company = $con -> prepare("SELECT * FROM $company WHERE razon_social = :razon_social");
            $s_company->bindValue(':razon_social', $razon_social);
            $s_company->setFetchMode(PDO::FETCH_OBJ);
            $s_company->execute();

            $f_company = $s_company->fetchAll();

            if ($s_company -> rowCount() > 0) {
                foreach ($f_company as $item) {
                    $id_empresa = $item -> id;
                    $rfc_val = $item -> rfc;
                    $compania_val = $item -> razon_social;

                    mensaje_ayuda();
                }
            } else {
                mensaje_error();
            }

        } elseif ($rfc != '') { // No se recibe Razón Social
            $s_company = $con -> prepare("SELECT * FROM $company WHERE rfc = :rfc");
            $s_company->bindValue(':rfc', $rfc);
            $s_company->setFetchMode(PDO::FETCH_OBJ);
            $s_company->execute();

            $f_company = $s_company->fetchAll();

            if ($s_company -> rowCount() > 0) {
                foreach ($f_company as $item) {
                    $id_empresa = $item -> id;
                    $rfc_val = $item -> rfc;
                    $compania_val = $item -> razon_social;

                    mensaje_ayuda();
                }
            } else {
                mensaje_error();
            }
        } else {
            mensaje_error();
        }

    } else {
        echo '<script>alert("No se detectó el iniciador de la petición, por favor, inténtalo de nuevo o contacta al Soporte Técnico")</script>';
        echo '<meta http-equiv="refresh" content="0; url=index.php">';
    }

?>

<table>
        <tr>
        <a href="empresa.php"><button type="submit" value="Volver" name="" class="btn btn-primary" style="text-align:center"><i class="fa fa-reply"></i>&nbsp;&nbsp;Volver</button></a>
        </tr>
        </td>
        </table>

        <div class="container" style="width: 1030px;">
            <div class="row" style="width: 770px;">
                <div class="col-sm-12">
                    <div class="page-header2">
                        <h1 class="animated lightSpeedIn">Validación de Empresa</h1>
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
                        <div class="panel-heading text-center"><strong>Valida que la información del contador sea la correcta</strong></div>
                            <div class="panel-body">
                                <form role="form" action="../../../functions/add/company.php" method="POST" enctype="multipart/form-data">
                                    <div>
                                        <div class="col-sm-12">
                                        <label><i class="fa fa-building" aria-hidden="true"></i>&nbsp;Razón Social:</label>
                                        <input class="form-control" type="text" name="razon_social" id="razon_social" value="<?php echo $compania_val; ?>" readonly><br>
                                        </div>

                                        <div class="col-sm-6">
                                        <label><i class="fa fa-id-badge" aria-hidden="true"></i>&nbsp;RFC:</label>
                                        <input class="form-control" type="text" name="rfc" id="rfc" value="<?php echo $rfc_val; ?>" readonly>
                                        </div>

                                        <div class="col-sm-6">
                                        <label><i class="fa fa-map-marker" aria-hidden="true"></i>&nbsp;Sede:</label>
                                        <select class="form-control" name="sede" required>
                                            <option value=""> - Selecciona la sede - </option>
                                            <?php
                                            $s_build = $con -> prepare("SELECT id_edificio, empresa_id, descripcion FROM $build WHERE empresa_id = :id_empresa");
                                            $s_build->bindValue(':id_empresa', $id_empresa);
                                            $s_build->setFetchMode(PDO::FETCH_OBJ);
                                            $s_build->execute();

                                            $f_build = $s_build->fetchAll();

                                            if ($s_build -> rowCount() > 0) {
                                                foreach ($f_build as $edificio) {
                                                    $id_edificio = $edificio -> id_edificio;
                                                    $empresa_id = $edificio -> empresa_id;
                                                    $descripcion_edificio = $edificio -> descripcion;

                                                    echo '<option value="'.$id_edificio.'">'.$descripcion_edificio.'</option>';
                                                }
                                            }
                                            ?>
                                        </select><br>
                                        </div>

                                        <center><input class="btn btn-sm btn-success" type="submit" value="Siguiente" name="continuar"></center>
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