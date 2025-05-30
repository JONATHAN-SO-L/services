<?php
session_start();

if ($_SESSION['nombre'] != '' && $_SESSION['tipo'] == 'devecchi' || $_SESSION['tipo'] == 'admin') {

    $id_documento = $_SERVER['QUERY_STRING'];

    if (isset($_POST['guardar_formulario'])) {
        require '../conex_serv.php';
        $certified = 'fdv_s_032';

        // Recepción de datos
        $intervalo_calibracion = $_POST['intervalo_calibracion'];
        $condicion_fisica = $_POST['condicion_fisica'];
        $condicion_calibracion = $_POST['condicion_calibracion'];
        $condicion_final = $_POST['condicion_final'];
        $comentarios = $_POST['comentarios'];

        $fecha_calibracion = date('d/m/Y');

        $presion_barometrica = $_POST['presion_barometrica'];
        $temperatura = $_POST['temperatura'];
        $humedad_relativa = $_POST['humedad_relativa'];

        $save_form = $con->prepare("UPDATE $certified
                                            SET intervalo_calibracion = ?,
                                                condicion_recepcion = ?,
                                                condicion_calibracion = ?,
                                                condicion_calibracion_final = ?,
                                                comentarios = ?,
                                                fecha_calibracion = ?,
                                                presion_barometrica = ?,
                                                temperatura = ?,
                                                humedad_relativa = ?
                                            WHERE id_documento = ?");
        
        $val_save_form = $save_form->execute([$intervalo_calibracion, $condicion_fisica, $condicion_calibracion, $condicion_final,
                                            $comentarios, $fecha_calibracion, $presion_barometrica, $temperatura,
                                            $humedad_relativa, $id_documento]);
        
        if ($val_save_form) {
            echo '<script>alert("Registro exitoso, continúa con el llenado de información")</script>';
            echo '<meta http-equiv="refresh" content="0; url=../../certifies/fdv/032/mediciones_electronicas.php?'.$id_documento.'">';
        } else {
            echo '<script>alert("Ocurrió un problema al intentar guardar la información, por favor, inténtalo de nuevo o contacta al Soporte Técnico")</script>';
            echo '<meta http-equiv="refresh" content="0; url=../../certifies/fdv/032/form.php?'.$id_documento.'">';
        }

    } else {
        echo '<script>alert("No se detectó el iniciador de la petición, por favor, inténtalo de nuevo o contacta al Soporte Técnico")</script>';
        echo '<meta http-equiv="refresh" content="0; url=../../certifies/fdv/032/form.php?'.$id_documento.'">';
    }

} else {
    die(header('Location: ../../../index.php'));
}

?>