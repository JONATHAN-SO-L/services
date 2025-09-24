<?php
session_start();

if ($_SESSION['nombre'] != '' && $_SESSION['tipo'] == 'devecchi' || $_SESSION['tipo'] == 'admin') {
    // Configuración de la base de datos
    require '../conex_serv.php';
    $services = 'fdv_s_032';

    // Campos que deseas exportar (en el orden que quieras)
    $campos = [
        'fecha_calibracion', 'modelo_contador', 'tecnico',
        'tamano_real_03' , 'amplitud_esperada_03', 'como_encuentra_03', 'condicion_final_03', 'tolerancia_03',
        'tamano_real_05' , 'amplitud_esperada_05', 'como_encuentra_05', 'condicion_final_05', 'tolerancia_05',
        'tamano_real_10' , 'amplitud_esperada_10', 'como_encuentra_10', 'condicion_final_10', 'tolerancia_10',
        'tamano_real_50' , 'amplitud_esperada_50', 'como_encuentra_50', 'condicion_final_50', 'tolerancia_50',
    ];

    // Encabezados personalizados para el CSV
    $encabezados_personalizados = [
        'FECHA DE LA CALIBRACIÓN', 'MODELO', 'NOMBRE DEL CALIBRADOR',
        'TAMAÑO DE PARTÍCULA 0.3', 'CALIBRACIÓN ANTERIOR', 'CÓMO SE ENCONTRÓ', 'CALIBRACIÓN ACTUAL', 'TOLERANCIA DE REFERENCIA',
        'TAMAÑO DE PARTÍCULA 0.5', 'CALIBRACIÓN ANTERIOR', 'CÓMO SE ENCONTRÓ', 'CALIBRACIÓN ACTUAL', 'TOLERANCIA DE REFERENCIA',
        'TAMAÑO DE PARTÍCULA 1', 'CALIBRACIÓN ANTERIOR', 'CÓMO SE ENCONTRÓ', 'CALIBRACIÓN ACTUAL', 'TOLERANCIA DE REFERENCIA',
        'TAMAÑO DE PARTÍCULA 5', 'CALIBRACIÓN ANTERIOR', 'CÓMO SE ENCONTRÓ', 'CALIBRACIÓN ACTUAL',  'TOLERANCIA DE REFERENCIA'
    ];

    // Construir consulta SQL con campos seleccionados
    $campos_sql = implode(', ', $campos);
    $sql = "SELECT $campos_sql FROM $services";
    $stmt = $con->query($sql);

    // Generar nombre dinámico con fecha
    $fecha = date('d-m-Y');
    $nombre_archivo = "Reporte_Calibraciones_$fecha.csv";

    // Encabezados HTTP para descarga
    header('Content-Type: text/csv; charset=utf-8');
    header("Content-Disposition: attachment; filename=$nombre_archivo");

    // Abrir salida estándar como archivo
    $output = fopen('php://output', 'w');

    // Escribir BOM para UTF-8 (útil para Excel)
    fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));

    // Escribir encabezados personalizados
    fputcsv($output, $encabezados_personalizados);

    // Escribir filas
    while ($fila = $stmt->fetch(PDO::FETCH_NUM)) {
        fputcsv($output, $fila);
    }

    fclose($output);

    require '../drop_con.php';
} else {
    header('Location: ../../index.php');
}

?>