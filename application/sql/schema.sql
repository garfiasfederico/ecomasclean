DROP DATABASE IF EXISTS ecoclean;

CREATE DATABASE ecoclean CHARACTER SET UTF8;
USE ecoclean;

CREATE TABLE IF NOT EXISTS direcciones(
                    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
                    calle VARCHAR(30),
                    num_interno VARCHAR(5),
                    num_externo VARCHAR(5),
                    colonia VARCHAR(20),
                    localidad VARCHAR(20),
                    municipio VARCHAR(20),
                    cp VARCHAR(10),
                    estado VARCHAR(20) DEFAULT "OAXACA",
                    referencia VARCHAR(200)
)ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS empleados(
                    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                    nombre VARCHAR(20),
                    apellido_paterno VARCHAR(20),
                    apellido_materno VARCHAR(20),
                    curp VARCHAR(18),
                    direcciones_id INT UNSIGNED,                    
                    telefono_casa VARCHAR(15),
                    telefono_celular VARCHAR(15),
                    sexo CHAR(1),
                    correo_electronico VARCHAR(50),
                    CONSTRAINT fkdireccion_ FOREIGN KEY (direcciones_id) REFERENCES direcciones(id) ON UPDATE CASCADE,
                    status BOOLEAN DEFAULT TRUE,
                    fecha_registro TIMESTAMP DEFAULT NOW()
)ENGINE=InnoDB;


CREATE TABLE IF NOT EXISTS roles(
                    id SMALLINT UNSIGNED  AUTO_INCREMENT PRIMARY KEY,
                    descripcion VARCHAR(50)
)ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS usuarios(
                    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                    cuenta VARCHAR(50),
                    password VARCHAR(40),
                    fecha_registro TIMESTAMP DEFAULT NOW(),
                    status BOOLEAN DEFAULT true,
                    empleados_id INT UNSIGNED,
                    CONSTRAINT fkempleado_ FOREIGN KEY (empleados_id) REFERENCES empleados(id) ON UPDATE CASCADE ON DELETE CASCADE                    
)ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS usuario_roles(
                    usuarios_id INT UNSIGNED NOT NULL,
                    roles_id SMALLINT UNSIGNED NOT NULL,
                    CONSTRAINT fkusuario_ FOREIGN KEY (usuarios_id) REFERENCES usuarios(id) ON DELETE CASCADE ON UPDATE CASCADE,
                    CONSTRAINT fkrol_ FOREIGN KEY (roles_id) REFERENCES roles(id) ON DELETE CASCADE ON UPDATE CASCADE                  
)ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS categorias(
                    id SMALLINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                    descripcion VARCHAR(30)
)ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS empleado_acceso(
                    empleados_id INT UNSIGNED,
                    codigo_acceso VARCHAR(4),
                    turno VARCHAR(10),    
                    CONSTRAINT fkempleados_a FOREIGN KEY(empleados_id) REFERENCES empleados(id) ON UPDATE CASCADE ON DELETE CASCADE             
)ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS catalogo_claves_sat  (
                            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                            clave VARCHAR(10),
                            descripcion VARCHAR(150),
                            nivel CHAR(1) DEFAULT '1'
)ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS catalogo_unidades_sat(
                            id SMALLINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                            clave VARCHAR(3),
                            descripcion VARCHAR(15),
                            abrev VARCHAR(15)
)Engine=InnoDB;


