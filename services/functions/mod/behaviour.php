<?php
session_start();

if ($_SESSION['nombre'] != '' && $_SESSION['tipo'] == 'devecchi' || $_SESSION['tipo'] == 'admin') {

    $id_documento = $_SERVER['QUERY_STRING'];

    include '../../assets/admin/links.php';

    function mensaje_ayuda(){
    echo '
    <div class="alert alert-success alert-dismissible fade in col-sm-3 animated bounceInDown" role="alert" style="position:fixed; top:70px; right:10px; z-index:10;"> 
    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
    <h4 class="text-center"><strong>MODIFICACIÓN EXITOSA</strong></h4>
    <p class="text-center">
    Se modificaron correctamente las mediciones del comportamiento en el sistema.
    </p>
    </div>
    ';
    }

    function mensaje_error() {
        echo '
            <div class="alert alert-danger alert-dismissible fade in col-sm-3 animated bounceInDown" role="alert" style="position:fixed; top:70px; right:10px; z-index:10;"> 
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
            <h4 class="text-center"><strong>OCURRIÓ UN ERROR</strong></h4>
            <p class="text-center">
            <u>No se logró recibir información correctamente, por favor, inténtalo de nuevo o contácta al Soporte Técnico.
            </p>
            </div>
            ';
    }

    function redirect_failed($id_documento) {
        echo '
            <div class="container" style="margin-left: 40%">
                <img src="../../assets/img/loading_dvi.gif" height="40%" weight="40%">
                <br>
                <a href="../../certifies/fdv/032/mod/mod_electronic_measure.php?'.$id_documento.'" class="btn btn-sm btn-danger" style="margin-left: 15%">Regresar</a>
            </div>';
    }

    function redirect_failed2($id_documento) {
        echo '
            <div class="container" style="margin-left: 40%">
                <img src="../../assets/img/loading_dvi.gif" height="40%" weight="40%">
                <br>
                <a href="../../certifies/fdv/032/mod/mod_behaviour2.php?'.$id_documento.'" class="btn btn-sm btn-success" style="margin-left: 15%">Continuar</a>
            </div>';
    }

    function redirect_success($id_documento) {
        echo '
            <div class="container" style="margin-left: 40%">
                <img src="../../assets/img/loading_dvi.gif" height="40%" weight="40%">
                <br>
                <a href="../../certifies/fdv/032/mod/modificar.php?'.$id_documento.'" class="btn btn-sm btn-success" style="margin-left: 15%">Continuar</a>
            </div>';
    }

    // Consulta el flujo de aire esperado para redirigir al modificador correcto
    require '../conex_serv.php';
    $certified = 'fdv_s_032';

    $s_fa = $con->prepare("SELECT fa_esperado FROM $certified WHERE id_documento = :id_documento");
    $s_fa->bindValue(':id_documento', $id_documento);
    $s_fa->setFetchMode(PDO::FETCH_OBJ);
    $s_fa->execute();

    $f_fa = $s_fa->fetchAll();

    if ($s_fa -> rowCount() > 0) {
        foreach ($f_fa as $behaviour) {
            $fa_esperado = $behaviour -> fa_esperado;
        }
    } else {
        mensaje_error();
        redirect_success($id_documento);
    }

    //Información para auditlog
    $tecnico_mod = $_SESSION['nombre_completo'];
    include '../../assets/timezone.php';
    $fecha_hora_carga = date("d/m/Y H:i:s");
    $log = 'auditlog';
    $movimiento = utf8_decode('El usuario '.$tecnico_mod.' modificó el registro '.$id_documento.' actualizando las medidas del comportamiento el '.$fecha_hora_carga.'');
    $url = $_SERVER['PHP_SELF'].'?'.$id_documento;
    $database = 'SIS';

    switch ($fa_esperado) {
        case $fa_esperado < 100:
            if (isset($_POST['modificar_comportamiento1'])) {
                // Recepción de datos mod_behaviour

                # Primera fila
                $amplitud_esperada_03 = $_POST['amplitud_esperada_03'];
                $amplitud_esperada_05 = $_POST['amplitud_esperada_05'];
                $amplitud_esperada_10 = $_POST['amplitud_esperada_10'];
                $amplitud_esperada_50 = $_POST['amplitud_esperada_50'];

                # Segunda fila
                $tolerancia_03 = $_POST['tolerancia_03'];
                $tolerancia_05 = $_POST['tolerancia_05'];
                $tolerancia_10 = $_POST['tolerancia_10'];
                $tolerancia_50 = $_POST['tolerancia_50'];

                # Tercera fila
                $como_encuentra_03 = $_POST['como_encuentra_03'];
                $como_encuentra_05 = $_POST['como_encuentra_05'];
                $como_encuentra_10 = $_POST['como_encuentra_10'];
                $como_encuentra_50 = $_POST['como_encuentra_50'];

                # Cuarta fila
                $pasa_03 = $_POST['pasa_03'];
                $pasa_05 = $_POST['pasa_05'];
                $pasa_10 = $_POST['pasa_10'];
                $pasa_50 = $_POST['pasa_50'];

                # Quinta Fila
                $condicion_final_03 = $_POST['condicion_final_03'];
                $condicion_final_05 = $_POST['condicion_final_05'];
                $condicion_final_10 = $_POST['condicion_final_10'];
                $condicion_final_50 = $_POST['condicion_final_50'];

                $save_behavoiur1 = $con->prepare("UPDATE $certified
                                                            SET amplitud_esperada_03 = ?, tolerancia_03 = ?, como_encuentra_03 = ?, pasa_03 = ?, condicion_final_03 = ?,
                                                                amplitud_esperada_05 = ?, tolerancia_05 = ?, como_encuentra_05 = ?, pasa_05 = ?, condicion_final_05 = ?,
                                                                amplitud_esperada_10 = ?, tolerancia_10 = ?, como_encuentra_10 = ?, pasa_10 = ?, condicion_final_10 = ?,
                                                                amplitud_esperada_50 = ?, tolerancia_50 = ?, como_encuentra_50 = ?, pasa_50 = ?, condicion_final_50 = ?,
                                                                modifica_data = ?, fecha_hora_modificacion = ?
                                                    WHERE id_documento = ?");
                
                $val_save_behaviour1 = $save_behavoiur1 -> execute([$amplitud_esperada_03, $tolerancia_03, $como_encuentra_03, $pasa_03, $condicion_final_03,
                                                                    $amplitud_esperada_05, $tolerancia_05, $como_encuentra_05, $pasa_05, $condicion_final_05,
                                                                    $amplitud_esperada_10, $tolerancia_10, $como_encuentra_10, $pasa_10, $condicion_final_10,
                                                                    $amplitud_esperada_50, $tolerancia_50, $como_encuentra_50, $pasa_50, $condicion_final_50,
                                                                    $tecnico_mod, $fecha_hora_carga,
                                                                    $id_documento]);

                if ($val_save_behaviour1) {
                    // Registro en log
                    $save_move = $con->prepare("INSERT INTO $log (movimiento, link, ddbb, usuario_movimiento, fecha_hora)
                                                        VALUES (?, ?, ?, ?, ?)");
                    $val_save_move1 = $save_move->execute([$movimiento, $url, $database, $tecnico_mod, $fecha_hora_carga]);

                    if ($val_save_move1) {
                        require '../drop_con.php';
                        mensaje_ayuda();
                        redirect_success($id_documento);
                    } else {
                        mensaje_error();
                        redirect_failed($id_documento);
                        die();
                    }
                } else {
                    mensaje_error();
                    redirect_failed($id_documento);
                    die();
                }

            } else {
                mensaje_error();
                redirect_failed($id_documento);
                die();
            }
        break;

        case $fa_esperado >= 100:
            if (isset($_POST['modificar_comportamiento2'])) {
                // Recepción de datos mod_behaviour2

                # Primera fila
                $amplitud_esperada_05_100 = $_POST['amplitud_esperada_05'];
                $amplitud_esperada_10_100 = $_POST['amplitud_esperada_10'];
                $amplitud_esperada_30_100 = $_POST['amplitud_esperada_30'];
                $amplitud_esperada_50_100 = $_POST['amplitud_esperada_50'];

                # Segunda fila
                $tolerancia_05_100 = $_POST['tolerancia_05'];
                $tolerancia_10_100 = $_POST['tolerancia_10'];
                $tolerancia_30_100 = $_POST['tolerancia_30'];
                $tolerancia_50_100 = $_POST['tolerancia_50'];

                # Tercera fila
                $como_encuentra_05_100 = $_POST['como_encuentra_05'];
                $como_encuentra_10_100 = $_POST['como_encuentra_10'];
                $como_encuentra_30_100 = $_POST['como_encuentra_30'];
                $como_encuentra_50_100 = $_POST['como_encuentra_50'];

                # Cuarta fila
                $pasa_05_100 = $_POST['pasa_05'];
                $pasa_10_100 = $_POST['pasa_10'];
                $pasa_30_100 = $_POST['pasa_30'];
                $pasa_50_100 = $_POST['pasa_50'];

                # Quinta fila
                $condicion_final_05_100 = $_POST['condicion_final_05'];
                $condicion_final_10_100 = $_POST['condicion_final_10'];
                $condicion_final_30_100 = $_POST['condicion_final_30'];
                $condicion_final_50_100 = $_POST['condicion_final_50'];

                $save_behavoiur2 = $con->prepare("UPDATE $certified
                                                            SET amplitud_esperada_05_100= ?, tolerancia_05_100 = ?, como_encuentra_05_100 = ?, pasa_05_100 = ?, condicion_final_05_100 = ?,
                                                                amplitud_esperada_10_100 = ?, tolerancia_10_100 = ?, como_encuentra_10_100 = ?, pasa_10_100 = ?, condicion_final_10_100 = ?,
                                                                amplitud_esperada_30_100 = ?, tolerancia_30_100 = ?, como_encuentra_30_100 = ?, pasa_30_100 = ?, condicion_final_30_100 = ?,
                                                                amplitud_esperada_50_100 = ?, tolerancia_50_100 = ?, como_encuentra_50_100 = ?, pasa_50_100 = ?, condicion_final_50_100 = ?, modifica_data = ?, fecha_hora_modificacion = ?
                                                    WHERE id_documento = ?");

                $val_save_behaviour2 = $save_behavoiur2 -> execute([$amplitud_esperada_05_100, $tolerancia_05_100, $como_encuentra_05_100, $pasa_05_100, $condicion_final_05_100,
                                                                    $amplitud_esperada_10_100, $tolerancia_10_100, $como_encuentra_05_100, $pasa_05_100, $condicion_final_05_100,
                                                                    $amplitud_esperada_30_100, $tolerancia_30_100, $como_encuentra_30_100, $pasa_30_100, $condicion_final_30_100,
                                                                    $amplitud_esperada_50_100, $tolerancia_50_100, $como_encuentra_50_100, $pasa_50_100, $condicion_final_50_100,
                                                                    $tecnico_mod, $fecha_hora_carga,
                                                                    $id_documento]);

                if ($val_save_behaviour2) {
                    // Registro en log
                    $save_move = $con->prepare("INSERT INTO $log (movimiento, link, ddbb, usuario_movimiento, fecha_hora)
                                                        VALUES (?, ?, ?, ?, ?)");
                    $val_save_move2 = $save_move->execute([$movimiento, $url, $database, $tecnico_mod, $fecha_hora_carga]);

                    if ($val_save_move2) {
                        require '../drop_con.php';
                        mensaje_ayuda();
                        redirect_success($id_documento);
                    } else {
                        mensaje_error();
                        redirect_failed2($id_documento);
                        die();
                    }
                } else {
                    mensaje_error();
                    redirect_failed2($id_documento);
                    die();
                }

            } else {
                mensaje_error();
                redirect_failed2($id_documento);
                die();
            }
        break;

        default:
            die(header('Location: ../../certifies/fdv/032/mod/modificar.php?'.$id_documento.''));
        break;
    }

} else {
    die(header('Location: ../../../index.php'));
}
?>