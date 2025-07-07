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
    Se registró correctamentela información del formulario en el sistema.
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
                <a href="../../certifies/fdv/032/form.php?'.$id_documento.'" class="btn btn-sm btn-danger" style="margin-left: 15%">Regresar</a>
            </div>';
    }

    function redirect_success($id_documento) {
        echo '
            <div class="container" style="margin-left: 40%">
                <img src="../../assets/img/loading_dvi.gif" height="40%" weight="40%">
                <br>
                <a href="../../certifies/fdv/032/mediciones_electronicas.php?'.$id_documento.'" class="btn btn-sm btn-success" style="margin-left: 15%">Continuar</a>
            </div>';
    }

    if (isset($_POST['guardar_formulario'])) {
        require '../conex_serv.php';
        $certified = 'fdv_s_032';

        // Recepción de datos
        $intervalo_calibracion = $_POST['intervalo_calibracion'];
        $condicion_fisica = $_POST['condicion_fisica'];
        $condicion_calibracion = $_POST['condicion_calibracion'];
        $condicion_final = $_POST['condicion_final'];
        $comentarios = $_POST['comentarios'];

        require '../../assets/timezone.php';
        $fecha_calibracion = date('d/m/Y');
        $fecha_calibracion = strftime('%d%b%y');

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
            // Información para auditlog
            include '../../assets/timezone.php';
            $fecha_hora_carga = date("d/m/Y H:i:s");
            $tecnico = $_SESSION['nombre_completo'];

            // Registro en log
            $log = 'auditlog';
            $movimiento = utf8_decode('El usuario '.$tecnico.' guardó información en el formulario con la fecha de calibración '.$fecha_calibracion.' el '.$fecha_hora_carga.'');
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