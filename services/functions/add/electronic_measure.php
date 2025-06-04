<?php
session_start();

if ($_SESSION['nombre'] != '' && $_SESSION['tipo'] == 'devecchi' || $_SESSION['tipo'] == 'admin') {

    $id_documento = $_SERVER['QUERY_STRING'];

    if (isset($_POST['guardar_mediciones'])) {
        require '../conex_serv.php';
        $certified = 'fdv_s_032';
        $null = NULL;

        // Recepción de datos
        # Primera fila
        $esperado_voltaje = $_POST['esperado_voltaje'];
        $condicion_encontrada_voltaje = $_POST['condicion_encontrada_voltaje'];
        $pasa_voltaje = $_POST['pasa_voltaje'];
        $condicion_final_voltaje = $_POST['condicion_final_voltaje'];

        # Segunda fila
        $esperado_flujo = $_POST['esperado_flujo'];
        $condicion_encontrada_flujo = $_POST['condicion_encontrada_flujo'];
        $pasa_flujo = $_POST['pasa_flujo'];
        $condicion_final_flujo = $_POST['condicion_final_flujo'];

        # Tercera fila
        $condicion_esperada_ruido = $_POST['condicion_esperada_ruido'];
        $pasa_ruido = $_POST['pasa_ruido'];
        $condicion_final_ruido = $_POST['condicion_final_ruido'];

        $flujo_volumetrico = $_POST['flujo_volumetrico'];

        $save_measures = $con->prepare("UPDATE $certified
                                                SET vl_esperado = ?, vl_tolerancia = ?, vl_condicion_encontrada = ?, vl_pasa = ?,
                                                    vl_condicion_final = ?, fa_esperado = ?, fa_tolerancia = ?, fa_condicion_encontrada = ?,
                                                    fa_pasa = ?, fa_condicion_final = ?, rm_esperado = ?, rm_tolerancia = ?,
                                                    rm_condicion_encontrada = ?, rm_pasa = ?, rm_condicion_final = ?, flujo_volumetrico = ?
                                                WHERE id_documento = ?");

        $val_save_measures = $save_measures->execute([$esperado_voltaje, $null, $condicion_encontrada_voltaje, $pasa_voltaje,
                                                    $condicion_encontrada_voltaje, $esperado_flujo, $null, $condicion_encontrada_flujo,
                                                    $pasa_flujo, $condicion_final_flujo, $null, $null,
                                                    $condicion_esperada_ruido, $pasa_ruido, $condicion_final_ruido, $flujo_volumetrico,
                                                    $id_documento]);

        if ($val_save_measures) {
            switch ($esperado_flujo) {
                case $esperado_flujo < 100:
                    // Información para auditlog
                    include '../../assets/timezone.php';
                    $fecha_hora_carga = date("d/m/Y H:i:s");
                    $tecnico = $_SESSION['nombre_completo'];

                    // Registro en log
                    $log = 'auditlog';
                    $movimiento = utf8_decode('El usuario '.$tecnico.' guardó mediciones electrónicas del certificado '.$id_documento.' el '.$fecha_hora_carga.'');
                    $url = $_SERVER['PHP_SELF'].'?'.$id_documento;
                    $database = 'SIS';
                    $save_move = $con->prepare("INSERT INTO $log (movimiento, link, ddbb, usuario_movimiento, fecha_hora)
                                                            VALUES (?, ?, ?, ?, ?)");
                    $val_save_move = $save_move->execute([$movimiento, $url, $database, $tecnico, $fecha_hora_carga]);

                    if ($val_save_move) {
                        echo '<script>alert("Registro exitoso, continúa con el llenado de información")</script>';
                        echo '<meta http-equiv="refresh" content="0; url=../../certifies/fdv/032/comportamiento.php?'.$id_documento.'">';
                    } else {
                        echo '<script>alert("Ocurrió un problema al intentar guardar la información, por favor, inténtalo de nuevo o contacta al Soporte Técnico")</script>';
                        echo '<meta http-equiv="refresh" content="0; url=../../certifies/fdv/032/contador.php?'.$id_documento.'">';
                    }
                break;
                
                case $esperado_flujo >= 100:
                    // Información para auditlog
                    include '../../assets/timezone.php';
                    $fecha_hora_carga = date("d/m/Y H:i:s");
                    $tecnico = $_SESSION['nombre_completo'];

                    // Registro en log
                    $log = 'auditlog';
                    $movimiento = utf8_decode('El usuario '.$tecnico.' guardó mediciones electrónicas del certificado '.$id_documento.' el '.$fecha_hora_carga.'');
                    $url = $_SERVER['PHP_SELF'].'?'.$id_documento;
                    $database = 'SIS';
                    $save_move = $con->prepare("INSERT INTO $log (movimiento, link, ddbb, usuario_movimiento, fecha_hora)
                                                            VALUES (?, ?, ?, ?, ?)");
                    $val_save_move = $save_move->execute([$movimiento, $url, $database, $tecnico, $fecha_hora_carga]);

                    if ($val_save_move) {
                        echo '<script>alert("Registro exitoso, continúa con el llenado de información")</script>';
                        echo '<meta http-equiv="refresh" content="0; url=../../certifies/fdv/032/comportamiento2.php?'.$id_documento.'">';
                    } else {
                        echo '<script>alert("Ocurrió un problema al intentar guardar la información, por favor, inténtalo de nuevo o contacta al Soporte Técnico")</script>';
                        echo '<meta http-equiv="refresh" content="0; url=../../certifies/fdv/032/contador.php?'.$id_documento.'">';
                    }
                break;

                default:
                    echo '<script>alert("Ocurrió un problema al intentar guardar la información, por favor, inténtalo de nuevo o contacta al Soporte Técnico")</script>';
                    echo '<meta http-equiv="refresh" content="0; url=../../certifies/fdv/032/mediciones_electronicas.php?'.$id_documento.'">';
                break;
            }
        } else {
            echo '<script>alert("Ocurrió un problema al intentar guardar la información, por favor, inténtalo de nuevo o contacta al Soporte Técnico")</script>';
            echo '<meta http-equiv="refresh" content="0; url=../../certifies/fdv/032/mediciones_electronicas.php?'.$id_documento.'">';
        }

    } else {
        echo '<script>alert("No se detectó el iniciador de la petición, por favor, inténtalo de nuevo o contacta al Soporte Técnico")</script>';
        echo '<meta http-equiv="refresh" content="0; url=../../certifies/fdv/032/mediciones_electronicas.php?'.$id_documento.'">';
    }

} else {
    die(header('Location: ../../../index.php'));
}

?>