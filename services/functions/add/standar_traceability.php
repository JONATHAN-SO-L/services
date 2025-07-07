<?php
session_start();

if ($_SESSION['nombre'] != '' && $_SESSION['tipo'] == 'devecchi' || $_SESSION['tipo'] == 'admin') {

    $id_documento = $_SERVER['QUERY_STRING'];

    include '../../assets/admin/links.php';

    function mensaje_ayuda(){
    echo '
    <div class="alert alert-success alert-dismissible fade in col-sm-3 animated bounceInDown" role="alert" style="position:fixed; top:70px; right:10px; z-index:10;"> 
    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
    <h4 class="text-center"><strong>REGISTRO EXITOSO</strong></h4>
    <p class="text-center">
    Se registraron correctamente los instrumentos en el sistema.
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
                <a href="../../certifies/fdv/032/estandard_trazabilidad.php?'.$id_documento.'" class="btn btn-sm btn-danger" style="margin-left: 15%">Regresar</a>
            </div>';
    }

    function redirect_success($id_documento) {
        echo '
            <div class="container" style="margin-left: 40%">
                <img src="../../assets/img/loading_dvi.gif" height="40%" weight="40%">
                <br>
                <a href="../../certifies/fdv/032/particulas_estandar.php?'.$id_documento.'" class="btn btn-sm btn-success" style="margin-left: 15%">Continuar</a>
            </div>';
    }

    if (isset($_POST['guardar_estandard'])) {
        require '../conex_serv.php';
        $certified = 'fdv_s_032';

        // Recepción de datos
        $dmm_activo = $_POST['dmm_activo'];
        $dmm_modelo = $_POST['dmm_modelo'];
        $dmm_no_serie = $_POST['dmm_no_serie'];
        $dmm_control_no = $_POST['dmm_control_no'];
        $dmm_fecha_cal = $_POST['dmm_fecha_cal'];
        $dmm_prox_cal = $_POST['dmm_prox_cal'];

        $pha_activo = $_POST['pha_activo'];
        $pha_modelo = $_POST['pha_modelo'];
        $pha_no_serie = $_POST['pha_no_serie'];
        $pha_control_no = $_POST['pha_control_no'];
        $pha_fecha_cal = $_POST['pha_fecha_cal'];
        $pha_prox_cal = $_POST['pha_prox_cal'];

        $mfm_activo = $_POST['mfm_activo'];
        $mfm_modelo = $_POST['mfm_modelo'];
        $mfm_no_serie = $_POST['mfm_no_serie'];
        $mfm_control_no = $_POST['mfm_control_no'];
        $mfm_fecha_cal = $_POST['mfm_fecha_cal'];
        $mfm_prox_cal = $_POST['mfm_prox_cal'];

        $rh_activo = $_POST['rh_activo'];
        $rh_modelo = $_POST['rh_modelo'];
        $rh_no_serie = $_POST['rh_no_serie'];
        $rh_control_no = $_POST['rh_control_no'];
        $rh_fecha_cal = $_POST['rh_fecha_cal'];
        $rh_prox_cal = $_POST['rh_prox_cal'];

        $balometro_activo = $_POST['balometro_activo'];
        $balometro_modelo = $_POST['balometro_modelo'];
        $balometro_no_serie = $_POST['balometro_no_serie'];
        $balometro_control_no = $_POST['balometro_control_no'];
        $balometro_fecha_cal = $_POST['balometro_fecha_cal'];
        $balometro_prox_cal = $_POST['balometro_prox_cal'];

        require '../../assets/timezone.php';
        $fecha_documento = date('d/m/Y');
        $fecha_documento = strftime('%d%b%y');

        $save_instruments = $con->prepare("UPDATE $certified
                                                    SET dmm_activo = ?, dmm_modelo = ?, dmm_numero_serie = ?, dmm_numero_control = ?, dmm_fecha_calibracion = ?, dmm_proxima_calibracion = ?,
                                                        pha_activo = ?, pha_modelo = ?, pha_numero_serie = ?, pha_numero_control = ?, pha_fecha_calibracion = ?, pha_proxima_calibracion = ?,
                                                        mfm_activo = ?, mfm_modelo = ?, mfm_numero_serie = ?, mfm_numero_control = ?, mfm_fecha_calibracion = ?, mfm_proxima_calibracion = ?,
                                                        rh_activo = ?, rh_modelo = ?, rh_numero_serie = ?, rh_numero_control = ?, rh_fecha_calibracion = ?, rh_proxima_calibracion = ?,
                                                        balometro_activo = ?, balometro_modelo = ?, balometro_numero_serie = ?, balometro_numero_control = ?, balometro_fecha_calibracion = ?, balometro_proxima_calibracion = ?,
                                                        fecha_documento = ?
                                                    WHERE id_documento = ?");

        $val_save_instruments = $save_instruments->execute([$dmm_activo, $dmm_modelo, $dmm_no_serie, $dmm_control_no, $dmm_fecha_cal, $dmm_prox_cal,
                                                            $pha_activo, $pha_modelo, $pha_no_serie, $pha_control_no, $pha_fecha_cal, $pha_prox_cal,
                                                            $mfm_activo, $mfm_modelo, $mfm_no_serie, $mfm_control_no, $mfm_fecha_cal, $mfm_prox_cal,
                                                            $rh_activo, $rh_modelo, $rh_no_serie, $rh_control_no, $rh_fecha_cal, $rh_prox_cal,
                                                            $balometro_activo, $balometro_modelo, $balometro_control_no, $balometro_no_serie, $balometro_fecha_cal, $balometro_prox_cal,
                                                            $fecha_documento, $id_documento]);
        
        if ($val_save_instruments) {
            // Información para auditlog
            include '../../assets/timezone.php';
            $fecha_hora_carga = date("d/m/Y H:i:s");
            $tecnico = $_SESSION['nombre_completo'];

            // Registro en log
            $log = 'auditlog';
            $movimiento = utf8_decode('El usuario '.$tecnico.' guardó instrumentos para la calibración en el certificado '.$id_documento.' el '.$fecha_hora_carga.'');
            $url = $_SERVER['PHP_SELF'].'?'.$id_documento;
            $database = 'SIS';
            $save_move = $con->prepare("INSERT INTO $log (movimiento, link, ddbb, usuario_movimiento, fecha_hora)
                                                    VALUES (?, ?, ?, ?, ?)");
            $val_save_move = $save_move->execute([$movimiento, $url, $database, $tecnico, $fecha_hora_carga]);

            if ($val_save_move) {
                require '../drop_con.php';
                mensaje_ayuda();
                redirect_success($id_documento);
            } else {
                mensaje_error();
                redirect_failed($id_documento);
            }
        } else {
            mensaje_error();
            redirect_failed($id_documento);
        }

    } else {
        mensaje_error();
        redirect_failed($id_documento);
    }

} else {
    die(header('Location: ../../../index.php'));
}
?>