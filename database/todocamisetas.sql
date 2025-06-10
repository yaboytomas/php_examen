CREATE TABLE clientes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre_comercial VARCHAR(255) NOT NULL,
    rut VARCHAR(20) UNIQUE NOT NULL,
    direccion TEXT,
    categoria ENUM('Regular', 'Preferencial') DEFAULT 'Regular',
    contacto_nombre VARCHAR(255),
    contacto_email VARCHAR(255),
    porcentaje_oferta DECIMAL(5,2) DEFAULT 0.00,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE camisetas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(255) NOT NULL,
    club VARCHAR(255) NOT NULL,
    pais VARCHAR(100),
    tipo VARCHAR(100),
    color VARCHAR(100),
    precio DECIMAL(10,2) NOT NULL,
    precio_oferta DECIMAL(10,2) NULL,
    detalles TEXT,
    codigo_producto VARCHAR(50) UNIQUE NOT NULL,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE tallas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(10) UNIQUE NOT NULL,
    orden INT DEFAULT 0
);

CREATE TABLE camiseta_tallas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    camiseta_id INT,
    talla_id INT,
    FOREIGN KEY (camiseta_id) REFERENCES camisetas(id) ON DELETE CASCADE,
    FOREIGN KEY (talla_id) REFERENCES tallas(id) ON DELETE CASCADE,
    UNIQUE KEY unique_camiseta_talla (camiseta_id, talla_id)
);

INSERT INTO tallas (nombre, orden) VALUES 
('XS', 1), ('S', 2), ('M', 3), ('L', 4), ('XL', 5), ('XXL', 6);

INSERT INTO clientes (nombre_comercial, rut, direccion, categoria, contacto_nombre, contacto_email, porcentaje_oferta) VALUES 
('90minutos', '12345678-9', 'Providencia, Santiago', 'Preferencial', 'Juan Pérez', 'juan@90minutos.cl', 15.00),
('tdeportes', '98765432-1', 'Las Condes, Santiago', 'Regular', 'María González', 'maria@tdeportes.cl', 5.00);

INSERT INTO camisetas (titulo, club, pais, tipo, color, precio, precio_oferta, detalles, codigo_producto) VALUES 
('Camiseta Local 2025 – Selección Chilena', 'Selección Chilena', 'Chile', 'Local', 'Rojo y Azul', 45000.00, 38000.00, 'Edición aniversario 2025', 'SCL2025L'),
('Camiseta Visita 2025 – Real Madrid', 'Real Madrid', 'España', 'Visita', 'Negro', 55000.00, NULL, 'Temporada 2024-2025', 'RM2025V'),
('Camiseta Local 2025 – FC Barcelona', 'FC Barcelona', 'España', 'Local', 'Azul y Granate', 52000.00, 47000.00, 'Diseño clásico', 'FCB2025L');

INSERT INTO camiseta_tallas (camiseta_id, talla_id) VALUES 
(1, 1), (1, 2), (1, 3), (1, 4), (1, 5),
(2, 2), (2, 3), (2, 4), (2, 5),
(3, 3), (3, 4), (3, 5); 