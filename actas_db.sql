CREATE DATABASE actas_db;
USE actas_db;
CREATE TABLE actas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    fecha_creacion DATE,
    codigo_tarjeta VARCHAR(50),
    rut VARCHAR(20),
    nombre VARCHAR(100),
    unidad VARCHAR(100),
    email VARCHAR(100),
    solicita VARCHAR(100),
    patente VARCHAR(50),
    fono VARCHAR(50),
    adquisicion VARCHAR(50),
    tipo_usuario VARCHAR(50),
    folio VARCHAR(50)
);
