<?php
	require("Base_Dat_Calibracion.php");
	$db=new Base_Dat_Calibracion();
	if($db->isError()){
		die("Error al conectar con la base de datos!");
	}else{
		$datos=json_decode($_POST["json"],true);
		$guardar=array('accion'=>2,'usuario'=>$datos['user'],'fecha'=>$datos['fecha'],'ip'=>'127.0.0.1','str'=>$_POST["json"]);
		$db->guardarLog($guardar);
		$d=$db->editarDispositivo($datos);
		$db->closeConexion();
		header('Content-type: application/json');
		echo json_encode($d);
	}
?>