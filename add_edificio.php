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
		<tr>
       <a href="add_edificio2.php" ><button  type="submit" class="btn btn-primary" style="text-align:center"><i class="fa fa-reply"></i>&nbsp;&nbsp;Regresar</button></a>
	   </tr>
	</td>
	   </table>
		<!--************************************ Page content******************************-->
		<div class="container">
          <div class="row">
            <div class="col-sm-12">
              <div class="page-header2">
                <h1 class="animated lightSpeedIn">Nuevo Edificio</h1>
                <span class="label label-danger"></span>
                <p class="pull-right text-primary">
               </p>
              </div>
            </div>
          </div>
        </div>
		<!--************************************ Page content******************************-->
<div class="container">
  <div class="row">
    <div class="col-sm-8">
      <div class="panel panel-success">
        <div class="panel-heading text-center"><strong>Para poder crear un nuevo edificio es necesario llenar los siguientes campos, por favor evita usar acentos.</strong></div>
        <div class="panel-body">
            <form role="form" action="add_edificio3.php" method="POST" enctype="multipart/form-data">

<?php
 include ("conexi.php");		

	$emp = $_POST['empresa_id'];
			
	$usuario = "veco_dvi";
	$password = "Vec83Dv19iSa@";
	$servidor = "localhost";
	$basededatos = "veco_sims_devecchi";
	
	// creación de la conexión a la base de datos con mysql_connect()
	$conexion = mysqli_connect( $servidor, $usuario, $password) or die ("No se ha podido conectar al servidor de Base de datos");
	$conexion ->set_charset('utf8');
	$conexion->query("SET NAMES UTF8");
    $conexion->query("SET CHARACTER SET utf8");
	
	// Selección de la base de datos
	$db = mysqli_select_db( $conexion, $basededatos ) or die ( "Upps! Pues va a ser que no se ha podido conectar a la base de datos" );
	// establecer y realizar consulta. guardamos en variable.
	$consulta = "SELECT * from empresas WHERE id='$emp'";
	$resultado = mysqli_query( $conexion, $consulta) or die ( "Algo ha ido mal en la consulta a la base de datos");

