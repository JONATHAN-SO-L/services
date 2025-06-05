<?php
session_start();

if ($_SESSION['nombre'] != '' && $_SESSION['tipo'] == 'devecchi' || $_SESSION['tipo'] == 'admin') {

    $id_documento = $_SERVER['QUERY_STRING'];

    if (isset($_POST['modificar_empresa'])) {
        include '../../assets/loading.php';
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
                    echo '<script>alert("Ocurrió un error al intentar recuperar información del registro, por favor, inténtalo de nuevo o contacta al Soporte Técnico")</script>';
                    echo '<meta http-equiv="refresh" content="0; url=../../certifies/fdv/032/mod/mod_build.php?'.$id_documento.'">';
                }
            }
        } else {
            echo '<script>alert("Ocurrió un error al intentar recuperar información del registro, por favor, inténtalo de nuevo o contacta al Soporte Técnico")</script>';
            echo '<meta http-equiv="refresh" content="0; url=../../certifies/fdv/032/mod/mod_build.php?'.$id_documento.'">';
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
                echo '<script>alert("Registro exitoso, continúa con el llenado de información")</script>';
                echo '<meta http-equiv="refresh" content="0; url=../../certifies/fdv/032/mod/modificar.php?'.$id_documento.'">';
            } else {
                echo '<script>alert("Ocurrió un error al intentar guardar la información en el auditlog, por favor, inténtalo de nuevo o contacta al Soporte Técnico")</script>';echo '<meta http-equiv="refresh" content="0; url=../../certifies/fdv/032/mod/mod_build.php?'.$id_documento.'">';
            }
        } else {
            echo '<script>alert("Ocurrió un error al intentar guardar la información en el sistena, por favor, inténtalo de nuevo o contacta al Soporte Técnico")</script>';
            echo '<meta http-equiv="refresh" content="0; url=../../certifies/fdv/032/mod/mod_build.php?'.$id_documento.'">';
        }

    } else {
        echo '<script>alert("No se detectó el iniciador de la petición, por favor, inténtalo de nuevo o contacta al Soporte Técnico")</script>';
        echo '<meta http-equiv="refresh" content="0; url=../../certifies/fdv/032/mod/mod_build.php?'.$id_documento.'">';
    }

} else {
    die(header('Location: ../../../index.php'));
}
?>