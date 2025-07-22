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
    Se modificaron correctamente los instrumentos en el sistema.
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
                <a href="../../certifies/fdv/032/mod/mod_standar_particles.php?'.$id_documento.'" class="btn btn-sm btn-danger" style="margin-left: 15%">Regresar</a>
            </div>';
    }

    function redirect_success() {
        echo '
            <div class="container" style="margin-left: 40%">
                <img src="../../assets/img/loading_dvi.gif" height="40%" weight="40%">
                <br>
                <a href="../../certifies/fdv/032/index.php" class="btn btn-sm btn-success" style="margin-left: 15%">Continuar</a>
            </div>';
    }

    if (isset($_POST['modificar_particulas'])) {
        require '../conex_serv.php';
        $certified = 'fdv_s_032';

        // Recepción de datos
        $tamano_real_03 = $_POST['tamano_real_03'];
        $desviacion_tamano_03 = $_POST['desviacion_tamano_03'];
        $no_lote_03 = $_POST['no_lote_03'];
        $exp_fecha_03 = $_POST['exp_fecha_03'];

        $tamano_real_04 = $_POST['tamano_real_04'];
        $desviacion_tamano_04 = $_POST['desviacion_tamano_04'];
        $no_lote_04 = $_POST['no_lote_04'];
        $exp_fecha_04 = $_POST['exp_fecha_04'];

        $tamano_real_05 = $_POST['tamano_real_05'];
        $desviacion_tamano_05 = $_POST['desviacion_tamano_05'];
        $no_lote_05 = $_POST['no_lote_05'];
        $exp_fecha_05 = $_POST['exp_fecha_05'];

        $tamano_real_06 = $_POST['tamano_real_06'];
        $desviacion_tamano_06 = $_POST['desviacion_tamano_06'];
        $no_lote_06 = $_POST['no_lote_06'];
        $exp_fecha_06 = $_POST['exp_fecha_06'];

        $tamano_real_08 = $_POST['tamano_real_08'];
        $desviacion_tamano_08 = $_POST['desviacion_tamano_08'];
        $no_lote_08 = $_POST['no_lote_08'];
        $exp_fecha_08 = $_POST['exp_fecha_08'];

        $tamano_real_10 = $_POST['tamano_real_10'];
        $desviacion_tamano_10 = $_POST['desviacion_tamano_10'];
        $no_lote_10 = $_POST['no_lote_10'];
        $exp_fecha_10 = $_POST['exp_fecha_10'];

        $tamano_real_30 = $_POST['tamano_real_30'];
        $desviacion_tamano_30 = $_POST['desviacion_tamano_30'];
        $no_lote_30 = $_POST['no_lote_30'];
        $exp_fecha_30 = $_POST['exp_fecha_30'];

        $tamano_real_50 = $_POST['tamano_real_50'];
        $desviacion_tamano_50 = $_POST['desviacion_tamano_50'];
        $no_lote_50 = $_POST['no_lote_50'];
        $exp_fecha_50 = $_POST['exp_fecha_50'];

        //Información para auditlog
        $tecnico_mod = $_SESSION['nombre_completo'];
        include '../../assets/timezone.php';
		$fecha_hora_cierre = date("d/m/Y H:i:s");

        $update_particles = $con->prepare("UPDATE $certified
                                                        SET tamano_real_03 = ?, desviacion_tamano_03 = ?, no_lote_03 = ?, exp_fecha_03 = ?,
                                                            tamano_real_04 = ?, desviacion_tamano_04 = ?, no_lote_04 = ?, exp_fecha_04 = ?,
                                                            tamano_real_05 = ?, desviacion_tamano_05 = ?, no_lote_05 = ?, exp_fecha_05 = ?,
                                                            tamano_real_06 = ?, desviacion_tamano_06 = ?, no_lote_06 = ?, exp_fecha_06 = ?,
                                                            tamano_real_08 = ?, desviacion_tamano_08 = ?, no_lote_08 = ?, exp_fecha_08 = ?,
                                                            tamano_real_10 = ?, desviacion_tamano_10 = ?, no_lote_10 = ?, exp_fecha_10=  ?,
                                                            tamano_real_30 = ?, desviacion_tamano_30 = ?, no_lote_30 = ?, exp_fecha_30 = ?,
                                                            tamano_real_50 = ?, desviacion_tamano_50 = ?, no_lote_50 = ?, exp_fecha_50 = ?,
                                                            fecha_hora_cierre = ?, modifica_data = ?, fecha_hora_modificacion = ?
                                                      WHERE id_documento = ?");

        $val_update_particles = $update_particles->execute([$tamano_real_03, $desviacion_tamano_03, $no_lote_03, $exp_fecha_03,
                                                            $tamano_real_04, $desviacion_tamano_04, $no_lote_04, $exp_fecha_04,
                                                            $tamano_real_05, $desviacion_tamano_05, $no_lote_05, $exp_fecha_05,
                                                            $tamano_real_06, $desviacion_tamano_06, $no_lote_06, $exp_fecha_06,
                                                            $tamano_real_08, $desviacion_tamano_08, $no_lote_08, $exp_fecha_08,
                                                            $tamano_real_10, $desviacion_tamano_10, $no_lote_10, $exp_fecha_10,
                                                            $tamano_real_30, $desviacion_tamano_30, $no_lote_30, $exp_fecha_30,
                                                            $tamano_real_50, $desviacion_tamano_50, $no_lote_50, $exp_fecha_50,
                                                            $fecha_hora_cierre, $tecnico_mod, $fecha_hora_cierre, $id_documento]);

        if ($val_update_particles) {
            // Registro en log
            $log = 'auditlog';
            $movimiento = utf8_decode('El usuario '.$tecnico_mod.' modificó las partículas estándar del registro '.$id_documento.' el '.$fecha_hora_cierre.'');
            $url = $_SERVER['PHP_SELF'].'?'.$id_documento;
            $database = 'SIS';
            $save_move = $con->prepare("INSERT INTO $log (movimiento, link, ddbb, usuario_movimiento, fecha_hora)
                                                  VALUES (?, ?, ?, ?, ?)");
            $val_save_move = $save_move->execute([$movimiento, $url, $database, $tecnico_mod, $fecha_hora_cierre]);

            if ($val_save_move) {
                require '../drop_con.php';
                mensaje_ayuda();
                redirect_success();
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