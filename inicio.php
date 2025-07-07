<?php
session_start();

if( $_SESSION['nombre']!="" && $_SESSION['clave']!="" && $_SESSION['tipo']=="admin"){
  include './services/assets/admin/navbar.php';
  include './services/assets/admin/links.php';
?>
<style>
    .btn { 
        padding: 10px;
		}
		
		.bg-aqua, .callout.callout-info, .alert-info, .label-info, .modal-info .modal-body {
    background-color: #00c0ef !important;
}
.bg-green, .callout.callout-success, .alert-success, .label-success, .modal-success .modal-body {
    background-color: #00a65a !important;
}.bg-yellow, .callout.callout-warning, .alert-warning, .label-warning, .modal-warning .modal-body {
    background-color: #f39c12 !important;
}
.bg-red, .callout.callout-danger, .alert-danger, .alert-error, .label-danger, .modal-danger .modal-body {
    background-color: #dd4b39 !important;
}
.small-box>.inner {
    padding: 10px;
}
.bg-red, .bg-yellow, .bg-aqua, .bg-blue, .bg-light-blue, .bg-green, .bg-navy, .bg-teal, .bg-olive, .bg-lime, .bg-orange, .bg-fuchsia, .bg-purple, .bg-maroon, .bg-black, .bg-red-active, .bg-yellow-active, .bg-aqua-active, .bg-blue-active, .bg-light-blue-active, .bg-green-active, .bg-navy-active, .bg-teal-active, .bg-olive-active, .bg-lime-active, .bg-orange-active, .bg-fuchsia-active, .bg-purple-active, .bg-maroon-active, .bg-black-active, .callout.callout-danger, .callout.callout-warning, .callout.callout-info, .callout.callout-success, .alert-success, .alert-danger, .alert-error, .alert-warning, .alert-info, .label-danger, .label-info, .label-warning, .label-primary, .label-success, .modal-primary .modal-body, .modal-primary .modal-header, .modal-primary .modal-footer, .modal-warning .modal-body, .modal-warning .modal-header, .modal-warning .modal-footer, .modal-info .modal-body, .modal-info .modal-header, .modal-info .modal-footer, .modal-success .modal-body, .modal-success .modal-header, .modal-success .modal-footer, .modal-danger .modal-body, .modal-danger .modal-header, .modal-danger .modal-footer {
    color: #fff !important;
}
.small-box>.small-box-footer {
    position: relative;
    text-align: center;
    padding: 3px 0;
    color: #fff;
    color: rgba(255,255,255,0.8);
    display: block;
    z-index: 10;
    background: rgba(0,0,0,0.1);
    text-decoration: none;
}
.small-box h3 {
    font-size: 38px;
    font-weight: bold;
    margin: 0 0 10px 0;
    white-space: nowrap;
    padding: 0;
}
</style>
<script src="./services/assets/css/main.css"></script>
<section id="content">
  <header id="content-header" style="width: 1050px;>
  
  <table>
      
          
           </td>
       
       </table>
  <?php 
	$usuario = "veco_dvi";
	$password = "Vec83Dv19iSa@";
	$servidor = "localhost";
	$basededatos = "veco_sims_devecchi";
		  
	$conexion = mysqli_connect( $servidor, $usuario, $password ) or die ("No se ha podido conectar al servidor de Base de datos");
	$conexion ->set_charset('utf8');
	$conexion->query("SET NAMES UTF8");
    $conexion->query("SET CHARACTER SET utf8");
	
	// Selección de la base de datos
	$db = mysqli_select_db( $conexion, $basededatos ) or die ( "Upps! Pues va a ser que no se ha podido conectar a la base de datos" );
		   
		   

    $count_files = mysqli_query($conexion, "select * from empresas");
    $count_usuarios=mysqli_query($conexion, "select * from usuario");
	$count_dvi=mysqli_query($conexion, "select * from usuario_devecchi");
    $count_comments=mysqli_query($conexion, "select * from equipo");

?>

 <div class="content-wrapper" style="min-height: 292px;"><!-- Content Wrapper. Contains page content -->
        <section class="content-header"><!-- Content Header (Page header) -->
           <br>
		   <br>
		   <br>
        </section>
        <section class="content"><!-- Main content -->
            <div class="row" style="width: 1050px;"><!-- Small boxes (Stat box) -->
                <div class="col-lg-3 col-xs-6">
                    <!-- small box -->
                    <div class="small-box bg-aqua">
                        <div class="inner">
                            <h3><?php echo mysqli_num_rows($count_files); ?></h3>
                            <p>Empresas</p>
                        </div>
                        <a href="empresa.php" class="small-box-footer">Más info <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div><!-- ./col -->
                <div class="col-lg-3 col-xs-6">
                    <div class="small-box bg-green"><!-- small box -->
                        <div class="inner">
                            <h3><?php echo mysqli_num_rows($count_usuarios); ?></h3>
                            <p>Usuarios Totales</p>
                        </div>
                        <a href="tabla_usuarios.php" class="small-box-footer">Más info <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div><!-- ./col -->
				
				
                <div class="col-lg-3 col-xs-6">
                    <div class="small-box bg-yellow"><!-- small box -->
                        <div class="inner">
                            <h3><?php echo mysqli_num_rows($count_dvi); ?></h3>
                            <p>Usuarios De Vecchi</p>
                        </div>
                        <a href="tabla_devecchi.php" class="small-box-footer">Más info <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div><!-- ./col -->
				
				<div class="col-lg-3 col-xs-6">
                    <div class="small-box bg-red"><!-- small box -->
                        <div class="inner">
                          <h3><?php echo mysqli_num_rows($count_comments); ?></h3>
                          <p>Equipos</p>
                        </div>
                        <a href="equipo.php" class="small-box-footer">Más info <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div><!-- ./col -->
				
				
            </div><!-- /.row -->
            
        </section>
    </div><!-- /.content -->

 <?php
 
}else{
	header('Location: index.php');
}
?>
<footer></footer>

  <script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>

    <script src="js/index.js"></script>
</body>
</html>

<script>
    $(function(){
    $("input[name='file']").on("change", function(){
        var formData = new FormData($("#formulario")[0]);
        var ruta = "action/uploadprofile.php";
        $.ajax({
            url: ruta,
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            success: function(datos)
            {
                $("#respuesta").html(datos);
            }
        });
    });
    });
</script>