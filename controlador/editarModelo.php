<?php
	require("Base_Dat_Calibracion.php");
	$db=new Base_Dat_Calibracion();
	if($db->isError()){
		die("Error al conectar con la base de datos!");
	}else{
		$datos=json_decode($_POST["json"],true);
		$d=$db->editarModelo($datos);
		$guardar=array('accion'=>8,'usuario'=>$datos['user'],'fecha'=>$datos['fecha'],'ip'=>'127.0.0.1','str'=>$_POST["json"]);
		$db->guardarLog($guardar);
		$db->closeConexion();
		header('Content-type: application/json');
		echo json_encode($d);
	}
?>