<?php
session_start();

if ($_SESSION['nombre'] != '' && $_SESSION['tipo'] == 'devecchi' || $_SESSION['tipo'] == 'admin') {
    // Se requiere la librería FPDF para el diseño del formato FDV-S-032
    require '../../../lib/fpdf/fpdf.php';
    require '../../certifies/assets/lib/phpqrcode/qrlib.php';

    $id_documento = $_SERVER['QUERY_STRING'];

    require '../../functions/conex_serv.php';
    $certified = 'fdv_s_032';

    $format = 'formatos';
    $s_format = $con->prepare("SELECT nombre_formato, revision_formato FROM $format WHERE formato = 'FDV-S-032'");
    $s_format->setFetchMode(PDO::FETCH_OBJ);
    $s_format->execute();

    $f_format = $s_format->fetchAll();

    if ($s_format -> rowCount() > 0) {
        foreach ($f_format as $formato) {
        $nombre_formato = $formato -> nombre_formato;
        $revision_formato = $formato -> revision_formato;
        }
    } else {
        echo 'No se encontraron formatos registrados en sistema';
    }

    $recover_data = $con->prepare("SELECT * FROM $certified WHERE id_documento = :id_documento");
    $recover_data->bindValue(':id_documento', $id_documento);
    $recover_data->setFetchMode(PDO::FETCH_OBJ);
    $recover_data->execute();

    $show_data_recover = $recover_data->fetchAll();

    if ($recover_data -> rowCount() > 0) {
        foreach ($show_data_recover as $certificado) {
            $empresa = $certificado -> empresa;
            $direccion = $certificado -> direccion;
            $modelo_contador = $certificado -> modelo_contador;

            $intervalo_calibracion = $certificado -> intervalo_calibracion;
            $condicion_recepcion = $certificado -> condicion_recepcion;
            $condicion_calibracion = $certificado -> condicion_calibracion;
            $condicion_calibracion_final = $certificado -> condicion_calibracion_final;
            $comentarios = $certificado -> comentarios;

            $modelo_ci = $certificado -> modelo_ci;
            $numero_serie = $certificado -> numero_serie;
            $fecha_calibracion = $certificado -> fecha_calibracion;
            $identificacion_cliente = $certificado -> identificacion_cliente;
            
            $presion_barometrica = $certificado -> presion_barometrica;
            $temperatura = $certificado -> temperatura;
            $humedad_relativa = $certificado -> humedad_relativa;

            $vl_esperado = $certificado -> vl_esperado;
            $vl_tolerancia = $certificado -> vl_tolerancia;
            $vl_condicion_encontrada = $certificado -> vl_condicion_encontrada;
            $vl_pasa = $certificado -> vl_pasa;
            $vl_condicion_final = $certificado -> vl_condicion_final;
            
            $fa_esperado = $certificado -> fa_esperado;
            $fa_tolerancia = $certificado -> fa_tolerancia;
            $fa_condicion_encontrada = $certificado -> fa_condicion_encontrada;
            $fa_pasa = $certificado -> fa_pasa;
            $fa_condicion_final = $certificado -> fa_condicion_final;

            $rm_esperado = $certificado -> rm_esperado;
            $rm_tolerancia = $certificado -> rm_tolerancia;
            $rm_condicion_encontrada = $certificado -> rm_condicion_encontrada;
            $rm_pasa = $certificado -> rm_pasa;
            $rm_condicion_final = $certificado -> rm_condicion_final;

            $flujo_volumetrico = $certificado -> flujo_volumetrico;

            // Información para certificado iguales o superiores a 100 LPM
            $amplitud_esperada_05_100 = $certificado -> amplitud_esperada_05_100;
            $tolerancia_05_100 = $certificado -> tolerancia_05_100;
            $como_encuentra_05_100 = $certificado -> como_encuentra_05_100;
            $pasa_05_100 = $certificado -> pasa_05_100;
            $condicion_final_05_100 = $certificado -> condicion_final_05_100;

            $amplitud_esperada_10_100 = $certificado -> amplitud_esperada_10_100;
            $tolerancia_10_100 = $certificado -> tolerancia_10_100;
            $como_encuentra_10_100 = $certificado -> como_encuentra_10_100;
            $pasa_10_100 = $certificado -> pasa_10_100;
            $condicion_final_10_100 = $certificado -> condicion_final_10_100;

            $amplitud_esperada_30_100 = $certificado -> amplitud_esperada_30_100;
            $tolerancia_30_100 = $certificado -> tolerancia_30_100;
            $como_encuentra_30_100 = $certificado -> como_encuentra_30_100;
            $pasa_30_100 = $certificado -> pasa_30_100;
            $condicion_final_30_100 = $certificado -> condicion_final_30_100;

            $amplitud_esperada_50_100 = $certificado -> amplitud_esperada_50_100;
            $tolerancia_50_100 = $certificado -> tolerancia_50_100;
            $como_encuentra_50_100 = $certificado -> como_encuentra_50_100;
            $pasa_50_100 = $certificado -> pasa_50_100;
            $condicion_final_50_100 = $certificado -> condicion_final_50_100;

            $fecha_documento = $certificado -> fecha_documento;

            $dmm_activo = $certificado -> dmm_activo;
            $dmm_modelo = $certificado -> dmm_modelo;
            $dmm_numero_serie = $certificado -> dmm_numero_serie;
            $dmm_numero_control = $certificado -> dmm_numero_control;
            $dmm_fecha_calibracion = $certificado -> dmm_fecha_calibracion;
            $dmm_proxima_calibracion = $certificado -> dmm_proxima_calibracion;

            $pha_activo = $certificado -> pha_activo;
            $pha_modelo = $certificado -> pha_modelo;
            $pha_numero_serie = $certificado -> pha_numero_serie;
            $pha_numero_control = $certificado -> pha_numero_control;
            $pha_fecha_calibracion = $certificado -> pha_fecha_calibracion;
            $pha_proxima_calibracion = $certificado -> pha_proxima_calibracion;

            $mfm_activo = $certificado -> mfm_activo;
            $mfm_modelo = $certificado -> mfm_modelo;
            $mfm_numero_serie = $certificado -> mfm_numero_serie;
            $mfm_numero_control = $certificado -> mfm_numero_control;
            $mfm_fecha_calibracion = $certificado -> mfm_fecha_calibracion;
            $mfm_proxima_calibracion = $certificado -> mfm_proxima_calibracion;

            $rh_activo = $certificado -> rh_activo;
            $rh_modelo = $certificado -> rh_modelo;
            $rh_numero_serie = $certificado -> rh_numero_serie;
            $rh_numero_control = $certificado -> rh_numero_control;
            $rh_fecha_calibracion = $certificado -> rh_fecha_calibracion;
            $rh_proxima_calibracion = $certificado -> rh_proxima_calibracion;
            
            $balometro_activo = $certificado -> balometro_activo;
            $balometro_modelo = $certificado -> balometro_modelo;
            $balometro_numero_serie = $certificado -> balometro_numero_serie;
            $balometro_numero_control = $certificado -> balometro_numero_control;
            $balometro_fecha_calibracion = $certificado -> balometro_fecha_calibracion;
            $balometro_proxima_calibracion = $certificado -> balometro_proxima_calibracion;

            $tamano_real_03 = $certificado -> tamano_real_03;
            $desviacion_tamano_03 = $certificado -> desviacion_tamano_03;
            $no_lote_03 = $certificado -> no_lote_03;
            $exp_fecha_03 = $certificado -> exp_fecha_03;

            $tamano_real_04 = $certificado -> tamano_real_04;
            $desviacion_tamano_04 = $certificado -> desviacion_tamano_04;
            $no_lote_04 = $certificado -> no_lote_04;
            $exp_fecha_04 = $certificado -> exp_fecha_04;
            
            $tamano_real_05 = $certificado -> tamano_real_05;
            $desviacion_tamano_05 = $certificado -> desviacion_tamano_05;
            $no_lote_05 = $certificado -> no_lote_05;
            $exp_fecha_05 = $certificado -> exp_fecha_05;

            $tamano_real_06 = $certificado -> tamano_real_06;
            $desviacion_tamano_06 = $certificado -> desviacion_tamano_06;
            $no_lote_06 = $certificado -> no_lote_06;
            $exp_fecha_06 = $certificado -> exp_fecha_06;

            $tamano_real_08 = $certificado -> tamano_real_08;
            $desviacion_tamano_08 = $certificado -> desviacion_tamano_08;
            $no_lote_08 = $certificado -> no_lote_08;
            $exp_fecha_08 = $certificado -> exp_fecha_08;

            $tamano_real_10 = $certificado -> tamano_real_10;
            $desviacion_tamano_10 = $certificado -> desviacion_tamano_10;
            $no_lote_10 = $certificado -> no_lote_10;
            $exp_fecha_10 = $certificado -> exp_fecha_10;

            $tamano_real_30 = $certificado -> tamano_real_30;
            $desviacion_tamano_30 = $certificado -> desviacion_tamano_30;
            $no_lote_30 = $certificado -> no_lote_30;
            $exp_fecha_30 = $certificado -> exp_fecha_30;

            $tamano_real_50 = $certificado -> tamano_real_50;
            $desviacion_tamano_50 = $certificado -> desviacion_tamano_50;
            $no_lote_50 = $certificado -> no_lote_50;
            $exp_fecha_50 = $certificado -> exp_fecha_50;

            $tecnico = $certificado -> tecnico;
        }

    } else {
        echo '<script>alert("Ocurrión un error al intentar recuperar la información, por favor, inténtalo de nuevo o contacta al Soporte Técnico")</script>';
        echo '<meta http-equiv="refresh" content="0; url=../../certifies/fdv/032/index.php">';
    }

    // Clase herdada para la definición de funciones que se replicarán en todas las hojas
    class PDF extends FPDF {
        function Footer() {
            global $nombre_formato;
            global $revision_formato;

            $this->SetTextColor(0,88,147); // Color RGB azul
            $this->SetFont("Arial","",10); // Arial Normal 10

            // Línea divisora del pie de página
            $this->SetDrawColor(0,88,147); // Color RGB para la línea
            $this->SetLineWidth(0.7); // Ancho de la línea, por defecto es 0.2 mm
            //           X  Y   l   Y   l: largo
            $this->Line(10,253,200,253); // Coordenadas (Inicio largo de línea, inclinación inicial de línea, fin largo de línea, inclinación final de línea)

            // Lado izquierdo del pie de página
            $this->SetXY(10,255); // Ubicación en plano cartesiano
            $this->Cell (10,5,utf8_decode('Certificado por:'),0,1,'L'); // Objeto orientado a la izquierda
            $this->SetXY(10,260);
            $this->Cell (10,5,utf8_decode('De Vecchi Ingenieros'),0,1,'L');

            // Centro
            $this->SetXY(70,260);
            $this->Cell (10,5,utf8_decode('Certificado de Calibración, Página de '.$this->PageNo().' de {nb}'),0,1,'L');

            // Derecha
            $this->SetXY(160,265);
            $this->Cell (10,5,utf8_decode('REV. '.$revision_formato.' '.$nombre_formato.'.'),0,1,'D'); // Objeto orientado a la derecha
        }
    }

    // Generación del formato
    $pdf=new PDF('P','mm','Letter'); // Portrait milimétricos en carta
    $pdf->SetMargins(12,0); // Margen de 12 mm
    $pdf->AliasNbPages(); // Numeración de páginas
    $pdf->AddPage(); // Creación de hoja pdf

    /*****************************************************************************************************************************
    PÁGINA UNO
    *****************************************************************************************************************************/

    /********************************************************
    Cabecera con información de dirección y logo | Página Uno
    ********************************************************/
    $pdf->SetXY(0,0);
    $pdf->SetFillColor(0,88,147);
    $pdf->Cell(216,35,'',0,1,'C',true);

    $pdf->SetFont("Arial","",10);
    $pdf->SetTextColor(255,255,255);
    $pdf->SetXY(20,9);
    $pdf->MultiCell(30,5,utf8_decode('Pirineos No. 263 Col. Santa Cruz Atoyac CDMX Benito Juárez 03310'),0,'L',false);

    $pdf->SetXY(73,8);
    $pdf->MultiCell(35,5,utf8_decode('www.dvi.mx servicio@dvi.mx Tel.(55) 5688 - 3566 / 3977 Ext. 402 01-800-8326 - 345'),0,'L',false);

    $pdf->Image('../../assets/img/logo_devecchi_blanco.png', 125, 11, 75); // Logo


    /**********************************
    Cuerpo del formulario | Página Uno
    **********************************/
    $pdf->SetTextColor(0,88,147);
    $pdf->SetFont("Arial","b",24);
    $pdf->SetXY(5,37);
    $pdf->Cell(0,10,utf8_decode('CERTIFICADO DE CALIBRACIÓN'),0,0,'C');

    $pdf->SetXY(5,48);
    $pdf->SetFont("Arial","",8);
    $pdf->Cell(0,5,utf8_decode('(Esta prohibida la reproducción, modificación parcial o total de este documento, sin la aprobación por escrito de De Vecchi Ingenieros.)'),0,0,'C');

    // ID del Documento
    $pdf->SetFont("Arial","b",10);
    $pdf->SetXY(15,57);
    $pdf->Cell (10,5,utf8_decode('ID del documento:'),0,1,'L');
    $pdf->SetDrawColor(0,88,147);
    $pdf->SetLineWidth(0.2);
    //          X Y   l  Y   l: largo
    $pdf->Line(48,61,85,61); // Coordenadas (Inicio largo de línea, inclinación inicial de línea, fin largo de línea, inclinación final de línea)

    $pdf->SetFont("Arial","",10);
    $pdf->SetTextColor(0,0,0);
    $pdf->SetXY(63,56.5);
    $pdf->Cell (5,5,utf8_decode($id_documento),0,1,'C');
    $pdf->SetFont("Arial","b",10);
    $pdf->SetTextColor(0,88,147);

    // QR del documento
    $pdf->SetDrawColor(0,88,147);
    $dir = '../qr_codes/';

    if(!file_exists($dir))
    mkdir($dir);

    $filename = $dir.$id_documento.'.png';
    $size = 5;
    $level = 'S';
    $framesize = 1;
    $website = 'https://dvi.mx';
    $contenido = 'CERTIFICADO: '.$id_documento.', MODELO: '.$modelo_contador.', No. DE SERIE: '.$numero_serie.', FECHA DE CALIBRACIÓN: '.$fecha_calibracion.', TÉCNICO: '.$tecnico.' '.$website;

    QRCode::png($contenido, $filename, $level, $size, $framesize);

    $pdf->Image($filename, 33, 68, 30);

    $pdf->SetXY(100,57);
    $pdf->SetFont("Arial","b",10);
    $pdf->Cell(50,5,'PREPARADO POR:',0,0,'L');

    $pdf->SetFont("Arial","",10);
    $pdf->SetTextColor(0,0,0);
    $pdf->SetXY(160,57);
    $pdf->Cell (5,5,utf8_decode($tecnico),0,1,'C');
    $pdf->SetFont("Arial","b",10);
    $pdf->SetTextColor(0,88,147);

    // Información de la empresa
    $pdf->SetXY(100,65);
    $pdf->Cell(50,5,utf8_decode('COMPAÑÍA:'),0,0,'L');
    $pdf->Line(208,70,125,70);
    $pdf->Line(208,76,125,76);

    $pdf->SetFont("Arial","",10);
    $pdf->SetTextColor(0,0,0);
    $pdf->SetXY(124,65);
    $empresa = ucwords($empresa);
    $pdf->MultiCell(80,5,utf8_decode($empresa),0,'L',false);
    $pdf->SetFont("Arial","b",10);
    $pdf->SetTextColor(0,88,147);

    // Dirección de la empresa
    $pdf->SetXY(100,80);
    $pdf->Cell(50,5,utf8_decode('DIRECCIÓN:'),0,0,'L');
    $pdf->Line(208,85,125,85);
    $pdf->Line(208,91,125,91);

    $pdf->SetFont("Arial","",10);
    $pdf->SetTextColor(0,0,0);
    $pdf->SetXY(125,80.5);
    $pdf->MultiCell(80,5,utf8_decode($direccion),0,'L',false);
    $pdf->SetFont("Arial","b",10);
    $pdf->SetTextColor(0,88,147);

    $pdf->SetXY(100,95);
    $pdf->SetFont("Arial","",9);
    $pdf->Cell(50,5,utf8_decode('Intervalo máximo de calibración recomendado: __________ meses.'),0,0,'L');

    $pdf->SetFont("Arial","",10);
    $pdf->SetTextColor(0,0,0);
    $pdf->SetXY(174,94.5);
    $pdf->Cell (5,5,utf8_decode($intervalo_calibracion),0,1,'C');
    $pdf->SetFont("Arial","b",10);
    $pdf->SetTextColor(0,88,147);

    $pdf->SetXY(100,100);
    $pdf->SetFont("Arial","",7);
    $pdf->Cell(50,5,utf8_decode('(A partir de la fecha de calibración comienza el plazo para la fecha del intervalo recomendado).'),0,0,'L');


    /************************
    Condiciones | Página Uno
    ************************/
    // Izquierda
    $pdf->SetFont("Arial","b",10);
    $pdf->SetXY(10,115);
    $pdf->Cell(15,5,utf8_decode('Condición física al recibir:'),0,0,'L');

    $pdf->SetDrawColor(0,88,147);
    $pdf->SetXY(12,129);
    $pdf->Cell(3,3,'',1,1,'C',true);

    $pdf->SetFont("Arial","",9);
    $pdf->SetXY(16,128.5);
    $pdf->Cell(30,5,utf8_decode($condicion_recepcion),0,0,'L');

    // Centro
    $pdf->SetFont("Arial","b",10);
    $pdf->SetXY(88,115);
    $pdf->MultiCell(45,5,utf8_decode('Condición de calibración encontrada:'),0,'L',false);

    $pdf->SetDrawColor(0,88,147);
    $pdf->SetXY(90,129);
    $pdf->Cell(3,3,'',1,1,'C',true);

    $pdf->SetFont("Arial","",9);
    $pdf->SetXY(95,128.5);
    $pdf->Cell(30,5,utf8_decode($condicion_calibracion),0,0,'L');

    //Derecha
    $pdf->SetFont("Arial","b",10);
    $pdf->SetXY(160,115);
    $pdf->MultiCell(45,5,utf8_decode('Condición de calibración final:'),0,'L',false);

    $pdf->SetDrawColor(0,88,147);
    $pdf->SetXY(162,129);
    $pdf->Cell(3,3,'',1,1,'C',true);

    $pdf->SetFont("Arial","",9);
    $pdf->SetXY(166,128.5);
    $pdf->MultiCell(30,5,utf8_decode($condicion_calibracion_final),0,'L',false);


    /************************
    Comentarios | Página Uno
    ************************/
    $pdf->SetFont("Arial","b",10);
    $pdf->SetXY(10,145);
    $pdf->Cell(15,5,utf8_decode('Comentarios:'),0,0,'L');
    
    // Líneas de comentarios
    $pdf->Line(205,150,35,150);
    $pdf->Line(205,155,10,155);
    $pdf->Line(205,160,10,160);

    $pdf->SetFont("Arial","",10);
    $pdf->SetTextColor(0,0,0);
    $pdf->SetXY(35,145);
    $comentarios = ucfirst($comentarios);
    $comentarios = substr($comentarios, 0, 300);
    $pdf->MultiCell(170,5,utf8_decode($comentarios),0,'L',false);
    $pdf->SetFont("Arial","b",10);
    $pdf->SetTextColor(0,88,147);   


    /*********************
    Información Página Uno
    *********************/
    $pdf->SetXY(10,170);
    $pdf->SetFont("Arial","",10);
    $pdf->MultiCell(195,5,utf8_decode('PARÁMETROS DE CALIBRACIÓN: La potencia del láser y ruido máximo se registran solo con fines de referencia. El flujo de aire es un parámetro crítico durante la calibración, ya que establece el volumen de muestra nominal y la velocidad de las partículas, lo cual afecta el dimensionamiento de las mismas. Debido a que las variaciones de flujo después de la calibración afectan el dimensionamiento y el volumen de la muestra de forma inversa, las variaciones de hasta 10% tienen un efecto insignificante en los conteos registrados. Las amplitudes de respuesta de partículas corresponden a los umbrales de detección. Las amplitudes superiores a los umbrales darán como resultado recuentos mayores de lo normal. Las amplitudes por debajo de los umbrales darán lugar a un conteo insuficiente. El resultado asentado en este certificado de calibración solo es aplicable a la unidad referenciada en este documento.'),0,'J',false);
    $pdf->SetX(10);
    $pdf->Cell(15,5,utf8_decode('Calibración realizada por:'),0,0,'L');

    /******************************
    Firmas del reporte | Página Uno
    ******************************/
    // Firma Técnico
    $pdf->Line(23,235,80,235);
    $pdf->SetXY(44,236);
    $pdf->Cell(15,5,utf8_decode('Nombre del Técnico Certificado'),0,0,'C');

    $pdf->SetFont("Arial","",10);
    $pdf->SetTextColor(0,0,0);
    $pdf->SetXY(50,230);
    $pdf->Cell (5,5,utf8_decode($tecnico),0,1,'C');
    $pdf->SetTextColor(0,88,147);

    // Firma Cliente
    $pdf->Line(190,235,130,235);
    $pdf->SetXY(153,236);
    $pdf->Cell(15,5,utf8_decode('Firma'),0,0,'C');


    /*****************************************************************************************************************************
    PÁGINA DOS
    *****************************************************************************************************************************/
    // Se agrega nueva página
    $pdf->AddPage();

    /********************************************************
    Cabecera con información de dirección y logo | Página Dos
    ********************************************************/
    $pdf->Line(20,22,35,22);
    $pdf->SetFont("Arial","",8);
    $pdf->SetXY(50,10);
    $pdf->MultiCell(32,5,utf8_decode('www.dvi.mx servicio@dvi.mx'),0,'C',false);
    $pdf->SetX(40);
    $pdf->MultiCell(50,5,utf8_decode('Tel.(55)5688-3566 / 3977 - Ext. 402 01800 8326 - 345'),0,'C',false);
    $pdf->Line(95,22,110,22);
    
    $pdf->Image('../../assets/img/dvi.png', 125, 11, 75); // Logo

    $pdf->SetTextColor(0,88,147);
    $pdf->SetFont("Arial","b",24);
    $pdf->SetXY(10,37);
    $pdf->Cell(0,10,utf8_decode('CERTIFICADO DE CALIBRACIÓN'),0,0,'C');
    $pdf->SetFont("Arial","b",12);
    $pdf->SetXY(10,45);
    $pdf->Cell(0,10,utf8_decode('Datos Prueba de Calibración'),0,0,'C');

    $pdf->SetFont("Arial","",8);
    $pdf->SetXY(30,55);
    $pdf->Cell(15,10,utf8_decode('Modelo CI- _________________'),0,0,'C');

    $pdf->SetFont("Arial","",9);
    $pdf->SetTextColor(0,0,0);
    $pdf->SetXY(40,55);
    $pdf->Cell (5,5,utf8_decode($modelo_contador),0,0,'C');
    $pdf->SetFont("Arial","",8);
    $pdf->SetTextColor(0,88,147);

    $pdf->SetX(95);
    $pdf->Cell(15,10,utf8_decode('Contador de partículas, Núm. Serie: ______________________'),0,0,'C');

    $pdf->SetFont("Arial","",9);
    $pdf->SetTextColor(0,0,0);
    $pdf->SetXY(115,55);
    $pdf->Cell (5,5,utf8_decode($numero_serie),0,0,'C');
    $pdf->SetFont("Arial","",8);
    $pdf->SetTextColor(0,88,147);   

    $pdf->SetX(165);
    $pdf->Cell(15,10,utf8_decode('Fecha de calibración: __________________'),0,0,'C');

    $pdf->SetFont("Arial","",9);
    $pdf->SetTextColor(0,0,0);
    $pdf->SetXY(180,55);
    $pdf->Cell (5,5,utf8_decode($fecha_calibracion),0,0,'C');
    $pdf->SetFont("Arial","",8);
    $pdf->SetTextColor(0,88,147);

    $pdf->SetXY(33,65);
    $pdf->Cell(15,10,utf8_decode('Identificación de Cliente: __________'),0,0,'C');

    $pdf->SetFont("Arial","",9);
    $pdf->SetTextColor(0,0,0);
    $pdf->SetXY(54,65);
    $pdf->Cell (5,5,utf8_decode($identificacion_cliente),0,0,'C');
    $pdf->SetFont("Arial","",8);
    $pdf->SetTextColor(0,88,147);

    $pdf->SetX(97);
    $pdf->Cell(15,10,utf8_decode('Técnico: ________________________________________'),0,0,'C');

    $pdf->SetFont("Arial","",9);
    $pdf->SetTextColor(0,0,0);
    $pdf->SetXY(100,65);
    $pdf->Cell (5,5,utf8_decode($tecnico),0,0,'C');
    $pdf->SetFont("Arial","",8);
    $pdf->SetTextColor(0,88,147);

    $pdf->SetX(165);
    $pdf->Cell(15,10,utf8_decode('ID del Documento: __________________'),0,0,'C');

    $pdf->SetFont("Arial","",9);
    $pdf->SetTextColor(0,0,0);
    $pdf->SetXY(180,65);
    $pdf->Cell (5,5,utf8_decode($id_documento),0,0,'C');
    $pdf->SetFont("Arial","",8);
    $pdf->SetTextColor(0,88,147);

    $pdf->SetLineWidth(0.5);
    $pdf->Rect(10,35,195,40); // Rectángulo de 19.5 cm x 4.0 cm

    $pdf->SetFont("Arial","",9);
    $pdf->SetXY(40,75);
    $pdf->Cell(15,10,utf8_decode('Presión Barométrica: ________________'),0,0,'C');

    $pdf->SetFont("Arial","",10);
    $pdf->SetTextColor(0,0,0);
    $pdf->SetXY(55,75);
    $pdf->Cell (5,5,utf8_decode($presion_barometrica.' KPa'),0,0,'C');
    $pdf->SetFont("Arial","",9);
    $pdf->SetTextColor(0,88,147);

    $pdf->SetX(100);
    $pdf->Cell(15,10,utf8_decode('Temperatura: ________________'),0,0,'C');

    $pdf->SetFont("Arial","",10);
    $pdf->SetTextColor(0,0,0);
    $pdf->SetXY(115,75);
    $pdf->Cell (5,5,utf8_decode($temperatura.' °C'),0,0,'C');
    $pdf->SetFont("Arial","",9);
    $pdf->SetTextColor(0,88,147);

    $pdf->SetX(160);
    $pdf->Cell(15,10,utf8_decode('Humedad Relativa: _________________'),0,0,'C');

    $pdf->SetFont("Arial","",10);
    $pdf->SetTextColor(0,0,0);
    $pdf->SetXY(175,75);
    $pdf->Cell (5,5,utf8_decode($humedad_relativa.' % HR'),0,0,'C');
    $pdf->SetTextColor(0,88,147);

    $pdf->SetFont("Arial","",10);
    $pdf->SetXY(17,85);
    $pdf->MultiCell(160,5,utf8_decode('Controles ambientales: La temperatura ambiental durante la calibración debe de encontrarse entre 18° - 26.7°C, la humedad relativa no afecta el proceso de calibración.'),0,'J',false);

    /*******************************
    TABLA DE MEDICIONES ELECTRÓNICAS
    *******************************/
    $pdf->SetFont("Arial","b",12);
    $pdf->SetXY(10,95);
    $pdf->Cell(0,10,utf8_decode('Mediciones Electrónicas'),0,0,'C');

    // ENCABEZADOS
    $pdf->SetFont("Arial","",9);
    $pdf->SetTextColor(255,255,255);
    $pdf->SetXY(10,105);
    $pdf->Cell(35,5,utf8_decode('PRUEBA'),0,0,'C',true);
    $pdf->Cell(20,5,utf8_decode('ESPERADO'),0,0,'C',true);
    $pdf->Cell(30,5,utf8_decode('TOLERANCIA'),0,0,'C',true);
    $pdf->Cell(45,5,utf8_decode('CONDICIÓN ENCONTRADA'),0,0,'C',true);
    $pdf->Cell(20,5,utf8_decode('PASA'),0,0,'C',true);
    $pdf->Cell(45,5,utf8_decode('CONDICIÓN FINAL'),0,0,'C',true);

    // VOLTAJE LÁSER
    $pdf->SetFont("Arial","",8);
    $pdf->SetLineWidth(0);
    $pdf->SetTextColor(0,88,147);
    $pdf->SetXY(10,110);
    $pdf->Cell(35,5,utf8_decode('VOLTAJE DE LÁSER'),1,0,'L');
    $pdf->SetTextColor(0,0,0);
    $pdf->Cell(20,5,utf8_decode($vl_esperado.' Vdc±'),1,0,'C');
    $pdf->Cell(30,5,utf8_decode('(Valor de referencia)'),1,0,'C');
    $pdf->Cell(45,5,utf8_decode($vl_condicion_encontrada.' Vdc'),1,0,'C');
    $pdf->Cell(20,5,utf8_decode($vl_pasa),1,0,'C');
    $pdf->Cell(45,5,utf8_decode($vl_condicion_final.' Vdc'),1,0,'C');

    // FLUJO DE AIRE
    $pdf->SetXY(10,115);
    $pdf->SetTextColor(0,88,147);
    $pdf->Cell(35,5,utf8_decode('FLUJO DE AIRE'),1,0,'L');
    $pdf->SetTextColor(0,0,0);
    $pdf->Cell(20,5,utf8_decode($fa_esperado.' LPM'),1,0,'C');
    $pdf->Cell(30,5,utf8_decode('± 1.4 LPM'),1,0,'R');
    $pdf->Cell(45,5,utf8_decode($fa_condicion_encontrada.' LPM*'),1,0,'C');
    $pdf->Cell(20,5,utf8_decode($fa_pasa),1,0,'C');
    $pdf->Cell(45,5,utf8_decode($fa_condicion_final.' LPM*'),1,0,'C');

    // RUIDO MÁXIMO
    $pdf->SetXY(10,120);
    $pdf->SetTextColor(0,88,147);
    $pdf->Cell(35,5,utf8_decode('RUIDO MÁXIMO'),1,0,'L');
    $pdf->SetTextColor(0,0,0);
    $pdf->Cell(20,5,utf8_decode('< 200 mV'),1,0,'C');
    $pdf->Cell(30,5,utf8_decode('(Valor de referencia)'),1,0,'C');
    $pdf->Cell(45,5,utf8_decode($rm_condicion_encontrada.' mV'),1,0,'C');
    $pdf->Cell(20,5,utf8_decode($rm_pasa),1,0,'C');
    $pdf->Cell(45,5,utf8_decode($rm_condicion_final.' mV'),1,0,'C');

    // Información restante
    $pdf->SetFont("Arial","",7);
    $pdf->SetTextColor(0,88,147);
    $pdf->SetXY(10,125);
    $pdf->MultiCell(70,5,utf8_decode('± Valor inicial; el voltaje aumenta a medida que el diodo láser se desgasta.'),0,'L',false);
    $pdf->SetXY(123,125);
    $pdf->Cell(45,5,utf8_decode('* Las lecturas del medidor de flujo volumétrico y reflejan una compensación correctiva de: __________ LPM'),0,0,'C');

    $pdf->SetFont("Arial","",9);
    $pdf->SetTextColor(0,0,0);
    $pdf->SetXY(189,125);
    $pdf->Cell (5,5,utf8_decode($flujo_volumetrico),0,0,'C');
    $pdf->SetFont("Arial","",8);
    $pdf->SetTextColor(0,88,147);

    /**********************
    TABLA DE COMPORTAMIENTO
    **********************/
    $pdf->SetFont("Arial","b",12);
    $pdf->SetXY(10,130);
    $pdf->Cell(0,10,utf8_decode('Comportamiento'),0,0,'C');

    // ENCABEZADOS
    $pdf->SetFont("Arial","",9);
    $pdf->SetTextColor(255,255,255);
    $pdf->SetXY(10,140);
    $pdf->Cell(75,5,utf8_decode('TAMAÑO DE PARTÍCULA NOMINAL'),0,0,'C',true);
    $pdf->Cell(30,5,utf8_decode('0.5 µm'),0,0,'C',true);
    $pdf->Cell(30,5,utf8_decode('1.0 µm'),0,0,'C',true);
    $pdf->Cell(30,5,utf8_decode('3.0 µm'),0,0,'C',true);
    $pdf->Cell(30,5,utf8_decode('5.0 µm'),0,0,'C',true);

    // AMPLITUD ESPERADA
    $pdf->SetFont("Arial","",8);
    $pdf->SetTextColor(0,88,147);
    $pdf->SetXY(10,145);
    $pdf->Cell(75,5,utf8_decode('AMPLITUD ESPERADA (desde la última calibración)'),1,0,'L');
    $pdf->SetTextColor(0,0,0);
    $pdf->Cell(30,5,utf8_decode($amplitud_esperada_05_100.' mV'),1,0,'C');
    $pdf->Cell(30,5,utf8_decode($amplitud_esperada_10_100.' mV'),1,0,'C');
    $pdf->Cell(30,5,utf8_decode($amplitud_esperada_30_100.' V'),1,0,'C');
    $pdf->Cell(30,5,utf8_decode($amplitud_esperada_50_100.' mV'),1,0,'C');

    // TOLERANCIA
    $pdf->SetXY(10,150);
    $pdf->SetTextColor(0,88,147);
    $pdf->Cell(75,5,utf8_decode('TOLERANCIA'),1,0,'L');
    $pdf->Cell(30,5,utf8_decode('± 60 mV'),1,0,'R');
    $pdf->Cell(30,5,utf8_decode('± 30 mV'),1,0,'R');
    $pdf->Cell(30,5,utf8_decode('± 150 mV'),1,0,'R');
    $pdf->Cell(30,5,utf8_decode('± 50 mV'),1,0,'R');

    // COMO SE ENCUENTRA
    $pdf->SetXY(10,155);
    $pdf->Cell(75,5,utf8_decode('COMO SE ENCUENTRA'),1,0,'L');
    $pdf->SetTextColor(0,0,0);
    $pdf->Cell(30,5,utf8_decode($como_encuentra_05_100.' mV'),1,0,'C');
    $pdf->Cell(30,5,utf8_decode($como_encuentra_10_100.' mV'),1,0,'C');
    $pdf->Cell(30,5,utf8_decode($como_encuentra_30_100.' V'),1,0,'C');
    $pdf->Cell(30,5,utf8_decode($como_encuentra_50_100.' mV'),1,0,'C');

    // PASA (S/N)
    $pdf->SetXY(10,160);
    $pdf->SetTextColor(0,88,147);
    $pdf->Cell(75,5,utf8_decode('PASA (S/N)'),1,0,'L');
    $pdf->SetTextColor(0,0,0);
    $pdf->Cell(30,5,utf8_decode($pasa_05_100),1,0,'C');
    $pdf->Cell(30,5,utf8_decode($pasa_10_100),1,0,'C');
    $pdf->Cell(30,5,utf8_decode($pasa_30_100),1,0,'C');
    $pdf->Cell(30,5,utf8_decode($pasa_50_100),1,0,'C');

    // CONDICIÓN FINAL
    $pdf->SetXY(10,165);
    $pdf->SetTextColor(0,88,147);
    $pdf->Cell(75,5,utf8_decode('CONDICIÓN FINAL'),1,0,'L');
    $pdf->SetTextColor(0,0,0);
    $pdf->Cell(30,5,utf8_decode($condicion_final_05_100.' mV'),1,0,'C');
    $pdf->Cell(30,5,utf8_decode($condicion_final_10_100.' mV'),1,0,'C');
    $pdf->Cell(30,5,utf8_decode($condicion_final_30_100.' V'),1,0,'C');
    $pdf->Cell(30,5,utf8_decode($condicion_final_50_100.' mV'),1,0,'C');

    /*********************
    Información Página Dos
    *********************/
    // INCERTIDUMBRE COLECTIVA DE LA MEDICIÓN
    $pdf->SetFont("Arial","b",9);
    $pdf->SetTextColor(0,88,147);
    $pdf->SetXY(45,170);
    $pdf->Cell(15,5,utf8_decode('INCERTIDUMBRE COLECTIVA DE LA MEDICIÓN:'),0,0,'C');
    $pdf->SetFont("Arial","",9);
    $pdf->SetXY(135,170);
    $pdf->Cell(15,5,utf8_decode('± 1% del flujo, ± 1% de las partículas de 0.3 µm, 0,5 µm, 10 µm y 0,5 µm'),0,0,'C');
    $pdf->SetXY(15,175);
    $pdf->MultiCell(185,5,utf8_decode('La incertidumbre colectiva se basa en las contribuciones del Analizador de Alturas de Pulso, el Medidor de Flujo de masa y la opinión del técnico al establecer la mediana de la distribución mostrada, según lo determinado por las pruebas empíricas y el cálculo de incertidumbre de 1 sigma.'),0,'J',false);

    // RELACIÓN DE PRECISIÓN
    $pdf->SetFont("Arial","b",9);
    $pdf->SetXY(29,195);
    $pdf->Cell(15,5,utf8_decode('RELACIÓN DE PRECISIÓN:'),0,0,'C');
    $pdf->SetFont("Arial","",9);
    $pdf->SetXY(119,195);
    $pdf->Cell(15,5,utf8_decode('La incertidumbre colectiva de los estándares de medición es inferior al 25% de la tolerancia enu-'),0,0,'C');
    $pdf->SetXY(29,200);
    $pdf->Cell(23,5,utf8_decode('merada (relación de medición 4: 1)'),0,0,'C');

    // TOLERANCIAS DE CALIBRACIÓN
    $pdf->SetFont("Arial","b",9);
    $pdf->SetXY(34,210);
    $pdf->Cell(15,5,utf8_decode('TOLERANCIAS DE CALIBRACIÓN:'),0,0,'C');
    $pdf->SetFont("Arial","",9);
    $pdf->SetXY(123,210);
    $pdf->Cell(15,5,utf8_decode('El tamaño de partícula enumerado es nominal; consulte el registro de equipamiento de'),0,0,'C');
    $pdf->SetXY(15,215);
    $pdf->MultiCell(185,5,utf8_decode('prueba para los tamaños reales. Los voltajes de tolerancia listados representan un error de dimensionamiento del 2% y la desviación de partículas del tamaño. Si la respuesta de la partícula está por debajo de la tolerancia para la amplitud esperada, la partícula estará subdimensionada, lo que dará como resultado conteos menores que lo normal. Si la respuesta de la partícula está por encima de la tolerancia esperada, la partícula será más grande de lo que realmente es, lo que resulta en recuentos que son mayores de lo que realmente deberían ser. Los recuentos reales no se pueden extrapolar a partir de los recuentos fuera de tolerancia. Los sensores de temperatura y humedad, si están presentes, son para referencia y no son parte de la calibración.'),0,'J',false);

    // Información complementaria
    $pdf->SetXY(84,248);
    $pdf->Cell(15,5,utf8_decode('PROCEDIMIENTO DE CALIBRACIÓN: 92045102 Procedimiento de calibración estándar, x5x serie portátil.'),0,0,'C');

    /*****************************************************************************************************************************
    PÁGINA TRES
    *****************************************************************************************************************************/
    // Se agrega nueva página
    $pdf->AddPage();

    /*********************************************************
    Cabecera con información de dirección y logo | Página Tres
    *********************************************************/
    $pdf->Line(20,22,35,22);
    $pdf->SetFont("Arial","",8);
    $pdf->SetXY(50,10);
    $pdf->MultiCell(32,5,utf8_decode('www.dvi.mx servicio@dvi.mx'),0,'C',false);
    $pdf->SetX(40);
    $pdf->MultiCell(50,5,utf8_decode('Tel.(55)5688-3566 / 3977 - Ext. 402 01800 8326 - 345'),0,'C',false);
    $pdf->Line(95,22,110,22);
    
    $pdf->Image('../../assets/img/dvi.png', 125, 11, 75); // Logo

    /************************
    ESTÁNDARD DE TRAZABILIDAD
    ************************/
    // Figura para el sello
    $pdf->SetDrawColor(0,88,147);
    //         X  Y   l  A     l: largo, A: Ancho
    $pdf->Rect(13,35,195,145); // Rectángulo de 7.5 cm x 4.5 cm


    $pdf->SetFont("Arial","b",24);
    $pdf->SetXY(10,37);
    $pdf->Cell(0,10,utf8_decode('CERTIFICADO DE CALIBRACIÓN'),0,0,'C');
    $pdf->SetFont("Arial","b",11);
    $pdf->SetXY(10,45);
    $pdf->Cell(0,10,utf8_decode('Estándard de Trazabilidad'),0,0,'C');
    $pdf->SetFont("Arial","",8);
    $pdf->SetXY(75,50);
    $pdf->Cell(15,10,utf8_decode('Fecha del documento: __________'),0,0,'C');
    $pdf->SetX(120);
    $pdf->Cell(15,10,utf8_decode('ID del documento: __________'),0,0,'C');

    $pdf->SetFont("Arial","",9);
    $pdf->SetTextColor(0,0,0);
    $pdf->SetXY(95,52);
    $pdf->Cell (5,5,utf8_decode($fecha_documento),0,0,'C');
    $pdf->SetXY(137,52);
    $pdf->Cell (5,5,utf8_decode($id_documento),0,0,'C');
    $pdf->SetTextColor(0,88,147);


    // DECLARACIÓN DE TRAZABILIDAD | LADO IZQUIERDO
    $pdf->SetXY(50,58);
    $pdf->SetFont("Arial","b",9);
    $pdf->Cell(15,10,utf8_decode('DECLARACIÓN DE TRAZABILIDAD'),0,0,'C');

    $pdf->SetFont("Arial","",8);
    $pdf->SetXY(20,65);
    $pdf->MultiCell(80,5,utf8_decode('Este instrumento ha sido calibrado de acuerdo con ISO 10012-1 y ANSI Z540-1. La calibración cumple con los estándares internos que cumplen o superan los requisitos de ISO 21501-4.'),0,'J',false);
    $pdf->SetXY(20,85);
    $pdf->MultiCell(80,5,utf8_decode('La temperatura y la humedad relativa no se controlan durante la calibración debido al amplio rango de operación del instrumento. (Temperatura: 30ºF a 122ºF; Humedad: 0-100%, sin condensación)'),0,'J',false);
    $pdf->SetXY(20,105);
    $pdf->MultiCell(80,5,utf8_decode('Todo el equipo de prueba utilizado en la calibración de los productos de Climet Instruments se calibra a intervalos recomendados por el fabricante mediante un servicio de calibración externo aprobado. Los certificados de calibración para cada pieza del equipo de prueba se encuentran archivados en De Vecchi Ingenieros; se proporcionarán copias si se solicita.'),0,'J',false);
    $pdf->SetXY(20,140);
    $pdf->MultiCell(80,5,utf8_decode('Rastreabilidad de calibración a estándares de medición (NIST). La respuesta de partículas se establece desafiando al sensor con esferas de látex monodispersas de tamaño estándar. Estas esferas se dimensionan según los métodos trazables, por número de lote, al NIST.'),0,'J',false);
    $pdf->SetXY(20,165);
    $pdf->MultiCell(80,5,utf8_decode('Los instrumentos y los estándares de referencia que se enumeran a continuación se usaron para calibrar el instrumento certificado por este documento.'),0,'J',false);
    

    // MÉTODO DE CALIBRACIÓN | LADO DERECHO
    $pdf->SetXY(145,58);
    $pdf->SetFont("Arial","b",9);
    $pdf->Cell(15,10,utf8_decode('MÉTODO DE CALIBRACIÓN'),0,0,'C');

    $pdf->SetFont("Arial","",8);
    $pdf->SetXY(110,65);
    $pdf->MultiCell(87,5,utf8_decode('Los contadores de partículas Climet se calibran usando uno o más tamaños de esferas de látex de poliestireno, que sirven como estándar para comparar y ajustar la respuesta del amplificador a los umbrales de detección a tamaños de partículas conocidos. Las partículas se introducen al sensor en una muestra de aerosol con una concentración moderada. El voltímetro digital se usa para hacer mediciones de referencia. El flujo se mide utilizando un medidor de flujo de masa apropiado para la velocidad de flujo.'),0,'J',false);
    $pdf->SetXY(110,110);
    $pdf->MultiCell(87,5,utf8_decode('Un Analizador de Altura de Pulso interno o externo (PHA) mide la respuesta del sensor a las partículas de desafío. El PHA muestra un histograma de la distribución de partículas que se usa para determinar la mediana de la distribución de pulsos.'),0,'J',false);
    $pdf->SetXY(110,135);
    $pdf->MultiCell(87,5,utf8_decode('La medición de la distribución se utiliza para ajustar los valoresdel amplificador, según sea necesario, para que coincida con el umbral o el umbral se ajusta a la distribución de la mediana.'),0,'J',false);
    $pdf->SetXY(110,155);
    $pdf->MultiCell(87,5,utf8_decode('La precisión del recuento se verifica durante la calibración inicial de fábrica mediante la prueba de eficiencia de conteo. La prueba de eficiencia del conteo se realiza por comparación de conteo utilizando el sistema CNC / DMA, una referencia principal, como referencia de conteo del 100%.'),0,'J',false);

    /**********************************
    TABLA DEL ESTÁNDARD DE TRAZABILIDAD
    **********************************/
    // ENCABEZADOS
    $pdf->SetFont("Arial","",9);
    $pdf->SetTextColor(255,255,255);
    $pdf->SetXY(13,180);
    $pdf->Cell(40,5,utf8_decode('INSTRUMENTO'),0,0,'C',true);
    $pdf->Cell(20,5,utf8_decode('ACTIVO'),0,0,'C',true);
    $pdf->Cell(20,5,utf8_decode('MODELO'),0,0,'C',true);
    $pdf->Cell(35,5,utf8_decode('NO. DE SERIE'),0,0,'C',true);
    $pdf->Cell(30,5,utf8_decode('CONTROL No.'),0,0,'C',true);
    $pdf->Cell(25,5,utf8_decode('FECHA CAL'),0,0,'C',true);
    $pdf->Cell(25,5,utf8_decode('PROX. CAL.'),0,0,'C',true);

    // DMM
    $pdf->SetFont("Arial","",8);
    $pdf->SetTextColor(0,88,147);
    $pdf->SetXY(13,185);
    $pdf->Cell(40,5,utf8_decode('DMM'),1,0,'L');
    $pdf->SetTextColor(0,0,0);
    $pdf->Cell(20,5,utf8_decode($dmm_activo),1,0,'C');
    $pdf->Cell(20,5,utf8_decode($dmm_modelo),1,0,'C');
    $pdf->Cell(35,5,utf8_decode($dmm_numero_serie),1,0,'C');
    $pdf->Cell(30,5,utf8_decode($dmm_numero_control),1,0,'C');
    $pdf->Cell(25,5,utf8_decode($dmm_fecha_calibracion),1,0,'C');
    $pdf->Cell(25,5,utf8_decode($dmm_proxima_calibracion),1,0,'C');

    // PHA
    $pdf->SetXY(13,190);
    $pdf->SetTextColor(0,88,147);
    $pdf->Cell(40,5,utf8_decode('PHA'),1,0,'L');
    $pdf->SetTextColor(0,0,0);
    $pdf->Cell(20,5,utf8_decode($pha_activo),1,0,'C');
    $pdf->Cell(20,5,utf8_decode($pha_modelo),1,0,'C');
    $pdf->Cell(35,5,utf8_decode($pha_numero_serie),1,0,'C');
    $pdf->Cell(30,5,utf8_decode($pha_numero_control),1,0,'C');
    $pdf->Cell(25,5,utf8_decode($pha_fecha_calibracion),1,0,'C');
    $pdf->Cell(25,5,utf8_decode($pha_proxima_calibracion),1,0,'C');

    // Medidor de flujo de masa
    $pdf->SetXY(13,195);
     $pdf->SetTextColor(0,88,147);
    $pdf->Cell(40,5,utf8_decode('Medidor de flujo de masa'),1,0,'L');
    $pdf->SetTextColor(0,0,0);
    $pdf->Cell(20,5,utf8_decode($mfm_activo),1,0,'C');
    $pdf->Cell(20,5,utf8_decode($mfm_modelo),1,0,'C');
    $pdf->Cell(35,5,utf8_decode($mfm_numero_serie),1,0,'C');
    $pdf->Cell(30,5,utf8_decode($mfm_numero_control),1,0,'C');
    $pdf->Cell(25,5,utf8_decode($mfm_fecha_calibracion),1,0,'C');
    $pdf->Cell(25,5,utf8_decode($mfm_proxima_calibracion),1,0,'C');

    // RH/TEMP SENSOR
    $pdf->SetXY(13,200);
     $pdf->SetTextColor(0,88,147);
    $pdf->Cell(40,5,utf8_decode('RH/TEMP SENSOR'),1,0,'L');
    $pdf->SetTextColor(0,0,0);
    $pdf->Cell(20,5,utf8_decode($rh_activo),1,0,'C');
    $pdf->Cell(20,5,utf8_decode($rh_modelo),1,0,'C');
    $pdf->Cell(35,5,utf8_decode($rh_numero_serie),1,0,'C');
    $pdf->SetFont("Arial","",6);
    $pdf->Cell(30,5,utf8_decode($rh_numero_control),1,0,'C');
    $pdf->Cell(25,5,utf8_decode($rh_fecha_calibracion),1,0,'C');
    $pdf->Cell(25,5,utf8_decode($rh_proxima_calibracion),1,0,'C');

    // Balómetro
    $pdf->SetFont("Arial","",8);
    $pdf->SetXY(13,205);
     $pdf->SetTextColor(0,88,147);
    $pdf->Cell(40,5,utf8_decode('Balómetro'),1,0,'L');
    $pdf->SetTextColor(0,0,0);
    $pdf->Cell(20,5,utf8_decode($balometro_activo),1,0,'C');
    $pdf->Cell(20,5,utf8_decode($balometro_modelo),1,0,'C');
    $pdf->Cell(35,5,utf8_decode($balometro_numero_serie),1,0,'C');
    $pdf->Cell(30,5,utf8_decode($balometro_numero_control),1,0,'C');
    $pdf->Cell(25,5,utf8_decode($balometro_fecha_calibracion),1,0,'C');
    $pdf->Cell(25,5,utf8_decode($balometro_proxima_calibracion),1,0,'C');

     /***************************
    TABLA DE PARTÍCULAS ESTÁNDAR
    ***************************/
    $pdf->SetXY(10,208);
    $pdf->SetTextColor(0,88,147);
    $pdf->SetFont("Arial","b",11);
    $pdf->Cell(0,10,utf8_decode('PARTÍCULAS ESTÁNDAR'),0,0,'C');

    /******
    TABLA 1
    ******/
    // ENCABEZADOS
    $pdf->SetFont("Arial","",9);
    $pdf->SetTextColor(255,255,255);
    $pdf->SetXY(13,215);
    $pdf->MultiCell(17,5,utf8_decode('MEDIDA NOMINAL'),0,'C',true);
    $pdf->SetXY(30,215);
    $pdf->MultiCell(23,5,utf8_decode('TAMAÑO REAL'),0,'C',true);
    $pdf->SetXY(53,215);
    $pdf->MultiCell(25,5,utf8_decode('DESVIACIÓN DE TAMAÑO'),0,'C',true);
    $pdf->SetXY(78,215);
    $pdf->MultiCell(16,5,utf8_decode('No. LOTE'),0,'C',true);
    $pdf->SetXY(94,215);
    $pdf->MultiCell(17,5,utf8_decode('EXP. FECHA'),0,'C',true);

    // 0.3 µm
    $pdf->SetTextColor(0,88,147);
    $pdf->SetXY(13,225);
    $pdf->Cell(17,5,utf8_decode('0.3 µm'),1,0,'C');
    $pdf->SetTextColor(0,0,0);
    $pdf->Cell(23,5,utf8_decode($tamano_real_03),1,0,'C');
    $pdf->Cell(25,5,utf8_decode('± '.$desviacion_tamano_03),1,0,'C');
    $pdf->Cell(16,5,utf8_decode($no_lote_03),1,0,'C');
    $pdf->Cell(17,5,utf8_decode($exp_fecha_03),1,0,'C');

    // 0.4 µm
    $pdf->SetXY(13,230);
    $pdf->SetTextColor(0,88,147);
    $pdf->Cell(17,5,utf8_decode('0.4 µm'),1,0,'C');
    $pdf->SetTextColor(0,0,0);
    $pdf->Cell(23,5,utf8_decode($tamano_real_04),1,0,'C');
    $pdf->Cell(25,5,utf8_decode('± '.$desviacion_tamano_04),1,0,'C');
    $pdf->Cell(16,5,utf8_decode($no_lote_04),1,0,'C');
    $pdf->Cell(17,5,utf8_decode($exp_fecha_04),1,0,'C');

    // 0.5 µm
    $pdf->SetXY(13,235);
    $pdf->SetTextColor(0,88,147);
    $pdf->Cell(17,5,utf8_decode('0.5 µm'),1,0,'C');
    $pdf->SetTextColor(0,0,0);
    $pdf->Cell(23,5,utf8_decode($tamano_real_05),1,0,'C');
    $pdf->Cell(25,5,utf8_decode('± '.$desviacion_tamano_05),1,0,'C');
    $pdf->Cell(16,5,utf8_decode($no_lote_05),1,0,'C');
    $pdf->Cell(17,5,utf8_decode($exp_fecha_05),1,0,'C');

    // 0.6 µm
    $pdf->SetXY(13,240);
    $pdf->SetTextColor(0,88,147);
    $pdf->Cell(17,5,utf8_decode('0.6 µm'),1,0,'C');
    $pdf->SetTextColor(0,0,0);
    $pdf->Cell(23,5,utf8_decode($tamano_real_06),1,0,'C');
    $pdf->Cell(25,5,utf8_decode('± '.$desviacion_tamano_06),1,0,'C');
    $pdf->Cell(16,5,utf8_decode($no_lote_06),1,0,'C');
    $pdf->Cell(17,5,utf8_decode($exp_fecha_06),1,0,'C');


    /******
    TABLA 2
    ******/
    // ENCABEZADOS
    $pdf->SetFont("Arial","",9);
    $pdf->SetTextColor(255,255,255);
    $pdf->SetXY(111,215);
    $pdf->MultiCell(17,5,utf8_decode('MEDIDA NOMINAL'),0,'C',true);
    $pdf->SetXY(128,215);
    $pdf->MultiCell(23,5,utf8_decode('TAMAÑO REAL'),0,'C',true);
    $pdf->SetXY(151,215);
    $pdf->MultiCell(25,5,utf8_decode('DESVIACIÓN DE TAMAÑO'),0,'C',true);
    $pdf->SetXY(176,215);
    $pdf->MultiCell(16,5,utf8_decode('No. LOTE'),0,'C',true);
    $pdf->SetXY(192,215);
    $pdf->MultiCell(17,5,utf8_decode('EXP. FECHA'),0,'C',true);

    // 0.8 µm
    $pdf->SetTextColor(0,88,147);
    $pdf->SetXY(111,225);
    $pdf->Cell(17,5,utf8_decode('0.8 µm'),1,0,'C');
    $pdf->SetTextColor(0,0,0);
    $pdf->Cell(23,5,utf8_decode($tamano_real_08),1,0,'C');
    $pdf->Cell(25,5,utf8_decode('± '.$desviacion_tamano_08),1,0,'C');
    $pdf->Cell(16,5,utf8_decode($no_lote_08),1,0,'C');
    $pdf->Cell(17,5,utf8_decode($exp_fecha_08),1,0,'C');

    // 1.0 µm
    $pdf->SetXY(111,230);
    $pdf->SetTextColor(0,88,147);
    $pdf->Cell(17,5,utf8_decode('1.0 µm'),1,0,'C');
    $pdf->SetTextColor(0,0,0);
    $pdf->Cell(23,5,utf8_decode($tamano_real_10),1,0,'C');
    $pdf->Cell(25,5,utf8_decode('± '.$desviacion_tamano_10),1,0,'C');
    $pdf->Cell(16,5,utf8_decode($no_lote_10),1,0,'C');
    $pdf->Cell(17,5,utf8_decode($exp_fecha_10),1,0,'C');

    // 3.0 µm
    $pdf->SetXY(111,235);
    $pdf->SetTextColor(0,88,147);
    $pdf->Cell(17,5,utf8_decode('3.0 µm'),1,0,'C');
    $pdf->SetTextColor(0,0,0);
    $pdf->Cell(23,5,utf8_decode($tamano_real_30),1,0,'C');
    $pdf->Cell(25,5,utf8_decode('± '.$desviacion_tamano_30),1,0,'C');
    $pdf->Cell(16,5,utf8_decode($no_lote_30),1,0,'C');
    $pdf->Cell(17,5,utf8_decode($exp_fecha_30),1,0,'C');

    // 5.0 µm
    $pdf->SetXY(111,240);
    $pdf->SetTextColor(0,88,147);
    $pdf->Cell(17,5,utf8_decode('5.0 µm'),1,0,'C');
    $pdf->SetTextColor(0,0,0);
    $pdf->Cell(23,5,utf8_decode($tamano_real_50),1,0,'C');
    $pdf->Cell(25,5,utf8_decode('± '.$desviacion_tamano_50),1,0,'C');
    $pdf->Cell(16,5,utf8_decode($no_lote_50),1,0,'C');
    $pdf->Cell(17,5,utf8_decode($exp_fecha_50),1,0,'C');

    // Información complementaria
     $pdf->SetFont("Arial","",7);
     $pdf->SetTextColor(0,88,147);
    $pdf->SetXY(102,246);
    $pdf->Cell(17,5,utf8_decode('(Los estándares de partículas utilizados en esta calibración son fabricados por Duke Scientific. Solo los tamaños enumerados con el Número de lote se usaron en la calibración).'),0,0,'C');

    /*****************************************************************************************************************************
    PÁGINA CUATRO
    *****************************************************************************************************************************/
    // Se agrega nueva página
    $pdf->AddPage();

    /***********************************************************
    Cabecera con información de dirección y logo | Página Cuatro
    ***********************************************************/
    $pdf->Line(20,22,35,22);
    $pdf->SetFont("Arial","",8);
    $pdf->SetXY(50,10);
    $pdf->MultiCell(32,5,utf8_decode('www.dvi.mx servicio@dvi.mx'),0,'C',false);
    $pdf->SetX(40);
    $pdf->MultiCell(50,5,utf8_decode('Tel.(55)5688-3566 / 3977 - Ext. 402 01800 8326 - 345'),0,'C',false);
    $pdf->Line(95,22,110,22);
    
    $pdf->Image('../../assets/img/dvi.png', 125, 11, 75); // Logo

    /*******************************************
    INFORMACIÓN COMPLEMENTARIA DE LA HOJA CUATRO
    *******************************************/
    $pdf->SetFont("Arial","",8);
    $pdf->SetXY(33,30);
    $pdf->Cell(15,10,utf8_decode('ID del documento: __________'),0,0,'C');

    $pdf->SetFont("Arial","",9);
    $pdf->SetTextColor(0,0,0);
    $pdf->SetXY(50,32);
    $pdf->Cell (5,5,utf8_decode($id_documento),0,0,'C');

    //Comprender los datos de calibración y prueba
    $pdf->SetFont("Arial","b",10);
    $pdf->SetTextColor(0,88,147);
    $pdf->SetXY(10,35);
    $pdf->Cell(0,10,utf8_decode('Comprender los datos de calibración y prueba'),0,0,'C');

    $pdf->SetFont("Arial","",9);
    $pdf->SetXY(20,42);
    $pdf->MultiCell(180,5,utf8_decode('Los contadores de partículas son instrumentos únicos que están calibrados de forma diferente a otros equipos de medición. Hay pruebas especiales realizadas durante la fabricación que aseguran la precisión del instrumento. Estas pruebas están definidas en ISO 21501-4. Para las calibraciones de intervalo (posteriores a la fabricación), De Vecchi Ingenieros ofrece una calibración estándar y, a una tasa más exigente, la calibración conforme a ISO 21501-4 que incluye la prueba adicional de resolución y conteo de eficiencia. A continuación hay una explicación de la metodología de calibración y las pruebas adicionales.'),0,'J',false);

    // COLUMNA IZQUIERDA
    // Flujo de aire
    $pdf->SetFont("Arial","b",10);
    $pdf->SetXY(24,73);
    $pdf->Cell(15,10,utf8_decode('Flujo de aire'),0,0,'C');

    $pdf->SetFont("Arial","",9);
    $pdf->SetXY(20,80);
    $pdf->MultiCell(90,5,utf8_decode('El flujo de aire se mide usando un medidor de flujo de masa. El flujo de aire determina el volumen muestreado y la velocidad de la partícula a medida que pasa a través del rayo láser. La señal producida por una partícula que pasa a través del rayo láser se integra, por lo que cuanto menor sea el flujo, mayor será la señal de pulso producida. Esto hace que el flujo sea un parámetro crítico durante la calibración, ya que afecta la amplitud de la señal, pero no tanto durante el muestreo, ya que compensa con eficacia los conteos perdidos debido a la disminución del volumen. La tolerancia es del 5%, según ISO 21501-4.'),0,'J',false);

    // Análisis de tamaño
    $pdf->SetFont("Arial","b",10);
    $pdf->SetXY(29,133);
    $pdf->Cell(15,10,utf8_decode('Análisis de tamaño'),0,0,'C');

    $pdf->SetFont("Arial","",9);
    $pdf->SetXY(20,140);
    $pdf->MultiCell(90,5,utf8_decode('El análisis de tamaño se realiza con un circuito interno de análisis de pulso de altura (PHA) o externamente con un PHA. Las partículas de forma y tamaño idénticos producen una distribución. La calibración consiste en determinar la mediana de la distribución utilizando una pantalla de histograma PHA, y hacer coincidir el umbral de detección para un tamaño de partícula dado con la distribución mediana.'),0,'J',false);

    // Tolerancias de respuesta de partículas
    $pdf->SetFont("Arial","b",10);
    $pdf->SetXY(45.5,175);
    $pdf->Cell(15,10,utf8_decode('Tolerancias de respuesta de partículas'),0,0,'C');

    $pdf->SetFont("Arial","",9);
    $pdf->SetXY(20,183);
    $pdf->MultiCell(90,5,utf8_decode('La tolerancia en ¨Condición Encontrada¨ para la respuesta de partículas es basado en un 10% de error de conteo en respuesta a las partículas de pruebas. El error de recuento es menor en el aire ambiente.'),0,'J',false);

    // Contar la precisión
    $pdf->SetFont("Arial","b",10);
    $pdf->SetXY(29,202);
    $pdf->Cell(15,10,utf8_decode('Contar la precisión'),0,0,'C');

    $pdf->SetFont("Arial","",9);
    $pdf->SetXY(20,210);
    $pdf->MultiCell(90,5,utf8_decode('La precisión de conteo de un contador de partículas se conoce como eficiencia de conteo. ISO 21501-4 define dos pruebas: 50% y 100%. La prueba del 50% demuestra la honestidad de la sensibilidad especificada (la partícula más pequeña detectada) del instrumento.'),0,'J',false);

    // COLUMNA DERECHA
    $pdf->SetXY(113,80);
    $pdf->MultiCell(90,5,utf8_decode('La tolerancia ISO es ± 20 puntos porcentuales; nuestra tolerancia de fabricación es de ± 10 puntos porcentuales. La prueba al 100% verifica la alineación de la columna de aire y el rayo láser. Una mala alineación da como resultado un conteo por debajo o posiblemente por encima del conteo. La tolerancia es de ± 10%. Climet utiliza un Contador de Núcleo de Condensación (CNC) y un Analizador de Movilidad Diferencial (DMA), que son estándares primarios, para establecer la eficiencia del recuento durante la fabricación. Las alineaciones del sensor de Climet están bloqueadas mecánicamente para evitar el desajuste de estas alineaciones. La eficiencia del conteo se realiza después de que el sensor ha sido calibrado.'),0,'J',false);

    // Prueba de resolución
    $pdf->SetFont("Arial","b",10);
    $pdf->SetXY(125,145);
    $pdf->Cell(15,10,utf8_decode('Prueba de resolución'),0,0,'C');

    $pdf->SetFont("Arial","",9);
    $pdf->SetXY(114,153);
    $pdf->MultiCell(90,5,utf8_decode('La resolución se basa en la curva de respuesta establecida por dos tamaños de partículas durante la calibración. El cálculo se basa en IEST-RP-CC014: Calibración y Caracterización de Contadores de Partículas Ópticos en el Aire. La resolución afecta la precisión del tamaño.'),0,'J',false);

    // Controles de mantenimiento preventivo
    $pdf->SetFont("Arial","b",10);
    $pdf->SetXY(140,177);
    $pdf->Cell(15,10,utf8_decode('Controles de mantenimiento preventivo'),0,0,'C');

    $pdf->SetFont("Arial","",9);
    $pdf->SetXY(114,185);
    $pdf->MultiCell(90,5,utf8_decode('La salida del láser está regulada, por lo que incluso cuando el diodo láser se desgasta, la intensidad de la luz es constante. En algún momento después de un aumento del 20% en la corriente del láser, la luz del láser se saldrá de regulación. La corriente del variador láser se registra en el certificado de calibración, por lo que se puede monitorear para mantenimiento preventivo.'),0,'J',false);
    $pdf->SetXY(114,220);
    $pdf->MultiCell(90,5,utf8_decode('El ruido máximo se controla como un indicador de contaminación del sensor. Si bien el ruido no tiene un efecto inmediato en los conteos, es un importante indicador de mantenimiento preventivo, ya que puede ocasionar errores de calibración y un eventual hallazgo fuera de tolerancia.'),0,'J',false);

    $pdf->SetFont("Arial","",7);
    $pdf->SetXY(100,245);
    $pdf->Cell(15,10,utf8_decode('(Este prohibida la reproducirá parcial o total, sin la aprobación por escrito de De Vecchi Ingenieros.)'),0,0,'C');


    /***********************************************************************************
    Se indica el nombre del arcvhivo y los parámetros de exportación | Fin del documento
    ***********************************************************************************/
    $pdf->output('I',utf8_decode('FDV-S-032 Certificado de Calibración').'-'.$id_documento.'.pdf');

} else {
    header ('Location: ../../../index.php');
}
?>