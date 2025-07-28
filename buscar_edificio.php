<!--************
 * @author: Meraz Prudencio Griselda  
 
 * ghriz2811@gmail.com

 * @version: 08/2019 v1
 
 */********-->
<style>
#body{
    width: calc(100% + 500px);
    height: 193px;
    margin: -150px 0 0 -90px;
    background: white;
    padding: 50px;
}	  
table {
    background-color: #6ce42e52;
}
.table-striped > tbody > tr:nth-child(odd) > td, .table-striped > tbody > tr:nth-child(odd) > th {
    background-color: #32a3f354;
}
.buscar_index{
display: none;
}
</style>

<?php

	   $buscar = $_POST['busc'];
        
      if(!empty($buscar)) {
            buscar($buscar);
      }
        
      function buscar($bu) {
		  $usuario = "veco";
			$password = "Vec83Dv19iSa@";
			$servidor = "localhost";
		  $basededatos = "veco_sims_devecchi";
		  
		  $conexion = mysqli_connect( $servidor, $usuario, $password ) or die ("No se ha podido conectar al servidor de Base de datos");
	$conexion ->set_charset('utf8');
	$conexion->query("SET NAMES UTF8");
    $conexion->query("SET CHARACTER SET utf8");
	
	// Selección de la base de datos
	$db = mysqli_select_db( $conexion, $basededatos ) or die ( "Upps! Pues va a ser que no se ha podido conectar a la base de datos" );
		   
		   
		   
		   $consulta = "SELECT * FROM edificio";
	$resultado = mysqli_query( $conexion, $consulta ) or die ( "Algo ha ido mal en la consulta a la base de datos");
		   
        $consulta = "SELECT * from edificio inner join empresas on edificio.empresa_id=empresas.id 
	WHERE id_edificio LIKE '%".$bu."%' or descripcion LIKE '%".$bu."%'  or nombre_corto LIKE '%".$bu."%' ";
            //$sql = mysqli_query("SELECT * FROM empresas WHERE nombre LIKE '%".$b."%' LIMIT 9" ,$con);
           $sql = mysqli_query( $conexion, $consulta ) or die ( "Algo ha ido mal en la consulta a la base de datos");  
		   
            $contar = @mysqli_num_rows($sql);
	
            //if($contar == 0){
				if($sql->num_rows == 0){
                  echo "No se han encontrado resultados para '<b>".$bu."</b>'.";
            }else{		
				ECHO "<table class='table table-hover table-striped table-bordered'><th>Acción</th><th>Id</th><th>Empresa</th><th>Descrpción</th><th>Calle</th><th>Núm Exterior</th><th>Núm Interior</th><th>Colonia</th><th>Municipio</th><th>Entidad Federativa</th><th>Codigo Postal</th><th>País</th><th>Dirección GPS</th><th>Nombre de Contacto</th><th>Apellido de Contacto</th><th>Puesto de Contacto</th><th>Email de Contacto</th><th>Telefono de Contacto</th><th>Requisitos de Acceso</th>";

				 while($row=$sql->fetch_assoc()){
				  echo "<tr>";
      ?>
		<td class="text-center"><a href="edit_empresa.php?id=<?php echo $row['id_edificio']; ?>" class="btn btn-sm btn-success" title="Editar"><i class="fa fa-pencil" aria-hidden="true"></i></a>
				    <a href="copy_empresa.php?id=<?php echo $row['id_edificio']; ?>"class="btn btn-sm btn-warning" title="Copiar"><i class="fa fa-clone" aria-hidden="true"></i></a>
					<!--a href="eliminar_empresa.php?id=<?php echo $row['id_edificio']; ?>"class="btn btn-sm btn-danger" title="Borrar"><i class="fa fa-trash-o" aria-hidden="true"></i></a-->
					<a href="ver_empresa.php?id=<?php echo $row['id_edificio']; ?>" class="btn btn-info" title="Ver"><i class="fa fa-eye" aria-hidden="true"></i></a>
         </td>
        <?php
		ECHO " <TD>".utf8_decode($row["id_edificio"])."</TD>";
        ECHO " <TD>".utf8_decode($row["nombre_corto"])."</TD>";
        ECHO " <TD>".utf8_decode($row["descripcion"])."</TD>";
		ECHO " <TD>".utf8_decode($row["calle"])."</TD>";
        ECHO " <TD>".utf8_decode($row["numero_exterior"])."</TD>";	
        ECHO " <TD>".utf8_decode($row["numero_interior"])."</TD>";
        ECHO " <TD>".utf8_decode($row["colonia"])."</TD>";
        ECHO " <TD>".utf8_decode($row["municipio"])."</TD>";
        ECHO " <TD>".utf8_decode($row["entidad_federativa"])."</TD>";	
        ECHO " <TD>".utf8_decode($row["codigo_postal"])."</TD>";	
        ECHO " <TD>".utf8_decode($row["pais"])."</TD>";	
        ECHO " <TD>".utf8_decode($row["direccion_gps"])."</TD>";	
        ECHO " <TD>".utf8_decode($row["contacto_nombre"])."</TD>";	
        ECHO " <TD>".utf8_decode($row["contacto_apellido"])."</TD>";
        ECHO " <TD>".utf8_decode($row["contacto_puesto"])."</TD>";
        ECHO " <TD>".utf8_decode($row["contacto_email"])."</TD>";
        ECHO " <TD>".utf8_decode($row["contacto_telefono"])."</TD>";
		ECHO " <TD>".utf8_decode($row["requisitos_acceso"])."</TD>";
  echo "</tr>";
            }
        }
  }
  ?>