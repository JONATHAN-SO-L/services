<?php
session_start();

if ($_SESSION['nombre'] != '' && $_SESSION['tipo'] == 'devecchi' || $_SESSION['tipo'] == 'admin') {
    if (isset($_POST['continuar'])) {
        include '../../assets/admin/links.php';

        function mensaje_ayuda(){
        echo '
            <div class="alert alert-success alert-dismissible fade in col-sm-3 animated bounceInDown" role="alert" style="position:fixed; top:70px; right:10px; z-index:10;"> 
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
            <h4 class="text-center"><strong>REGISTRO EXITOSO</strong></h4>
            <p class="text-center">
            Se registró correctamente la empresa en el sistema.
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

        function redirect_failed() {
        echo '
            <div class="container" style="margin-left: 40%">
                <img src="../../assets/img/loading_dvi.gif" height="40%" weight="40%">
                <br>
                <a href="../../certifies/fdv/032/empresa.php" class="btn btn-sm btn-danger" style="margin-left: 15%">Regresar</a>
            </div>';
        }

        function redirect_success($id_documento) {
            echo '
                <div class="container" style="margin-left: 40%">
                    <img src="../../assets/img/loading_dvi.gif" height="40%" weight="40%">
                    <br>
                    <a href="../../certifies/fdv/032/contador.php?'.$id_documento.'" class="btn btn-sm btn-success" style="margin-left: 15%">Continuar</a>
                </div>';
        }

        // Recepción de datos
        $razon_social = $_POST['razon_social'];
        $rfc = $_POST['rfc'];
        $sede = $_POST['sede']; // id_edificio

        require '../conex.php';

        $build = 'edificio'; // Tabla de edificios

        // Búsqueda de la dirección de la empresa
        $s_address = $con -> prepare("SELECT * FROM $build WHERE id_edificio = :sede");
        $s_address->bindValue(':sede', $sede);
        $s_address->setFetchMode(PDO::FETCH_OBJ);
        $s_address->execute();

        $f_address = $s_address->fetchAll();

        if ($s_address -> rowCount() > 0) {
            foreach ($f_address as $direccion) {
                $calle = $direccion -> calle;
                $numero_exterior = $direccion -> numero_exterior;
                $numero_interior = $direccion -> numero_interior;
                $colonia = $direccion -> colonia;
                $municipio = $direccion -> municipio;
                $entidad_federativa = $direccion -> entidad_federativa;
                $codigo_postal = $direccion -> codigo_postal;
                $pais = $direccion -> pais;

                $direccion_entrega = $calle.' '.$numero_exterior.' '.$numero_interior.' '.$colonia.' '.$municipio.' '.$entidad_federativa.' '.$codigo_postal.' '.$pais;
        }
        } else {
            echo '<script>alert("No se logró recibir información complementaria de la empresa, por favor, inténtalo de nuevo o contacta al Soporte Técnico.")</script>';
        }

        $certified = 'fdv_s_032'; // Tabla de almacenamiento de certificados

        /***************
        CAPTURA DE DATOS
        ***************/
        require '../conex_serv.php';

        /**********************************************************************************\
        *   GENERACIÓN DEL ID DEL DOCUMENTO                                                *
        *                                                                                  *
        *   El formato que se utiliza para el Id del documento es el que sigue: 25-010301  *
        *   25=año                                                                         *
        *   01=mes                                                                         *
        *   03=número de semana del año                                                    *
        *   01=número consecutivo de calibraciones por año                                 *
        \**********************************************************************************/
        $complemento_id = date("y\ "); // últimos dos dígitos del año
       $complemento_id1 = date("y\-mW"); // año-mes número de semana del año*/

        // Se valida que no coincida el ID con otro, en caso de que sí, se agrega 1
        $s_id_documento = $con->prepare("SELECT id_documento FROM $certified ORDER BY id_documento DESC LIMIT 1");
        $s_id_documento->setFetchMode(PDO::FETCH_OBJ);
        $s_id_documento->execute();

        $f_id_documento = $s_id_documento->fetchAll();

        if ($s_id_documento -> rowCount() > 0) { // Si ya existe un folio, realiza lo siguiente:
            foreach ($f_id_documento as $folio_actual) {
                // Obtiene el folio actualmente registrado
                $id_documento_actual = $folio_actual -> id_documento;

                // Extrae los primeros valores en el formato año (Por ejemplo: 25)
                $complemento = substr($id_documento_actual, 0, 2);

                // Verifica si el año es el que está en curso
                if ($complemento_id == $complemento) {
                    // En caso de que el año sea el mismo en curso, se continúa con el consecutivo, extrae el último valor y se suma el consecutivo
                    $complemento_id2 = substr($id_documento_actual, 7);
                    $complemento_id2 = $complemento_id2+1;
                    $id_documento = $complemento_id1.$complemento_id2;
                } else {
                    // En caso de que el año sea distinto, se reinicia / inicializa para un nuevo año                    
                    $id_documento = $complemento_id1.+1;
                }
            }
        } else {
            // Si aún no existe ningún en folio en la DDBB genera uno nuevo para la base virgen
            $id_documento = $complemento_id1.+1;
        }

        // GENERADOR DE FOLIO COMÚN
        /*if ($f_id_documento <= 0) {
            $id_documento = $f_id_documento+1;
        } else {
            $id_documento = $f_id_documento+1;
        }*/

        // Información para auditlog
        $tecnico = $_SESSION['nombre_completo'];
        include '../../assets/timezone.php';
        $fecha_hora_registro = date("d/m/Y H:i:s");

        // Guardado de información en DDBB
        $save_data = $con->prepare(
            "INSERT INTO $certified (empresa, edificio, direccion, id_documento, tecnico, fecha_hora_registro)
            VALUES (?, ?, ?, ?, ?, ?)");
            
        $val_save_data = $save_data->execute([$razon_social, $sede, $direccion_entrega, $id_documento, $tecnico, $fecha_hora_registro]);

        if ($val_save_data) {
            // Registro en log
            $log = 'auditlog';
            $movimiento = utf8_decode('El usuario '.$tecnico.' registra el certificado '.$id_documento.' el '.$fecha_hora_registro.'');
            $url = $_SERVER['PHP_SELF'].'?'.$id_documento;
            $database = 'SIS';
            $save_move = $con->prepare("INSERT INTO $log (movimiento, link, ddbb, usuario_movimiento, fecha_hora)
                                                  VALUES (?, ?, ?, ?, ?)");
            $val_save_move = $save_move->execute([$movimiento, $url, $database, $tecnico, $fecha_hora_registro]);

            if ($val_save_move) {
                require '../drop_con.php';
                mensaje_ayuda();
                redirect_success($id_documento);
            } else {
                mensaje_error();
                redirect_failed();
            }

        } else {
            mensaje_error();
            redirect_failed();
        }
    } else {
        mensaje_error();
        redirect_failed();
    }

} else {
    die(header('Location: ../../../index.php'));
}

?>