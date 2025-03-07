<?php
header('Content-Type: text/html; charset=UTF-8');
session_start();
if( $_SESSION['nombre']!="" && $_SESSION['clave']!="" && $_SESSION['tipo']=="devecchi"){ 
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>Servicios</title>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link rel="stylesheet" href="./assets/css/main.css">
	<?php include "./assets/links.php"; ?> 
</head>

<body>
    
<div id="menu-overlay"></div>
<div id="menu-toggle" class="closed" data-title="Menu">
    <i class="fa fa-bars"></i>
    <i class="fa fa-times"></i>
  </div>
<header id="main-header">
<?php include './assets/navbar.php'; ?>
</header>

<section id="content">	 
  <header id="content-header">

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
	<center>FDV-S-032</center>
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