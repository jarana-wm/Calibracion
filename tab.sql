create table dat_carga_id(
	n_carga_id bigint(20) not null auto_increment,
	n_dispositivo_id int(11),
	n_carga_tanque tinyint(1) default 1,
	d_carga_fechainicio datetime,
	d_carga_fechafin datetime,
	b_carga_volumen double,
	n_carga_longitud double,
	n_carga_latitud double,
	primary key(n_carga_id),
	foreign key(n_dispositivo_id)
		refereces dat_dispositivo(n_dispositivo_id)
		ON DELETE NO ACTION
			ON UPDATE NO ACTION
)ENGINE = InnoDB;
create table dat_descarga_id(
	n_descarga_id bigint(20) not null auto_increment,
	n_dispositivo_id int(11),
	n_descarga_tanque tinyint(1)  default 1,
	d_descarga_fechainicio datetime,
	d_descarga_fechafin datetime,
	b_descarga_volumen double,
	n_descarga_longitud double,
	n_descarga_latitud double,
	primary key(n_descarga_id),
	foreign key(n_dispositivo_id)
		references dat_dispositivo(n_dispositivo_id)
		ON DELETE NO ACTION
			ON UPDATE NO ACTION
)ENGINE = InnoDB;
create table dat_historico_id(
	n_historico_id bigint(20) not null auto_increment,
	n_dispositivo_id int(11),
	n_historico_tanque tinyint(1) default 1, 
	d_historico_fecha datetime,
	d_historico_llegada datetime default CURRENT_TIMESTAMP,
	n_historico_longitud double,
	n_historico_latitud double,
	n_historico_velocidad double,
	n_historico_rumbo double,
	c_historico_fix char(1) default 'A',
	b_historico_ignicion tinyint(1) default 1, 
	n_historico_nivel double,
	n_historico_puntos double,
	primary key(n_historico_id),
	foreign key(n_dispositivo_id)
		references dat_dispositivo(n_dispositivo_id)
		ON DELETE NO ACTION
			ON UPDATE NO ACTION
)ENGINE = InnoDB;
create table dat_resumen_id(
	n_historico_id bigint(20) not null auto_increment,
	n_dispositivo_id int(11),
	n_historico_tanque tinyint(1) default 1, 
	d_historico_fecha datetime,
	n_historico_longitud double,
	n_historico_latitud double,
	n_historico_velocidad double,
	n_historico_rumbo double,
	c_historico_fix char(1) default 'A',
	b_historico_ignicion tinyint(1) default 1, 
	n_historico_nivel double,
	n_historico_puntos double,
	primary key(n_historico_id),
	foreign key(n_dispositivo_id)
		references dat_dispositivo(n_dispositivo_id)
		ON DELETE NO ACTION
			ON UPDATE NO ACTION
)ENGINE = InnoDB;
