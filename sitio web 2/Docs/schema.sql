-- Creación de la base de datos
CREATE DATABASE IF NOT EXISTS biblioteca_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE biblioteca_db;

-- 1. Tabla de Roles
CREATE TABLE roles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL UNIQUE
);

-- 2. Tabla de Usuarios (Empleados y Administradores)
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre_completo VARCHAR(150) NOT NULL,
    correo VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    rol_id INT NOT NULL,
    activo TINYINT(1) DEFAULT 1,
    FOREIGN KEY (rol_id) REFERENCES roles(id)
);

-- 3. Tabla de Clientes (Lectores)
CREATE TABLE clientes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    identificador_unico VARCHAR(20) NOT NULL UNIQUE,
    nombre_completo VARCHAR(150) NOT NULL,
    edad INT NOT NULL,
    sexo ENUM('M', 'F', 'Otro') NOT NULL,
    direccion TEXT NOT NULL
);

-- 4. Tabla de Categorías de Libros
CREATE TABLE categorias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL UNIQUE,
    descripcion TEXT
);

-- 5. Tabla de Libros (Catálogo)
CREATE TABLE libros (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(200) NOT NULL,
    autor VARCHAR(150) NOT NULL,
    isbn VARCHAR(20) UNIQUE NOT NULL,
    categoria_id INT NOT NULL,
    disponible TINYINT(1) DEFAULT 1,
    FOREIGN KEY (categoria_id) REFERENCES categorias(id)
);

-- 6. Tabla de Préstamos
CREATE TABLE prestamos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cliente_id INT NOT NULL,
    usuario_id INT NOT NULL,
    fecha_prestamo DATETIME DEFAULT CURRENT_TIMESTAMP,
    fecha_devolucion_esperada DATE NOT NULL,
    estatus ENUM('activo', 'devuelto', 'atrasado') DEFAULT 'activo',
    FOREIGN KEY (cliente_id) REFERENCES clientes(id),
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
);

-- 7. Tabla Detalle de Préstamo
CREATE TABLE detalle_prestamo (
    id INT AUTO_INCREMENT PRIMARY KEY,
    prestamo_id INT NOT NULL,
    libro_id INT NOT NULL,
    devuelto TINYINT(1) DEFAULT 0,
    FOREIGN KEY (prestamo_id) REFERENCES prestamos(id),
    FOREIGN KEY (libro_id) REFERENCES libros(id)
);

-- Datos iniciales
INSERT INTO roles (id, nombre) VALUES (1, 'Administrador'), (2, 'Empleado');
INSERT INTO categorias (nombre) VALUES ('Terror'), ('Ciencia'), ('Fantasía'), ('Comedia');

-- Usuario Administrador Inicial (Password: admin123)
INSERT INTO usuarios (nombre_completo, correo, password, rol_id) 
VALUES ('Administrador General', 'admin@biblioteca.com', 'admin123', 1);

-- Creación de usuarios MariaDB sin privilegio root
CREATE USER IF NOT EXISTS 'admin_bib'@'localhost' IDENTIFIED BY 'AdminPass123!';
GRANT ALL PRIVILEGES ON biblioteca_db.* TO 'admin_bib'@'localhost';

CREATE USER IF NOT EXISTS 'empleado_bib'@'localhost' IDENTIFIED BY 'EmpleadoPass123!';
GRANT SELECT, INSERT, UPDATE ON biblioteca_db.* TO 'empleado_bib'@'localhost';

FLUSH PRIVILEGES;
