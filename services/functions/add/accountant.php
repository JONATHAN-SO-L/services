<?php
session_start();

if ($_SESSION['nombre'] != '' && $_SESSION['tipo'] == 'devecchi' || $_SESSION['tipo'] == 'admin') {
    
    $id_documento = $_SERVER['QUERY_STRING'];

    if (isset($_POST['guardar_contador'])) {
        require '../conex_serv.php';
        $certified = 'fdv_s_032';

        // Recepción de datos
        $modelo_ci = $_POST['modelo_ci'];
        $numero_serie = $_POST['numero_serie'];
        $control_no = $_POST['control_no'];
        $identificacion_cliente = $_POST['identificacion_cliente'];

        $save_accontant = $con->prepare("UPDATE $certified
                                                SET modelo_ci = ?,
                                                    numero_serie = ?,
                                                    identificacion_cliente = ?
                                                WHERE id_documento = ?");
        
        $val_save_accountant = $save_accontant->execute([$modelo_ci, $numero_serie, $identificacion_cliente, $id_documento]);

        if ($val_save_accountant) {
            echo '<script>alert("Registro exitoso, continúa con el llenado de información")</script>';
            echo '<meta http-equiv="refresh" content="0; url=../../certifies/fdv/032/form.php?'.$id_documento.'">';
        } else {
            echo '<script>alert("Ocurrió un problema al intentar guardar la información, por favor, inténtalo de nuevo o contacta al Soporte Técnico")</script>';
            echo '<meta http-equiv="refresh" content="0; url=../../certifies/fdv/032/contador.php?'.$id_documento.'">';
        }

    } else {
        echo '<script>alert("No se detectó el iniciador de la petición, por favor, inténtalo de nuevo o contacta al Soporte Técnico")</script>';
        echo '<meta http-equiv="refresh" content="0; url=../../certifies/fdv/032/contador.php?'.$id_documento.'">';
    }

} else {
    die(header('Location: ../../../index.php'));
}

?>