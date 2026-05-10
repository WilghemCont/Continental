-- ============================================================
-- MIGRACIÓN: Módulo de Ingresos y Patrocinios v2
-- Integración del nuevo módulo de donaciones al proyecto Continental
-- Base de datos: bdsocial
-- Ejecutar después del schema principal (bdsocial.sql)
-- ============================================================

USE bdsocial;

-- ── Donadores (patrocinadores) ────────────────────────────
CREATE TABLE IF NOT EXISTS `donadores` (
    `id`         INT AUTO_INCREMENT PRIMARY KEY,
    `nombre`     VARCHAR(150) NOT NULL,
    `email`      VARCHAR(150) UNIQUE NOT NULL,
    `documento`  VARCHAR(20)  DEFAULT '',
    `telefono`   VARCHAR(20)  DEFAULT '',
    `tipo`       ENUM('persona','empresa') DEFAULT 'persona',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ── Patrocinios (detalle de ingresos en especie / servicios) ─
CREATE TABLE IF NOT EXISTS `patrocinios` (
    `id`                INT AUTO_INCREMENT PRIMARY KEY,
    `donacion_id`       INT NOT NULL,
    `tipo_aporte`       ENUM('efectivo','bienes','servicios') NOT NULL,
    `descripcion`       TEXT NOT NULL,
    `valor_estimado`    DECIMAL(12,2) NOT NULL,
    `archivo_evidencia` VARCHAR(300) DEFAULT NULL,
    `created_at`        TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`donacion_id`) REFERENCES `ingresos`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ── Datos de ejemplo (donadores de referencia) ────────────
INSERT IGNORE INTO `donadores` (`nombre`, `email`, `documento`, `tipo`) VALUES
('Banco Central de Reserva', 'bcrp@ejemplo.com', '20131066505', 'empresa'),
('Tech Solutions SAC',       'contacto@techsolutions.pe', '20601234567', 'empresa'),
('Peru2 Corp',               'peru2@ejemplo.com', '20987654321', 'empresa');
