<?php
	require("Base_Dat_Calibracion.php");
	$db=new Base_Dat_Calibracion();
	if($db->isError()){
		die("Error al conectar con la base de datos!");
	}else{
		$datos=json_decode($_POST["json"],true);
		$d=$db->registrarCalibracion($datos);
		$db->closeConexion();
		header('Content-type: application/json');
		echo json_encode($d);
	}
?>