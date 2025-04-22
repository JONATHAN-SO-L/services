<?php
session_start();
include '../../../assets/layout2.php';

if ($_SESSION['nombre'] != '' && $_SESSION['tipo'] == 'devecchi' || $_SESSION['tipo'] == 'admin') {
section(); ?>

<table>
    <tr>
        <a href="modificar.php"><button type="submit" value="Volver" class="btn btn-primary" style="text-align:center"><i class="fa fa-reply"></i>&nbsp;&nbsp;Volver</button></a>
    </tr>
</table>

<div class="container" style="width: 1030px;">
    <div class="row" style="width: 770px;">
        <div class="col-sm-12">
            <div class="page-header2">
                <h1 class="animated lightSpeedIn">Certificado: # | Comportamiento</h1>
                <span class="label label-danger"></span> 		 
                <p class="pull-right text-warning"></p>
            </div>
        </div>
    </div>
</div>

<div class="container" style="margin-left: 5%;">
<div class="row">
<div class="col-sm-13">
<div class="panel panel-warning">
<div class="panel-heading text-center"><strong>Verifica que los nuevos datos son correctos</strong></div>
<div class="panel-body">
<form role="form" action="estandard_trazabilidad.php" method="POST" enctype="multipart/form-data">
<div>
<div class="container">
<table class="table table-responsive table-hover table-bordered table-striped table-warning" style="margin-left: -15px;">
<thead>
<tr>
<th>TAMAÑO DE PARTÍCULA NOMINAL</th>
<th>0.5 µm</th>
<th>1.0 µm</th>
<th>3.0 µm</th>
<th>5.0 µm</th>
</tr>
</thead>

<!-- PRIMERA FILA -->
<tbody>
<tr>
<td><strong>AMPLITUD ESPERADA (desde la última calibración)</strong></td>
<td><input class="form-control" type="number" name="amplitud_esperada_05" step="0.01" min="0" placeholder="Por ejemplo: 317">  mV</td>
<td><input class="form-control" type="number" name="amplitud_esperada_10" step="0.01" min="0" placeholder="Por ejemplo: 275"> mV</td>
<td><input class="form-control" type="number" name="amplitud_esperada_30" step="0.01" min="0" placeholder="Por ejemplo: 1414"> mV</td>
<td><input class="form-control" type="number" name="amplitud_esperada_50" step="0.01" min="0" placeholder="Por ejemplo: 395"> mV</td>
</tr>
</tbody>

<!-- SEGUNDA FILA -->
<tbody>
<tr>
<td><strong>TOLERANCIA</strong></td>
<td>± <input class="form-control" type="number" name="tolerancia_05" value="60" readonly>  mV</td>
<td>± <input class="form-control" type="number" name="tolerancia_10" value="30" readonly>  mV</td>
<td>± <input class="form-control" type="number" name="tolerancia_30" value="150" readonly>  mV</td>
<td>± <input class="form-control" type="number" name="tolerancia_50" value="50" readonly>  mV</td>
</tr>
</tbody>

<!-- TERCERA FILA -->
<tbody>
<tr>
<td><strong>COMO SE ENCUENTRA</strong></td>
<td><input class="form-control" type="number" name="como_encuentra_05" step="0.01" min="0" placeholder="Por ejemplo: 312">  mV</td>
<td><input class="form-control" type="number" name="como_encuentra_10" step="0.01" min="0" placeholder="Por ejemplo: 296">  mV</td>
<td><input class="form-control" type="number" name="como_encuentra_30" step="0.01" min="0" placeholder="Por ejemplo: 1598">  mV</td>
<td><input class="form-control" type="number" name="como_encuentra_50" step="0.01" min="0" placeholder="Por ejemplo: 407">  mV</td>
</tr>
</tbody>

<!-- CUARTA FILA -->
<tbody>
<tr>
<td><strong>PASA (S/N)</strong></td>
<td>
<select class="form-control" name="pasa_05">
<option value=""> - Selecciona - </option>
<option value="SI">SI</option>
<option value="NO">NO</option>
</select>
</td>
<td>
<select class="form-control" name="pasa_10">
<option value=""> - Selecciona - </option>
<option value="SI">SI</option>
<option value="NO">NO</option>
</select>
</td>
<td>
<select class="form-control" name="pasa_30">
<option value=""> - Selecciona - </option>
<option value="SI">SI</option>
<option value="NO">NO</option>
</select>
</td>
<td>
<select class="form-control" name="pasa_50">
<option value=""> - Selecciona - </option>
<option value="SI">SI</option>
<option value="NO">NO</option>
</select>
</td>
</tr>
</tbody>

<!-- QUINTA FILA -->
<tbody>
<tr>
<td><strong>CONDICIÓN FINAL</strong></td>
<td><input class="form-control" type="number" name="condicion_final_05" step="0.01" min="0" placeholder="Por ejemplo: 300">  mV</td>
<td><input class="form-control" type="number" name="condicion_final_10" step="0.01" min="0" placeholder="Por ejemplo: 300">  mV</td>
<td><input class="form-control" type="number" name="condicion_final_30" step="0.01" min="0" placeholder="Por ejemplo: 1618">  mV</td>
<td><input class="form-control" type="number" name="condicion_final_50" step="0.01" min="0" placeholder="Por ejemplo: 405">  mV</td>
</tr>
</tbody>
</table>

</div><br>

<center><input class="btn btn-sm btn-danger" type="submit" value="Guardar" name="guardar"></center>
</div>
</form>
</div>
</div>
</div>
</div>
</div>

<?php end_section();
}else{
    header('Location: ../../../../../index.php');
}
?>