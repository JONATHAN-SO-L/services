<?php
session_start();

if ($_SESSION['nombre'] != '' && $_SESSION['tipo'] == 'devecchi' || $_SESSION['tipo'] == 'admin') {

    $id_documento = $_SERVER['QUERY_STRING'];

    include '../../assets/admin/links.php';

    function mensaje_ayuda(){
    echo '
    <div class="alert alert-success alert-dismissible fade in col-sm-3 animated bounceInDown" role="alert" style="position:fixed; top:70px; right:10px; z-index:10;"> 
    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
    <h4 class="text-center"><strong>MODIFICACIÓN EXITOSA</strong></h4>
    <p class="text-center">
    Se modificó correctamente la empresa en el sistema.
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
            <u>No se logró recibir información correctamente, por favor, inténtalo de nuevo o contácta al Soporte Técnico.
            </p>
            </div>
            ';
    }

    function redirect_failed($id_documento) {
        echo '
            <div class="container" style="margin-left: 40%">
                <img src="../../assets/img/loading_dvi.gif" height="40%" weight="40%">
                <br>
                <a href="../../certifies/fdv/032/mod/mod_build.php?'.$id_documento.'" class="btn btn-sm btn-danger" style="margin-left: 15%">Regresar</a>
            </div>';
    }

    function redirect_success($id_documento) {
        echo '
            <div class="container" style="margin-left: 40%">
                <img src="../../assets/img/loading_dvi.gif" height="40%" weight="40%">
                <br>
                <a href="../../certifies/fdv/032/mod/modificar.php?'.$id_documento.'" class="btn btn-sm btn-success" style="margin-left: 15%">Continuar</a>
            </div>';
    }

    if (isset($_POST['modificar_empresa'])) {
        require '../conex.php';
        $company = 'empresas';
        $build = 'edificio';

        // Recepción de datos
        $razon_social = $_POST['razon_social']; // empresa_id
        $rfc = $_POST['rfc'];
        $sede = $_POST['sede'];

        // Se busca edificio relacionado con la empresa
        $s_build = $con->prepare("SELECT * FROM $build WHERE empresa_id = :razon_social");
        $s_build->bindValue(':razon_social', $razon_social);
        $s_build->setFetchMode(PDO::FETCH_OBJ);
        $s_build->execute();

        $f_build = $s_build->fetchAll();

        if ($s_build -> rowCount() > 0) {
            foreach ($f_build as $edificio) {
                $empresa_id = $edificio -> empresa_id;
                $descripcion = $edificio -> descripcion;
                $calle = $edificio -> calle;
                $numero_exterior = $edificio -> numero_exterior;
                $numero_interior = $edificio -> numero_interior;
                $colonia = $edificio -> colonia;
                $municipio = $edificio -> municipio;
                $entidad_federativa = $edificio -> entidad_federativa;
                $codigo_postal = $edificio -> codigo_postal;
                $pais = $edificio -> pais;

                // Dirección de entrega
                $direccion_completa = $calle.' '.$numero_exterior.' '.$numero_interior.' '.$colonia.' '.$municipio.' '.$entidad_federativa.' '.$codigo_postal.' '.$pais;

                // Buscar razon social
                $s_company = $con->prepare("SELECT id, razon_social, rfc FROM $company WHERE id = :empresa_id");
                $s_company->bindValue(':empresa_id', $empresa_id);
                $s_company->setFetchMode(PDO::FETCH_OBJ);
                $s_company->execute();

                $f_company = $s_company->fetchAll();

                if ($s_company -> rowCount() > 0) {
                    foreach ($f_company as $empresa) {
                        $id = $empresa -> id;
                        $nombre_empresa = $empresa -> razon_social;
                        $rfc_empresa = $empresa -> rfc;
                    }
                } else {
                    mensaje_error();
                    redirect_failed($id_documento);
                    die();
                }
            }
        } else {
            mensaje_error();
            redirect_failed($id_documento);
            die();
        }

        $tecnico_mod = $_SESSION['nombre_completo'];
        include '../../assets/timezone.php';
        $fecha_hora_modificacion = date("d/m/Y H:i:s");

        // Actualización en DDBB del registro
        require '../conex_serv.php';
        $certified = 'fdv_s_032';

        $save_company = $con->prepare("UPDATE $certified
                                                SET empresa = ?,
                                                    direccion = ?,
                                                    modifica_data = ?,
                                                    fecha_hora_modificacion = ?
                                        WHERE id_documento = ?");

        $val_save_company = $save_company->execute([$nombre_empresa, $direccion_completa, $tecnico_mod, $fecha_hora_modificacion, $id_documento]);

        if ($val_save_company) {
            // Información para auditlog
            include '../../assets/timezone.php';
            $fecha_hora_carga = date("d/m/Y H:i:s");
            $tecnico = $_SESSION['nombre_completo'];

            // Registro en log
            $log = 'auditlog';
            $movimiento = utf8_decode('El usuario '.$tecnico_mod.' modificó el registro '.$id_documento.' actualizando la empresa a '.$nombre_empresa.' el '.$fecha_hora_modificacion.'');
            $url = $_SERVER['PHP_SELF'].'?'.$id_documento;
            $database = 'SIS';
            $save_move = $con->prepare("INSERT INTO $log (movimiento, link, ddbb, usuario_movimiento, fecha_hora)
                                                  VALUES (?, ?, ?, ?, ?)");
            $val_save_move = $save_move->execute([$movimiento, $url, $database, $tecnico, $fecha_hora_carga]);

            if ($val_save_move) {
                require '../drop_con.php';
                mensaje_ayuda();
                redirect_success($id_documento);
            } else {
                mensaje_error();
                redirect_failed($id_documento);
                die();
            }
        } else {
            mensaje_error();
            redirect_failed($id_documento);
            die();
        }

    } else {
        mensaje_error();
        redirect_failed($id_documento);
        die();
    }

} else {
    die(header('Location: ../../../index.php'));
}
?>