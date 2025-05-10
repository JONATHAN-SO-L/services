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
    $pdf->SetX(95);
    $pdf->Cell(15,10,utf8_decode('Contador de partículas, Núm. Serie: ______________________'),0,0,'C');
    $pdf->SetX(165);
    $pdf->Cell(15,10,utf8_decode('Fecha de calibración: __________________'),0,0,'C');
    $pdf->SetXY(60,65);
    $pdf->Cell(15,10,utf8_decode('Identificación de Cliente: ____________________________________________'),0,0,'C');
    $pdf->SetX(155);
    $pdf->Cell(15,10,utf8_decode('Técnico:__________________________________________'),0,0,'C');
    $pdf->SetLineWidth(0.5);
    $pdf->Rect(10,35,195,40); // Rectángulo de 19.5 cm x 4.0 cm

    $pdf->SetFont("Arial","",9);
    $pdf->SetXY(40,75);
    $pdf->Cell(15,10,utf8_decode('Presión Barométrica: ________________'),0,0,'C');
    $pdf->SetX(100);
    $pdf->Cell(15,10,utf8_decode('Temperatura: ________________'),0,0,'C');
    $pdf->SetX(160);
    $pdf->Cell(15,10,utf8_decode('Humedad Relativa: _________________'),0,0,'C');
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
    $pdf->Cell(20,5,utf8_decode('Vdc±'),1,0,'C');
    $pdf->Cell(30,5,utf8_decode('(Valor de referencia)'),1,0,'C');
    $pdf->Cell(45,5,utf8_decode('Vdc'),1,0,'C');
    $pdf->Cell(20,5,utf8_decode(''),1,0,'C');
    $pdf->Cell(45,5,utf8_decode('Vdc'),1,0,'C');

    // FLUJO DE AIRE
    $pdf->SetXY(10,115);
    $pdf->Cell(35,5,utf8_decode('FLUJO DE AIRE'),1,0,'L');
    $pdf->Cell(20,5,utf8_decode('LPM'),1,0,'C');
    $pdf->Cell(30,5,utf8_decode('± 1.4 LPM'),1,0,'R');
    $pdf->Cell(45,5,utf8_decode('LPM*'),1,0,'C');
    $pdf->Cell(20,5,utf8_decode(''),1,0,'C');
    $pdf->Cell(45,5,utf8_decode('LPM*'),1,0,'C');

    // RUIDO MÁXIMO
    $pdf->SetXY(10,120);
    $pdf->Cell(35,5,utf8_decode('RUIDO MÁXIMO'),1,0,'L');
    $pdf->Cell(20,5,utf8_decode('< 200 mV'),1,0,'C');
    $pdf->Cell(30,5,utf8_decode('(Valor de referencia)'),1,0,'C');
    $pdf->Cell(45,5,utf8_decode('mV'),1,0,'C');
    $pdf->Cell(20,5,utf8_decode(''),1,0,'C');
    $pdf->Cell(45,5,utf8_decode('mV'),1,0,'C');

    // Información restante
    $pdf->SetFont("Arial","",7);
    $pdf->SetXY(10,125);
    $pdf->MultiCell(70,5,utf8_decode('± Valor inicial; el voltaje aumenta a medida que el diodo láser se desgasta.'),0,'L',false);
    $pdf->SetXY(123,125);
    $pdf->Cell(45,5,utf8_decode('* Las lecturas del medidor de flujo volumétrico y reflejan una compensación correctiva de: __________ LPM'),0,0,'C');

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
    $pdf->Cell(30,5,utf8_decode('0.3 µm'),0,0,'C',true);
    $pdf->Cell(30,5,utf8_decode('0.5 µm'),0,0,'C',true);
    $pdf->Cell(30,5,utf8_decode('1.0 µm'),0,0,'C',true);
    $pdf->Cell(30,5,utf8_decode('5.0 µm'),0,0,'C',true);

    // AMPLITUD ESPERADA
    $pdf->SetFont("Arial","",8);
    $pdf->SetTextColor(0,88,147);
    $pdf->SetXY(10,145);
    $pdf->Cell(75,5,utf8_decode('AMPLITUD ESPERADA (desde la última calibración)'),1,0,'L');
    $pdf->Cell(30,5,utf8_decode('mV'),1,0,'C');
    $pdf->Cell(30,5,utf8_decode('mV'),1,0,'C');
    $pdf->Cell(30,5,utf8_decode('V'),1,0,'C');
    $pdf->Cell(30,5,utf8_decode('mV'),1,0,'C');

    // TOLERANCIA
    $pdf->SetXY(10,150);
    $pdf->Cell(75,5,utf8_decode('TOLERANCIA'),1,0,'L');
    $pdf->Cell(30,5,utf8_decode('± 60 mV'),1,0,'R');
    $pdf->Cell(30,5,utf8_decode('± 30 mV'),1,0,'R');
    $pdf->Cell(30,5,utf8_decode('± 165 mV'),1,0,'R');
    $pdf->Cell(30,5,utf8_decode('± 50 mV'),1,0,'R');

    // COMO SE ENCUENTRA
    $pdf->SetXY(10,155);
    $pdf->Cell(75,5,utf8_decode('COMO SE ENCUENTRA'),1,0,'L');
    $pdf->Cell(30,5,utf8_decode('mV'),1,0,'C');
    $pdf->Cell(30,5,utf8_decode('mV'),1,0,'C');
    $pdf->Cell(30,5,utf8_decode('V'),1,0,'C');
    $pdf->Cell(30,5,utf8_decode('mV'),1,0,'C');

    // PASA (S/N)
    $pdf->SetXY(10,160);
    $pdf->Cell(75,5,utf8_decode('PASA (S/N)'),1,0,'L');
    $pdf->Cell(30,5,utf8_decode(''),1,0,'C');
    $pdf->Cell(30,5,utf8_decode(''),1,0,'C');
    $pdf->Cell(30,5,utf8_decode(''),1,0,'C');
    $pdf->Cell(30,5,utf8_decode(''),1,0,'C');

    // CONDICIÓN FINAL
    $pdf->SetXY(10,165);
    $pdf->Cell(75,5,utf8_decode('CONDICIÓN FINAL'),1,0,'L');
    $pdf->Cell(30,5,utf8_decode('mV'),1,0,'C');
    $pdf->Cell(30,5,utf8_decode('mV'),1,0,'C');
    $pdf->Cell(30,5,utf8_decode('V'),1,0,'C');
    $pdf->Cell(30,5,utf8_decode('mV'),1,0,'C');

    /*********************
    Información Página Dos
    *********************/
    // INCERTIDUMBRE COLECTIVA DE LA MEDICIÓN
    $pdf->SetFont("Arial","b",9);
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












    /***********************************************************************************
    Se indica el nombre del arcvhivo y los parámetros de exportación | Fin del documento
    ***********************************************************************************/
    $pdf->output('I',utf8_decode('FDV-S-032 Certificado de Calibración').'.pdf');

} else {
    header ('Location: ../../../index.php');
}
?>