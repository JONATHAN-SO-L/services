<?php
session_start();
include '../../../assets/layout2.php';

if ($_SESSION['nombre'] != '' && $_SESSION['tipo'] == 'devecchi' || $_SESSION['tipo'] == 'admin') {
    section();

    $id_documento = $_SERVER['QUERY_STRING'];

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

    // Busqueda de datos de la empresa
    require '../../../../functions/conex_serv.php';
    $certified = 'fdv_s_032';

    $s_reg = $con->prepare("SELECT empresa FROM $certified WHERE id_documento = :id_documento");
    $s_reg->bindValue(':id_documento', $id_documento);
    $s_reg->setFetchMode(PDO::FETCH_OBJ);
    $s_reg->execute();
    $f_reg = $s_reg->fetchAll();

    if ($s_reg -> rowCount() > 0) {
        foreach ($f_reg as $data_certified) {
            $empresa = $data_certified -> empresa;

            // Busqueda de información de la empresa en la tabla de empresas
            require '../../../../functions/conex.php';
            $builds = 'empresas';

            $s_build = $con->prepare("SELECT * FROM $builds WHERE razon_social = :razon_social");
            $s_build->bindValue(':razon_social', $empresa);
            $s_build->setFetchMode(PDO::FETCH_OBJ);
            $s_build->execute();
            $f_build = $s_build->fetchAll();

            if ($s_build -> rowCount() > 0) {
                foreach ($f_build as $build) {
                    $razon_social = $build -> razon_social;
                    $rfc = $build -> rfc;
                }
            } else {
                mensaje_error();
            }
        }
    } else {
        mensaje_error();
    }
?>

<table>  
    <tr>
        <a href="<?php echo 'modificar.php?'.$id_documento; ?>"><button type="submit" value="Volver" class="btn btn-primary" style="text-align:center"><i class="fa fa-reply"></i>&nbsp;&nbsp;Volver</button></a>
    </tr>
</table>

<div class="container" style="width: 1030px;">
    <div class="row" style="width: 770px;">
        <div class="col-sm-12">
                <div class="page-header2">
                <h1 class="animated lightSpeedIn">Cambio de Empresa | <strong><?php echo $empresa; ?></strong></h1>
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
                <div class="panel-heading text-center"><strong>Aquí puedes modificar la información de la empresa</strong></div>
                    <div class="panel-body">
                        <?php echo '<form role="form" action="mod_validador_empresa.php?'.$id_documento.'" method="POST" enctype="multipart/form-data">'; ?>
                            <div>
                                <label><i class="fa fa-building-o"></i>&nbsp;Empresa:</label>
                                <input class="form-control" type="text" name="empresa" id="empresa" placeholder="Por ejemplo: VECO" value="<?php echo $razon_social; ?>">
                                <br>

                                <label><i class="fa fa-map-marker"></i>&nbsp;RFC: <i>(Opcional)</i></label>
                                <input class="form-control" type="text" name="rfc" id="rfc" placeholder="Por ejemplo: XAXX010101000" value="<?php echo $rfc; ?>">
                                <br>

                                <center><input class="btn btn-sm btn-primary" type="submit" value="Buscar" name="guadar_empresa"></center>
                            </div>
                        </form>
                    </div>    
                </div>
            </div>
        </div>
    </div>
</div>

<?php end_section();
}else{
    header('Location: ../../../../../index.php');
}
?>