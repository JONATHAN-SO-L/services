<?php
session_start();

if ($_SESSION['nombre'] != '' && $_SESSION['tipo'] == 'devecchi' || $_SESSION['tipo'] == 'admin') {
    // Se requiere la librería FPDF para el diseño del formato FDV-S-032
    require '../../../lib/fpdf/fpdf.php';

    // Clase herdada para la definición de funciones que se replicarán en todas las hojas
    class PDF extends FPDF {
        function Footer() {
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
            $this->SetXY(70,255);
            $this->Cell (10,5,utf8_decode('ID del documento:'),0,1,'L');
            $this->SetDrawColor(0,88,147);
            $this->SetLineWidth(0.2);
            $this->Line(100,259,145,259);
            $this->SetXY(70,260);
            $this->Cell (10,5,utf8_decode('Certificado de Calibración, Página de '.$this->PageNo().' de {nb}'),0,1,'L');

            // Derecha
            $this->SetXY(160,265);
            $this->Cell (10,5,utf8_decode('REV. 0; 05/20 FDV-S-032.'),0,1,'D'); // Objeto orientado a la derecha
        }
    }

    // Generación del formato
    $pdf=new PDF('P','mm','Letter'); // Portrait milimétricos en carta
    $pdf->SetMargins(12,0); // Margen de 12 mm
    $pdf->AliasNbPages(); // Numeración de páginas
    $pdf->AddPage(); // Creación de hoja pdf

    /*********
    PÁGINA UNO
    *********/

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

    // Figura para el sello
    $pdf->SetDrawColor(0,88,147);
    //         X  Y  l  A   l: largo, A: Ancho
    $pdf->Rect(10,57,75,45); // Rectángulo de 7.5 cm x 4.5 cm

    $pdf->SetXY(100,57);
    $pdf->SetFont("Arial","b",10);
    $pdf->Cell(50,5,'PREPARADO POR:',0,0,'L');

    // Información de la empresa
    $pdf->SetXY(100,65);
    $pdf->Cell(50,5,utf8_decode('COMPAÑÍA:'),0,0,'L');
    $pdf->Line(208,70,125,70);
    $pdf->Line(208,76,125,76);

    // Dirección de la empresa
    $pdf->SetXY(100,80);
    $pdf->Cell(50,5,utf8_decode('DIRECCIÓN:'),0,0,'L');
    $pdf->Line(208,85,125,85);
    $pdf->Line(208,91,125,91);

    $pdf->SetXY(100,95);
    $pdf->SetFont("Arial","",9);
    $pdf->Cell(50,5,utf8_decode('Intervalo máximo de calibración recomendado: __________ meses.'),0,0,'L');
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
    $pdf->Cell(30,5,utf8_decode('Bueno'),0,0,'L');

    // Centro
    $pdf->SetFont("Arial","b",10);
    $pdf->SetXY(88,115);
    $pdf->MultiCell(45,5,utf8_decode('Condición de calibración encontrada:'),0,'L',false);

    $pdf->SetDrawColor(0,88,147);
    $pdf->SetXY(90,129);
    $pdf->Cell(3,3,'',1,1,'C',true);

    $pdf->SetFont("Arial","",9);
    $pdf->SetXY(95,128.5);
    $pdf->Cell(30,5,utf8_decode('En tolerancia'),0,0,'L');

    //Derecha
    $pdf->SetFont("Arial","b",10);
    $pdf->SetXY(160,115);
    $pdf->MultiCell(45,5,utf8_decode('Condición de calibración final:'),0,'L',false);

    $pdf->SetDrawColor(0,88,147);
    $pdf->SetXY(162,129);
    $pdf->Cell(3,3,'',1,1,'C',true);

    $pdf->SetFont("Arial","",9);
    $pdf->SetXY(166,128.5);
    $pdf->MultiCell(30,5,utf8_decode('Dentro de las especificaciones'),0,'L',false);


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

    // Firma Cliente
    $pdf->Line(190,235,130,235);
    $pdf->SetXY(153,236);
    $pdf->Cell(15,5,utf8_decode('Firma'),0,0,'C');

    /*********
    PÁGINA DOS
    *********/

    // Se agrega nueva página
    $pdf->AddPage();

    // Generar cabecera
    $pdf->Line(20,22,35,22);
    $pdf->SetFont("Arial","",8);
    $pdf->SetXY(50,10);
    $pdf->MultiCell(32,5,utf8_decode('www.dvi.mx servicio@dvi.mx'),0,'C',false);
    $pdf->SetX(40);
    $pdf->MultiCell(50,5,utf8_decode('Tel.(55)5688-3566 / 3977 - Ext. 402 01800 8326 - 345'),0,'C',false);


    /***********************************************************************************
    Se indica el nombre del arcvhivo y los parámetros de exportación | Fin del documento
    ***********************************************************************************/
    $pdf->output('I',utf8_decode('FDV-S-032 Certificado de Calibración').'.pdf');

} else {
    header ('Location: ../../../index.php');
}
?>