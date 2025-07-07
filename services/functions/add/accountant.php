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
        Se registró correctamente el contador de partículas en el sistema.
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
                <a href="../../certifies/fdv/032/contador.php?'.$id_documento.'" class="btn btn-sm btn-danger" style="margin-left: 15%">Regresar</a>
            </div>';
    }

    function redirect_success($id_documento) {
        echo '
            <div class="container" style="margin-left: 40%">
                <img src="../../assets/img/loading_dvi.gif" height="40%" weight="40%">
                <br>
                <a href="../../certifies/fdv/032/form.php?'.$id_documento.'" class="btn btn-sm btn-success" style="margin-left: 15%">Continuar</a>
            </div>';
    }

    if (isset($_POST['guardar_contador'])) {
        require '../conex_serv.php';
        $certified = 'fdv_s_032';

        // Recepción de datos
        $modelo_ci = $_POST['modelo_ci'];
        $numero_serie = $_POST['numero_serie'];
        $control_no = $_POST['control_no'];
        $identificacion_cliente = $_POST['identificacion_cliente'];

        $save_accontant = $con->prepare("UPDATE $certified
                                                SET modelo_ci = ?,
                                                    numero_serie = ?,
                                                    identificacion_cliente = ?
                                                WHERE id_documento = ?");
        
        $val_save_accountant = $save_accontant->execute([$modelo_ci, $numero_serie, $identificacion_cliente, $id_documento]);

        if ($val_save_accountant) {
            // Información para auditlog
            include '../../assets/timezone.php';
            $fecha_hora_carga = date("d/m/Y H:i:s");
            $tecnico = $_SESSION['nombre_completo'];

            // Registro en log
            $log = 'auditlog';
            $movimiento = utf8_decode('El usuario '.$tecnico.' registró el contador '.$modelo_ci.' con número de serie '.$numero_serie.' el '.$fecha_hora_carga.'');
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