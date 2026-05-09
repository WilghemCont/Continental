-- ============================================================
-- BASE DE DATOS: Sistema de Gestión de Donaciones
-- Compatible con XAMPP / MySQL
-- ============================================================

CREATE DATABASE IF NOT EXISTS donaciones_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE donaciones_db;

-- Tabla de ONGs
CREATE TABLE IF NOT EXISTS ongs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(150) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    telefono VARCHAR(20),
    direccion TEXT,
    logo VARCHAR(255),
    activo TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla de casos sociales
CREATE TABLE IF NOT EXISTS casos_sociales (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ong_id INT NOT NULL,
    titulo VARCHAR(200) NOT NULL,
    descripcion TEXT,
    meta_monto DECIMAL(10,2) NOT NULL,
    monto_recaudado DECIMAL(10,2) DEFAULT 0.00,
    fecha_inicio DATE NOT NULL,
    fecha_fin DATE NOT NULL,
    estado ENUM('pendiente','aprobado','cerrado','completado','cancelado') DEFAULT 'pendiente',
    imagen VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (ong_id) REFERENCES ongs(id) ON DELETE CASCADE
);

-- Tabla de donadores
CREATE TABLE IF NOT EXISTS donadores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(150) NOT NULL,
    email VARCHAR(150) NOT NULL,
    telefono VARCHAR(20),
    dni VARCHAR(20),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla de donaciones
CREATE TABLE IF NOT EXISTS donaciones (
    id INT AUTO_INCREMENT PRIMARY KEY,
    caso_social_id INT NOT NULL,
    donador_id INT NOT NULL,
    monto DECIMAL(10,2) NOT NULL,
    metodo_pago ENUM('transferencia','tarjeta','efectivo','yape','plin') NOT NULL,
    codigo_transaccion VARCHAR(100),
    estado ENUM('pendiente','verificado','rechazado') DEFAULT 'pendiente',
    comprobante VARCHAR(255),
    notas TEXT,
    fecha_donacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (caso_social_id) REFERENCES casos_sociales(id) ON DELETE CASCADE,
    FOREIGN KEY (donador_id) REFERENCES donadores(id) ON DELETE CASCADE
);

-- Tabla de transferencias a ONGs
CREATE TABLE IF NOT EXISTS transferencias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    caso_social_id INT NOT NULL,
    ong_id INT NOT NULL,
    monto DECIMAL(10,2) NOT NULL,
    documento VARCHAR(255),
    estado ENUM('pendiente','en_proceso','completado','rechazado') DEFAULT 'pendiente',
    fecha_transferencia TIMESTAMP NULL,
    fecha_confirmacion TIMESTAMP NULL,
    notas TEXT,
    confirmado_por VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (caso_social_id) REFERENCES casos_sociales(id),
    FOREIGN KEY (ong_id) REFERENCES ongs(id)
);

-- Tabla de notificaciones
CREATE TABLE IF NOT EXISTS notificaciones (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ong_id INT NOT NULL,
    caso_social_id INT,
    tipo ENUM('meta_alcanzada','transferencia_iniciada','transferencia_completada','alerta') NOT NULL,
    titulo VARCHAR(200) NOT NULL,
    mensaje TEXT NOT NULL,
    leida TINYINT(1) DEFAULT 0,
    enviada_email TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (ong_id) REFERENCES ongs(id),
    FOREIGN KEY (caso_social_id) REFERENCES casos_sociales(id)
);

-- Tabla de incidencias
CREATE TABLE IF NOT EXISTS incidencias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    transferencia_id INT,
    tipo VARCHAR(100),
    descripcion TEXT,
    estado ENUM('abierta','en_revision','resuelta') DEFAULT 'abierta',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (transferencia_id) REFERENCES transferencias(id)
);

-- ============================================================
-- DATOS DE PRUEBA
-- ============================================================

INSERT INTO ongs (nombre, email, telefono, direccion) VALUES
('ONG Corazón Solidario', 'contacto@corazonsolidario.org', '987654321', 'Av. Los Álamos 123, Lima'),
('Fundación Esperanza Viva', 'info@esperanzaviva.org', '912345678', 'Jr. Las Flores 456, Miraflores'),
('Asociación Manos Unidas', 'ayuda@manosunidas.pe', '956789012', 'Calle Real 789, San Isidro');

INSERT INTO casos_sociales (ong_id, titulo, descripcion, meta_monto, monto_recaudado, fecha_inicio, fecha_fin, estado) VALUES
(1, 'Niños sin hogar en Lima', 'Apoyo a 50 niños en situación de calle con alimentación y educación.', 15000.00, 11200.00, '2025-01-01', '2025-06-30', 'aprobado'),
(2, 'Reconstrucción post sismo Arequipa', 'Ayuda para reconstruir 20 viviendas afectadas por el sismo.', 30000.00, 30500.00, '2025-02-01', '2025-05-31', 'completado'),
(3, 'Becas educativas zona rural', 'Financiamiento de útiles y uniformes para 100 estudiantes rurales.', 8000.00, 4500.00, '2025-03-01', '2025-07-31', 'aprobado'),
(1, 'Atención médica adultos mayores', 'Medicamentos y consultas para 80 adultos mayores sin seguro.', 20000.00, 2000.00, '2025-04-01', '2025-08-31', 'aprobado');

INSERT INTO donadores (nombre, email, telefono, dni) VALUES
('Carlos Mendoza', 'carlos@email.com', '999111222', '45678901'),
('Ana Torres', 'ana@email.com', '999333444', '56789012'),
('Luis García', 'luis@email.com', '999555666', '67890123'),
('María Quispe', 'maria@email.com', '999777888', '78901234');

INSERT INTO donaciones (caso_social_id, donador_id, monto, metodo_pago, codigo_transaccion, estado) VALUES
(1, 1, 500.00, 'yape', 'YP20250101001', 'verificado'),
(1, 2, 1200.00, 'transferencia', 'BCP20250102001', 'verificado'),
(1, 3, 300.00, 'tarjeta', 'VISA20250103001', 'verificado'),
(2, 1, 2000.00, 'transferencia', 'BCP20250201001', 'verificado'),
(3, 4, 450.00, 'plin', 'PLN20250301001', 'verificado');

INSERT INTO transferencias (caso_social_id, ong_id, monto, estado, fecha_transferencia, notas) VALUES
(2, 2, 30500.00, 'completado', '2025-06-01 10:00:00', 'Transferencia completada exitosamente vía BCP');