CREATE TABLE IF NOT EXISTS items(
                    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
                    identificador VARCHAR(50),
                    clave VARCHAR(30),
                    nombre VARCHAR(100),
                    alias VARCHAR(15),
                    unidad VARCHAR(10),
                    costo FLOAT(2),
                    precio_publico FLOAT(2),
                    precio_mayoreo FLOAT(2),
                    precio_distribuidor FLOAT(2) DEFAULT 0,
                    existencias DOUBLE UNSIGNED DEFAULT 0,
                    avatar VARCHAR(50),
                    categorias_id VARCHAR(10),
                    linea VARCHAR (15),
                    status BOOLEAN DEFAULT TRUE,
                    iva FLOAT(2) DEFAULT .16                                    
)ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS turnos(
                    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                    usuarios_id INT UNSIGNED,
                    fecha DATE,
                    fecha_registro TIMESTAMP DEFAULT NOW(),
                    fecha_cierre TIMESTAMP DEFAULT NOW(),
                    estado CHAR(1) COMMENT '1 abierto, 2 cerrado',
                    saldo_inicial DOUBLE DEFAULT 0,
                    saldo_final DOUBLE DEFAULT 0,
                    saldo_final_manual DOUBLE DEFAULT 0,
                    CONSTRAINT fkusuario_t FOREIGN KEY (usuarios_id) REFERENCES usuarios(id) ON UPDATE CASCADE
)ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS ventas(
                    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                    turnos_id BIGINT UNSIGNED,                    
                    fecha_registro TIMESTAMP DEFAULT NOW(),                    
                    estado VARCHAR(15),
                    status BOOLEAN DEFAULT TRUE,
                    descuento DOUBLE DEFAULT 0,
                    iva DOUBLE DEFAULT 0,
                    ieps DOUBLE DEFAULT 0,
                    subtotal DOUBLE DEFAULT 0,
                    total DOUBLE,
                    forma_pago VARCHAR(13),
                    pago DOUBLE DEFAULT 0,
                    cambio DOUBLE DEFAULT 0,
                    CONSTRAINT fkturnos_v FOREIGN KEY (turnos_id) REFERENCES turnos(id) ON DELETE CASCADE ON UPDATE CASCADE                    
)ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS venta_items(
                    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                    ventas_id BIGINT UNSIGNED,
                    items_id BIGINT UNSIGNED,
                    cantidad DOUBLE DEFAULT 1,
                    estado CHAR(1) DEFAULT '1',
                    fecha_registro TIMESTAMP DEFAULT NOW(),
                    precio FLOAT,  
                    total FLOAT,
                    descuento FLOAT,
                    iva FLOAT,     
                    subtotal FLOAT,
                    iva_monto FLOAT,                    
                    status BOOLEAN DEFAULT 1,
                    CONSTRAINT fkventas_i FOREIGN KEY(ventas_id) REFERENCES ventas(id) ON UPDATE CASCADE ON DELETE CASCADE,
                    CONSTRAINT fkitems_m FOREIGN KEY(items_id) REFERENCES items(id) ON UPDATE CASCADE ON DELETE CASCADE
)ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS clientes(
                    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                    fecha_registro TIMESTAMP DEFAULT NOW(),
                    tipo_persona VARCHAR(6),
                    rfc VARCHAR(13),
                    nombre VARCHAR(100),
                    nombre_comercial VARCHAR(200),
                    notas VARCHAR(300),
                    giro_comercio VARCHAR(50),
                    telefono_casa VARCHAR(10),
                    telefono_celular VARCHAR(10),
                    correo_electronico VARCHAR(50),
                    status BOOLEAN DEFAULT 1,
                    direcciones_id INT UNSIGNED,                
                    ubicacion TEXT DEFAULT NULL,
                    direccion_entrega VARCHAR(200),
                    nombre_contacto VARCHAR(100),
                    telefono_casa_contacto VARCHAR(10),
                    celular_contacto VARCHAR(10),
                    correo_electronico_contacto VARCHAR(50),
                    confactura BOOLEAN DEFAULT FALSE,
                    contacto BOOLEAN DEFAULT FALSE,                
                    CONSTRAINT fkdireccion_c FOREIGN KEY(direcciones_id) REFERENCES direcciones(id)
)ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS proveedores(
                    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                    fecha_registro TIMESTAMP DEFAULT NOW(),
                    tipo_persona VARCHAR(6),
                    rfc VARCHAR(13),
                    nombre VARCHAR(100),
                    telefono_casa VARCHAR(10),
                    telefono_celular VARCHAR(10),
                    correo_electronico VARCHAR(50),
                    status BOOLEAN DEFAULT 1,
                    direcciones_id INT UNSIGNED,
                    CONSTRAINT fkdireccion_p FOREIGN KEY(direcciones_id) REFERENCES direcciones(id)
)ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS proveedor_productos(
                    proveedores_id INT UNSIGNED,
                    items_id BIGINT UNSIGNED,
                    CONSTRAINT fkproveedores_p FOREIGN KEY(proveedores_id) REFERENCES proveedores(id) ON DELETE CASCADE ON UPDATE CASCADE,
                    CONSTRAINT fkitems_p FOREIGN KEY(items_id) REFERENCES items(id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS retiros(
                    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                    turnos_id BIGINT UNSIGNED,
                    fecha TIMESTAMP DEFAULT NOW(),
                    monto FLOAT(2),
                    motivo TEXT,
                    nuevo_saldo FLOAT(2),
                    status BOOLEAN DEFAULT TRUE,
                    CONSTRAINT fkturnos_r FOREIGN KEY(turnos_id) REFERENCES turnos(id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS compras(
                                    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                                    identificador VARCHAR(30),
                                    tipo_documento VARCHAR(5),
                                    fecha_compra DATE DEFAULT NULL,
                                    proveedores_id INT UNSIGNED,
                                    fecha_registro TIMESTAMP DEFAULT NOW(),
                                    total_compra DOUBLE DEFAULT 0,
                                    origen_pago VARCHAR(10),
                                    status BOOLEAN DEFAULT TRUE,
                                    usuarios_id INT UNSIGNED,
                                    CONSTRAINT fkusuarios_c FOREIGN KEY(usuarios_id) REFERENCES usuarios(id) ON DELETE CASCADE ON UPDATE CASCADE,
                                    CONSTRAINT fkproveedores_c FOREIGN KEY(proveedores_id) REFERENCES proveedores(id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS compra_detalles(
                                            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                                            compras_id BIGINT UNSIGNED,
                                            items_id BIGINT UNSIGNED,
                                            cantidad DOUBLE DEFAULT 0,
                                            status BOOLEAN DEFAULT TRUE,
                                            CONSTRAINT fkitems_cd FOREIGN KEY(items_id) REFERENCES items(id) ON DELETE CASCADE ON UPDATE CASCADE,
                                            CONSTRAINT fkcompras_cd FOREIGN KEY(compras_id) REFERENCES compras(id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB;



CREATE TABLE IF NOT EXISTS facturacion(
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    ventas_id BIGINT UNSIGNED,
    rfc_receptor VARCHAR(13),
    nombre_receptor VARCHAR(200),
    usoCFDI VARCHAR(5),
    fecha_registro TIMESTAMP DEFAULT NOW(),
    soap TEXT,
    status BOOLEAN DEFAULT TRUE,
    uuid VARCHAR(40),
    CONSTRAINT fkventas_f FOREIGN KEY(ventas_id) REFERENCES ventas(id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB;


CREATE TABLE IF NOT EXISTS movimientos_dev(
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    tipo CHAR(1),
    ventas_id BIGINT UNSIGNED,
    items_id BIGINT UNSIGNED,
    cantidad DOUBLE DEFAULT 1,        
    precio FLOAT,  
    total FLOAT,
    descuento FLOAT,
    iva FLOAT,     
    subtotal FLOAT,
    iva_monto FLOAT,              
    fecha_registro TIMESTAMP DEFAULT NOW(),
    status BOOLEAN DEFAULT TRUE,
    CONSTRAINT fkventas_m FOREIGN KEY(ventas_id) REFERENCES ventas(id) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fkitems_mo FOREIGN KEY(items_id) REFERENCES items(id) ON DELETE CASCADE ON UPDATE CASCADE
)Engine=InnoDB;

CREATE TABLE IF NOT EXISTS cupones_dev(
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    movimientos_id BIGINT UNSIGNED,
    monto FLOAT,
    vigencia DATE,
    fecha_creacion TIMESTAMP DEFAULT NOW(),
    status BOOLEAN DEFAULT TRUE,
    codigo VARCHAR(10),
    estado CHAR(1),
    CONSTRAINT fkmovimientos_c FOREIGN KEY(movimientos_id) REFERENCES movimientos_dev(id) ON DELETE CASCADE ON UPDATE CASCADE
)Engine=InnoDB;

CREATE TABLE IF NOT EXISTS venta_pagos(
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    ventas_id BIGINT UNSIGNED,
    forma_pago VARCHAR(13),
    pago DOUBLE,
    obs VARCHAR(10),
    registro TIMESTAMP DEFAULT NOW(),
    CONSTRAINT fkventa_pagos FOREIGN KEY (ventas_id) REFERENCES ventas(id) ON DELETE CASCADE ON UPDATE CASCADE
)Engine=InnoDB;
