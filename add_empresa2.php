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
       <a href="empresa.php" ><button  type="submit" value="Regresar" name="" class="btn btn-primary" style="text-align:center"><i class="fa fa-reply"></i>&nbsp;&nbsp;Regresar</button></a>
	   </tr>
	</td>
	   </table>
		<!--************************************ Page content******************************-->
		<div class="container">
          <div class="row">
            <div class="col-sm-12">
              <div class="page-header2">
                <h1 class="animated lightSpeedIn">Nueva Empresa</h1>
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
        <div class="panel-heading text-center"><strong>Por favor llena todos los campos de este formulario para crear una nueva empresa, evita usar acentos.</strong></div>
        <div class="panel-body">
          <!--///////////////////////////////////   FORMULARIO    //////////////////////////////////////////////////////////////////-->
            <form role="form" action="add_empresa3.php" method="POST" enctype="multipart/form-data">						
            <div class="form-group">
              <label class="control-label"><i class="fa fa-building" aria-hidden="true"></i>&nbsp;RFC</label>
              <input type="text" id="input_user" class="form-control" name="rfc" maxlength="20" placeholder="Por ejemplo: XAXX010101000"  required>
            </div>
						
              <div class="form-group">
                <label class="col-sm-222 control-label"><i class="fa fa-building-o" aria-hidden="true"></i> Razón Social</label>
                <div class="col-sm-110">
                  <div class='input-group'>
                    <input type="text" class="form-control" name="razon_social" maxlength="100" placeholder="Por ejemplo: VECO S.A. de C.V." required>
                    <span class="input-group-addon"><i class="fa fa-pencil-square-o"></i></span>
                  </div>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-222 control-label"><i class="fa fa-user-circle-o" aria-hidden="true"></i> Nombre Usuario <i><u>(Por favor, introduce el usuario en mayúsulas)</u></i></label>
                <div class='col-sm-110'>
                  <div class="input-group">
                    <input class="form-control" type="text" name="nombre_corto" maxlength="20" placeholder="Por ejemplo: VECO" required>
                    <span class="input-group-addon"><i class="fa fa-pencil-square-o"></i></span>
                  </div>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-222 control-label"><i class="fa fa-road" aria-hidden="true"></i> Calle</label>
                <div class='col-sm-110'>
                  <div class="input-group">
                    <input class="form-control" type="text" name="calle" maxlength="80" placeholder="Por ejemplo: 13 Este" required>
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
                <label  class="col-sm-222 control-label"><i class="fa fa-location-arrow" aria-hidden="true"></i> Número Interior <u>(Opcional)*</u></label>
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
                    <input class="form-control" name="entidad_federativa" placeholder="Morelos" required>
                    <span class="input-group-addon"><i class="fa fa-pencil-square-o"></i></span>
                  </div>
                </div>
              </div>

              <div class="form-group">
                <label  class="col-sm-222 control-label"><i class="fa fa-map-signs" aria-hidden="true"></i> Código Postal</label>
                <div class="col-sm-110">
                  <div class='input-group'>
                    <input type="text" class="form-control" name="codigo_postal" placeholder="Por ejemplo: 62578" required>
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
                <label  class="col-sm-222 control-label"><i class="fa fa-map-marker" aria-hidden="true"></i> Dirección GPS <u>(Opcional)*</u></label>
                <div class="col-sm-110">
                  <div class='input-group'>
                    <input type="text" class="form-control" name="direccion_gps" placeholder="Por ejemplo: https://maps.app.goo.gl/qfgrHj5aq96Su9Ba9">
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
                    <input type="text" class="form-control" name="contacto_puesto" maxlength="30" placeholder="Por ejemplo: Dirección General" required>
                    <span class="input-group-addon"><i class="fa fa-pencil-square-o"></i></span>
                  </div>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-222 control-label"><i class="fa fa-envelope" aria-hidden="true"></i> Email del Contacto</label>
                <div class="col-sm-110">
                  <div class='input-group'>
                    <input type="email" class="form-control" name="contacto_email" placeholder="Por ejemplo: email@email.com" required>
                    <span class="input-group-addon"><i class="fa fa-pencil-square-o"></i></span>
                  </div>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-222 control-label"><i class="fa fa-phone-square" aria-hidden="true"></i> Telefono del Contacto</label>
                <div class="col-sm-110">
                  <div class='input-group'>
                    <input type="text" class="form-control" name="contacto_telefono" maxlength="50" placeholder="Por ejemplo: 01 800 333 1111" required>
                    <span class="input-group-addon"><i class="fa fa-pencil-square-o"></i></span>
                  </div>
                </div>
              </div>

              <div class="form-group">
              <div class="col-sm-offset-2 col-sm-10 text-center">
              <button type="submit" name="guardar_empresa" class="btn btn-success">Guardar</button>
              </div>
              </div>
              <br>
              </form>
              </div>
              </div>
              </div>
              </div>
              </div>
	  <?php
}else{
	header('Location: index.php');
}
?>
<script>
    $(document).ready(function(){
        $("#input_user").keyup(function(){
            $.ajax({
                url:"./process/val_empresa.php?id="+$(this).val(),
                success:function(data){
                    $("#com_form").html(data);
                }
            });
        });
    });
</script>
</body>
</html>