<?php
session_start();
if ($_SESSION['nombre'] != '' && $_SESSION['tipo'] == 'devecchi' || $_SESSION['tipo'] == 'admin') {

    $id_documento = $_SERVER['QUERY_STRING'];

    if ($_POST['guardar_particulas']) {
        require '../conex_serv.php';
        $certified = 'fdv_s_032';

        // Recepción de datos

        # Primera fila
        $tamano_real_03 = $_POST['tamano_real_03'];
        $desviacion_tamano_03 = $_POST['desviacion_tamano_03'];
        $no_lote_03 = $_POST['no_lote_03'];
        $exp_fecha_03 = $_POST['exp_fecha_03'];

        $tamano_real_08 = $_POST['tamano_real_08'];
        $desviacion_tamano_08 = $_POST['desviacion_tamano_08'];
        $no_lote_08 = $_POST['no_lote_08'];
        $exp_fecha_08 = $_POST['exp_fecha_08'];

        # Segunda fila
        $tamano_real_04 = $_POST['tamano_real_04'];
        $desviacion_tamano_04 = $_POST['desviacion_tamano_04'];
        $no_lote_04 = $_POST['no_lote_04'];
        $exp_fecha_04 = $_POST['exp_fecha_04'];

        $tamano_real_10 = $_POST['tamano_real_10'];
        $desviacion_tamano_10 = $_POST['desviacion_tamano_10'];
        $no_lote_10 = $_POST['no_lote_10'];
        $exp_fecha_10 = $_POST['exp_fecha_10'];

        # Tercera fila
        $tamano_real_05 = $_POST['tamano_real_05'];
        $desviacion_tamano_05 = $_POST['desviacion_tamano_05'];
        $no_lote_05 = $_POST['no_lote_05'];
        $exp_fecha_05 = $_POST['exp_fecha_05'];

        $tamano_real_30 = $_POST['tamano_real_30'];
        $desviacion_tamano_30 = $_POST['desviacion_tamano_30'];
        $no_lote_30 = $_POST['no_lote_30'];
        $exp_fecha_30 = $_POST['exp_fecha_30'];

        # Cuarta fila
        $tamano_real_06 = $_POST['tamano_real_06'];
        $desviacion_tamano_06 = $_POST['desviacion_tamano_06'];
        $no_lote_06 = $_POST['no_lote_06'];
        $exp_fecha_06 = $_POST['exp_fecha_06'];

        $tamano_real_50 = $_POST['tamano_real_50'];
        $desviacion_tamano_50 = $_POST['desviacion_tamano_50'];
        $no_lote_50 = $_POST['no_lote_50'];
        $exp_fecha_50 = $_POST['exp_fecha_50'];

        // Información para auditlog
		$tecnico = $_SESSION['nombre_completo'];
		require '../../assets/timezone.php';
		$fecha_hora_cierre = date("d/m/Y H:i:s");

        $save_particles = $con->prepare("UPDATE $certified
                                                SET tamano_real_03 = ?, desviacion_tamano_03 = ?, no_lote_03 = ?, exp_fecha_03 = ?,
                                                    tamano_real_04 = ?, desviacion_tamano_04 = ?, no_lote_04 = ?, exp_fecha_04 = ?,
                                                    tamano_real_05 = ?, desviacion_tamano_05 = ?, no_lote_05 = ?, exp_fecha_05 = ?,
                                                    tamano_real_06 = ?, desviacion_tamano_06 = ?, no_lote_06 = ?, exp_fecha_06 = ?,
                                                    tamano_real_08 = ?, desviacion_tamano_08 = ?, no_lote_08 = ?, exp_fecha_08 = ?,
                                                    tamano_real_10 = ?, desviacion_tamano_10 = ?, no_lote_10 = ?, exp_fecha_10=  ?,
                                                    tamano_real_30 = ?, desviacion_tamano_30 = ?, no_lote_30 = ?, exp_fecha_30 = ?,
                                                    tamano_real_50 = ?, desviacion_tamano_50 = ?, no_lote_50 = ?, exp_fecha_50 = ?,
                                                    fecha_hora_cierre=  ?
                                            WHERE id_documento = ?");

        $val_save_particles = $save_particles->execute([$tamano_real_03, $desviacion_tamano_03, $no_lote_03, $exp_fecha_03,
                                                        $tamano_real_04, $desviacion_tamano_04, $no_lote_04, $exp_fecha_04,
                                                        $tamano_real_05, $desviacion_tamano_05, $no_lote_05, $exp_fecha_05,
                                                        $tamano_real_06, $desviacion_tamano_06, $no_lote_06, $exp_fecha_06,
                                                        $tamano_real_08, $desviacion_tamano_08, $no_lote_08, $exp_fecha_08,
                                                        $tamano_real_10, $desviacion_tamano_10, $no_lote_10, $exp_fecha_10,
                                                        $tamano_real_30, $desviacion_tamano_30, $no_lote_30, $exp_fecha_30,
                                                        $tamano_real_50, $desviacion_tamano_50, $no_lote_50, $exp_fecha_50,
                                                        $fecha_hora_cierre, $id_documento]);

        if ($val_save_particles) {
            // Registro en log
            $log = 'auditlog';
            $movimiento = utf8_decode('El usuario '.$tecnico.' cargó las partículas estándar en el certificado '.$id_documento.' el '.$fecha_hora_cierre.'');
            $url = $_SERVER['PHP_SELF'].'?'.$id_documento;
            $database = 'SIS';
            $save_move = $con->prepare("INSERT INTO $log (movimiento, link, ddbb, usuario_movimiento, fecha_hora)
                                                    VALUES (?, ?, ?, ?, ?)");
            $val_save_move = $save_move->execute([$movimiento, $url, $database, $tecnico, $fecha_hora_cierre]);

            if ($val_save_move) {
                require '../drop_con.php';
                echo '<script>alert("Registro exitoso, continúa con el llenado de información")</script>';
                echo '<meta http-equiv="refresh" content="0; url=../../certifies/fdv/032/index.php">';
            } else {
                echo '<script>alert("Ocurrió un problema al intentar guardar la información, por favor, inténtalo de nuevo o contacta al Soporte Técnico")</script>';
                echo '<meta http-equiv="refresh" content="0; url=../../certifies/fdv/032/particulas_estandar.php?'.$id_documento.'">';
            }
        } else {
            echo '<script>alert("Ocurrió un error al intentar guardar la información, por favor, inténtalo de nuevo o contacta al Soporte Técnico")</script>';
            echo '<meta http-equiv="refresh" content="0; url=../../certifies/fdv/032/particulas_estandar.php?'.$id_documento.'">';
        }

    } else {
        echo '<script>alert("No se detectó el iniciador de la petición, por favor, inténtalo de nuevo o contacta al Soporte Técnico")</script>';
        echo '<meta http-equiv="refresh" content="0; url=../../certifies/fdv/032/particulas_estandar.php?'.$id_documento.'">';
    }

} else {
    die(header('Location: ../../../index.php'));
}
?>