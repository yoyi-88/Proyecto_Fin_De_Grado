DROP DATABASE IF EXISTS dmcalt;
CREATE DATABASE dmcalt CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE dmcalt;

-- 1. Tablas del Framework (Seguridad y Usuarios)
CREATE TABLE roles (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(20) NOT NULL, -- 'Admin' (Chef), 'Cliente'
    description VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    update_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    update_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE roles_users (
    user_id INT UNSIGNED NOT NULL,
    role_id INT UNSIGNED NOT NULL,
    PRIMARY KEY (user_id, role_id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE CASCADE
);

-- 2. Tablas del Negocio (Chef Privado)

-- Tabla MENÚ (Gestionada por el Chef/Admin)
CREATE TABLE menus (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT NOT NULL,
    precio DECIMAL(10, 2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    update_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabla CITAS (Reservas)
CREATE TABLE citas (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    fecha DATE NOT NULL,
    hora TIME NOT NULL,
    estado ENUM('Pendiente', 'Confirmada', 'Cancelada', 'Finalizada') DEFAULT 'Pendiente',
    user_id INT UNSIGNED NOT NULL, -- El Cliente que reserva
    menu_id INT UNSIGNED NOT NULL, -- El Menú elegido
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE RESTRICT,
    FOREIGN KEY (menu_id) REFERENCES menus(id) ON DELETE RESTRICT
);

-- Insertar Roles Base
INSERT INTO roles (id, name, description) VALUES 
(1, 'Admin', 'Rol para el Chef - Gestión total'),
(2, 'Editor', 'Ayudante de cocina (opcional)'),
(3, 'Cliente', 'Usuario registrado que puede reservar');

