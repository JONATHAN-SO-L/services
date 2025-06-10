<?php
session_start();

if ($_SESSION['nombre'] != '' && $_SESSION['tipo'] == 'devecchi' || $_SESSION['tipo'] == 'admin') {

    require '../conex_serv.php';

    $id_documento = $_REQUEST['empid'];
    $certified = 'fdv_s_032';
    $log = 'auditlog';

    //Información para auditlog
    $tecnico_mod = $_SESSION['nombre_completo'];
    include '../../assets/timezone.php';
    $fecha_hora_carga = date("d/m/Y H:i:s");

    if (isset($id_documento)) {

        $d_register = $con -> prepare("DELETE FROM $certified WHERE id_documento = :id_documento");
        $d_register->bindValue(':id_documento', $id_documento);
        $val_d_register = $d_register->execute();

        if ($val_d_register) {
            // Registro en log
            $movimiento = utf8_decode('El usuario '.$tecnico_mod.' eliminó el registro '.$id_documento.' el '.$fecha_hora_carga.'');
            $url = $_SERVER['PHP_SELF'].'?'.$id_documento;
            $database = 'SIS';
            $save_move = $con->prepare("INSERT INTO $log (movimiento, link, ddbb, usuario_movimiento, fecha_hora)
                                                  VALUES (?, ?, ?, ?, ?)");
            $val_save_move = $save_move->execute([$movimiento, $url, $database, $tecnico_mod, $fecha_hora_carga]);

            // Correo
            $asunto = "SIS | Registro ".$id_documento." eliminado";
            $destinatario = "tecnicos@veco.lat";
            $cabecera = "From: <noreply@veco.lat>";
            $mensaje = $movimiento."\n\n
            Atentamente\n\n
            Soporte Devinsa\n\n
            tecnicos@veco.lat";

            mail($destinatario, $asunto, $mensaje, $cabecera);
        } else {
            echo '<script>alert("Ocurrió un error al intentar eliminar el registro seleccionado, por favor, inténtalo de nuevo o contacta al Soporte Técnico")</script>';
        }

    } else {
        echo '<script>alert("No se detectó el iniciador de la petición, por favor, inténtalo de nuevo o contacta al Soporte Técnico")</script>';
    }

} else {
    die(header('Location: ../../../index.php'));
}
?>