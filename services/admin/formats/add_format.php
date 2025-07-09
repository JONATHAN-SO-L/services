<?php
session_start();

if( $_SESSION['nombre']!="" && $_SESSION['clave']!="" && $_SESSION['tipo']=="admin"){
  include '../../assets/admin/navbar2.php';
  include '../../assets/admin/links2.php';
  
  function mensaje_error() {
    echo'
                <div class="alert alert-danger alert-dismissible fade in col-sm-3 animated bounceInDown" role="alert" style="position:fixed; top:70px; right:10px; z-index:10;"> 
                    <a href="empresa.php"><button type="button" class="close"  data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                    <h4 class="text-center">OCURRIÓ UN ERROR</h4>
                    <p class="text-center">
                        No fue posible agregar el formato, valida si YA EXISTE o YA ESTÁ REGISTRADO, por favor inténtalo de nuevo o contacta al Soporte Técnico.
                    </p>
                </div>
            '; 
  }

  function mensaje_ayuda(){
        echo '
            <div class="alert alert-success alert-dismissible fade in col-sm-3 animated bounceInDown" role="alert" style="position:fixed; top:70px; right:10px; z-index:10;"> 
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
            <h4 class="text-center"><strong>REGISTRO EXITOSO</strong></h4>
            <p class="text-center">
            Se registró correctamente el formato a utilizar.
            </p>
            </div>
            ';
    }

	function redirect_failed() {
		echo '
			<div class="container" style="margin-left: 40%">
				<img src="../../assets/img/loading_dvi.gif" height="40%" weight="40%">
				<br>
				<a href="index.php" class="btn btn-sm btn-danger" style="margin-left: 15%">Regresar</a>
			</div>';
	}

	function redirect_success() {
		echo '
			<div class="container" style="margin-left: 40%">
				<img src="../../assets/img/loading_dvi.gif" height="40%" weight="40%">
				<br>
				<a href="index.php" class="btn btn-sm btn-success" style="margin-left: 15%">Continuar</a>
			</div>';
	}
    
    if (isset($_POST['guardar_formato'])) {
        require '../../functions/conex_serv.php';

        // Recpción de datos
        $nombre_formato = $_POST['nombre_formato'];
        $revision_formato = $_POST['revision_formato'];

        // Información para auditlog
        $tecnico = $_SESSION['nombre_completo'];
        require '../../assets/timezone.php';
        $fecha_hora_carga = date("d/m/Y H:i:s");

        // Validación de que no existe en sistema
        $formats = 'formatos';
        $log = 'auditlog';

        $s_format = $con -> prepare("SELECT formato FROM $formats WHERE formato = :nombre_formato");
        $s_format->bindValue(':nombre_formato', $nombre_formato);
        $s_format->setFetchMode(PDO::FETCH_OBJ);
        $s_format->execute();

        $f_format = $s_format->fetchAll();

        if ($s_format -> rowCount() > 0) {
            mensaje_error();
            redirect_failed();
        } else {
            // Registro de información
            $save_format = $con -> prepare("INSERT INTO $formats (formato, nombre_formato, revision_formato, registra_data, fecha_hora_registro)
                                                          VALUES (?, ?, ?, ?, ?)");
            
            $val_save_format = $save_format->execute([$nombre_formato, $nombre_formato, $revision_formato, $tecnico, $fecha_hora_carga]);

            if ($val_save_format) {
                // Registro en auditlog
                $movimiento = utf8_decode('El usuario '.$tecnico.' agrega un nuevo formato '.$nombre_formato.' con revision '.$revision_formato.' el '.$fecha_hora_carga.'');
                $url = $_SERVER['PHP_SELF'];
                $database = 'SIS';
                $save_move = $con->prepare("INSERT INTO $log (movimiento, link, ddbb, usuario_movimiento, fecha_hora)
                                    VALUES (?, ?, ?, ?, ?)");
                $val_save_move = $save_move->execute([$movimiento, $url, $database, $tecnico, $fecha_hora_carga]);

                if ($val_save_move) {
                    mensaje_ayuda();
                    redirect_success();
                    die();
                } else {
                    mensaje_error();
                    redirect_failed();
                    die();
                }
            } else {
                mensaje_error();
                redirect_failed();
                die();
            }
        }
    }
?>
<script src="../../assets/css/main.css"></script>

<section id="content">
  <header id="content-header">
  
  <table>  
    <td>
		<tr>
       <a href="index.php" ><button  type="submit" class="btn btn-primary" style="text-align:center"><i class="fa fa-reply"></i>&nbsp;&nbsp;Regresar</button></a>
	   </tr>
	</td>
	   </table>
		<!--************************************ Page content******************************-->
		<div class="container">
          <div class="row">
            <div class="col-sm-12">
              <div class="page-header2">
                <h1 class="animated lightSpeedIn">Nuevo Formato</h1>
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
        <div class="panel-heading text-center"><strong>Para poder crear un nuevo contador es necesario llenar los siguientes campos.</strong></div>
        <div class="panel-body">
            <form role="form" action="" method="POST" enctype="multipart/form-data">
                    
                        <div class="form-group">
                            <label class="col-sm-222 control-label"><i class="fa fa-file" aria-hidden="true"></i> Nombre de Formato</label>
                            <div class='col-sm-110'>
                                <div class="input-group">
                                   <input class="form-control" type="text" name="nombre_formato" placeholder="Por ejemplo: FDV-S-032" required>
								   <span class="input-group-addon"><i class="fa fa-pencil-square-o"></i></span>
                                </div>
                            </div>
                        </div>
						
						<div class="form-group">
                            <label class="col-sm-222 control-label"><i class="fa fa-barcode" aria-hidden="true"></i> REV:</label>
                             <div class="col-sm-110">
                              <div class='input-group'>
                                <input type="text" class="form-control" name="revision_formato" placeholder="Por ejemplo: 0; 05/20" required>
                                <span class="input-group-addon"><i class="fa fa-pencil-square-o"></i></span>
                              </div>
                          </div>
                        </div>

                        <div class="form-group">
                          <div class="col-sm-offset-2 col-sm-10 text-center"><br>
                              <button type="submit" name="guardar_formato" class="btn btn-success">Guardar</button>
                          </div>
                        </div>
                    <br>
            </form>
            </div>
          </div>
	  
	  
		</div>
	  </div>
      </div>
    </div>

<?php
}else{
	header('Location: ../../../index.php');
}
?>
</body>
</html>