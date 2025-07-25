<?php
/*
 * @author: Meraz Prudencio Griselda  
 * ghriz2811@gmail.com
 * @version: 08/2019 v1
 */
 ?>
<?php
    if(isset($_POST['nombre_login']) && isset($_POST['contrasena_login'])){
        include "./process/login.php";
    }
?>
<style type="text/css">
/* Estilo de barra de navegación De Vecchi */
        .navbar-sop {
        background-color: #137098;
        border-color: #1fe61c;
    }
</style>
<nav class="navbar navbar-sop navbar-fixed-top" role="navigation">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                <span class="sr-only">toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span> 
</button>
            <a style="color: white;" class="navbar-brand" href="index.php"><i class="fa fa-cubes"></i>&nbsp;&nbsp;De Vecchi</a>
        </div>
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <?php if(isset($_SESSION['tipo']) && isset($_SESSION['nombre'])): ?>
            <ul class="nav navbar-nav navbar-right">
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <span class="glyphicon glyphicon-user"></span> &nbsp; <?php echo $_SESSION['nombre']; ?><b class="caret"></b>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- usuarios -->
                        <?php if($_SESSION['tipo']=="user"): ?>
						<li>
						<a href="./inicio_user.php"><span class="fa fa-home"></span> &nbsp; Consulta</a>
						</li>
						<li>
                            <a href="password_user.php"><span class="fa fa-key"></span>&nbsp;&nbsp;Cambio de contraseña</a>
                        </li>
                        <!--li>
                            <a href="#!"><span class="glyphicon glyphicon-comment"></span>&nbsp;&nbsp;Mensajes</a>
                        </li-->
                        <!--li>
                            <a href="./index.php?view=configuracion"><i class="fa fa-cogs"></i>&nbsp;&nbsp;Configuracion</a>
                        </li--> 
                        <?php endif; ?>
						
						<!-- usuarios _devecchi -->
                        <?php if($_SESSION['tipo']=="devecchi"): ?>
						<li>
						<a href="./seccion.php"><span class="fa fa-home"></span> &nbsp; Servicio</a>
						</li>
						<li>
                            <a href="password_dvi.php"><span class="fa fa-key"></span>&nbsp;&nbsp;Cambio de contraseña</a>
                        </li>
                        <!--li>
                            <a href="#!"><span class="glyphicon glyphicon-comment"></span>&nbsp;&nbsp;Mensajes</a>
                        </li-->
                        <!--li>
                            <a href="./index.php?view=configuracion"><i class="fa fa-cogs"></i>&nbsp;&nbsp;Configuracion</a>
                        </li--> 
                        <?php endif; ?>

                        <!-- admins -->
                        <?php if($_SESSION['tipo']=="admin"): ?>
                        <li>
                           <a href="./inicio.php"><span class="fa fa-home"></span> &nbsp; Home</a>
                        </li>
                        <li>
                            <a href="password_admin.php"><span class="fa fa-key"></span>&nbsp;&nbsp;Cambio de contraseña</a>
                        </li>
                        <!--li>
                            <a href="admin.php?view=users"><span class="glyphicon glyphicon-user"></span> &nbsp;Administrar Usuarios</a>
                        </li>
                        <li>
                            <a href="admin.php?view=admin"><span class="glyphicon glyphicon-user"></span> &nbsp;Administrar Administradores</a>
                        </li>
                        <li>
                            <a href="admin.php?view=config"><i class="fa fa-cogs"></i> &nbsp; Configuracion</a>
                        </li>
						<li>
							<a href="./index.php?view=registro"><i class="fa fa-users"></i>&nbsp;&nbsp;Registrar Usuario</a>
						</li-->
                        <?php endif; ?> 
                        <li class="divider"></li>
                        <li ><a href="./process/logout.php"><i class="fa fa-power-off"></i>&nbsp;&nbsp;Cerrar sesión</a></li>
                    </ul>
                </li>
            </ul>
            <?php endif; ?>
            <ul class=" nav navbar-nav navbar-right">
                <!--li>
                    <a href="./inicio.php"><span class="glyphicon fa fa-home"></span> &nbsp; Home</a>
                </li>
				<li>
                    <a href="./seccion.php"><span class="glyphicon fa fa-cogs"></span> &nbsp; Servicio</a>
                </li>
                <li>
                    <a href="./inicio_user.php"><span class="glyphicon fa fa-users"></span> &nbsp; Cliente</a>
                </li>
                <li>
                    <a href="./index.php?view=soporte"><span class="glyphicon glyphicon-flag"></span>&nbsp;&nbsp;Incidencias</a>
                </li-->

                <?php if(!isset($_SESSION['tipo']) && !isset($_SESSION['nombre'])): ?>
                <!--li>
                    <a href="./index.php?view=registro"><i class="fa fa-users"></i>&nbsp;&nbsp;Registro</a>
                </li-->
                <li>
                    <a href="#!" data-toggle="modal" data-target="#modalLog"><span class="glyphicon glyphicon-user"></span>&nbsp;&nbsp;Login</a>
                </li>
                <?php endif; ?>

            </ul>
            <!--form class="navbar-form navbar-right hidden-xs" role="search">
                <div class="form-group">
                    <input type="text" class="form-control" placeholder="Buscar">
                </div>
                <button type="button" class="btn btn-success">Buscar</button>
            </form-->
        </div>
    </div>
</nav>

<div class="modal fade" tabindex="-1" role="dialog" id="modalLog" aria-hidden="true">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
              <h4 class="modal-title text-center text-primary" id="myModalLabel">Bienvenido</h4>
            </div>
          <form action="" method="POST" style="margin: 20px;">
              <div class="form-group">
                  <label><span class="glyphicon glyphicon-user"></span>&nbsp;Nombre</label>
                  <input type="text" class="form-control" name="nombre_login" placeholder="Escribe tu nombre" required=""/>
              </div>
              <div class="form-group">
                  <label><span class="glyphicon glyphicon-lock"></span>&nbsp;Contraseña</label>
                  <input type="password" class="form-control" name="contrasena_login" placeholder="Escribe tu contraseña" required=""/>
              </div>
              
              <div class="modal-footer">
                <button type="submit" class="btn btn-primary btn-sm">Iniciar sesión</button>
                <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Cancelar</button>
              </div>
          </form>
      </div>
    </div>
</div>