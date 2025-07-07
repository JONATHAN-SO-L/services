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
  
  <div class="container">
          <div class="row">
            <div class="col-sm-12">
           <div class="page-header2">		   
<a class="boton_personalizado" href="tabla_usuarios.php"><i class="fa fa-pencil"></i></a><strong>&nbsp;&nbsp;&nbsp;&nbsp;Usuario</strong>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<a class="boton_personalizado" href="tabla_devecchi.php"><i class="fa fa-pencil"></i></a><strong>&nbsp;&nbsp;&nbsp;&nbsp;Usuario De Vecchi</strong>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<a class="boton_personalizado" href="tabla_admin.php"><i class="fa fa-pencil"></i></a><strong>&nbsp;&nbsp;&nbsp;&nbsp;Administrador</strong>

				</div>
            </div>
          </div>
        </div>
		
		<br>
		<br>
		
  <table>
  <td>
  <tr>
		    <a href="add_us_dvi.php" ><button type="submit" value="Adicionar" name="" class="btn btn-primary" style="text-align:center"><i class="fa fa-plus"></i>&nbsp;&nbsp;Adicionar</button></a>
			<td>
 <tr>
      <button onClick="document.location.reload();" type="submit" class="btn btn-success" data-toggle="tooltip" data-placement="top" title="Click Actualizar Datos" HSPACE="10" VSPACE="10"><i class="fa fa-refresh fa-spin  fa-fw"></i>
<span class="sr-only">Loading...</span></button>
        </tr>
          </td>
			   
       </table>
		<!--************************************ Page content******************************-->
		<div class="container">
          <div class="row">
            <div class="col-sm-12">
              <div class="page-header2">
                <h1 class="animated lightSpeedIn">Usuario De Vecchi</h1>
                <span class="label label-danger"></span>
                <p class="pull-right text-primary">
               </p>
              </div>
			 </div>
          </div>
        </div>
		<!--************************************ Page content******************************-->
		<form style="float: left; height: 35px;" class="busc_dato" method="POST" action="usuarios.php" onSubmit="return validarForm(this)"><i class="fa fa-search" aria-hidden="true"></i>
				<input placeholder=" Id, Usuario o Nombre" type="text" id="busqueda_us" />
				<div id="resultado"></div>
				</form>
				
				<div id="datos"></div>
				<br>
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
                    url: "buscar_us_dvi.php",
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
		<div class="buscar_index">	
    <?php
	$usuario = "veco_dvi";
	$password = "Vec83Dv19iSa@";
	$servidor = "localhost";
	$basededatos = "veco_sims_devecchi";
	
	// creación de la conexión a la base de datos con mysql_connect()
	$conexion = mysqli_connect( $servidor, $usuario, $password ) or die ("No se ha podido conectar al servidor de Base de datos");
	
	// Selección de la base de datos
	$db = mysqli_select_db( $conexion, $basededatos ) or die ( "Upps! Pues va a ser que no se ha podido conectar a la base de datos" );
	// establecer y realizar consulta. guardamos en variable.
	$consulta = "SELECT * FROM usuario_devecchi";
	//SELECT usuario.nombre_usuario, empresas.nombre_corto from usuario inner join empresas  on  usuario.empresa_id=empresas.id;

	//$consulta = "SELECT usuario.id_cliente,usuario.nombre_usuario,usuario.email_cliente, usuario.clave, usuario.empresa_id, empresas.nombre_completo from usuario inner join empresas  on  usuario.empresa_id=empresas.id";

	$resultado = mysqli_query( $conexion, $consulta ) or die ( "Algo ha ido mal en la consulta a la base de datos");
	
ECHO "<table class='table table-hover table-striped table-bordered'><th WIDTH=10% >Acción</th><th WIDTH=2%>Id</th><th WIDTH=10%>Nombre Usuario</th><th WIDTH=12%>Clave</th><th>Nombre</th><th>Apellido</th><th>Especialidad</th><th>Horario</th><th>Email</th><th>Telefono</th>";
while ($fila = mysqli_fetch_array( $resultado )){
	echo "<tr>";
      ?>
		<td class="text-center">
                    <a href="edit_us_dvi.php?id=<?php echo $fila['id']; ?>" class="btn btn-sm btn-success"><i class="fa fa-pencil" aria-hidden="true"></i></a>
					<a href="ver_us_dvi.php?id=<?php echo $fila['id']; ?>" class="btn btn-info"><i class="fa fa-eye" aria-hidden="true"></i></a>
					<a href="eliminar_us_dvi.php?id=<?php echo $fila['id']; ?>"class="btn btn-sm btn-danger"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
        </td>
        <?php
		ECHO " <TD>".utf8_decode($fila["id"])."</TD>";
		ECHO " <TD>".utf8_encode($fila["nombre_usuario"])."</TD>";
		ECHO " <TD>".utf8_decode($fila["clave"])."</TD>";
		//ECHO " <TD>".utf8_decode($fila["fotografia"])."</TD>";
		ECHO " <TD>".utf8_decode($fila["nombre"])."</TD>";
		ECHO " <TD>".utf8_decode($fila["apellido"])."</TD>";
		ECHO " <TD>".utf8_decode($fila["especialidad"])."</TD>";
		ECHO " <TD>".utf8_decode($fila["horario"])."</TD>";
		ECHO " <TD>".utf8_decode($fila["email_devecchi"])."</TD>";
        ECHO " <TD>".utf8_encode($fila["telefono"])."</TD>";
     
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
<footer></footer>
  <script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
    <script src="js/index.js"></script>
</body>
</html>