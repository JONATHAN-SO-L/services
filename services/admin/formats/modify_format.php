<?php
session_start();

if( $_SESSION['nombre']!="" && $_SESSION['clave']!="" && $_SESSION['tipo']=="admin"){
  include '../../assets/admin/navbar2.php';
  include '../../assets/admin/links2.php';
  require '../../functions/conex_serv.php';
  $format = $_SERVER['QUERY_STRING'];
  
  function mensaje_error() {
    echo'
                <div class="alert alert-danger alert-dismissible fade in col-sm-3 animated bounceInDown" role="alert" style="position:fixed; top:70px; right:10px; z-index:10;"> 
                    <a href="empresa.php"><button type="button" class="close"  data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                    <h4 class="text-center">OCURRIÓ UN ERROR</h4>
                    <p class="text-center">
                        No fue posible modificar el formato, por favor inténtalo de nuevo o contacta al Soporte Técnico.
                    </p>
                </div>
            '; 
  }

  function mensaje_ayuda(){
        echo '
            <div class="alert alert-success alert-dismissible fade in col-sm-3 animated bounceInDown" role="alert" style="position:fixed; top:70px; right:10px; z-index:10;"> 
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
            <h4 class="text-center"><strong>MODIFICACIÓN EXITOSA</strong></h4>
            <p class="text-center">
            Se modificó correctamente el formato.
            </p>
            </div>
            ';
    }

	function redirect_failed($format) {
		echo '
			<div class="container" style="margin-left: 40%">
				<img src="../../assets/img/loading_dvi.gif" height="40%" weight="40%">
				<br>
				<a href="modify_format.php?'.$format.'" class="btn btn-sm btn-danger" style="margin-left: 15%">Regresar</a>
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

    // Recuperación de la información cargada en sistema
    $form = 'formatos';
    $s_format = $con -> prepare("SELECT * FROM $form WHERE formato = :format");
    $s_format->bindValue(':format', $format);
    $s_format->setFetchMode(PDO::FETCH_OBJ);
    $s_format->execute();

    $f_format = $s_format->fetchAll();

    if ($s_format -> rowCount() > 0) {
        foreach ($f_format as $formato) {
            $forma = $formato -> formato;
            $nombre_formato = $formato -> nombre_formato;
            $revision_formato = $formato -> revision_formato;
        }
    } else {
        mensaje_error();
        redirect_failed($format);
        die();
    }

    if (isset($_POST['modificar_formato'])) {
        // Recepción de datos
        $formatos = 'formatos';
        $formato = $_POST['formato'];
        $nombre_formato = $_POST['nombre_formato'];
        $revision_formato = $_POST['revision_formato'];

        // Información para auditlog
        $tecnico = $_SESSION['nombre_completo'];
        require '../../assets/timezone.php';
        $fecha_hora_modificacion = date("d/m/Y H:i:s");

        $update_format = $con->prepare("UPDATE $formatos SET nombre_formato = ?, revision_formato = ?,
                                                             modifica_data = ?, fecha_hora_modificacion = ?
                                                        WHERE formato = ?");

        $val_update_format = $update_format->execute([$nombre_formato, $revision_formato, $tecnico, $fecha_hora_modificacion, $format]);

        if ($val_update_format) {
            // Registro en auditlog
            $log = 'auditlog';
            $movimiento = utf8_decode('El usuario '.$tecnico.' modifica el formato '.$nombre_formato.' con revision '.$revision_formato.' el '.$fecha_hora_modificacion.'');
            $url = $_SERVER['PHP_SELF'].'?'.$format;
            $database = 'SIS';
            $save_move = $con->prepare("INSERT INTO $log (movimiento, link, ddbb, usuario_movimiento, fecha_hora)
                                VALUES (?, ?, ?, ?, ?)");
            $val_save_move = $save_move->execute([$movimiento, $url, $database, $tecnico, $fecha_hora_modificacion]);

            if ($val_save_move) {
                mensaje_ayuda();
                redirect_success();
                die();
            } else {
                mensaje_error();
                redirect_failed($format);
                die();
            }
        } else {
            mensaje_error();
            redirect_failed($format);
            die();
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
                <h1 class="animated lightSpeedIn">Modificar Formato: <strong><?php echo $format; ?></strong></h1>
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
      <div class="panel panel-warning">
        <div class="panel-heading text-center"><strong>Para modificar un formato es necesario llenar los siguientes campos.</strong></div>
        <div class="panel-body">
            <form role="form" action="" method="POST" enctype="multipart/form-data">
						
						<div class="form-group">
                            <label class="col-sm-222 control-label"><i class="fa fa-file" aria-hidden="true"></i> Formato <i><u>(Este código es irremplazable ya que es con el que el sistema se comunica internamente)</u></i> *</label>
                            <div class='col-sm-110'>
                                <div class="input-group">
                                   <input class="form-control" type="text" name="formato" placeholder="Por ejemplo: FDV-S-032" value="<?php echo $forma; ?>" readonly>
								   <span class="input-group-addon"><i class="fa fa-eye"></i></span>
                                </div>
                            </div>
                        </div>
                    
                        <div class="form-group">
                            <label class="col-sm-222 control-label"><i class="fa fa-file" aria-hidden="true"></i> Nombre de Formato</label>
                            <div class='col-sm-110'>
                                <div class="input-group">
                                   <input class="form-control" type="text" name="nombre_formato" placeholder="Por ejemplo: FDV-S-032" value="<?php echo $nombre_formato; ?>">
								   <span class="input-group-addon"><i class="fa fa-pencil-square-o"></i></span>
                                </div>
                            </div>
                        </div>
						
						<div class="form-group">
                            <label class="col-sm-222 control-label"><i class="fa fa-barcode" aria-hidden="true"></i> REV:</label>
                             <div class="col-sm-110">
                              <div class='input-group'>
                                <input type="text" class="form-control" name="revision_formato" placeholder="Por ejemplo: 0; 05/20" value="<?php echo $revision_formato; ?>">
                                <span class="input-group-addon"><i class="fa fa-pencil-square-o"></i></span>
                              </div>
                          </div>
                        </div>

                        <div class="form-group">
                          <div class="col-sm-offset-2 col-sm-10 text-center"><br>
                              <button type="submit" name="modificar_formato" class="btn btn-danger">Modificar</button>
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