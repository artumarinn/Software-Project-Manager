DROP DATABASE IF EXISTS software_project_manager;
CREATE DATABASE IF NOT EXISTS software_project_manager;
USE software_project_manager;

CREATE TABLE roles (
   role_id INT AUTO_INCREMENT PRIMARY KEY,
   description VARCHAR(50)
);

CREATE TABLE customer_type (
   customer_type_id INT AUTO_INCREMENT PRIMARY KEY,
   description VARCHAR(100)
);

CREATE TABLE customer (
   customer_id INT AUTO_INCREMENT PRIMARY KEY,
   customer_type_id INT,
   name VARCHAR(50),
   Cuil VARCHAR(11),
   address VARCHAR(150),
   email VARCHAR(50),
   phone VARCHAR(15),
   FOREIGN KEY (customer_type_id) REFERENCES customer_type(customer_type_id)
);

CREATE TABLE employee (
   employee_id INT AUTO_INCREMENT PRIMARY KEY,
   first_name VARCHAR(50),
   last_name VARCHAR(50),
   dni VARCHAR(8) UNIQUE,
   email VARCHAR(50) UNIQUE,
   phone VARCHAR(15),
   role_id INT,
   salary DECIMAL(10,2),
   hire_date DATE,
   password VARCHAR(255),
   FOREIGN KEY (role_id) REFERENCES roles(role_id)
);

CREATE TABLE status (
   status_id INT AUTO_INCREMENT PRIMARY KEY,
   description VARCHAR(50)
);

CREATE TABLE payment_status (
   payment_status_id INT AUTO_INCREMENT PRIMARY KEY,
   payment_status VARCHAR(50)
);

CREATE TABLE payment_method (
   payment_method_id INT AUTO_INCREMENT PRIMARY KEY,
   description VARCHAR(50)
);

CREATE TABLE payment (
   payment_id INT AUTO_INCREMENT PRIMARY KEY,
   date DATE,
   amount DECIMAL(10,2),
   payment_method_id INT,
   payment_status_id INT,
   FOREIGN KEY (payment_status_id) REFERENCES payment_status(payment_status_id),
   FOREIGN KEY (payment_method_id) REFERENCES payment_method(payment_method_id)
);

CREATE TABLE project (
   project_id INT AUTO_INCREMENT PRIMARY KEY,
   name VARCHAR(100),
   description VARCHAR(200),
   start_date DATE,
   end_date DATE,
   actual_end_date DATE,
   customer_id INT,
   status_id INT,
   payment_id INT,
   FOREIGN KEY (payment_id) REFERENCES payment(payment_id),
   FOREIGN KEY (status_id) REFERENCES status(status_id),
   FOREIGN KEY (customer_id) REFERENCES customer(customer_id)
);

CREATE TABLE priority (
   priority_id INT AUTO_INCREMENT PRIMARY KEY,
   description VARCHAR(50)
);

CREATE TABLE requirements (
   requirement_id INT AUTO_INCREMENT PRIMARY KEY,
   project_id INT,
   description VARCHAR(200),
   start_date DATE,
   end_date DATE,
   actual_end_date DATE,
   employee_id INT,
   priority_id INT,
   status_id INT,
   FOREIGN KEY (project_id) REFERENCES project(project_id),
   FOREIGN KEY (employee_id) REFERENCES employee(employee_id),
   FOREIGN KEY (priority_id) REFERENCES priority(priority_id),
   FOREIGN KEY (status_id) REFERENCES status(status_id)
);
