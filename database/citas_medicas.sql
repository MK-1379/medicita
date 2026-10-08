-- Base de datos de MediCita: esquema y datos de ejemplo.
-- Se puede ejecutar varias veces: borra las tablas y las vuelve a crear.
-- Todos los datos son inventados. Contraseña de las cuentas de ejemplo: Demo1234!

CREATE DATABASE IF NOT EXISTS citas_medicas CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE citas_medicas;

DROP TABLE IF EXISTS citas;
DROP TABLE IF EXISTS pacientes;
DROP TABLE IF EXISTS medicos;

CREATE TABLE medicos (
    id           INT UNSIGNED NOT NULL AUTO_INCREMENT,
    nombre       VARCHAR(50)  NOT NULL,
    apellido     VARCHAR(50)  NOT NULL,
    telefono     VARCHAR(15)  NOT NULL,
    email        VARCHAR(100) NOT NULL,
    fecha_nac    DATE         NOT NULL,
    sexo         ENUM('M','F','O') NOT NULL,
    password     VARCHAR(255) NOT NULL,
    especialidad VARCHAR(100) DEFAULT NULL,
    horario      TEXT         DEFAULT NULL,
    created_at   TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_medicos_telefono (telefono),
    UNIQUE KEY uq_medicos_email (email)
) ENGINE=InnoDB;

CREATE TABLE pacientes (
    id         INT UNSIGNED NOT NULL AUTO_INCREMENT,
    nombre     VARCHAR(50)  NOT NULL,
    apellido   VARCHAR(50)  NOT NULL,
    telefono   VARCHAR(15)  NOT NULL,
    email      VARCHAR(100) NOT NULL,
    fecha_nac  DATE         NOT NULL,
    sexo       ENUM('M','F','O') NOT NULL,
    password   VARCHAR(255) NOT NULL,
    created_at TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_pacientes_telefono (telefono),
    UNIQUE KEY uq_pacientes_email (email)
) ENGINE=InnoDB;

CREATE TABLE citas (
    id          INT UNSIGNED NOT NULL AUTO_INCREMENT,
    medico_id   INT UNSIGNED NOT NULL,
    paciente_id INT UNSIGNED DEFAULT NULL,
    fecha       DATE         NOT NULL,
    hora        TIME         NOT NULL,
    lugar       VARCHAR(150) NOT NULL,
    aseguradora VARCHAR(100) DEFAULT NULL,
    estado      ENUM('disponible','asignada') NOT NULL DEFAULT 'disponible',
    created_at  TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_cita_medico_fecha_hora (medico_id, fecha, hora),
    KEY fk_cita_paciente (paciente_id),
    CONSTRAINT fk_cita_medico   FOREIGN KEY (medico_id)   REFERENCES medicos (id)   ON DELETE CASCADE,
    CONSTRAINT fk_cita_paciente FOREIGN KEY (paciente_id) REFERENCES pacientes (id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- Médicos (contraseña: Demo1234!)
INSERT INTO medicos (nombre, apellido, telefono, email, fecha_nac, sexo, password, especialidad, horario) VALUES
    ('Elena',  'Vidal Soto',    '600100001', 'elena.vidal@example.com',  '1985-03-14', 'F', '$2y$10$whnpvwSBw.OxFpeAbyOsSO78XZP8Tw9IgxETBm1Sra05dZG5a5sR.', 'Cardiología',   'Lun - Jue | 09:00 - 13:00'),
    ('Marcos', 'Prieto Lara',   '600100002', 'marcos.prieto@example.com', '1979-11-02', 'M', '$2y$10$D3Y39v1a1iC3j9edq4lQyO0UabTgelpJ7sXmW4uvccJvB.aKvGTL.', 'Pediatría',     'Mar - Vie | 08:00 - 12:00'),
    ('Irene',  'Campos Ferrer', '600100003', 'irene.campos@example.com',  '1990-07-21', 'F', '$2y$10$fYR25j2QjKj0zEiquXDuteLEV1tKqJ2b8eUAOnadaCIzQ2VaSjSDy', 'Traumatología', 'Lun - Vie | 16:00 - 19:00');

-- Pacientes (contraseña: Demo1234!)
INSERT INTO pacientes (nombre, apellido, telefono, email, fecha_nac, sexo, password) VALUES
    ('Pablo', 'Rivas Mora',    '600200001', 'pablo.rivas@example.com',  '1996-05-09', 'M', '$2y$10$wQ8sQhYbxWVAJPMvtuTdU.o.vNusg28/QnavtW26lw21fwKbiwPrK'),
    ('Lucía', 'Navas Ortega',  '600200002', 'lucia.navas@example.com',  '2001-12-30', 'F', '$2y$10$Vo9d1zG/R7OExxtAc.rUXejSKc80OWxB8P6aB.JXKy5D4yZlYREFm'),
    ('Hugo',  'Serrano Peña',  '600200003', 'hugo.serrano@example.com', '1988-09-17', 'M', '$2y$10$hCkAT9fu/d4rwiazGvK6s.ZgqXUmfh5zy12LMiNKl03ZbvSvWCXtG');

-- Citas: las fechas son relativas al día en que se ejecuta el script.
INSERT INTO citas (medico_id, paciente_id, fecha, hora, lugar, aseguradora, estado) VALUES
    (1, NULL, DATE_ADD(CURDATE(), INTERVAL 3 DAY),  '09:00:00', 'Consulta 1, Centro de Salud Norte', NULL,       'disponible'),
    (1, NULL, DATE_ADD(CURDATE(), INTERVAL 3 DAY),  '10:00:00', 'Consulta 1, Centro de Salud Norte', NULL,       'disponible'),
    (1, 1,    DATE_ADD(CURDATE(), INTERVAL 5 DAY),  '11:30:00', 'Consulta 1, Centro de Salud Norte', 'Sanitas',  'asignada'),
    (2, NULL, DATE_ADD(CURDATE(), INTERVAL 4 DAY),  '08:30:00', 'Consulta 4, Hospital Central',      NULL,       'disponible'),
    (2, 2,    DATE_ADD(CURDATE(), INTERVAL 6 DAY),  '09:30:00', 'Consulta 4, Hospital Central',      'Adeslas',  'asignada'),
    (3, NULL, DATE_ADD(CURDATE(), INTERVAL 7 DAY),  '16:30:00', 'Consulta 2, Clínica Sur',           NULL,       'disponible');