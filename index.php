<!DOCTYPE html>
<?php
require("controlador/Base_Dat_Calibracion.php");
$db=new Base_Dat_Calibracion();
if(!isset($_GET['u'])){
   header('HTTP/1.0 401 Unauthorized');
   exit();
}
if($db->isError())
	die("Error al conectar con la base de datos!");
$dato=$db->obtenerDatUs($_GET["u"]);
if($dato==0||$dato[0]['estado']==0){
	echo "<script>
	datUsuario=0;
	usses=0;
	</script>";
	echo "<h3>No eres usuario... redirigiendo jejej</h3>";
}else{
	$acceso=array('usuario'=>$dato[0]['id_us'],'fecha'=>date("Y-m-d H:i:s"), 'ip'=>'127.0.0.1');
	$db->guardarAcceso($acceso);
	echo "<h3>Bienvenido ".$dato[0]['nombre']."</h3>";
	echo "<script>
	datUsuario=".$dato[0]['tipo'].";
	usses=".$dato[0]['id_us'].";
	</script>";
}
$db->closeConexion();
?>
<html>
<head>
	<title>Calibración de combustible</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="frameworks/bootstrap-3.3.7/css/bootstrap.min.css">
	<script src="frameworks/jquery/jquery-3.3.1.min.js"></script>
	<script src="frameworks/bootstrap-3.3.7/js/bootstrap.min.js"></script> 
	<script src="js/xlsx.core.min.js"></script>  
	<script src="js/xls.core.min.js"></script> 
	<script src="js/edicion.js"></script>
	<link rel="stylesheet" href="css/tablas.css">
</head>
<body >
 <div id="carga" class="loading" style="display:none">
		<img src='images/load.svg' width="100" />
   </div>
<div class="container-fluid"> 
<ul class="nav nav-tabs">
  <li id="menuCalibracion" class="active"><a>Calibración de dispositivos</a></li>
  <li id="menuEmpresa"><a>Alta de empresa</a></li>
  <li id="menuModelo"><a>Alta de modelo</a></li>
</ul>
	<div id="contenidoCalibracion">
		<div style="height: 400px; overflow-y: scroll;" class="col-md-12">
			<table id="tblDispositivos">
				<thead style="z-index: 1;">
					<tr> <th>id</th><th>Empresa</th><th>Dispositivo</th><th>Modelo</th><th>Fabricante</th><th>Carga</th><th>Descarga</th><th>Estatus</th><th style="width: 100px;">Comandos</th></tr>
				</thead>
				<tbody id="resultado">
				</tbody>
			</table>
			</div>
		<div class="col-sm-offset-10">
			<button class="btn btn-success" id="altaDisp" data-toggle="modal" data-target="#modalAlta">Alta de dispositivo</button>
		</div>
		<button class="btn hidden" id="abrirmodal" data-toggle="modal" data-target="#modalError"></button>
	</div> 
	<div id="contenidoEmpresa" style="display:none">
		<div style="height: 400px; overflow-y: scroll;" class="col-md-12">
			<table id="tblEmpresa">
				<thead style="z-index: 1;">
					<tr> <th>id</th><th>Nombre</th><th>Token</th><th>Fecha expiracion</th><th>Tipo</th><th style="width: 100px;">Comandos</th></tr>
				</thead>
				<tbody id="resultadoEpresa">
				</tbody>
			</table>
			</div>
		<div class="col-sm-offset-10">
			<button class="btn btn-success" id="mostrarAltaEmp">Alta de empresa</button>
			<button class="btn btn-success hidden" id="altaEmpresa" data-toggle="modal" data-target="#modalAltaEmp">empresa oculto</button>
		</div>
	</div>
	<div id="contenidoModelo" style="display:none">
		<div style="height: 400px; overflow-y: scroll;" class="col-md-12">
			<table id="tblModelo">
				<thead style="z-index: 1;">
					<tr> <th>id</th><th>Nombre</th><th>Fabricante</th><th style="width: 100px;">Comandos</th></tr>
				</thead>
				<tbody id="resultadoModelo">
				</tbody>
			</table>
			</div>
		<div class="col-sm-offset-10">
			<button class="btn btn-success" id="mostrarAltaMod">Alta de modelo</button>
			<button class="btn btn-success hidden" id="altaModelo" data-toggle="modal" data-target="#modalAltaMod">modelo oculto</button>
		</div>
	</div>
 </div>
