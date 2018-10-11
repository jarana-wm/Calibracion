<?php
class Base_Dat_Calibracion{
	private $server = "108.175.10.179";
	private $user = "jarana";
	private $psw = "R7v@Br4Tz";
	private $db = "wm_fuel_dev";	
	private $exito=false;
	public $con;
	
	function __construct(){
		$this->con = mysqli_connect($this->server, $this->user, $this->psw, $this->db);
		if (!$this->con) {
			$this->exito=true;
		}
		return $this->con;
	}
	public function isError(){
		return $this->exito;
	}
	public function closeConexion(){
		$this->con->close();
	}
	public function buscarDispositivo($nombre){
		$sql = "select * from dat_dispositivo where c_dispositivo_id like '".$nombre."';";
		$resultado = mysqli_query($this->con,$sql);
		if(mysqli_num_rows($resultado)== 0)
			return false;
		else
			return true;
	}
	public function buscarEmpresa($nombre){
		$sql = "select * from dat_usuario where c_usuario_nombre like '".$nombre."';";
		$resultado = mysqli_query($this->con,$sql);
		if(mysqli_num_rows($resultado)== 0)
			return false;
		else
			return true;
	}
	public function buscarModelo($nombre){
		$sql = "select * from cat_modelo where c_modelo_nombre like '".$nombre."';";
		$resultado = mysqli_query($this->con,$sql);
		if(mysqli_num_rows($resultado)== 0)
			return false;
		else
			return true;
	}	
	public function obtenerDatUs($hash){
		$sql="select n_usuario_id,c_usuario_nombre,c_usuario_login,c_usuario_token,n_tipousuario_id,b_usuario_activo
		from dat_usuario
		where c_usuario_token like '".$hash."';";
		$resultado = mysqli_query($this->con,$sql);
		if($resultado->num_rows >0){
			$r=mysqli_fetch_assoc($resultado);
				$datos[]=array('id_us'=>$r['n_usuario_id'],
								'nombre'=>$r['c_usuario_nombre'],
								'login'=>$r['c_usuario_login'],
								'hash'=>$r['c_usuario_token'],
								'tipo'=>$r['n_tipousuario_id'],
								'estado'=>$r['b_usuario_activo']
							);
		}	
		else
			$datos=0;
		return $datos;
	}
	public function obtenerDispositivos($tipo,$id){
		if($tipo==1){
			$sql = "select d.n_dispositivo_id,u.n_usuario_id,
			u.c_usuario_login,u.c_usuario_nombre,u.b_usuario_activo, d.c_dispositivo_id, d.n_modelo_id as id_mod, 
			m.c_modelo_nombre as nom_mod, f.c_fabricante_nombre as fab_nombre, d.n_dispositivo_umbraldescarga, 
			d.n_dispositivo_umbralcarga, d.c_dispositivo_ip, d.n_dispositivo_puerto, 
			d.b_dispositivo_notificacion, d.b_dispositivo_activo
			from dat_dispositivo as d, cat_modelo as m, cat_fabricante as f, dat_usuario as u
			where d.n_modelo_id=m.n_modelo_id
				and f.n_fabricante_id=m.n_fabricante_id
				and d.n_usuario_id=u.n_usuario_id
			order by 1; ";
		}else if($tipo==2){
			$sql = "select d.n_dispositivo_id,u.n_usuario_id,
				u.c_usuario_login,u.c_usuario_nombre,u.b_usuario_activo, d.c_dispositivo_id, d.n_modelo_id as id_mod, 
				m.c_modelo_nombre as nom_mod, f.c_fabricante_nombre as fab_nombre, d.n_dispositivo_umbraldescarga, 
				d.n_dispositivo_umbralcarga, d.c_dispositivo_ip, d.n_dispositivo_puerto, 
				d.b_dispositivo_notificacion, d.b_dispositivo_activo
				from dat_dispositivo as d, cat_modelo as m, cat_fabricante as f, dat_usuario as u
				where d.n_modelo_id=m.n_modelo_id
					and f.n_fabricante_id=m.n_fabricante_id
					and d.n_usuario_id=u.n_usuario_id 
					and u.n_usuario_id like '".$id."'
				order by 1; ";
		}else{
			
			return 0;
		}
		$resultado = mysqli_query($this->con,$sql);
		if($resultado->num_rows >0){
			while($r=mysqli_fetch_assoc($resultado)){
				$sql2 = "select l.n_lastdata_tanque, l.d_lastdata_fecha
					from dat_lastdata as l, dat_dispositivo as d
						where l.n_dispositivo_id = d.n_dispositivo_id
							and d.n_dispositivo_id like '".$r['n_dispositivo_id']."';";
				$estat = mysqli_query($this->con,$sql2);
				$estat_dat=Array();
				while($q=mysqli_fetch_assoc($estat))
					$estat_dat[]=array(
									'tanque_t'=>$q['n_lastdata_tanque'],
									'fecha' => $q['d_lastdata_fecha']
								);				
				$datos[]=array('id'=>$r['n_dispositivo_id'],
								'id_disp'=>$r['c_dispositivo_id'],
								'modelo_id'=>$r['id_mod'],
								'modelo_fabricante'=>$r['fab_nombre'],
								'modelo_nombre'=>$r['nom_mod'],
								'descarga'=>$r['n_dispositivo_umbraldescarga'],
								'carga'=>$r['n_dispositivo_umbralcarga'],
								'ip'=>$r['c_dispositivo_ip'],
								'puerto'=>$r['n_dispositivo_puerto'],
								'notificacion'=>$r['b_dispositivo_notificacion'],
								'activo'=>$r['b_dispositivo_activo'],
								'empresa'=>$r['c_usuario_nombre'],
								'estatus'=>$estat_dat
						);
			}
		}	
		return $datos;
	}
	public function obtenerCalibraciones($disp){
		$sql = "select dis.c_dispositivo_id,cal.n_calibracion_tanque,cal.n_calibracion_puntos,cal.n_calibracion_volumen
			from dat_calibracion as cal, dat_dispositivo as dis
				where  dis.n_dispositivo_id = cal.n_dispositivo_id
				and dis.c_dispositivo_id like'".$disp."';";
		$resultado = mysqli_query($this->con,$sql);
		if($resultado->num_rows >0){
			while($r=mysqli_fetch_assoc($resultado))
				$datos[]=array('id'=>$r['c_dispositivo_id'],
								'tanque'=>$r['n_calibracion_tanque'],
								'puntos'=>$r['n_calibracion_puntos'],
								'volumen'=>$r['n_calibracion_volumen']
						);
		}	
		else
			$datos=0;
		return $datos;
	}
	public function obtenerDispositivo($disp){
		$sql = "select dis.c_dispositivo_id
				from dat_dispositivo as dis
				where dis.c_dispositivo_id like'".$disp."';";
		$resultado = mysqli_query($this->con,$sql);
		if($resultado->num_rows >0){
			while($r=mysqli_fetch_assoc($resultado))
				$datos[]=array('id'=>$r['n_dispositivo_id']);
		}	
		else
			$datos=0;
		return $datos;
	}
	public function obtenerUsuarios($tipo,$id){
		if($tipo==1)
			$sql2 = "select * from dat_usuario;";
		else if($tipo==2)
			$sql2 = "select * from dat_usuario where n_usuario_id like '".$id."';";
		else
			return 0;
		$estat = mysqli_query($this->con,$sql2);
		while($q=mysqli_fetch_assoc($estat))
			$us_dat[]=array(
							'id_us'=>$q['n_usuario_id'],
							'nom_us' => $q['c_usuario_nombre'],
							'fecha' => $q['d_usuario_expiracion'],
							'token'=> $q['c_usuario_token'],
							'tipo' => $q['n_tipousuario_id'],
							'activo' => $q['b_usuario_activo']
						);	
		return $us_dat;
	}
	public function obtenerUsuario($id){
		$sql2 = "select * from dat_usuario where n_usuario_id like '".$id."';";
		$estat = mysqli_query($this->con,$sql2);
		while($q=mysqli_fetch_assoc($estat))
			$us_dat[]=array(
							'id_us'=>$q['n_usuario_id'],
							'nom_us' => $q['c_usuario_nombre'],
							'fecha' => $q['d_usuario_expiracion'],
							'token'=> $q['c_usuario_token'],
							'tipo' => $q['n_tipousuario_id'],
							'activo' => $q['b_usuario_activo']
						);	
		return $us_dat;
	}
	public function obtenerModelos(){
		$sql2 = "select m.n_modelo_id,m.c_modelo_nombre,m.n_fabricante_id,f.c_fabricante_nombre from cat_modelo m, cat_fabricante f
				where m.n_fabricante_id=f.n_fabricante_id;";
		$estat = mysqli_query($this->con,$sql2);
		while($q=mysqli_fetch_assoc($estat))
			$mod_dat[]=array(
							'id'=>$q['n_modelo_id'],
							'nombre' => $q['c_modelo_nombre'],
							'id_fabricate' => $q['n_fabricante_id'],
							'fabricante' => $q['c_fabricante_nombre']
						);	
		return $mod_dat;
	}
	public function obtenerModelo($id){
		$sql2 = "select * from cat_modelo
				where n_modelo_id=".$id.";";
		$estat = mysqli_query($this->con,$sql2);
		while($q=mysqli_fetch_assoc($estat))
			$mod_dat[]=array(
							'id'=>$q['n_modelo_id'],
							'nombre' => $q['c_modelo_nombre'],
							'fabricante' => $q['n_fabricante_id']
						);	
		return $mod_dat;
	}
	public function datosDispositivo($disp){
		$sql = "select * from dat_dispositivo
				where n_dispositivo_id like'".$disp."';";
		$resultado = mysqli_query($this->con,$sql);
		if($resultado->num_rows >0){
			$sql2 = "select c.n_calibracion_tanque, c.n_calibracion_puntos, c.n_calibracion_volumen, c.b_calibracion_activo
					from dat_calibracion as c, dat_dispositivo as d
						where c.n_dispositivo_id = d.n_dispositivo_id
							and d.n_dispositivo_id like '".$disp."';";
			
			$cal = mysqli_query($this->con,$sql2);
			if(mysqli_num_rows($cal)>0)
				while($q=mysqli_fetch_assoc($cal))
					$cal_dat[]=array(
								'tanque'=>$q['n_calibracion_tanque'],
								'puntos'=>$q['n_calibracion_puntos'],
								'volumen'=>$q['n_calibracion_volumen'],
								'cal_activa' => $q['b_calibracion_activo']
							);
			else
				$cal_dat=0;			
			$r=mysqli_fetch_assoc($resultado);
				$datos[]=array('id'=>$r['n_dispositivo_id'],
								'id_disp'=>$r['c_dispositivo_id'],
								'modelo_id'=>$r['n_modelo_id'],
								'usuario' => $r['n_usuario_id'],
								'descarga'=>$r['n_dispositivo_umbraldescarga'],
								'carga'=>$r['n_dispositivo_umbralcarga'],
								'ip'=>$r['c_dispositivo_ip'],
								'puerto'=>$r['n_dispositivo_puerto'],
								'notificacion'=>$r['b_dispositivo_notificacion'],
								'activo'=>$r['b_dispositivo_activo'],
								'calibracion'=>$cal_dat
								);
		}	
		else
			$datos=0;
		return $datos;
	}
	public function registrarCalibracion($datos){
		$usuario=$datos['usuario'];
		$id=$datos['id_disp'];
		$modelo=$datos['modelo'];
		$carga=$datos['carga'];
		$descarga=$datos['descarga'];
		$fecha = $datos['fecha'];
		if($modelo==1||$modelo==2){
			$ip="216.250.115.76";
			$puerto=5033;
			$notificacion=1;
		}else if($modelo==3){
			$ip="";
			$puerto=0;
			$notificacion=0;
		}
		$sql="insert into dat_dispositivo(c_dispositivo_id,n_usuario_id,n_modelo_id,n_dispositivo_umbraldescarga,
		n_dispositivo_umbralcarga,c_dispositivo_ip,n_dispositivo_puerto,b_dispositivo_notificacion,b_dispositivo_activo)
		values('".$id."',".$usuario.",".$modelo.",".$descarga.",".$carga.",'".$ip."',".$puerto.",".$notificacion.",1);";
	
