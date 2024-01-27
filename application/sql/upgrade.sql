alter table clientes add column tipo_cliente VARCHAR(20) DEFAULT NULL;

DROP TABLE IF EXISTS giros;
CREATE TABLE IF NOT EXISTS giros(
                id SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
                clave VARCHAR(50),
                descripcion VARCHAR(50)
        )ENGINE=INNODB;

INSERT INTO giros (clave,descripcion)VALUES
("comercializadora","Comercializadora"),
("veterinaria","Veterinaria"),
("educativa","Educativa"),
("gobierno","Gobierno"),
("municipios","Municipios"),
("oficinas","Oficinas"),
("lavaautos","Lava Autos"),
("restaurante","Restaurante"),
("hospital","Hospital"),
("clinica","Clínica"),
("bares","Bares"),
("gymnasio","Gymnasio"),
("iglesia","Iglesia"),
("hogar","Hogar"),
("salon","Salón de Fiestas"),
("servicios","Servicios de Limpieza"),
("hoteleria","Hotelería"),
("lavanderia","Lavandería"),
("miscelanea","Miscelanea"),
("comercio","Comercio en General");

alter table retiros add column tipo varchar(10) default 'M';

CREATE table if not exists movimientos_cupon(
        id INT UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,        
        codigo_cupon varchar(10),
        monto FLOAT(2),
        ventas_id BIGINT UNSIGNED,
        registro TIMESTAMP DEFAULT NOW(),
        CONSTRAINT fkventa_cupon FOREIGN KEY(ventas_id) REFERENCES ventas(id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=INNODB;


ALTER TABLE  cupones_dev change column monto saldo_inicial float(2);
ALTER TABLE cupones_dev add column saldo_final float(2);

ALTER TABLE cupones_dev add column ventas_id BIGINT UNSIGNED;
ALTER TABLE cupones_dev add CONSTRAINT fk_ventacupon FOREIGN KEY (ventas_id) REFERENCES ventas(id) ON DELETE CASCADE ON UPDATE CASCADE;

CREATE TABLE IF NOT EXISTS cotizaciones(
        id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
        clientes_id INT,
        folio VARCHAR(5),
        descuento DOUBLE DEFAULT 0,
        iva DOUBLE DEFAULT 0,
        ieps DOUBLE DEFAULT 0,
        subtotal DOUBLE DEFAULT 0,
        total DOUBLE,
        turnos_id BIGINT UNSIGNED,
        fecha_creacion TIMESTAMP DEFAULT NOW(),
        status CHAR(1) DEFAULT "I",
        CONSTRAINT fk_cotizacion_turno FOREIGN KEY (turnos_id) REFERENCES turnos(id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=INNODB;

CREATE TABLE IF NOT EXISTS cotizacion_detalles(                    
                    cotizaciones_id BIGINT UNSIGNED,
                    items_id BIGINT UNSIGNED,
                    cantidad DOUBLE DEFAULT 1,
                    estado CHAR(1) DEFAULT '1',                    
                    precio FLOAT,  
                    total FLOAT,
                    descuento FLOAT,
                    iva FLOAT,     
                    subtotal FLOAT,
                    iva_monto FLOAT,                    
                    status BOOLEAN DEFAULT 1,
                    CONSTRAINT fkcotizaciones_i FOREIGN KEY(cotizaciones_id) REFERENCES cotizaciones(id) ON UPDATE CASCADE ON DELETE CASCADE,
                    CONSTRAINT fkitems_cotizacion FOREIGN KEY(items_id) REFERENCES items(id) ON UPDATE CASCADE ON DELETE CASCADE
)ENGINE=INNODB;

-- 14/12/2022

ALTER TABLE cotizaciones ADD COLUMN comentarios TEXT;
ALTER TABLE cotizaciones ADD COLUMN tipo_precio VARCHAR(15) DEFAULT "menudeo";
ALTER TABLE cotizaciones ADD COLUMN vigencia SMALLINT UNSIGNED DEFAULT 1;
--16/12/2022

CREATE TABLE IF NOT EXISTS regimenes_sat(
        id SMALLINT AUTO_INCREMENT PRIMARY KEY,
        clave VARCHAR(3),
        descripcion TINYTEXT
)ENGINE=INNODB;

INSERT INTO regimenes_sat (clave,descripcion) VALUES
("601","General de Ley Personas Morales"),
("603","Personas Morales con Fines no Lucrativos"),
("605","Sueldos y Salarios e Ingresos Asimilados a Salarios"),
("606","Arrendamiento"),
("607","Régimen de Enajenación o Adquisición de Bienes"),
("608","Demás ingresos"),
("609","Consolidación"),
("610","Residentes en el Extranjero sin Establecimiento Permanente en México"),
("611","Ingresos por Dividendos (socios y accionistas)"),
("612","Personas Físicas con Actividades Empresariales y Profesionales"),
("614","Ingresos por intereses"),
("615","Régimen de los ingresos por obtención de premios"),
("616","Sin obligaciones fiscales"),
("620","Sociedades Cooperativas de Producción que optan por diferir sus ingresos"),
("621","Incorporación Fiscal"),
("622","Actividades Agrícolas, Ganaderas, Silvícolas y Pesqueras"),
("623","Opcional para Grupos de Sociedades"),
("624","Coordinados"),
("625","Régimen de las Actividades Empresariales con ingresos a través de Plataformas Tecnológicas"),
("626","Régimen Simplificado de Confianza"),
("628","Hidrocarburos"),
("629","De los Regímenes Fiscales Preferentes y de las Empresas Multinacionales"),
("630","Enajenación de acciones en bolsa de valores");

ALTER TABLE clientes ADD COLUMN regimenes_id SMALLINT DEFAULT 3;








