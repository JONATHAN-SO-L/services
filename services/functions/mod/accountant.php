<?php
session_start();

if ($_SESSION['nombre'] != '' && $_SESSION['tipo'] == 'devecchi' || $_SESSION['tipo'] == 'admin') {

    $id_documento = $_SERVER['QUERY_STRING'];

    if (isset($_POST['modificar_contador'])) {
        require '../conex_serv.php';
        $certified = 'fdv_s_032';

        // Recepción de datos
        $modelo_ci = $_POST['modelo_ci'];
        $numero_serie = $_POST['numero_serie'];
        $control_no = $_POST['control_no'];

        $save_accountant = $con->prepare("UPDATE $certified
                                                SET modelo_contador = ?,
                                                    modelo_ci = ?,
                                                    numero_serie = ?,
                                                    identificacion_cliente = ?
                                                WHERE id_documento = ?");

        $val_save_accountant = $save_accountant->execute([$modelo_ci, $modelo_ci, $numero_serie, $control_no, $id_documento]);

        if ($val_save_accountant) {
            //Información para auditlog
            $tecnico_mod = $_SESSION['nombre_completo'];
            include '../../assets/timezone.php';
            $fecha_hora_carga = date("d/m/Y H:i:s");

            // Registro en log
            $log = 'auditlog';
            $movimiento = utf8_decode('El usuario '.$tecnico_mod.' modificó el registro '.$id_documento.' actualizandolo con el contador '.$modelo_ci.' con el número de serie '.$numero_serie.' el '.$fecha_hora_carga.'');
            $url = $_SERVER['PHP_SELF'].'?'.$id_documento;
            $database = 'SIS';
            $save_move = $con->prepare("INSERT INTO $log (movimiento, link, ddbb, usuario_movimiento, fecha_hora)
                                                  VALUES (?, ?, ?, ?, ?)");
            $val_save_move = $save_move->execute([$movimiento, $url, $database, $tecnico_mod, $fecha_hora_carga]);

            if ($val_save_move) {
                echo '<script>alert("Registro exitoso, continúa con el llenado de información")</script>';
                echo '<meta http-equiv="refresh" content="0; url=../../certifies/fdv/032/mod/modificar.php?'.$id_documento.'">';
            } else {
                echo '<script>alert("Ocurrió un error al intentar guardar la información en el auditlog, por favor, inténtalo de nuevo o contacta al Soporte Técnico")</script>';echo '<meta http-equiv="refresh" content="0; url=../../certifies/fdv/032/mod/mod_accountant.php?'.$id_documento.'">';
            }
        } else {
             echo '<script>alert("Ocurrión un error al intentar guardar la información, por favor, inténtalo de nuevo o contacta al Soporte Técnico")</script>';
             echo '<meta http-equiv="refresh" content="0; url=../../certifies/fdv/032/mod/mod_accountant.php?'.$id_documento.'">';
        }

    } else {
        echo '<script>alert("No se detectó el iniciador de la petición, por favor, inténtalo de nuevo o contacta al Soporte Técnico")</script>';
        echo '<meta http-equiv="refresh" content="0; url=../../certifies/fdv/032/mod/mod_accountant.php?'.$id_documento.'">';
    }

} else {
    die(header('Location: ../../../index.php'));
}
?>