<?php
session_start();

if( $_SESSION['nombre']!="" && $_SESSION['clave']!="" && $_SESSION['tipo']=="admin"){
  include './services/assets/admin/navbar.php';
  include './services/assets/admin/links.php';
?>
<script src="./services/assets/css/main.css"></script>

<section id="content">	 
  <header id="content-header">
  
		  <script type="text/javascript">
			  function validarForm(formulario)
			  {
				  if(formulario.palabra.value.length==0)
				  { //¿Tiene 0 caracteres?
					  formulario.palabra.focus();  // Damos el foco al control
					  alert('Debes rellenar este campo'); //Mostramos el mensaje
					  return false;
				   } //devolvemos el foco
				   return true;
			   }
		  </script>
  
  <table>
		    <a href="add_edificio2.php" ><button type="submit" class="btn btn-success" style="text-align:center"><i class="fa fa-plus"></i>&nbsp;&nbsp;Nuevo Edificio</button></a>
			<td>
 <tr>
      <button onClick="document.location.reload();" type="submit" class="btn btn-warning" data-toggle="tooltip" data-placement="top" title="Click Actualizar Datos" HSPACE="10" VSPACE="10"><i class="fa fa-refresh fa-spin  fa-fw"></i>
		<span class="sr-only">Loading...</span></button>
        </tr>
          </td>
       </table>
		<!--form style="float:right; margin-right: 25%;" class="busc_dato" method="POST" action="busquedaEmpresa.php" onSubmit="return validarForm(this)">
					 <input type="text" placeholder="Buscar por nombre o id" name="palabra">
					 <input type="submit" value="Buscar" name="buscar" class="btn btn-primary">
				</form-->
				<!--************************************ Page content******************************-->
		<div class="container">
          <div class="row" style="width: 1270px;">
            <div class="col-sm-12">
           <div class="page-header2">
				
                <h1 class="animated lightSpeedIn">Edificio</h1>
                <span class="label label-danger"></span> 		 
				<p class="pull-right text-primary"></p>
		   </div>
            </div>
          </div>
        </div>
		<!--************************************ Page content******************************-->
				<form style="float: left; height: 35px;" class="busc_dato" method="POST" action="edificio.php" onSubmit="return validarForm(this)"><i class="fa fa-search" aria-hidden="true"></i>
				<input placeholder=" Id o Edificio" type="text" id="busqueda" />
				<div id="resultado"></div>
				</form>
				
				<div id="datos"></div>
				
<script>
$(document).ready(function(){
                                
        var consulta;
                                                                          
         //hacemos focus al campo de búsqueda
        $("#busqueda").focus();
                                                                                                    
        //comprobamos si se pulsa una tecla
        $("#busqueda").keyup(function(e){
                                     
              //obtenemos el texto introducido en el campo de búsqueda
              consulta = $("#busqueda").val();
                                                                           
              //hace la búsqueda
                                                                                  
              $.ajax({
                    type: "POST",
                    url: "buscar_edificio.php",
                    data: "busc="+consulta,
                    dataType: "html",
                    beforeSend: function(){
                          //imagen de carga
                          $("#resultado").html("<p align='center'><img src='ajax-loader.gif' /></p>");
                    },
                    error: function(){
                          alert("error petición ajax");
                    },
                    success: function(data){                                                    
                          $("#resultado").empty();
                          $("#resultado").append(data);
                                                             
                    }
              });
                                                                                  
                                                                           
        });
                                                                   
});
</script>
				
<div class="buscar_index" style="width: 3550px;">	
    <?php
	$usuario = "veco_dvi";
	$password = "Vec83Dv19iSa@";
	$servidor = "localhost";
	$basededatos = "veco_sims_devecchi";
	
	// creación de la conexión a la base de datos con mysql_connect()
	$conexion = mysqli_connect( $servidor, $usuario, $password ) or die ("No se ha podido conectar al servidor de Base de datos");
	$conexion ->set_charset('utf8');
	$conexion->query("SET NAMES UTF8");
    $conexion->query("SET CHARACTER SET utf8");
	
	// Selección de la base de datos
	$db = mysqli_select_db( $conexion, $basededatos ) or die ( "Upps! Pues va a ser que no se ha podido conectar a la base de datos" );
	// establecer y realizar consulta. guardamos en variable.
	$consulta = "SELECT * FROM edificio ORDER BY empresa_id DESC";
  $resultado = mysqli_query( $conexion, $consulta) or die ( "Algo ha ido mal en la consulta a la base de datos");

ECHO "<table class='table table-hover table-striped table-bordered'><th 'width:15px'>Acción</th><th>Id</th><th>Empresa</th><th>Descripción</th>
<th>Calle</th><th>Núm Exterior</th><th>Núm Interior</th><th>Colonia</th><th>Municipio</th><th>Entidad Federativa</th><th>Codigo Postal</th><th>País</th>
<th>Dirección GPS</th><th>Nombre de Contacto</th><th>Apellido de Contacto</th><th>Puesto de Contacto</th><th>Email de Contacto</th><th>Telefono de Contacto</th><th>Requisitos de Acceso</th>";
while ($fila = mysqli_fetch_array($resultado)){
  $id_edificio = $fila["id_edificio"];
  $empresa_id = $fila['empresa_id'];

  // Mostrar la empresa correspondiente
  $consulta2 = "SELECT razon_social from edificio, empresas WHERE empresas.id = edificio.empresa_id AND empresas.id = $empresa_id";
  $resultado2 = mysqli_query($conexion, $consulta2);
  while ($row = mysqli_fetch_array($resultado2)) {
    $razon_soc = $row['razon_social'];
  }

	echo "<tr>";
      ?>
		<td class="text-center">
					<a href="edit_edificio.php?id=<?php echo $fila['id_edificio']; ?>" class="btn btn-sm btn-success"><i class="fa fa-pencil" aria-hidden="true"></i></a>
				    <a href="copy_edificio.php?id=<?php echo $fila['id_edificio']; ?>"class="btn btn-sm btn-warning"><i class="fa fa-clone" aria-hidden="true"></i></a>
					<a href="eliminar_edificio.php?id=<?php echo $fila['id_edificio']; ?>"class="btn btn-sm btn-danger"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
					<a href="ver_edificio.php?id=<?php echo $fila['id_edificio']; ?>" class="btn btn-info"><i class="fa fa-eye" aria-hidden="true"></i></a>
        </td>
        <?php
		ECHO " <TD>".utf8_decode($fila["id_edificio"])."</TD>";
        ECHO " <TD>".utf8_decode($razon_soc)."</TD>";
        ECHO " <TD>".utf8_decode($fila["descripcion"])."</TD>";
		ECHO " <TD>".utf8_decode($fila["calle"])."</TD>";
        ECHO " <TD>".utf8_decode($fila["numero_exterior"])."</TD>";	
        ECHO " <TD>".utf8_decode($fila["numero_interior"])."</TD>";
        ECHO " <TD>".utf8_decode($fila["colonia"])."</TD>";
        ECHO " <TD>".utf8_decode($fila["municipio"])."</TD>";
        ECHO " <TD>".utf8_decode($fila["entidad_federativa"])."</TD>";	
        ECHO " <TD>".utf8_decode($fila["codigo_postal"])."</TD>";	
        ECHO " <TD>".utf8_decode($fila["pais"])."</TD>";	
        ECHO " <TD>".utf8_decode($fila["direccion_gps"])."</TD>";	
        ECHO " <TD>".utf8_decode($fila["contacto_nombre"])."</TD>";	
        ECHO " <TD>".utf8_decode($fila["contacto_apellido"])."</TD>";
        ECHO " <TD>".utf8_decode($fila["contacto_puesto"])."</TD>";
        ECHO " <TD>".utf8_decode($fila["contacto_email"])."</TD>";
        ECHO " <TD>".utf8_decode($fila["contacto_telefono"])."</TD>";
		ECHO " <TD>".utf8_decode($fila["requisitos_acceso"])."</TD>";
     
  echo "</tr>";
}
ECHO "</table>";

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$connect = mysqli_connect("localhost", "veco", "Vec83Dv19iSa@", "veco_sims_devecchi");
$query = "SELECT * FROM edificio";
$result = mysqli_query($connect, $query);
	//////////////////////////////////////////////////////////////////////////////////////////////////7

while($reg = mysqli_fetch_array($result))
      {
		  ?>
	 <?php
      }
      ?>
    
</div>
 <?php
}else{
	header('Location: index.php');
}
?>
	<script src="/devecchi/ejercicio/Resources/js/empresa.js"></script>
	<script src="/devecchi/ejercicio/Models/empresa.js"></script>
</body>
</html>