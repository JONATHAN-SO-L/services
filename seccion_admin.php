<?php
session_start();

if( $_SESSION['nombre']!="" && $_SESSION['clave']!="" && $_SESSION['tipo']=="admin"){
  include './services/assets/admin/navbar.php';
  include './services/assets/admin/links.php';
?>
<script src="./services/assets/css/main.css"></script>
<section id="content">	 
  <header id="content-header">
  
		 
  
  <table>
		   <td>
 
          </td>
       </table>
				<!--************************************ Page content******************************-->
		<div class="container" style="width:1180px;">
          <div class="row">
            <div class="col-sm-12">
           <div class="page-header2">
				
                <h1 class="animated lightSpeedIn">Servicio</h1>
                <span class="label label-danger"></span> 		 
				<p class="pull-right text-primary"></p>
		   </div>
            </div>
          </div>
        </div>
		<!--************************************ Page content******************************-->
		

<style>
.page-header{
display:none;
}


.button {
	
	--offset: 10px;
	--border-size: 2px;
	
	display: block;
	position: relative;
	padding: 1.5em 3em;
	appearance: none;
	border: 0;
	background: #35495d;
	color: #3fb5ef;
	text-transform: uppercase;
	letter-spacing: .25em;
	outline: none;
	cursor: pointer;
	font-weight: bold;
	border-radius: 0;
	box-shadow: inset 0 0 0 var(--border-size) currentcolor;
	transition: background .8s ease;
	
	&:hover {
		background: rgba(100, 0, 0, .03);
	}
	
	&__horizontal,
	&__vertical {
		position: absolute;
		top: var(--horizontal-offset, 0);
		right: var(--vertical-offset, 0);
		bottom: var(--horizontal-offset, 0);
		left: var(--vertical-offset, 0);
		transition: transform .8s ease;
		will-change: transform;
		
		&::before {
			content: '';
			position: absolute;
			border: inherit;
		}
	}
	
	&__horizontal {
		--vertical-offset: calc(var(--offset) * -1);
		border-top: var(--border-size) solid currentcolor;
		border-bottom: var(--border-size) solid currentcolor;
		
		&::before {
			top: calc(var(--vertical-offset) - var(--border-size));
			bottom: calc(var(--vertical-offset) - var(--border-size));
			left: calc(var(--vertical-offset) * -1);
			right: calc(var(--vertical-offset) * -1);
		}
	}
	
	&:hover &__horizontal {
		transform: scaleX(0);
	}
	
	&__vertical {
		--horizontal-offset: calc(var(--offset) * -1);
		border-left: var(--border-size) solid currentcolor;
		border-right: var(--border-size) solid currentcolor;
		
		&::before {
			top: calc(var(--horizontal-offset) * -1);
			bottom: calc(var(--horizontal-offset) * -1);
			left: calc(var(--horizontal-offset) - var(--border-size));
			right: calc(var(--horizontal-offset) - var(--border-size));
		}
	}
	
	&:hover &__vertical {
		transform: scaleY(0);
	}
	
}
</style>


<a class="button" href="seccion3_admin.php" style="width: 234px;margin-left: 40px;">
	<center>Crear</center>
	<div class="button__horizontal"></div>
	<div class="button__vertical"></div>
</a>
<br>
<br>
<a class="button" href="seccion0_admin.php" style="width: 234px;margin-left: 40px;">
	<center>Consultar</center>
	<div class="button__horizontal"></div>
	<div class="button__vertical"></div>
</a>
<br>
<br>
<a class="button" href="seccion_adm_servicio.php" style="width: 234px;margin-left: 40px;">
	<center>No tocar</center>
	<div class="button__horizontal"></div>
	<div class="button__vertical"></div>
</a>
<br>
<td>
<tr>
<a class="button" href="./services/index.php" style="width: 234px;margin-left: 40px; margin-top: 15px;">
	<center>Certificados de
	Calibración</center>
	<div class="button__horizontal"></div>
	<div class="button__vertical"></div>
</a>
<tr>
<td>

 <?php
}else{
	header('Location: index.php');
}
?>

<footer></footer>

  <script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>

    <script src="js/index.js"></script>
	<script src="/devecchi/ejercicio/Resources/js/empresa.js"></script>
	<script src="/devecchi/ejercicio/Models/empresa.js"></script>
</body>
</html>