<?php
//! Se obtiene el ID del Documento
$id_documento = $_SERVER['QUERY_STRING'];

//TODO: Se recopila información del contador a través del ID
require '../../functions/conex_serv.php';
$certifie = 'fdv_s_032';

//* Sitio Web
$website = '<a href="https://dvi.mx">dvi.mx</a>';

$s_cert = $con->prepare("SELECT * FROM $certifie WHERE id_documento = :id_documento");
$s_cert->bindValue(':id_documento', $id_documento);
$s_cert->setFetchMode(PDO::FETCH_OBJ);
$s_cert->execute();

$f_cert = $s_cert->fetchAll();

if ($s_cert -> rowCount() > 0) {
    foreach ($f_cert as $info_cert) {
        $id_documento_f = $info_cert -> id_documento;
        $modelo_ci = $info_cert -> modelo_ci;
        $numero_serie = $info_cert -> numero_serie;
        $fecha_calibracion = $info_cert -> fecha_calibracion;
        $tecnico = $info_cert -> tecnico;
    }
} else {
    echo 'CERTIFICADO: <strong></strong>';
    echo '<br><br>';
    echo 'MODELO: <strong></strong>';
    echo '<br><br>';
    echo 'No. DE SERIE: <strong></strong>';
    echo '<br><br>';
    echo 'FECHA DE CALIBRACIÓN: <strong></strong>';
    echo '<br><br>';
    echo 'TÉCNICO: <strong></strong>';
    echo '<br><br>';
    echo $website;
    die();
}

// Despliegue de información
echo 'CERTIFICADO: <strong>'.$id_documento_f.'</strong>';
echo '<br><br>';
echo 'MODELO: <strong>'.$modelo_ci.'</strong>';
echo '<br><br>';
echo 'No. DE SERIE: <strong>'.$numero_serie.'</strong>';
echo '<br><br>';
echo 'FECHA DE CALIBRACIÓN: <strong>'.$fecha_calibracion.'</strong>';
echo '<br><br>';
echo 'TÉCNICO: <strong>'.$tecnico.'</strong>';
echo '<br><br>';
echo $website;
?>