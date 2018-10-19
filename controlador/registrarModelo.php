<?php
	require("Base_Dat_Calibracion.php");
	$db=new Base_Dat_Calibracion();
	if($db->isError()){
		die("Error al conectar con la base de datos!");
	}else{
		$d=$db->registrarModelo($_POST['nombre'],$_POST['fabricante']);
		$llega=json_encode($_POST);
		$guardar=array('accion'=>7,'usuario'=>$_POST['user'],'fecha'=>$_POST['fecha'],'ip'=>'127.0.0.1','str'=>$llega);
		$db->guardarLog($guardar);
		$db->closeConexion();
		header('Content-type: application/json');
		echo json_encode($d);
	}
?>