while ($reg = mysqli_fetch_array( $resultado )){
?>
								  <input type="hidden" readonly="" name="empresa_id" readonly="" value="<?php echo $reg['id']?>">
						
						<div class="form-group">
                            <label class="col-sm-222 control-label"><i class="fa fa-building-o" aria-hidden="true"></i> Descripción</label>
                             <div class="col-sm-110">
                              <div class='input-group'>
							      <input type="text" class="form-control" name="descripcion" maxlength="30" placeholder="Por ejemplo: Planta Morelos" required>
                                <span class="input-group-addon"><i class="fa fa-pencil-square-o"></i></span>
                              </div>
                          </div>
                        </div>
                    
                        <div class="form-group">
                            <label class="col-sm-222 control-label"><i class="fa fa-road" aria-hidden="true"></i> Calle</label>
                            <div class='col-sm-110'>
                                <div class="input-group">
                                   <input class="form-control" type="text" name="calle" placeholder="Por ejemplo: 13 Este" required>
								   <span class="input-group-addon"><i class="fa fa-pencil-square-o"></i></span>
                                </div>
                            </div>
                        </div>
						<div class="form-group">
                          <label  class="col-sm-222 control-label"><i class="fa fa-location-arrow" aria-hidden="true"></i> Número Exterior</label>
                          <div class="col-sm-110">
                              <div class='input-group'>
                                  <input type="text" class="form-control" name="numero_exterior" placeholder="Por ejemplo: 116" required>
								  <span class="input-group-addon"><i class="fa fa-pencil-square-o"></i></span>
                              </div>
                          </div>
                        </div>
						
                        <div class="form-group">
                          <label  class="col-sm-222 control-label"><i class="fa fa-location-arrow" aria-hidden="true"></i> Número Interior <u>(Opcional)</u>*</label>
                          <div class="col-sm-110">
                              <div class='input-group'>
                                  <input type="text" class="form-control" name="numero_interior">
								  <span class="input-group-addon"><i class="fa fa-pencil-square-o"></i></span>
                              </div>
                          </div>
                        </div>
						
						<div class="form-group">
                            <label class="col-sm-222 control-label"><i class="fa fa-map" aria-hidden="true"></i> Colonia</label>
                             <div class="col-sm-110">
                              <div class='input-group'>
                                <input type="text" class="form-control" name="colonia" placeholder="Por ejemplo: CIVAC" required>
                                <span class="input-group-addon"><i class="fa fa-pencil-square-o"></i></span>
                              </div>
                          </div>
                        </div>
						
						<div class="form-group">
                            <label class="col-sm-222 control-label"><i class="fa fa-map-o" aria-hidden="true"></i> Municipio</label>
                             <div class="col-sm-110">
                              <div class='input-group'>
                                <input type="text" class="form-control" name="municipio" placeholder="Por ejemplo: Jiutepec" required>
                                <span class="input-group-addon"><i class="fa fa-pencil-square-o"></i></span>
                              </div>
                          </div>
                        </div>

                        <div class="form-group">
                          <label class="col-sm-222 control-label"><i class="fa fa-street-view" aria-hidden="true"></i> Entidad Federativa</label>
                          <div class="col-sm-110">
                              <div class='input-group'>
                                  <input class="form-control" name="entidad_federativa" placeholder="Por ejemplo: Morelos" required>
								  <span class="input-group-addon"><i class="fa fa-pencil-square-o"></i></span>
                              </div> 
                          </div>
                        </div>

                        <div class="form-group">
                          <label  class="col-sm-222 control-label"><i class="fa fa-map-signs" aria-hidden="true"></i> Código Postal</label>
                          <div class="col-sm-110">
                              <div class='input-group'>
                                  <input type="text" class="form-control"  name="codigo_postal" placeholder="Por ejemplo: 62578" required>
								  <span class="input-group-addon"><i class="fa fa-pencil-square-o"></i></span>
                              </div> 
                          </div>
                        </div>
						
						<div class="form-group">
                          <label  class="col-sm-222 control-label"><i class="fa fa-globe" aria-hidden="true"></i> País</label>
                          <div class="col-sm-110">
                              <div class='input-group'>
                                  <input type="text" class="form-control" name="pais" placeholder="Por ejemplo: México" required>
								  <span class="input-group-addon"><i class="fa fa-pencil-square-o"></i></span>
                              </div> 
                          </div>
                        </div>

                        <div class="form-group">
                          <label  class="col-sm-222 control-label"><i class="fa fa-map-marker" aria-hidden="true"></i> Dirección GPS <u>(Opcional)</u>*</label>
                          <div class="col-sm-110">
                              <div class='input-group'>
                                  <input type="text" class="form-control"  name="direccion_gps" placeholder="Por ejemplo: https://maps.app.goo.gl/qfgrHj5aq96Su9Ba9">
								  <span class="input-group-addon"><i class="fa fa-pencil-square-o"></i></span>
                              </div> 
                          </div>
                        </div>
						
						<div class="form-group">
                            <label class="col-sm-222 control-label"><i class="fa fa-user" aria-hidden="true"></i> Nombre del Contacto</label>
                             <div class="col-sm-110">
                              <div class='input-group'>
                                <input type="text" class="form-control" name="contacto_nombre" maxlength="30" placeholder="Por ejemplo: Angel Francisco" required>
                                <span class="input-group-addon"><i class="fa fa-pencil-square-o"></i></span>
                              </div>
                          </div>
                        </div>
						
						<div class="form-group">
                            <label class="col-sm-222 control-label"><i class="fa fa-user-o" aria-hidden="true"></i> Apellido del Contacto</label>
                             <div class="col-sm-110">
                              <div class='input-group'>
                                <input type="text" class="form-control" name="contacto_apellido" maxlength="30" placeholder="Por ejemplo: De Vecchi Flores" required>
                                <span class="input-group-addon"><i class="fa fa-pencil-square-o"></i></span>
                              </div>
                          </div>
                        </div>
						
						<div class="form-group">
                            <label class="col-sm-222 control-label"><i class="fa fa-sitemap" aria-hidden="true"></i> Puesto del Contacto</label>
                             <div class="col-sm-110">
                              <div class='input-group'>
                                <input type="text" class="form-control" name="contacto_puesto" maxlength="30" placeholder="Por ejemplo: Direccion General" required>
                                <span class="input-group-addon"><i class="fa fa-pencil-square-o"></i></span>
                              </div>
                          </div>
                        </div>
						
						<div class="form-group">
                            <label class="col-sm-222 control-label"><i class="fa fa-envelope" aria-hidden="true"></i> Email del Contacto</label>
                             <div class="col-sm-110">
                              <div class='input-group'>
                                <input type="email" class="form-control" name="contacto_email" maxlength="100" placeholder="Por ejemplo: email@email.com" required>
                                <span class="input-group-addon"><i class="fa fa-pencil-square-o"></i></span>
                              </div>
                          </div>
                        </div>
						
						<div class="form-group">
                            <label class="col-sm-222 control-label"><i class="fa fa-phone-square" aria-hidden="true"></i> Telefono del Contacto</label>
                             <div class="col-sm-110">
                              <div class='input-group'>
                                <input type="text" class="form-control" name="contacto_telefono" maxlength="50" placeholder="Por ejemplo: 01-800-333-111" required>
                                <span class="input-group-addon"><i class="fa fa-pencil-square-o"></i></span>
                              </div>
                          </div>
                        </div>
						
						<div class="form-group">
                          <label  class="col-sm-222 control-label"><i class="fa fa-shield" aria-hidden="true"></i> Requisitos de acceso <u>(Opcional)</u>*</label>
                          <div class="col-sm-110">
                              <div class='input-group'>
                                <input type="text" class="form-control" name="requisitos_acceso" placeholder="Por ejemplo: Calzado de seguridad, cofia, cubrebocas y tapones auditivos">
                                <span class="input-group-addon"><i class="fa fa-pencil-square-o"></i></span>
                          </div>
                          </div>
						  <?php } ?>
                        </div>
                        <div class="form-group">
                          <div class="col-sm-offset-2 col-sm-10 text-center"><br>
                              <button type="submit" name="guardar_edificio" class="btn btn-success">Guardar</button>
                          </div>
                        </div>
                    <br>
            </form>
            </div><!--col-md-12-->
          </div><!--container-->
	  
	  
		</div>
	  </div>
      </div>
    </div>
	  <?php
}else{
	header('Location: index.php');
}
?>
</body>
</html>