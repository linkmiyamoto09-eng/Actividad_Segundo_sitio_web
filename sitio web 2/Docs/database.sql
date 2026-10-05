-- Creación de la base de datos
CREATE DATABASE IF NOT EXISTS biblioteca_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE biblioteca_db;

-- 1. Tabla de Roles
CREATE TABLE roles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL UNIQUE
);

-- 2. Tabla de Usuarios (Administradores y Empleados)
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre_completo VARCHAR(150) NOT NULL,
    correo VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    rol_id INT NOT NULL,
    FOREIGN KEY (rol_id) REFERENCES roles(id) ON DELETE CASCADE
);

-- 3. Tabla de Categorías de Libros
CREATE TABLE categorias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL UNIQUE
);

-- 4. Tabla de Libros
CREATE TABLE libros (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(200) NOT NULL,
    autor VARCHAR(150) NOT NULL,
    isbn VARCHAR(20) NOT NULL UNIQUE,
    categoria_id INT NOT NULL,
    FOREIGN KEY (categoria_id) REFERENCES categorias(id) ON DELETE CASCADE
);

-- 5. Tabla de Clientes
CREATE TABLE clientes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    identificador_unico VARCHAR(20) NOT NULL UNIQUE,
    nombre_completo VARCHAR(150) NOT NULL,
    edad INT NOT NULL,
    sexo ENUM('M', 'F', 'Otro') NOT NULL,
    correo VARCHAR(100) NOT NULL,
    direccion TEXT NOT NULL
);

-- 6. Tabla de Préstamos
CREATE TABLE prestamos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cliente_id INT NOT NULL,
    empleado_id INT NOT NULL,
    fecha_prestamo DATETIME DEFAULT CURRENT_TIMESTAMP,
    estatus ENUM('activo', 'devuelto') DEFAULT 'activo',
    FOREIGN KEY (cliente_id) REFERENCES clientes(id) ON DELETE CASCADE,
    FOREIGN KEY (empleado_id) REFERENCES usuarios(id) ON DELETE CASCADE
);

-- 7. Tabla Detalle de Préstamos (Libros solicitados por préstamo)
CREATE TABLE detalle_prestamos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    prestamo_id INT NOT NULL,
    libro_id INT NOT NULL,
    FOREIGN KEY (prestamo_id) REFERENCES prestamos(id) ON DELETE CASCADE,
    FOREIGN KEY (libro_id) REFERENCES libros(id) ON DELETE CASCADE
);

-- Inserción de Datos Iniciales
INSERT INTO roles (nombre) VALUES ('Administrador'), ('Empleado');
INSERT INTO categorias (nombre) VALUES ('Terror'), ('Ciencia'), ('Fantasía'), ('Comedia');

-- Administrador por defecto (password: admin123)
INSERT INTO usuarios (nombre_completo, correo, password, rol_id) 
VALUES ('Administrador General', 'admin@biblioteca.com', '$2y$10$wE8bQ2yJqPZ9y7H2K3w1E.m9G9o9xG7G7a7b7c7d7e7f7g7h7i7j7', 1);

-- Creación de usuarios con privilegios limitados en MariaDB
CREATE USER IF NOT EXISTS 'admin_user'@'localhost' IDENTIFIED BY 'AdminPass2026!';
GRANT ALL PRIVILEGES ON biblioteca_db.* TO 'admin_user'@'localhost';

CREATE USER IF NOT EXISTS 'empleado_user'@'localhost' IDENTIFIED BY 'EmpleadoPass2026!';
GRANT SELECT, INSERT, UPDATE ON biblioteca_db.* TO 'empleado_user'@'localhost';

FLUSH PRIVILEGES;