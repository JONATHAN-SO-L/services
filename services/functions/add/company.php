<?php
session_start();

if ($_SESSION['nombre'] != '' && $_SESSION['tipo'] == 'devecchi' || $_SESSION['tipo'] == 'admin') {
    if (isset($_POST['continuar'])) {
        // Recepción de datos
        $razon_social = $_POST['razon_social'];
        $rfc = $_POST['rfc'];
        $sede = $_POST['sede']; // id_edificio

        require '../conex.php';

        $build = 'edificio'; // Tabla de edificios

        // Búsqueda de la dirección de la empresa
        $s_address = $con -> prepare("SELECT * FROM $build WHERE id_edificio = :sede");
        $s_address->bindValue(':sede', $sede);
        $s_address->setFetchMode(PDO::FETCH_OBJ);
        $s_address->execute();

        $f_address = $s_address->fetchAll();

        if ($s_address -> rowCount() > 0) {
            foreach ($f_address as $direccion) {
                $calle = $direccion -> calle;
                $numero_exterior = $direccion -> numero_exterior;
                $numero_interior = $direccion -> numero_interior;
                $colonia = $direccion -> colonia;
                $municipio = $direccion -> municipio;
                $entidad_federativa = $direccion -> entidad_federativa;
                $codigo_postal = $direccion -> codigo_postal;
                $pais = $direccion -> pais;

                $direccion_entrega = $calle.' '.$numero_exterior.' '.$numero_interior.' '.$colonia.' '.$municipio.' '.$entidad_federativa.' '.$codigo_postal.' '.$pais;
        }
        } else {
            echo '<script>alert("No se logró recibir información complementaria de la empresa, por favor, inténtalo de nuevo o contacta al Soporte Técnico.")</script>';
        }

        $certified = 'fdv_s_032'; // Tabla de almacenamiento de certificados

        /***************
        CAPTURA DE DATOS
        ***************/
        require '../conex_serv.php';

        /**********************************************************************************\
        *   Generación del ID del documento                                                *
        *                                                                                  *
        *   El formato que se utiliza para el Id del documento es el que sigue: 25-010301  *
        *   25=año                                                                         *
        *   01=mes                                                                         *
        *   03=número de semana del año                                                    *
        *   01=número consecutivo de calibraciones por año                                 *
        \**********************************************************************************/
       /*$complemento_id1 = date("y\-mW"); // año-mesnúmero de semana del año*/

        // Se valida que no coincida el ID con otro, en caso de que sí, se agrega 1
        $s_id_documento = $con->prepare("SELECT id_documento FROM $certified ORDER BY id_documento DESC LIMIT 1");
        $s_id_documento->setFetchMode(PDO::FETCH_OBJ);
        $s_id_documento->execute();

        $f_id_documento = $s_id_documento->fetchColumn();

        if ($f_id_documento <= 0) {
            $id_documento = $f_id_documento+1;
        } else {
            $id_documento = $f_id_documento+1;
        }
        
        // Se elimina el complemento1 perteneciente a la codificación en curso y se extrae el consecutivo restante en caso de que exista
        /*$f_id_documento2 = str_replace($complemento_id1,'',$f_id_documento);
        $complemento_id2 = $f_id_documento2++;

        echo 'El ID ENCONTRADO en la DDBB es:';
        echo '<br>';
        echo $f_id_documento;
        echo '<br><br>';

        // Si el folio encontrado es el mismo al generado por el código
        if ($f_id_documento -> $complemento_id2) {
            // Se toma el valor generado actual y se coloca en la DDBB
            $complemento_id2 = $f_id_documento2++;
            $id_documento = $complemento_id1;
            echo 'El ID GENERADO es el mismo:';
            echo '<br>';
            echo $id_documento;
        } else { // Si no es igual
            // Se suma uno al valor genereado y se coloca en la DDBB
            $complemento_id2 = $f_id_documento2++;
            $id_documento = $complemento_id1;
            echo 'El ID GENERADO es distinto:';
            echo '<br>';
            echo $id_documento;
        }*/

        // Información para auditlog
        $tecnico = $_SESSION['nombre_completo'];
        include '../../assets/timezone.php';
        $fecha_hora_registro = date("d/m/Y H:i:s");

        // Guardado de información en DDBB
        $save_data = $con->prepare(
            "INSERT INTO $certified (empresa, direccion, id_documento, tecnico, fecha_hora_registro)
            VALUES (?, ?, ?, ?, ?)");
            
        $val_save_data = $save_data->execute([$razon_social, $direccion_entrega, $id_documento, $tecnico, $fecha_hora_registro]);

        if ($val_save_data) {
            echo '<script>alert("Registro exitoso, continúa con el llenado de información")</script>';
            echo '<meta http-equiv="refresh" content="0; url=../../certifies/fdv/032/contador.php?'.$id_documento.'">';
        } else {
            echo '<script>alert("Ocurrió un problema al intentar guardar la información, por favor, inténtalo de nuevo o contacta al Soporte Técnico")</script>';
            echo '<meta http-equiv="refresh" content="0; url=../../certifies/fdv/032/empresa.php">';
        }
    } else {
        echo '<script>alert("No se detectó el iniciador de la petición, por favor, inténtalo de nuevo o contacta al Soporte Técnico")</script>';
        echo '<meta http-equiv="refresh" content="0; url=validador_empresa.php">';
    }

} else {
    die(header('Location: ../../../index.php'));
}

?>