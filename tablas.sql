create table cat_acciones(
	n_accion_id int not null auto_increment,
    c_accion_nombre varchar(100),
    primary key (n_accion_id)
)ENGINE=InnoDB;
create table log_acciones(
	c_log_id int not null auto_increment,
    n_accion_id int,
    n_usuario_id int,
    d_fecha_log datetime,
    c_usuario_ip varchar(15),
    c_string_log varchar(250),
    primary key (c_log_id),
    foreign key (n_accion_id) references cat_acciones (n_accion_id) on update cascade on delete cascade,
    foreign key (n_usuario_id) references dat_usuario (n_usuario_id) on update cascade on delete cascade
)ENGINE=InnoDB;
create table log_accesos(
	c_logaccesos_id int not null auto_increment,
    n_usuario_id int,
    d_fecha_log datetime,
    c_usuario_ip varchar(15),
    primary key (c_logaccesos_id),
    foreign key (n_usuario_id) references dat_usuario (n_usuario_id) on update cascade on delete cascade
)ENGINE=InnoDB;
insert into cat_acciones(c_accion_nombre) 
values('Alta dispositivo'),('Edicion de dispositivo'),
('Eliminar dispositivo'),('Alta empresa'),
('Edicion empresa'),('Eliminar empresa'),
('Alta modelo'),('Edicion modelo'),
('Eliminar modelo');