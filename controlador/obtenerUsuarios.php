<?php
	require("Base_Dat_Calibracion.php");
	$db=new Base_Dat_Calibracion();
	if($db->isError()){
		die("Error al conectar con la base de datos!");
	}else{
		$dato=$db->obtenerUsuarios($_POST['tipo'],$_POST['id']);
		$db->closeConexion();
		header('Content-type: application/json');
		echo json_encode($dato);
	}
?>