<!-- Modal alta dispositivo-->
<div id="modalAlta" class="modal fade" role="dialog">
	  <div class="modal-dialog" style="width: 90%;">
		<div class="modal-content" >
			<div class="modal-header" >
				<h5 class="modal-title" id="title">Alta de dispositivo</h5>
			</div>
			<div class="modal-body" style=" background-color: #fff; border-radius: 10px;">
				 <form action="" method="post">
					<div class="col-sm-12">
						<div class="form-group col-sm-2">
							<label>ID disp.</label>
							<input type="text" id="idDisp" title="Debe contener entre 3 y 15 caracteres." class="form-control" placeholder="ID"></input>
						</div>
							<div class="form-group col-sm-2">
							<label>Umbral de carga.</label>
							<input type="number" id="uCarga" title="Solo numeros." value="10" min='10' max='100' step='10' class="form-control"></input>
						</div>
						<div class="form-group col-sm-2">
							<label>Umbral de descarga.</label>
							<input type="number" id="uDescarga" title="Solo numeros." value="10" min='10' max='100' step='10' class="form-control"></input>
						</div>	
						
						
						<div class="form-group col-sm-2">
							<label>Modelo.</label>
							<select class="form-control" id="modelo">
								
							 </select>
						</div>
						<div class="form-group col-md-2">
							<label for="sel1">Empresa:</label>
							  <select class="form-control" id="usuariosSel">
								
							  </select>
						</div>
					</div>
					<div class="col-md-12">
						<div class="form-group col-sm-3">
							<label>Numero de tanques</label>
							<div class="input-group">
								<div class="radio" style="display:inline;">
									<label><input type="radio" name="tanque" id="tanque1" value="1" checked>1</label>
								</div>
								<div class="radio" style="display:inline;">
									<label><input type="radio" name="tanque" id="tanque2" value="2" >2</label>
								</div>
								<div class="radio" style="display:inline;">
									<label><input type="radio" name="tanque" id="tanque3" value="3" >3</label>
								</div>
								<div class="radio" style="display:inline;">
									<label><input type="radio" name="tanque" id="tanque4" value="4" >4</label>
								</div>
							</div>
						</div>
						<div class="form-group col-sm-3">
								<input type="button" id="export-btn" value="Cargar" class="btn btn-primary"></input>
								<input type="button" id="modificar-btn" value="Modificar" class="btn btn-primary hidden"></input>
								
								<button type="button" id="cerrarAlta" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
						</div>
					</div>
					<div class="col-sm-12">
						<div id="t1" class="col-sm-3" style="display: inline;">
							<div class="form-group">
								<label>Tanque 1</label>
								<input type="file" id="excelfile1" class="btn" onchange="habilitar(1)" ></input> 
							</div>
							<div class="form-group">
								<input type="button" id="btnArchivo1" value="Importar" onclick="ExportToTable(1)" class="btn btn-primary" disabled="true"></input>  
								<input type="button" id="btnClean1" value="Limpiar" onclick="limpiar(1)" class="btn"></input>
							</div>
						<div id="table1" class="table-editable" style="overflow-y: scroll; height: 350px; ">
							<table id="exceltable1" class="tab">	
								<tr>
									<th>puntos</th><th>volumen</th><th>Agregar</th><th><span onclick="agregar(1)" class="table-add glyphicon glyphicon-plus-sign"></span></th>
								</tr>
							<tbody class="table1">
							</tbody>
							</table> 
						</div>
						
						</div>
							
						<div id="t2" class="col-sm-3" style="display: none;">
						<div class="form-group">
								<label>Tanque 2</label>
								<input type="file" id="excelfile2" class="btn" onchange="habilitar(2)" ></input> 
							</div>
							<div class="form-group">
								<input type="button" id="btnArchivo2" value="Importar" onclick="ExportToTable(2)" class="btn btn-primary" disabled="true"></input> 
								<input type="button" id="btnClean2" value="Limpiar" onclick="limpiar(2)" class="btn"></input>				
							</div>
						<div id="table2" class="table-editable" style=" overflow-y: scroll; height: 350px;">
							<table id="exceltable2"  class="tab">	
								<tr>
									<th>puntos</th><th>volumen</th><th>Agregar</th><th><span onclick="agregar(2)" class="table-add glyphicon glyphicon-plus-sign"></span></th>
								</tr>
							<tbody class="table2">
							</tbody>
							</table> 
						</div>
						</div>

						<div id="t3" class="col-sm-3" style="display: none;">
						<div class="form-group">
								<label>Tanque 3</label>
								<input type="file" id="excelfile3" class="btn" onchange="habilitar(3)"></input> 
							</div>
							<div class="form-group">
								<input type="button" id="btnArchivo3" value="Importar" onclick="ExportToTable(3)" class="btn btn-primary" disabled="true"></input>  
								<input type="button" id="btnClean3" value="Limpiar" onclick="limpiar(3)" class="btn"></input>
							</div>
						<div id="table3" class="table-editable" style=" overflow-y: scroll; height: 350px;">
							<table id="exceltable3"  class="tab">	
								<tr>
									<th>puntos</th><th>volumen</th><th>Agregar</th><th><span onclick="agregar(3)" class="table-add glyphicon glyphicon-plus-sign"></span></th>
								</tr>
								<tbody class="table3">
							</tbody>
							</table> 
						</div>
						</div>
						
						<div id="t4" class="col-sm-3" style="display: none;">
						<div class="form-group">
								<label>Tanque 4</label>
								<input type="file" id="excelfile4" class="btn" onchange="habilitar(4)"></input> 
							</div>
							<div class="form-group">
								<input type="button" id="btnArchivo4" value="Importar" onclick="ExportToTable(4)" class="btn btn-primary" disabled="true"></input>  
								<input type="button" id="btnClean4" value="Limpiar" onclick="limpiar(4)" class="btn"></input>
							</div>
						<div id="table4" class="table-editable " style=" overflow-y: scroll; height: 350px;">
							<table id="exceltable4"  class="tab">	
								<tr>
									<th>puntos</th><th>volumen</th><th>Agregar</th><th><span onclick="agregar(4)" class="table-add glyphicon glyphicon-plus-sign"></span></th>
								</tr>
								<tbody class="table4">
							</tbody>
							</table> 
						</div>
						</div>
					</div>
					
				 </form>
	 
			</div>
			<div class="modal-footer" style="padding: 0px; border-style: none;">
				
			</div>
		</div>
	</div>
</div>
<!-- Modal alta empresa-->
<div id="modalAltaEmp" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header" >
				<h5 class="modal-title" id="titleEmp">Alta de empresa</h5>
			</div>
			<div class="modal-body" style=" background-color: #fff; border-radius: 10px;">
				<div class="form-group">
					<label>Nombre:</label>
					<input type="text" class="form-control" id="modeloNom" placeholder="Nombre"></input>
				</div>
				<div class="form-group">
					<label>Contraseña:</label>
					<div class="input-group">
						<input type="password" class="form-control"  id="psw1" name="psw1" placeholder="********" required/>	
						<span class="input-group-btn">
							<button type="button"class="btn btn-default" name="btnvisible" onclick="pswvisible(1)"><span id="ver1" class="glyphicon glyphicon-eye-open"></span></button>
						</span>
					</div>
				</div>
				<div class="form-group">
					<label>Repetir contraseña:</label>
					<div class="input-group">
						<input type="password" class="form-control"  id="psw2" name="psw2" placeholder="********" required/>	
						<span class="input-group-btn">
							<button type="button"class="btn btn-default" name="btnvisible" onclick="pswvisible(2)"><span id="ver2" class="glyphicon glyphicon-eye-open"></span></button>
						</span>
					</div>
				</div>
				<div class="form-group">
					<label>Fecha de expiracion:</label>
					<input type="date" class="form-control" id="modeloFecha" placeholder="Nombre"></input>
				</div>
				<div class="form-group">
					<label>Tipo:</label>
					<select class="form-control" id="usuariosSel">
							<option value=1>Master</option>
							<option value=2>Administrador</option>	
					</select>
				</div>
			</div>
			<div class="modal-footer" style="padding: 0px; border-style: none;">
				<div class="form-group col-sm-12">
					<input type="button" id="registrarEmp" value="Registrar" class="btn btn-primary"></input>
					<button type="button" id="cerrarAltaEmp" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
				</div>
			</div>
		</div>
	</div>
</div> 
<!-- Modal editar empresa-->
<div id="modalEditEmp" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header" >
				<h5 class="modal-title" id="titleModEmp">Modificar de empresa</h5>
			</div>
			<div class="modal-body" style=" background-color: #fff; border-radius: 10px;">
				<div class="form-group">
					<label>Nombre:</label>
					<input type="text" class="form-control" id="modeloNomE" placeholder="Nombre"></input>
				</div>
				
				<div class="form-group">
					<label>Fecha de expiracion:</label>
					<input type="date" class="form-control" id="modeloFechaE" placeholder="Nombre"></input>
				</div>
				<div class="form-group">
					<label>Tipo:</label>
					<select class="form-control" id="usuariosSelE">
							<option value=1>Master</option>
							<option value=2>Administrador</option>	
					</select>
				</div>
			</div>
			<div class="modal-footer" style="padding: 0px; border-style: none;">
				<div class="form-group col-sm-12">
					<input type="button" id="modEmp" value="Modificar" class="btn btn-primary"></input>
					<input type="button" id="modificarEmp" value="mod" class="btn btn-primary hidden"></input>
					<button type="button" id="cerrarModEmp" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
				</div>
			</div>
		</div>
	</div>
</div> 
<!-- Modal mensaje eliminar dispositivo-->
<div id="modalEliminar" class="modal fade" role="dialog">
	  <div class="modal-dialog" style="width: 350px;">
		<div class="modal-content">
			<div class="modal-header conazul">
				<h4 class="modal-title">Eliminar dispositivo</h4>
			</div>
			<div class="modal-body" style="background-color: #fff; border-radius: 10px;" id="dispositivoEliminar">	
			</div>
		</div>
	</div>
</div>
 <!-- Modal mensaje eliminar empresa-->
<div id="modalEliminarE" class="modal fade" role="dialog">
	  <div class="modal-dialog" style="width: 350px;">
		<div class="modal-content">
			<div class="modal-header conazul">
				<h4 class="modal-title">Eliminar empresa</h4>
			</div>
			<div class="modal-body" style="background-color: #fff; border-radius: 10px;" id="usuarioEliminarem">	
			</div>
		</div>
	</div>
</div>
<!-- Modal editar modelo-->
<div id="modalEditMod" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header" >
				<h5 class="modal-title" id="titleModEmp">Modificar de modelo</h5>
			</div>
			<div class="modal-body" style=" background-color: #fff; border-radius: 10px;">
				<div class="form-group">
					<label>Nombre:</label>
					<input type="text" class="form-control" id="nombreMod" placeholder="Nombre"></input>
				</div>
				<div class="form-group">
					<label>Fabricante:</label>
					<select class="form-control" id="selectFab">
					</select>
				</div>
			</div>
			<div class="modal-footer" style="padding: 0px; border-style: none;">
				<div class="form-group col-sm-12">
					<input type="button" id="modModelo" value="Modificar" class="btn btn-primary"></input>
					<button type="button" id="cerrarModMod" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
				</div>
			</div>
		</div>
	</div>
</div> 
<div id="modalEliminarM" class="modal fade" role="dialog">
	  <div class="modal-dialog" style="width: 350px;">
		<div class="modal-content">
			<div class="modal-header conazul">
				<h4 class="modal-title">Eliminar empresa</h4>
			</div>
			<div class="modal-body" style="background-color: #fff; border-radius: 10px;" id="modeloEliminarmod">	
			</div>
		</div>
	</div>
</div>
<!-- Modal alta modelo-->
<div id="modalAltaMod" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header" >
				<h5 class="modal-title" id="titleModEmp">Alta de modelo</h5>
			</div>
			<div class="modal-body" style=" background-color: #fff; border-radius: 10px;">
				<div class="form-group">
					<label>Nombre:</label>
					<input type="text" class="form-control" id="nombreModA" placeholder="Nombre"></input>
				</div>
				<div class="form-group">
					<label>Fabricante:</label>
					<select class="form-control" id="selectFabA">
					</select>
				</div>
			</div>
			<div class="modal-footer" style="padding: 0px; border-style: none;">
				<div class="form-group col-sm-12">
					<input type="button" id="registrarMod" value="Agregar" class="btn btn-primary"></input>
					<button type="button" id="cerrarModAlt" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
				</div>
			</div>
		</div>
	</div>
</div> 
<!-- Modal mensaje eliminar dispositivo-->
<div id="modalEliminarMod" class="modal fade" role="dialog">
	  <div class="modal-dialog" style="width: 350px;">
		<div class="modal-content">
			<div class="modal-header conazul">
				<h4 class="modal-title">Eliminar modelo</h4>
			</div>
			<div class="modal-body" style="background-color: #fff; border-radius: 10px;" id="modeloEliminar">	
			</div>
		</div>
	</div>
</div>

<!-- Modal errores-->
<div id="modalError" class="modal fade" role="dialog" onclick="enfocar()" onkeyup="enfocar()">
	  <div class="modal-dialog modal-sm" >
		<div class="modal-content"style="z-index: 99999">
			<div class="modal-header" >
				<h5 class="modal-title">Mensaje</h5>
			</div>
			<div class="modal-body" style=" background-color: #fff; border-radius: 10px;" id="usuarioEliminar">
				
			</div>
			<div class="modal-footer" style="padding: 0px; border-style: none;">
				<button type="button" class="btn btn-default" id="aceptarMensaje" data-dismiss="modal">Aceptar</button>
			</div>
		</div>
	</div>
</div>
</body>
</html>