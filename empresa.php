<?php
session_start();

if( $_SESSION['nombre']!="" && $_SESSION['clave']!="" && $_SESSION['tipo']=="admin"){ 
    include './services/assets/admin/navbar.php';
    include './services/assets/admin/links.php';
?>
<script src="./services/assets/css/main.css"></script>
<meta charset="utf-8">
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
   <a href="add_empresa2.php" ><button type="submit" class="btn btn-success" style="text-align:center"><i class="fa fa-plus"></i>&nbsp;&nbsp;Nueva Empresa</button></a>
			<td>
 <tr>
      <button onClick="document.location.reload();" type="submit" class="btn btn-warning" data-toggle="tooltip" data-placement="top" title="Click Actualizar Datos" HSPACE="10" VSPACE="10"><i class="fa fa-refresh fa-spin  fa-fw"></i>
		<span class="sr-only">Loading...</span></button>
        </tr>
          </td>
    </table>
				<!--************************************ Page content******************************-->
		<div class="container" >
          <div class="row">
            <div class="col-sm-12">
           <div class="page-header2">
				
                <h1 class="animated lightSpeedIn">Empresa</h1>
                <span class="label label-danger"></span> 		 
				<p class="pull-right text-primary"></p>
		   </div>
            </div>
          </div>
        </div>
		<!--************************************ Page content******************************-->
		<form style="float: left; height: 35px;" class="busc_dato" method="POST" action="usuarios.php" onSubmit="return validarForm(this)"><i class="fa fa-search" aria-hidden="true"></i>
				<input placeholder=" Id,  RFC, Razon Social" type="text" id="busqueda_us"/>
				<div id="resultado"></div>
				</form>
				
				<div id="datos"></div>
				
<script>
$(document).ready(function(){
                                
        var consulta;
                                                                          
         //hacemos focus al campo de búsqueda
        $("#busqueda_us").focus();
                                                                                                    
        //comprobamos si se pulsa una tecla
        $("#busqueda_us").keyup(function(e){
                                     
              //obtenemos el texto introducido en el campo de búsqueda
              consulta = $("#busqueda_us").val();
                                                                           
              //hace la búsqueda
                                                                                  
              $.ajax({
                    type: "POST",
                    url: "buscar.php",
                    data: "b="+consulta,
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
		<div class="buscar_index" style="width: 2950px;">	
    <?php
	$usuario = "veco_dvi";
	$password = "Vec83Dv19iSa@";
	$servidor = "localhost";
	$basededatos = "veco_sims_devecchi";
	
	// creación de la conexión a la base de datos con mysql_connect()
	$conexion = mysqli_connect( $servidor, $usuario, $password ) or die ("No se ha podido conectar al servidor de Base de datos");
	
	// Selección de la base de datos
	$db = mysqli_select_db( $conexion, $basededatos ) or die ( "Upps! Pues va a ser que no se ha podido conectar a la base de datos" );
	$consulta = "SELECT * FROM empresas";
	$resultado = mysqli_query( $conexion, $consulta ) or die ( "Algo ha ido mal en la consulta a la base de datos");
	
ECHO "<table class='table table-hover table-striped table-bordered'>
        <th>Acción</th>
        <th>Id</th>
        <th>RFC</th>
        <th>Razón Social</th>
        <th>Nombre Corto</th>
        <th>Calle</th>
        <th>Número Ex</th>
        <th>Número Int</th>
        <th>Colonia</th>
        <th>Municipio</th>
        <th>Entidad</th>
        <th>Codigo Postal</th>
        <th>País</th>
        <th>Dirección GPS</th>
        <th>Nombre de Contacto</th>
        <th>Apellido de Contacto</th>
        <th>Puesto de Contacto</th>
        <th>Email de Contacto</th>
        <th>Telefono de Contacto</th>";
while ($fila = mysqli_fetch_array( $resultado )){
	echo "<tr>";
      ?>
		<td class="text-center">
                    <a href="edit_empresa.php?id=<?php echo $fila['id']; ?>" class="btn btn-sm btn-success"><i class="fa fa-pencil" aria-hidden="true"></i></a>
                    
                    <a href="copy_empresa.php?id=<?php echo $fila['id']; ?>"class="btn btn-sm btn-warning" title="Copiar"><i class="fa fa-clone" aria-hidden="true"></i></a>
					
					<a href="eliminar_empresa.php?id=<?php echo $fila['id']; ?>"class="btn btn-sm btn-danger"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
					
					<a href="ver_empresa.php?id=<?php echo $fila['id']; ?>" class="btn btn-info"><i class="fa fa-eye" aria-hidden="true"></i></a>
        </td>
        <?php
		ECHO " <TD>".utf8_decode($fila["id"])."</TD>";
		ECHO " <TD>".utf8_decode($fila["rfc"])."</TD>";
        ECHO " <TD>".utf8_encode($fila["razon_social"])."</TD>";
		ECHO " <TD>".utf8_decode($fila["nombre_corto"])."</TD>";
		ECHO " <TD>".utf8_decode($fila["calle"])."</TD>";
		ECHO " <TD>".utf8_decode($fila["numero_exterior"])."</TD>";	
		ECHO " <TD>".utf8_decode($fila["numero_interior"])."</TD>";
		ECHO " <TD>".utf8_decode($fila["colonia"])."</TD>";
		ECHO " <TD>".utf8_encode($fila["municipio"])."</TD>";
		ECHO " <TD>".utf8_decode($fila["entidad_federativa"])."</TD>";
		ECHO " <TD>".utf8_decode($fila["codigo_postal"])."</TD>";
		ECHO " <TD>".utf8_decode($fila["pais"])."</TD>";
		ECHO " <TD>".utf8_decode($fila["direccion_gps"])."</TD>";
		ECHO " <TD>".utf8_decode($fila["contacto_nombre"])."</TD>";
		ECHO " <TD>".utf8_decode($fila["contacto_apellido"])."</TD>";
		ECHO " <TD>".utf8_decode($fila["contacto_puesto"])."</TD>";
		ECHO " <TD>".utf8_decode($fila["contacto_email"])."</TD>";
		ECHO " <TD>".utf8_decode($fila["contacto_telefono"])."</TD>";
     
  echo "</tr>";
}
ECHO "</table>";


		  ?>
	
     </div>
 <?php
}else{
	header('Location: index.php');
	 }
?>
</body>
</html>