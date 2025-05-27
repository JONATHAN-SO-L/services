<?php
session_start();

if ($_SESSION['nombre'] != '' && $_SESSION['tipo'] == 'devecchi' || $_SESSION['tipo'] == 'admin') {
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
    $complemento_id1 = date("y\-mW"); // año-mesnúmero de semana del año

    // Se valida que no coincida el ID con otro, en caso de que sí, se agrega 1
    $s_id_documento = $con->prepare("SELECT id_documento FROM $certified ORDER BY id_documento DESC LIMIT 1");
    $s_id_documento->setFetchMode(PDO::FETCH_OBJ);
    $s_id_documento->execute();
    
    $f_id_documento = $s_id_documento->fetchColumn();

    $f_id_documento = str_replace($complemento_id1,'',$f_id_documento);
    $complemento_id2 = $f_id_documento++;

    $id_documento = $complemento_id1.$complemento_id2+1;


    // Guardado de información en DDBB
    $save_data = $con->prepare(
        "INSERT INTO $certified (empresa, direccion, id_documento)
         VALUES (?, ?, ?)");
         
    $val_save_data = $save_data->execute([$razon_social, $direccion_entrega, $id_documento]);

    if ($val_save_data) {
        echo '<script>alert("Guardado exitoso")</script>';
        header('Location: ../../certifies/fdv/032/contador.php?'.$id_documento);
    } else {
        echo '<script>alert("Ocurrió un error al intentar guardar, por favor, inténtalo de nuevo o contacta al Soporte Técnico.")</script>';
        header('Location: ../../certifies/fdv/032/empresa.php');
    }

} else {
    header('Location: ../../../index.php');
}

?>