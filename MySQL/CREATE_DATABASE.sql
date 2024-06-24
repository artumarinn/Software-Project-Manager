DROP DATABASE IF EXISTS sistemaconsultora;
CREATE DATABASE IF NOT EXISTS sistemaconsultora;
USE sistemaconsultora;

CREATE TABLE roles (
   id_rol INT AUTO_INCREMENT PRIMARY KEY,
   Descripcion VARCHAR(50) 
);

CREATE TABLE tipo_cliente (
   id_tipo_cliente INT AUTO_INCREMENT PRIMARY KEY,
   Descripcion VARCHAR(100) 
);

CREATE TABLE cliente (
   id_cliente INT AUTO_INCREMENT PRIMARY KEY,
   id_tipo_cliente INT,
   Nombre VARCHAR(50), 
   Cuil_cuit VARCHAR(11), 
   Direccion VARCHAR(150), 
   Correo VARCHAR(50), 
   Telefono VARCHAR(15), 
   FOREIGN KEY (id_tipo_cliente) REFERENCES tipo_cliente(id_tipo_cliente)
);

CREATE TABLE empleado (
   id_empleado INT AUTO_INCREMENT PRIMARY KEY,
   Nombre VARCHAR(50), 
   Apellido VARCHAR(50), 
   DNI VARCHAR(8) UNIQUE, 
   Correo VARCHAR(50) UNIQUE,
   Telefono VARCHAR(15), 
   Rol INT,
   Salario DECIMAL(10,2), 
   Fecha_Contratacion DATE,
   Contraseña VARCHAR(255),
   FOREIGN KEY (Rol) REFERENCES roles(id_rol)
);

CREATE TABLE estado (
   id_estado INT AUTO_INCREMENT PRIMARY KEY,
   Descripcion VARCHAR(50) 
);

CREATE TABLE estado_pago (
   id_estado_pago INT AUTO_INCREMENT PRIMARY KEY,
   estado_pago VARCHAR(50) 
);

CREATE TABLE metodo_pago (
   id_metodo_pago INT AUTO_INCREMENT PRIMARY KEY,
   Descripcion VARCHAR(50) 
);

CREATE TABLE pago (
   id_pago INT AUTO_INCREMENT PRIMARY KEY,
   Fecha DATE,
   Monto DECIMAL(10,2), 
   Metodo_pago INT,
   Estado_pago INT,
   FOREIGN KEY (Estado_pago) REFERENCES estado_pago(id_estado_pago),
   FOREIGN KEY (Metodo_pago) REFERENCES metodo_pago(id_metodo_pago)
);

CREATE TABLE proyecto (
   id_proyecto INT AUTO_INCREMENT PRIMARY KEY,
   Nombre VARCHAR(100), 
   Descripcion VARCHAR(200), 
   Fecha_inicio DATE,
   Fecha_final DATE,
   Fecha_final_real DATE,
   id_cliente INT,
   id_estado INT,
   id_pago INT,
   FOREIGN KEY (id_pago) REFERENCES pago(id_pago),
   FOREIGN KEY (id_estado) REFERENCES estado(id_estado),
   FOREIGN KEY (id_cliente) REFERENCES cliente(id_cliente)
);

CREATE TABLE prioridad (
   id_prioridad INT AUTO_INCREMENT PRIMARY KEY,
   Descripcion VARCHAR(50) 
);

CREATE TABLE requisitos (
   id_requisito INT AUTO_INCREMENT PRIMARY KEY,
   id_proyecto INT,
   Descripcion VARCHAR(200),
   Fecha_inicial DATE,
   Fecha_final DATE,
   Fecha_final_real DATE,
   id_empleado INT,
   id_prioridad INT,
   id_estado INT,
   FOREIGN KEY (id_proyecto) REFERENCES proyecto(id_proyecto),
   FOREIGN KEY (id_empleado) REFERENCES empleado(id_empleado),
   FOREIGN KEY (id_prioridad) REFERENCES prioridad(id_prioridad),
   FOREIGN KEY (id_estado) REFERENCES estado(id_estado)
);