		if (mysqli_query($this->con, $sql)) {
			$obtenID="select * from dat_dispositivo where c_dispositivo_id like '".$id."';";
			$resultado = mysqli_query($this->con,$obtenID);
			if($resultado->num_rows >0){
				while($r=mysqli_fetch_assoc($resultado))
					$id_d=$r['n_dispositivo_id']; //el id bueno :v
				$tanques=0;
				$sql2="insert into dat_calibracion(n_dispositivo_id,n_calibracion_tanque,n_calibracion_puntos,n_calibracion_volumen,b_calibracion_activo) values";
				$d=$datos['datos'];

				for($i=0;$i<count($d);$i++){
					$tanque=$d[$i]['tanque'];
					$puntos=$d[$i]['puntos'];
					$volumen=$d[$i]['volumen'];
					$tanques=$tanque;
					$vt[$tanques]['ultimo']=$volumen;
					$sql2.="(".$id_d.",".$tanque.",".$puntos.",".$volumen.",1)";
					if($i==count($d)-1)
						$sql2.=";";
					else
						$sql2.=",";
				}
				if (mysqli_query($this->con, $sql2)) {
					//tablas extras
					$sql3="CREATE TABLE dat_carga_".$id."(
						  n_carga_id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
						  n_dispositivo_id int(11) DEFAULT NULL,
						  n_carga_tanque tinyint(1) DEFAULT '1',
						  d_carga_fechainicio datetime DEFAULT NULL,
						  d_carga_fechafin datetime DEFAULT NULL,
						  b_carga_volumen double DEFAULT NULL,
						  n_carga_longitud double DEFAULT NULL,
						  n_carga_latitud double DEFAULT NULL,
						  PRIMARY KEY (n_carga_id),
						  KEY fk_dispositivo (n_dispositivo_id),
						  CONSTRAINT dat_carga_".$id."_ibfk_1 FOREIGN KEY (n_dispositivo_id) REFERENCES dat_dispositivo (n_dispositivo_id)
						) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;
						CREATE TABLE dat_descarga_".$id." (
						  n_descarga_id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
						  n_dispositivo_id int(11) DEFAULT NULL,
						  n_descarga_tanque tinyint(1) DEFAULT '1',
						  d_descarga_fechainicio datetime DEFAULT NULL,
						  d_descarga_fechafin datetime DEFAULT NULL,
						  b_descarga_volumen double DEFAULT NULL,
						  n_descarga_longitud double DEFAULT NULL,
						  n_descarga_latitud double DEFAULT NULL,
						  PRIMARY KEY (n_descarga_id),
						  KEY fk_dispositivo (n_dispositivo_id),
						  CONSTRAINT dat_descarga_".$id."_ibfk_1 FOREIGN KEY (n_dispositivo_id) REFERENCES dat_dispositivo (n_dispositivo_id)
						) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;
						CREATE TABLE dat_historico_".$id." (
						  n_historico_id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
						  n_dispositivo_id int(11) DEFAULT NULL,
						  n_historico_tanque tinyint(1) DEFAULT '1',
						  d_historico_fecha datetime DEFAULT NULL,
						  d_historico_llegada datetime DEFAULT CURRENT_TIMESTAMP,
						  n_historico_longitud double DEFAULT NULL,
						  n_historico_latitud double NOT NULL,
						  n_historico_velocidad double DEFAULT NULL,
						  n_historico_rumbo double DEFAULT NULL,
						  c_historico_fix char(1) COLLATE latin1_spanish_ci DEFAULT 'A',
						  b_historico_ignicion tinyint(1) DEFAULT '1',
						  n_historico_nivel double DEFAULT NULL,
						  n_historico_puntos double DEFAULT NULL,
						  PRIMARY KEY (n_historico_id),
						  KEY fk_hist_disp (n_dispositivo_id),
						  CONSTRAINT dat_historico_".$id."_ibfk_1 FOREIGN KEY (n_dispositivo_id) REFERENCES dat_dispositivo (n_dispositivo_id)
						) ENGINE=InnoDB AUTO_INCREMENT=1643 DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;
						CREATE TABLE dat_resumen_".$id." (
						  n_historico_id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
						  n_dispositivo_id int(11) DEFAULT NULL,
						  n_historico_tanque tinyint(1) DEFAULT '1',
						  d_historico_fecha datetime DEFAULT NULL,
						  n_historico_longitud double DEFAULT NULL,
						  n_historico_latitud double NOT NULL,
						  n_historico_velocidad double DEFAULT NULL,
						  n_historico_rumbo double DEFAULT NULL,
						  c_historico_fix char(1) COLLATE latin1_spanish_ci DEFAULT 'A',
						  b_historico_ignicion tinyint(1) DEFAULT '1',
						  n_historico_nivel double DEFAULT NULL,
						  n_historico_puntos double DEFAULT NULL,
						  PRIMARY KEY (n_historico_id),
						  KEY fk_hist_disp_".$id." (n_dispositivo_id),
						  CONSTRAINT dat_resumen_".$id."_ibfk_1 FOREIGN KEY (n_dispositivo_id) REFERENCES dat_dispositivo (n_dispositivo_id)
						) ENGINE=InnoDB AUTO_INCREMENT=30410 DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;
						CREATE TRIGGER ADD_TO_RES_".$id." AFTER INSERT ON dat_historico_".$id." FOR EACH ROW BEGIN
						DECLARE lastNivel DOUBLE;
						DECLARE lastFecha DOUBLE;
						DECLARE timediff INT;
						DECLARE ucarga INT;
						DECLARE udescarga INT;
									  SELECT b.d_lastdata_fecha,b.n_lastdata_nivel,n_dispositivo_umbralcarga, n_dispositivo_umbraldescarga INTO lastFecha,lastNivel,ucarga,udescarga  FROM dat_dispositivo a, dat_lastdata b WHERE a.n_dispositivo_id = b.n_dispositivo_id AND b.n_dispositivo_id=NEW.n_dispositivo_id AND b.n_lastdata_tanque=NEW.n_historico_tanque;
									  SELECT ABS(TIMESTAMPDIFF(SECOND,lastFecha,NEW.d_historico_fecha)) INTO timediff;        	
						#IF timediff > 600 OR lastNivel <> NEW.n_historico_nivel THEN
						   INSERT INTO dat_resumen_".$id."(n_dispositivo_id, n_historico_tanque,d_historico_fecha,n_historico_longitud,n_historico_latitud,n_historico_velocidad,n_historico_rumbo,c_historico_fix,b_historico_ignicion,n_historico_nivel,n_historico_puntos) 
						   VALUES(NEW.n_dispositivo_id, NEW.n_historico_tanque,NEW.d_historico_fecha,NEW.n_historico_longitud,NEW.n_historico_latitud,NEW.n_historico_velocidad,NEW.n_historico_rumbo,NEW.c_historico_fix,NEW.b_historico_ignicion,NEW.n_historico_nivel,NEW.n_historico_puntos);
										  UPDATE dat_lastdata SET d_lastdata_fecha = NEW.d_historico_fecha, n_lastdata_nivel=NEW.n_historico_nivel WHERE n_dispositivo_id=NEW.n_dispositivo_id AND n_lastdata_tanque=NEW.n_historico_tanque;
						#END IF;
									   IF NEW.n_historico_nivel-lastNivel >= ucarga THEN
										  INSERT INTO dat_carga_".$id."(n_dispositivo_id,n_carga_tanque,d_carga_fechainicio,d_carga_fechafin,b_carga_volumen,n_carga_longitud,n_carga_latitud) VALUES(NEW.n_dispositivo_id, NEW.n_historico_tanque,lastFecha,NEW.d_historico_fecha,NEW.n_historico_nivel-lastNivel,NEW.n_historico_longitud,NEW.n_historico_latitud);
									   END IF;
									   IF lastNivel-NEW.n_historico_nivel >= ucarga THEN
										  INSERT INTO dat_descarga_".$id."(n_dispositivo_id,n_descarga_tanque,d_descarga_fechainicio,d_descarga_fechafin,b_descarga_volumen,n_descarga_longitud,n_descarga_latitud) VALUES(NEW.n_dispositivo_id, NEW.n_historico_tanque,lastFecha,NEW.d_historico_fecha,lastNivel-NEW.n_historico_nivel,NEW.n_historico_longitud,NEW.n_historico_latitud);
									   END IF;
					END;";
					for($k=1;$k<=$tanques;$k++){
						$sql3.="insert into dat_lastdata(n_dispositivo_id,n_lastdata_tanque,n_lastdata_nivel,d_lastdata_fecha)values(".intval($id_d).",".intval($k).",".intval($vt[$k]['ultimo']).",'".$fecha."');";
					}
					if(mysqli_multi_query($this->con,$sql3))
						return true;
					else	
						return "Error al crear tablas.".mysqli_error($this->con);
				}else{
					if(mysqli_query($this->con, "delete from dat_dispositivo where c_dispositivo_id like '".$id."';")){
						return "No se pudo registrar, accion deshecha";
					}else{
						return "No se puso regresar el cambio";
					}
				}
			}else{
				return "Erorr al obtener el id";
			}
		}else {
			return "No se pudo registrar: ". mysqli_error($this->con);
		}

	}
	public function eliminarDispositivo($disp){
		$sql="update dat_dispositivo set b_dispositivo_activo = 0 where n_dispositivo_id like '".$disp."';";
		if(mysqli_query($this->con,$sql))
			return "Dispositivo eliminado.";
		return "Error al eliminar el dispositivo.";
	}
	public function editarDispositivo($datos){
		$id=$datos['id'];
		$id_disp=$datos['id_disp'];
		$modelo=$datos['modelo'];
		$carga=$datos['carga'];
		$descarga=$datos['descarga'];
		$usuario=$datos['usuario'];
		$fecha=$datos['fecha'];
		$sql="update dat_dispositivo
			set c_dispositivo_id=".$id_disp."
			where n_dispositivo_id = ".$id.";";
		if(mysqli_query($this->con,$sql)){
			$sql="update dat_dispositivo
			set n_dispositivo_umbralcarga=".$carga."
			where n_dispositivo_id = ".$id.";";
			if(mysqli_query($this->con,$sql)){
				$sql="update dat_dispositivo
				set n_dispositivo_umbraldescarga=".$descarga."
				where n_dispositivo_id = ".$id.";";
				if(mysqli_query($this->con,$sql)){
					$sql="update dat_dispositivo
					set n_modelo_id=".$modelo."
					where n_dispositivo_id = ".$id.";";
					if(mysqli_query($this->con,$sql)){
						$sql="update dat_dispositivo
						set n_usuario_id=".$usuario."
						where n_dispositivo_id = ".$id.";";
						if(mysqli_query($this->con,$sql)){
							$sql="update dat_calibracion
							set b_calibracion_activo='0'
							where n_dispositivo_id = ".$id.";";
							if(mysqli_query($this->con,$sql)){
								$sql2="insert into dat_calibracion(n_dispositivo_id,n_calibracion_tanque,n_calibracion_puntos,n_calibracion_volumen,b_calibracion_activo) values";
								$d=$datos['datos'];
								for($i=0;$i<count($d);$i++){
									$tanque=$d[$i]['tanque'];
									$puntos=$d[$i]['puntos'];
									$volumen=$d[$i]['volumen'];
									$sql2.="(".$id.",".$tanque.",".$puntos.",".$volumen.",1)";
									if($i==count($d)-1)
										$sql2.=";";
									else
										$sql2.=",";
								}
								if(mysqli_query($this->con,$sql2)){
									$sql="delete from dat_lastdata where n_dispositivo_id like '".$id."';";
									if(mysqli_query($this->con,$sql)){
										$d=$datos['datos'];
										for($i=0;$i<count($d);$i++){
											$tanque=$d[$i]['tanque'];
											$volumen=$d[$i]['volumen'];
											$tanques=$tanque;
											$vt[$tanques]['ultimo']=$volumen;	
										}
										for($k=1;$k<=$tanques;$k++){
											$sql.="insert into dat_lastdata(n_dispositivo_id,n_lastdata_tanque,n_lastdata_nivel,d_lastdata_fecha)values(".intval($id).",".intval($k).",".intval($vt[$k]['ultimo']).",'".$fecha."');";
										}
										if(mysqli_multi_query($this->con,$sql))
											return "Dispositivo Registrado";
										else	
											return "Error al crear tablas extra.";
									}else{
										return "Error al actualizar ultimos datos.";
									}
								}else{
									return "Error al agregar las nuevas calibraciones";
								}
							}else{
								return "Error al eliminar calibraciones del dispositivo.";
							}
						}else{
							return "Error al modificar el usuario.";
						}
					}else{
						return "Error al modificar el modelo.";
					}
				}else{
					return "Error al modificar umbral de descarga.";
				}
			}else{
				return "Error al modificar umbral de carga.";
			}
		}else{
			return "Error al modificar el ID del dispositivo.";
		}
	}
	public function registrarUsuario($nombre,$con,$fecha,$tipo){
		$sql="INSERT INTO dat_usuario(c_usuario_nombre,c_usuario_login,c_usuario_token,d_usuario_expiracion,c_usuario_host,n_tipousuario_id,b_usuario_activo)VALUES('".$nombre."','','".sha1($nombre.$con)."','".$fecha."','0.0.0.0',".$tipo.",1);";
		if(mysqli_query($this->con,$sql))
			return true;
		else
			return "No se pudo registrar la empresa".mysqli_error($this->con);
	}
	public function editarUsuario($datos){
		$sql="update dat_usuario set c_usuario_nombre='".$datos['nombre']."' where n_usuario_id = ".$datos['id']." ";
		if(mysqli_query($this->con,$sql)){
			$sql="update dat_usuario set d_usuario_expiracion='".$datos['fecha']."' where n_usuario_id = ".$datos['id']." ";
			if(mysqli_query($this->con,$sql)){
				$sql="update dat_usuario set n_tipousuario_id='".$datos['tipo']."' where n_usuario_id = ".$datos['id']." ";
				if(mysqli_query($this->con,$sql)){
					return "Datos actualizados.";
				}
				else{
					return "Error al actualizar el tipo de usuario.";
				}
			}
			else{
				return "Error al actualizar el fecha de expiración.";
			}
		}
		else{
			return "Error al actualizar el nombre.";
		}
	}
	public function eliminarUsuario($disp){
		$sql="update dat_usuario set b_usuario_activo = 0 where n_usuario_id like '".$disp."';";
		if(mysqli_query($this->con,$sql))
			return "Usuario eliminado.";
		return "Error al eliminar el usuario.";
	}
	public function obtenerFabricantes(){
		$sql = "select * from cat_fabricante;";
		$estat = mysqli_query($this->con,$sql);
		while($q=mysqli_fetch_assoc($estat))
			$us_dat[]=array(
							'id_fab'=>$q['n_fabricante_id'],
							'nom_fab' => $q['c_fabricante_nombre']
						);	
		return $us_dat;
	}
	public function editarModelo($datos){
		$sql="update cat_modelo set c_modelo_nombre='".$datos['nombre']."' where n_modelo_id = ".$datos['id']." ";
		if(mysqli_query($this->con,$sql)){
			$sql="update cat_modelo set n_fabricante_id='".$datos['fab']."' where n_modelo_id = ".$datos['id']." ";
			if(mysqli_query($this->con,$sql)){
				return "Modelo actualizado.";
			}
			else{
				return "Error al actualizar el fabricante.";
			}
		}
		else{
			return "Error al actualizar el nombre.";
		}
	}
	public function registrarModelo($nombre,$fab){
		$sql="INSERT INTO cat_modelo(c_modelo_nombre,n_fabricante_id) VALUES('".$nombre."','".$fab."');";
		if(mysqli_query($this->con,$sql))
			return "Modelo registrado.";
		else
			return "No se pudo registrar el modelo".mysqli_error($this->con);
	}
}
?>