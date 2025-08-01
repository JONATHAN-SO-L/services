<?php
/*
 * @author: Meraz Prudencio Griselda  
 * ghriz2811@gmail.com
 * @version: 08/2019 v1
 */
session_start();
include './lib/class_mysql.php';
include './lib/config.php';
header('Content-Type: text/html; charset=UTF-8');  
?>
<!DOCTYPE html>
<html>
     <head><meta http-equiv="Content-Type" content="text/html; charset=gb18030">
        <title>SIS - De Vecchi Ingenieros</title>
        <?php include "./inc/links.php"; ?>        
    </head>
    <body>   
        <?php include "./inc/navbar.php"; ?>
        <div class="container">
          <div class="row">
            <div class="col-sm-12">
              <div class="page-header">
	
                <div id="devecchi">
	<img src="./img/Logo-dvi.png" alt="Image" class="img-responsive" width="900" height="600" />
	</div>
                <p class="pull-right text-primary">
                  <strong>
                  <?php include "./inc/timezone.php"; ?>
                 </strong>
               </p>
              </div>
            </div>
          </div>
        </div>
        <?php
            if(isset($_GET['view'])){
                $content=$_GET['view'];
                $WhiteList=["index","productos","soporte","ticket","ticketcon","registro","configuracion"];
                if(in_array($content, $WhiteList) && is_file("./user/".$content."-view.php")){
                    include "./user/".$content."-view.php";
                }else{
        ?>
                <div class="container">
                    <div class="row">
                        <div class="col-sm-4">
                            <img src="./img/Stop.png" alt="Image" class="img-responsive"/><br>
                            <img src="./img/SadTux.png" alt="Image" class="img-responsive"/>
                            
                        </div>
                        <div class="col-sm-7 text-center">
                            <h1 class="text-danger">Lo sentimos, la opción que ha seleccionado no se encuentra disponible</h1>
                            <h3 class="text-info">Por favor intente nuevamente</h3>
                        </div>
                        <div class="col-sm-1">&nbsp;</div>
                    </div>
                </div>
        <?php
                }
            }else{
                include "./user/index-view.php";
            }
        ?>

        
      <?php include './inc/footer.php'; ?>
    </body>
</html>
