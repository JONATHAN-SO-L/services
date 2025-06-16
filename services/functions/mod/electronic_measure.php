<?php
session_start();

if ($_SESSION['nombre'] != '' && $_SESSION['tipo'] == 'devecchi' || $_SESSION['tipo'] == 'admin') {

    $id_documento = $_SERVER['QUERY_STRING'];

    if (isset($_POST['modificar_mediciones'])) {
        require '../conex_serv.php';
        $certified = 'fdv_s_032';

        // Recepción de datos
        # Fila 1
        $esperado_voltaje = $_POST['esperado_voltaje'];
        $condicion_encontrada_voltaje = $_POST['condicion_encontrada_voltaje'];
        $pasa_voltaje = $_POST['pasa_voltaje'];
        $condicion_final_voltaje = $_POST['condicion_final_voltaje'];

        # Fila 2
        $esperado_flujo = $_POST['esperado_flujo'];
        $condicion_encontrada_flujo = $_POST['condicion_encontrada_flujo'];
        $pasa_flujo = $_POST['pasa_flujo'];
        $condicion_final_flujo = $_POST['condicion_final_flujo'];

        # Fila 3
        $condicion_esperada_ruido = $_POST['condicion_esperada_ruido'];
        $pasa_ruido = $_POST['pasa_ruido'];
        $condicion_final_ruido = $_POST['condicion_final_ruido'];
        
        $flujo_volumetrico = $_POST['flujo_volumetrico'];

        //Información para auditlog
        $tecnico_mod = $_SESSION['nombre_completo'];
        include '../../assets/timezone.php';
        $fecha_hora_carga = date("d/m/Y H:i:s");

        $save_measures = $con->prepare("UPDATE $certified
                                                SET vl_esperado = ?, vl_condicion_encontrada = ?, vl_pasa = ?, vl_condicion_final = ?,
                                                    fa_esperado = ?, fa_condicion_encontrada = ?, fa_pasa = ?, fa_condicion_final = ?,
                                                    rm_condicion_encontrada = ?, rm_pasa = ?, rm_condicion_final = ?,
                                                    flujo_volumetrico = ?, modifica_data = ?, fecha_hora_modificacion = ?
                                                WHERE id_documento = ?");
                    
        $val_save_measures = $save_measures->execute([$esperado_voltaje, $condicion_encontrada_voltaje, $pasa_voltaje, $condicion_final_voltaje,
                                                    $esperado_flujo, $condicion_encontrada_flujo, $pasa_flujo, $condicion_final_flujo,
                                                    $condicion_esperada_ruido, $pasa_ruido, $condicion_final_ruido,
                                                    $flujo_volumetrico, $tecnico_mod, $fecha_hora_carga,
                                                    $id_documento]);

        if ($val_save_measures) {
            // Registro en log
            $log = 'auditlog';
            $movimiento = utf8_decode('El usuario '.$tecnico_mod.' modificó el registro '.$id_documento.' actualizando las medidas electrónicas el '.$fecha_hora_carga.'');
            $url = $_SERVER['PHP_SELF'].'?'.$id_documento;
            $database = 'SIS';
            $save_move = $con->prepare("INSERT INTO $log (movimiento, link, ddbb, usuario_movimiento, fecha_hora)
                                                  VALUES (?, ?, ?, ?, ?)");
            $val_save_move = $save_move->execute([$movimiento, $url, $database, $tecnico_mod, $fecha_hora_carga]);

            if ($val_save_move) {
                require '../drop_con.php';
                echo '<script>alert("Registro exitoso, continúa con el llenado de información")</script>';
                echo '<meta http-equiv="refresh" content="0; url=../../certifies/fdv/032/mod/modificar.php?'.$id_documento.'">';
            } else {
                echo '<script>alert("Ocurrió un error al intentar guardar la información en el auditlog, por favor, inténtalo de nuevo o contacta al Soporte Técnico")</script>';echo '<meta http-equiv="refresh" content="0; url=../../certifies/fdv/032/mod/mod_electronic_measure.php?'.$id_documento.'">';
            }
        } else {
            echo '<script>alert("Ocurrión un error al intentar guardar la información, por favor, inténtalo de nuevo o contacta al Soporte Técnico")</script>';
            echo '<meta http-equiv="refresh" content="0; url=../../certifies/fdv/032/mod/mod_electronic_measure.php?'.$id_documento.'">';
        }

    } else {
        echo '<script>alert("No se detectó el iniciador de la petición, por favor, inténtalo de nuevo o contacta al Soporte Técnico")</script>';
        echo '<meta http-equiv="refresh" content="0; url=../../certifies/fdv/032/mod/mod_electronic_measure.php?'.$id_documento.'">';
    }

} else {
    die(header('Location: ../../../index.php'));
}
?>