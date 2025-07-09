<?php
session_start();
header('Content-Type: text/html; charset=UTF-8');

if( $_SESSION['nombre']!="" && $_SESSION['clave']!="" && $_SESSION['tipo']=="devecchi" || $_SESSION['tipo']=="admin"){ 
	include './assets/navbar.php';
	include './assets/links.php';
	require './functions/conex_serv.php';

	// Obtener el nombre de los formatos de certificados
	$format = 'formatos';
	$s_format = $con->prepare("SELECT nombre_formato FROM $format");
	$s_format->setFetchMode(PDO::FETCH_OBJ);
	$s_format->execute();

	$f_format = $s_format->fetchAll();

	if ($s_format -> rowCount() > 0) {
		foreach ($f_format as $formats) {
			$nombre_formato = $formats -> nombre_formato;
		}
	} else {
		echo 'No se encontraron formatos cargados en el sistema';
		die();
	}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>Servicios</title>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link rel="stylesheet" href="./assets/css/main.css">
</head>

<body>
    
<div id="menu-overlay"></div>
<div id="menu-toggle" class="closed" data-title="Menu">
    <i class="fa fa-bars"></i>
    <i class="fa fa-times"></i>
  </div>
<header id="main-header">
</header>

<section id="content">	 
  <header id="content-header">

		<div class="col-sm-2">
			<table>
				<tr>
					<?php
					if ($_SESSION['tipo']=="devecchi") {
						echo '<a href="../seccion.php"><button type="submit" value="Volver" class="btn btn-primary" style="text-align:center"><i class="fa fa-reply"></i>&nbsp;&nbsp;Volver</button></a>';
					} else {
						echo '<a href="../seccion_admin.php"><button type="submit" value="Volver" class="btn btn-primary" style="text-align:center"><i class="fa fa-reply"></i>&nbsp;&nbsp;Volver</button></a>';
					}
					?>
				</tr>
			</table>
		</div>

		<div class="container" style="width:1180px;">
          <div class="row">
            <div class="col-sm-12">
           <div class="page-header2">
				
                <h1 class="animated lightSpeedIn">Certificados de Calibración</h1>
                <span class="label label-danger"></span> 		 
				<p class="pull-right text-primary"></p>
		   </div>
            </div>
          </div>
        </div>


<section class="content">

<table>
	<a class="button" href="./certifies/fdv/032/index.php" style="width: 234px;margin-left: 40px;">
	<center><?php echo $nombre_formato; ?></center>
	<div class="button__horizontal"></div>
	<div class="button__vertical"></div>
</a>
<!--br>
<td>
<tr>
<a class="button" href="seccion0.php" style="width: 234px;margin-left: 40px;">
	<center>Consultar</center>
	<div class="button__horizontal"></div>
	<div class="button__vertical"></div>
</a>
<tr>
<td-->
</table>
 </section>

<?php
}else{
	header('Location: ../index.php');
}
?>
</body>
</html>