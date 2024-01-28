CREATE TABLE IF NOT EXISTS entradas(
                    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                    turnos_id BIGINT UNSIGNED,
                    fecha TIMESTAMP DEFAULT NOW(),
                    monto FLOAT(2),
                    motivo TEXT,
                    nuevo_saldo FLOAT(2),
                    status BOOLEAN DEFAULT TRUE,
                    CONSTRAINT fkturnos_e FOREIGN KEY(turnos_id) REFERENCES turnos(id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB;