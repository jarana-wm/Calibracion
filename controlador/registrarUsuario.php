<?php
	require("Base_Dat_Calibracion.php");
	$db=new Base_Dat_Calibracion();
	if($db->isError()){
		die("Error al conectar con la base de datos!");
	}else{
		$d=$db->registrarUsuario($_POST['nombre'],$_POST['contrasena'],$_POST['fechaExp'],$_POST['tipoUs']);
		$db->closeConexion();
		header('Content-type: application/json');
		echo json_encode($d);
	}
?>