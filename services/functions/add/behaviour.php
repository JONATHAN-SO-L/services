<?php
session_start();

if ($_SESSION['nombre'] != '' && $_SESSION['tipo'] == 'devecchi' || $_SESSION['tipo'] == 'admin') {
    
    $id_documento = $_SERVER['QUERY_STRING'];

    function mensaje_error() {
        echo '
            <div class="alert alert-danger alert-dismissible fade in col-sm-3 animated bounceInDown" role="alert" style="position:fixed; top:70px; right:10px; z-index:10;"> 
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
            <h4 class="text-center"><strong>OCURRIÓ UN ERROR</strong></h4>
            <p class="text-center">
            <u>No se logró recibir información del registro, por favor, inténtalo de nuevo o contácta al Soporte Técnico.
            </p>
            </div>
            ';
    }

    if (isset($_POST['guardar_comportamiento'])) {
        require '../conex_serv.php';
        $certified = 'fdv_S_032';

        // Buscar el valor de Flujo de Aire Esperado (LPM)
        $s_measure = $con->prepare("SELECT fa_esperado FROM $certified WHERE id_documento = :id_documento");
        $s_measure->bindValue('id_documento', $id_documento);
        $s_measure->setFetchMode(PDO::FETCH_OBJ);
        $s_measure->execute();
        $f_measure = $s_measure->fetchAll();

        if ($s_measure -> rowCount() > 0) {
            foreach ($f_measure as $flujo_aire) {
                $fa_esperado = $flujo_aire -> fa_esperado;
            }
        } else {
            mensaje_error();
        }

        switch ($fa_esperado) {
            case $fa_esperado < 100:
                // Recepción de datos comportamiento 1
                # Fila 1
                $amplitud_esperada_03 = $_POST['amplitud_esperada_03'];
                $amplitud_esperada_05 = $_POST['amplitud_esperada_05'];
                $amplitud_esperada_10 = $_POST['amplitud_esperada_10'];
                $amplitud_esperada_50 = $_POST['amplitud_esperada_50'];

                # Fila 2
                $tolerancia_03 = $_POST['tolerancia_03'];
                $tolerancia_05 = $_POST['tolerancia_05'];
                $tolerancia_10 = $_POST['tolerancia_10'];
                $tolerancia_50 = $_POST['tolerancia_50'];

                #Fila 3
                $como_encuentra_03 = $_POST['como_encuentra_03'];
                $como_encuentra_05 = $_POST['como_encuentra_05'];
                $como_encuentra_10 = $_POST['como_encuentra_10'];
                $como_encuentra_50 = $_POST['como_encuentra_50'];

                # Fila 4
                $pasa_03 = $_POST['pasa_03'];
                $pasa_05 = $_POST['pasa_05'];
                $pasa_10 = $_POST['pasa_10'];
                $pasa_50 = $_POST['pasa_50'];

                # Fila 5
                $condicion_final_03 = $_POST['condicion_final_03'];
                $condicion_final_05 = $_POST['condicion_final_05'];
                $condicion_final_10 = $_POST['condicion_final_10'];
                $condicion_final_50 = $_POST['condicion_final_50'];

                $save_fa_menor = $con->prepare("UPDATE $certified
                                                        SET amplitud_esperada_03 = ?, tolerancia_03 = ?, como_encuentra_03 = ?, pasa_03 = ?, condicion_final_03 = ?,
                                                            amplitud_esperada_05 = ?, tolerancia_05 = ?, como_encuentra_05 = ?, pasa_05 = ?, condicion_final_05 = ?,
                                                            amplitud_esperada_10 = ?, tolerancia_10 = ?, como_encuentra_10 = ?, pasa_10 = ?, condicion_final_10 = ?,
                                                            amplitud_esperada_50 = ?, tolerancia_50 = ?, como_encuentra_50 = ?, pasa_50 = ?, condicion_final_50 = ?
                                                WHERE id_documento = ?");
                
                $val_save_fa_menor = $save_fa_menor->execute([$amplitud_esperada_03, $tolerancia_03, $como_encuentra_03, $pasa_03, $condicion_final_03,
                                                            $amplitud_esperada_05, $tolerancia_05, $como_encuentra_05, $pasa_05, $condicion_final_05,
                                                            $amplitud_esperada_10, $tolerancia_10, $como_encuentra_10, $pasa_10, $condicion_final_10,
                                                            $amplitud_esperada_50, $tolerancia_50, $como_encuentra_50, $pasa_50, $condicion_final_50,
                                                            $id_documento]);

                if ($val_save_fa_menor) {
                    echo '<script>alert("Registro exitoso, continúa con el llenado de información")</script>';
                    echo '<meta http-equiv="refresh" content="0; url=../../certifies/fdv/032/instrumentos.php?'.$id_documento.'">';
                } else {
                    echo '<script>alert("Ocurrió un problema al intentar guardar la información, por favor, inténtalo de nuevo o contacta al Soporte Técnico")</script>';
                    echo '<meta http-equiv="refresh" content="0; url=../../certifies/fdv/032/comportamiento.php?'.$id_documento.'">';
                }
            break;

            case $fa_esperado >= 100:
                // Recepción de datos comportamiento 2
                # Fila 1
                $amplitud_esperada_05 = $_POST['amplitud_esperada_05'];
                $amplitud_esperada_10 = $_POST['amplitud_esperada_10'];
                $amplitud_esperada_30 = $_POST['amplitud_esperada_30'];
                $amplitud_esperada_50 = $_POST['amplitud_esperada_50'];

                # Fila 2
                $tolerancia_05 = $_POST['tolerancia_05'];
                $tolerancia_10 = $_POST['tolerancia_10'];
                $tolerancia_30 = $_POST['tolerancia_30'];
                $tolerancia_50 = $_POST['tolerancia_50'];

                # Fila 3
                $como_encuentra_05 = $_POST['como_encuentra_05'];
                $como_encuentra_10 = $_POST['como_encuentra_10'];
                $como_encuentra_30 = $_POST['como_encuentra_30'];
                $como_encuentra_50 = $_POST['como_encuentra_50'];

                # Fila 4
                $pasa_05 = $_POST['pasa_05'];
                $pasa_10 = $_POST['pasa_10'];
                $pasa_30 = $_POST['pasa_30'];
                $pasa_50 = $_POST['pasa_50'];

                # Fila 5
                $condicion_final_05 = $_POST['condicion_final_05'];
                $condicion_final_10 = $_POST['condicion_final_10'];
                $condicion_final_30 = $_POST['condicion_final_30'];
                $condicion_final_50 = $_POST['condicion_final_50'];

                $save_fa_mayor = $con->prepare("UPDATE $certified
                                                        SET amplitud_esperada_05_100 = ?, tolerancia_05_100 = ?, como_encuentra_05_100 = ?, pasa_05_100 = ?, condicion_final_05_100 = ?,
                                                            amplitud_esperada_10_100 = ?, tolerancia_10_100 = ?, como_encuentra_10_100 = ?, pasa_10_100 = ?, condicion_final_10_100 = ?,
                                                            amplitud_esperada_30_100 = ?, tolerancia_30_100 = ?, como_encuentra_30_100 = ?, pasa_30_100 = ?, condicion_final_30_100 = ?,
                                                            amplitud_esperada_50_100 = ?, tolerancia_50_100 = ?, como_encuentra_50_100 = ?, pasa_50_100 = ?, condicion_final_50_100 = ?
                                                WHERE id_documento = ?");
                
                $val_save_fa_mayor = $save_fa_mayor->execute([$amplitud_esperada_05, $tolerancia_05, $como_encuentra_05, $pasa_05, $condicion_final_05,
                                                            $amplitud_esperada_10, $tolerancia_10, $como_encuentra_10, $pasa_10, $condicion_final_10,
                                                            $amplitud_esperada_30, $tolerancia_30, $como_encuentra_30, $pasa_30, $condicion_final_30,
                                                            $amplitud_esperada_50, $tolerancia_50, $como_encuentra_50, $pasa_50, $condicion_final_50,
                                                            $id_documento]);

                if ($val_save_fa_mayor) {
                    echo '<script>alert("Registro exitoso, continúa con el llenado de información")</script>';
                    echo '<meta http-equiv="refresh" content="0; url=../../certifies/fdv/032/instrumentos.php?'.$id_documento.'">';
                } else {
                    echo '<script>alert("Ocurrió un problema al intentar guardar la información, por favor, inténtalo de nuevo o contacta al Soporte Técnico")</script>';
                    echo '<meta http-equiv="refresh" content="0; url=../../certifies/fdv/032/comportamiento2.php?'.$id_documento.'">';
                }
            break;
        }

    } else {
        echo '<script>alert("No se detectó el iniciador de la petición, por favor, inténtalo de nuevo o contacta al Soporte Técnico")</script>';
        echo '<meta http-equiv="refresh" content="0; url=../../certifies/fdv/032/mediciones_electronicas.php?'.$id_documento.'">';
    }

} else {
    die(header('Location: ../../../index.php'));
}

?>