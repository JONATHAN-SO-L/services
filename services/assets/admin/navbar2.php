<title>SIS - Administrador</title>

<!------------------------------------
MENÚ DE NAVEGACIÓN PARA LA VISTA ADMIN
------------------------------------->

<header id="main-header">
<nav id="sidenav">
    <div id="sidenav-header">
        <div id="profile-picture">
            <img style="background-color: white;" src="../../assets/img/dvi_logo1.png"/>
        </div>

        <a id="profile-link"><h4>&nbsp;&nbsp;&nbsp;<strong><?php echo $_SESSION['usuario']; ?></strong></h4>
    </div>
    <div id="account-actions">
        <a href="../../../index.php" ><button class="btn btn-success" title="Regresar al incio"><i class="fa fa-home"></i></button></a>
    </div>
       
    <ul id="main-nav">
      <?php
      switch ($_SESSION['tipo']) {
        case 'admin': ?>

        <ul id="main-nav">
        <li>
        <a href="../../../inicio.php">
        <i class="fa fa-grav"></i>
        Inicio
        </a>
        </li>
        <li>
        <a href="../../../empresa.php">
        <i class="fa fa-hospital-o"></i>
        Empresa
        </a>
        </li>
        <li>
        <a href="../../../edificio.php">
        <i class="fa fa-university"></i>
        Edificio
        </a>
        </li>
        <!--li>
        <a href="ubicacion.php">
        <i class="fa fa-map-marker"></i>
        Ubicación
        </a>
        </li>
        <li>
        <a href="area.php">
        <i class="fa fa-rss-square"></i>
        Área
        </a>
        </li-->

        <li>
          <a href="../accountant/">
            <i class="fa fa-tachometer" aria-hidden="true"></i>
            Contadores
          </a>
        </li>
        <li>
          <a href="../instruments/">
            <i class="fa fa-tasks" aria-hidden="true"></i>
            Instrumentos
          </a>
        </li>
        <li>
          <a href="../particles/">
            <i class="fa fa-filter" aria-hidden="true"></i>
            Partículas
          </a>
        </li>
        
        <!--li>
        <a href="equipo.php">
        <i class="fa fa-cubes"></i>
        Equipo
        </a>
        </li>
        <li>
        <a href="tarea.php">
        <i class="fa fa-cogs"></i>
        Config
        </a>
        </li-->
        <li>
        <a href="../../../seccion_admin.php">
        <i class="fa fa-wrench"></i>
        Servicio
        </a>
        </li>	
        <!--li>
        <a href="tabla_servicios.php">
        <i class="fa fa-pencil-square-o"></i>
        Editar Servicio
        </a>
        </li>
        <li>
        <a href="diario_servic.php">
        <i class="fa fa-calendar-check-o"></i>
        Diario
        </a>
        </li>	 
        <li>
        <a href="menu_grafica.php">
        <i class="fa fa-line-chart"></i>
        Grafica
        </a>
        </li-->

        <li>
        <a href="../formats/">
        <i class="fa fa-files-o" aria-hidden="true"></i>
        Formatos y REV.
        </a>
        </li>
        <li>
        <a href="../auditlog/">
        <i class="fa fa-search-plus" aria-hidden="true"></i>
        AuditLog
        </a>
        </li>

        <!--li>
        <a href="../../../tabla_usuarios.php">
        <i class="fa fa-user"></i>
        Usuario
        </a>
        </li-->
        </ul>
        
        <?php break;
      }

      switch ($_SESSION['tipo_usuario']) {
        case 'A': ?>
        <title>SIS - Administrador</title>
        <<ul id="main-nav">
        <li>
        <a href="../../../inicio.php">
        <i class="fa fa-grav"></i>
        Inicio
        </a>
        </li>
        <li>
        <a href="../../../empresa.php">
        <i class="fa fa-hospital-o"></i>
        Empresa
        </a>
        </li>
        <li>
        <a href="../../../edificio.php">
        <i class="fa fa-university"></i>
        Edificio
        </a>
        </li>
        <!--li>
        <a href="ubicacion.php">
        <i class="fa fa-map-marker"></i>
        Ubicación
        </a>
        </li>
        <li>
        <a href="area.php">
        <i class="fa fa-rss-square"></i>
        Área
        </a>
        </li-->

        <li>
          <a href="../accountant/">
            <i class="fa fa-tachometer" aria-hidden="true"></i>
            Contadores
          </a>
        </li>
        <li>
          <a href="../instruments/">
            <i class="fa fa-tasks" aria-hidden="true"></i>
            Instrumentos
          </a>
        </li>
        <li>
          <a href="../particles/">
            <i class="fa fa-filter" aria-hidden="true"></i>
            Partículas
          </a>
        </li>
        
        <!--li>
        <a href="equipo.php">
        <i class="fa fa-cubes"></i>
        Equipo
        </a>
        </li>
        <li>
        <a href="tarea.php">
        <i class="fa fa-cogs"></i>
        Config
        </a>
        </li-->
        <li>
        <a href="../../../seccion_admin.php">
        <i class="fa fa-wrench"></i>
        Servicio
        </a>
        </li>	
        <!--li>
        <a href="tabla_servicios.php">
        <i class="fa fa-pencil-square-o"></i>
        Editar Servicio
        </a>
        </li>
        <li>
        <a href="diario_servic.php">
        <i class="fa fa-calendar-check-o"></i>
        Diario
        </a>
        </li>	 
        <li>
        <a href="menu_grafica.php">
        <i class="fa fa-line-chart"></i>
        Grafica
        </a>
        </li-->

        <li>
        <a href="../formats/">
        <i class="fa fa-files-o" aria-hidden="true"></i>
        Formatos y REV.
        </a>
        </li>
        <li>
        <a href="../auditlog/">
        <i class="fa fa-search-plus" aria-hidden="true"></i>
        AuditLog
        </a>
        </li>

        <li>
        <a href="../../admin/users/usuarios_sis.php">
        <i class="fa fa-user"></i>
        Usuarios
        </a>
        </li>
        </ul>
        <?php break;
      }
      ?>
    </ul>
  </nav>

</header>