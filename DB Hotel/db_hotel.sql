-- DB Daniel Pérez Fernández 3º Versión

drop database if exists teis3_hotel; -- Eliminamos la base de datos si existe

create database teis3_hotel; -- Creamos la base de datos

use teis3_hotel; -- Establemos nuestra db como principal

-- Creamos la tabla Roles
create table Roles(
id bigint(20) not null unique,
nombre_rol varchar(50) not null unique,
primary key(id)
);

-- Creamos la tabla Usuarios
create table Usuarios (
id bigint(20) not null auto_increment,
nombre varchar(255) not null,
email varchar(50) not null unique,
telf varchar(9) not null unique,
direccion varchar(60) not null,
password varchar(255) not null,
rol_usuario bigint(20) not null,
PRIMARY KEY (id),
foreign key (rol_usuario) references Roles(id)
);


-- Creamos la tabla con los tipos de camas de nuestro hotel
CREATE TABLE habitacion_tipo (
    id BIGINT(20) NOT NULL AUTO_INCREMENT,
    tipo_habitacion VARCHAR(255) not null unique,
    descripcion varchar(255) not null,
    PRIMARY KEY (id)
);

-- Creamos la Tabla Imagenes_Habitaciones ya que una habitación puede tener más de una imagen
create table imagenes_habitaciones (
id bigint(20) not null auto_increment,
id_habitacion_tipo bigint(20) not null,
imagen_habitacion varchar(255) NOT NULL,
descripcion_imagen varchar(50) NOT NULL,
primary key (id),
foreign key (id_habitacion_tipo) references habitacion_tipo(id)
);

-- Creamos la Tabla  Habitaciones 
create table Habitaciones(
id bigint(20) not null auto_increment,
m2 DECIMAL(6,2) not null,
ventana bit not null default 0,
tipo_de_habitacion varchar(255) not null,
servicio_limpieza bit not null default 0,
internet bit not null default 0,
precio DECIMAL(6,2) not null,
primary key (id),
foreign key (tipo_de_habitacion) references habitacion_tipo(tipo_habitacion)
);

-- Creamos la Tabla Servicios
create table Servicios(
id bigint(20) not null auto_increment,
nombre_servicio varchar(255) not null,
precio_servicio DECIMAL(6,2) not null,
descripcion varchar(255) not null,
disponibilidad bit not null default 1,
primary key (id)
);

-- Creamos la Tabla Habitacion_Servicio que almacena la relación entre las habitaciones y los servicios que ofrecen
create table Habitacion_Servicio(
id_habitacion bigint(20) not null,
id_servicio bigint(20) not null,
fecha_servicio datetime not null,
fecha_fin_servicio datetime not null,
primary key (id_habitacion, id_servicio),
foreign key (id_habitacion) references Habitaciones(id),
foreign key (id_servicio) references Servicios(id)
);

-- Creamos la Tabla Reservas
create table Reservas(
num_reserva bigint(20) not null auto_increment,
id_usuario bigint(20) not null,
fecha_reserva timestamp not null default current_timestamp,
num_dias smallint(20) not null,
primary key (num_reserva), 
foreign key (id_usuario) references Usuarios(id)
);

-- Creamos la tabla Habitacion_Reserva
create table habitaciones_reservas(
id bigint(20) not null auto_increment,
num_reserva bigint(20) not null,
id_habitacion bigint(20) not null,
primary key (id),
foreign key (num_reserva) references  Reservas(num_reserva),
foreign key (id_habitacion) references Habitaciones(id)
);



