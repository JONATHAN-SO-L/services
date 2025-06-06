<?php
session_start();

if ($_SESSION['nombre'] != '' && $_SESSION['tipo'] == 'devecchi' || $_SESSION['tipo'] == 'admin') {

    $id_documento = $_SERVER['QUERY_STRING'];

    if (isset($_POST['guardar_cambios_instrumentos'])) {
        require '../conex_serv.php';
        $certified = 'fdv_s_032';

        // Recepción de datos

        # Primera fila
        $dmm_activo = $_POST['dmm_activo'];
        $dmm_modelo = $_POST['dmm_modelo'];
        $dmm_no_serie = $_POST['dmm_no_serie'];
        $dmm_control_no = $_POST['dmm_control_no'];
        $dmm_fecha_cal = $_POST['dmm_fecha_cal'];
        $dmm_prox_cal = $_POST['dmm_prox_cal'];

        # Segunda fila
        $pha_activo = $_POST['pha_activo'];
        $pha_modelo = $_POST['pha_modelo'];
        $pha_no_serie = $_POST['pha_no_serie'];
        $pha_control_no = $_POST['pha_control_no'];
        $pha_fecha_cal = $_POST['pha_fecha_cal'];
        $pha_prox_cal = $_POST['pha_prox_cal'];

        # Tercera fila
        $mfm_activo = $_POST['mfm_activo'];
        $mfm_modelo = $_POST['mfm_modelo'];
        $mfm_no_serie = $_POST['mfm_no_serie'];
        $mfm_control_no = $_POST['mfm_control_no'];
        $mfm_fecha_cal = $_POST['mfm_fecha_cal'];
        $mfm_prox_cal = $_POST['mfm_prox_cal'];

        # Cuarta fila
        $rh_activo = $_POST['rh_activo'];
        $rh_modelo = $_POST['rh_modelo'];
        $rh_no_serie = $_POST['rh_no_serie'];
        $rh_control_no = $_POST['rh_control_no'];
        $rh_fecha_cal = $_POST['rh_fecha_cal'];
        $rh_prox_cal = $_POST['rh_prox_cal'];
        
        # Quinta fila
        $balometro_activo = $_POST['balometro_activo'];
        $balometro_modelo = $_POST['balometro_modelo'];
        $balometro_no_serie = $_POST['balometro_no_serie'];
        $balometro_control_no = $_POST['balometro_control_no'];
        $balometro_fecha_cal = $_POST['balometro_fecha_cal'];
        $balometro_prox_cal = $_POST['balometro_prox_cal'];

        //Información para auditlog
        $tecnico_mod = $_SESSION['nombre_completo'];
        include '../../assets/timezone.php';
        $fecha_hora_carga = date("d/m/Y H:i:s");

        $save_instruments = $con->prepare("UPDATE $certified
                                                    SET dmm_activo = ?, dmm_modelo = ?, dmm_numero_serie = ?, dmm_numero_control = ?, dmm_fecha_calibracion = ?, dmm_proxima_calibracion = ?,
                                                        pha_activo = ?, pha_modelo = ?, pha_numero_serie = ?, pha_numero_control = ?, pha_fecha_calibracion = ?, pha_proxima_calibracion= ? ,
                                                        mfm_activo=  ?, mfm_modelo = ?, mfm_numero_serie = ?, mfm_numero_control = ?, mfm_fecha_calibracion = ?, mfm_proxima_calibracion = ?,
                                                        rh_activo = ?, rh_modelo = ?, rh_numero_serie = ?, rh_numero_control = ?, rh_fecha_calibracion = ?, rh_proxima_calibracion = ?,
                                                        balometro_activo = ?, balometro_modelo = ?, balometro_numero_serie = ?, balometro_numero_control = ?, balometro_fecha_calibracion =?, balometro_proxima_calibracion = ?,
                                                        modifica_data = ?, fecha_hora_modificacion = ?
                                            WHERE id_documento = ?");

        $val_save_instruments = $save_instruments->execute([$dmm_activo, $dmm_modelo, $dmm_no_serie, $dmm_control_no, $dmm_fecha_cal, $dmm_prox_cal,
                                                            $pha_activo, $pha_modelo, $pha_no_serie, $pha_control_no, $pha_fecha_cal, $pha_prox_cal,
                                                            $mfm_activo, $mfm_modelo, $mfm_no_serie, $mfm_control_no, $mfm_fecha_cal, $mfm_prox_cal,
                                                            $rh_activo, $rh_modelo, $rh_no_serie, $rh_control_no, $rh_fecha_cal, $rh_prox_cal,
                                                            $balometro_activo, $balometro_modelo, $balometro_no_serie, $balometro_control_no, $balometro_fecha_cal, $balometro_prox_cal, $tecnico_mod, $fecha_hora_carga, $id_documento]);

        if ($val_save_instruments) {
            // Registro en log
            $log = 'auditlog';
            $movimiento = utf8_decode('El usuario '.$tecnico_mod.' modificó el registro '.$id_documento.' actualizando los instrumentos el '.$fecha_hora_carga.'');
            $url = $_SERVER['PHP_SELF'].'?'.$id_documento;
            $database = 'SIS';
            $save_move = $con->prepare("INSERT INTO $log (movimiento, link, ddbb, usuario_movimiento, fecha_hora)
                                                  VALUES (?, ?, ?, ?, ?)");
            $val_save_move = $save_move->execute([$movimiento, $url, $database, $tecnico_mod, $fecha_hora_carga]);

            if ($val_save_move) {
                echo '<script>alert("Registro exitoso, continúa con el llenado de información")</script>';
                echo '<meta http-equiv="refresh" content="0; url=../../certifies/fdv/032/mod/modificar.php?'.$id_documento.'">';
            } else {
                echo '<script>alert("Ocurrió un error al intentar guardar la información en el auditlog, por favor, inténtalo de nuevo o contacta al Soporte Técnico")</script>';echo '<meta http-equiv="refresh" content="0; url=../../certifies/fdv/032/mod/mod_instruments.php?'.$id_documento.'">';
            }
        } else {
            echo '<script>alert("Ocurrión un error al intentar guardar la información, por favor, inténtalo de nuevo o contacta al Soporte Técnico")</script>';
            echo '<meta http-equiv="refresh" content="0; url=../../certifies/fdv/032/mod/mod_instruments.php?'.$id_documento.'">';
        }

    } else {
        echo '<script>alert("No se detectó el iniciador de la petición, por favor, inténtalo de nuevo o contacta al Soporte Técnico")</script>';
        echo '<meta http-equiv="refresh" content="0; url=../../certifies/fdv/032/mod/mod_instruments.php?'.$id_documento.'">';
    }

} else {
    die(header('Location: ../../../index.php'));
}

?>