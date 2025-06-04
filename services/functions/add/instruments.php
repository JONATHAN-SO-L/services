<?php
session_start();

if ($_SESSION['nombre'] != '' && $_SESSION['tipo'] == 'devecchi' || $_SESSION['tipo'] == 'admin') {
    
    $id_documento = $_SERVER['QUERY_STRING'];

    if (isset($_POST['guardar_contador'])) {
        require '../conex_serv.php';
        $certified = 'fdv_s_032';

        // Recepción de datos
        $dmm = $_POST['dmm'];
        $pha = $_POST['pha'];
        $mfm = $_POST['mfm'];
        $rh_temp = $_POST['rh_temp'];
        $balometro = $_POST['balometro'];

        $save_instruments = $con->prepare("UPDATE $certified
                                                    SET dmm_activo = ?,
                                                        pha_activo = ?,
                                                        mfm_activo = ?,
                                                        rh_activo = ?,
                                                        balometro_activo = ?
                                                    WHERE id_documento = ?");
        
        $val_save_instruments = $save_instruments->execute([$dmm, $pha, $mfm, $rh_temp, $balometro, $id_documento]);

        if ($val_save_instruments) {
            echo '<meta http-equiv="refresh" content="0; url=../../certifies/fdv/032/estandard_trazabilidad.php?'.$id_documento.'">';
        } else {
            echo '<script>alert("Ocurrión un error al intentar guardar la información, por favor, inténtalo de nuevo o contacta al Soporte Técnico")</script>';
            echo '<meta http-equiv="refresh" content="0; url=../../certifies/fdv/032/instrumentos.php?'.$id_documento.'">';
        }

    } else {
        echo '<script>alert("No se detectó el iniciador de la petición, por favor, inténtalo de nuevo o contacta al Soporte Técnico")</script>';
        echo '<meta http-equiv="refresh" content="0; url=../../certifies/fdv/032/instrumentos.php?'.$id_documento.'">';
    }

} else {
    die(header('Location: ../../../index.php'));
}

?>