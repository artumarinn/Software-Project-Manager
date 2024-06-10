DROP DATABASE IF EXISTS sistemaconsultora;
CREATE DATABASE IF NOT EXISTS sistemaconsultora;
USE sistemaconsultora;

CREATE TABLE rol (
   id_rol INT AUTO_INCREMENT PRIMARY KEY,
   Nombre_rol VARCHAR(100)
);

CREATE TABLE usuario(
   id_usuario INT AUTO_INCREMENT PRIMARY KEY,
   Usuario VARCHAR(100),
   Nombre_completo VARCHAR(100),
   Correo VARCHAR(255) UNIQUE,
   Contraseña VARCHAR(255),
   Rol VARCHAR(100)
);

CREATE TABLE cliente (
   id_cliente INT AUTO_INCREMENT PRIMARY KEY,
   Nombre_cliente VARCHAR(100),
   DNI VARCHAR(20),
   Direccion VARCHAR(255),
   Correo VARCHAR(255),
   Telefono VARCHAR(20)
);

CREATE TABLE empleado (
   id_empleado INT AUTO_INCREMENT PRIMARY KEY,
   id_usuario INT,
   Nombre VARCHAR(100),
   Apellido VARCHAR(100),
   DNI VARCHAR(20) UNIQUE,
   Correo VARCHAR(255) UNIQUE,
   Telefono VARCHAR(20),
   id_rol INT,
   Salario VARCHAR(20),
   Fecha_Contratacion DATE,
   FOREIGN KEY (id_usuario) REFERENCES usuario(id_usuario),
   FOREIGN KEY (id_rol) REFERENCES rol(id_rol)
);

CREATE TABLE requisito_cliente (
   id_requisito INT AUTO_INCREMENT PRIMARY KEY,
   Fecha DATE,
   Descripcion VARCHAR(400),
   id_cliente INT,
   id_empleado INT,
   FOREIGN KEY (id_cliente) REFERENCES cliente(id_cliente),
   FOREIGN KEY (id_empleado) REFERENCES empleado(id_empleado)
);

CREATE TABLE estado_proyecto (
   id_estado INT AUTO_INCREMENT PRIMARY KEY,
   Estado VARCHAR(100)
);

CREATE TABLE pago (
   id_pago INT AUTO_INCREMENT PRIMARY KEY,
   Fecha DATE,
   Monto INT,
   Metodo_pago VARCHAR(100),
   Estado VARCHAR(100)
);

CREATE TABLE proyecto (
   id_proyecto INT AUTO_INCREMENT PRIMARY KEY,
   Nombre VARCHAR(100),
   Descripcion VARCHAR(255),
   Fecha_inicio DATE,
   Fecha_final DATE,
   Fecha_final_real DATE,
   Gerente_proyecto INT,
   Desarrollador INT,
   id_requisito INT,
   id_estado INT,
   id_pago INT,
   FOREIGN KEY (id_pago) REFERENCES pago(id_pago),
   FOREIGN KEY (Gerente_proyecto) REFERENCES empleado(id_empleado),
   FOREIGN KEY (Desarrollador) REFERENCES empleado(id_empleado),
   FOREIGN KEY (id_estado) REFERENCES estado_proyecto(id_estado)
);
