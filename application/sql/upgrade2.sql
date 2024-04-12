CREATE TABLE IF NOT EXISTS marcas(
        id SMALLINT UNSIGNED auto_increment PRIMARY KEY,
        descripcion VARCHAR(20)
)ENGINE=INNODB;
 
INSERT INTO marcas VALUES 
(1,"A GRANEL"),
(2,"ALTEX"),
(3,"AT"),
(4,"BORIS"),
(5,"CARRASCO"),
(6,"CHARLY"),
(7,"CORONA"),
(8,"CUBASA"),
(9,"CUPLASA"),
(10,"DERMA CARE"),
(11,"EKCOS"),
(12,"ELITE"),
(13,"FREGON"),
(14,"GONZALEZ"),
(15,"HAPPY PANDA"),
(16,"HULTEX"),
(17,"IDEAL"),
(18,"IKEA"),
(19,"INFIMIDEAL"),
(20,"JUMBO"),
(21,"KEEP CLEAN"),
(22,"KIMBERLY"),
(23,"LA BONITA"),
(24,"LA HIGIENICA"),
(25,"MARBELLA"),
(26,"MAXILIM"),
(27,"MEX"),
(28,"NEWEN"),
(29,"OVAL"),
(30,"PETALO"),
(31,"PREMIER"),
(32,"SANCHEZ & MARTIN"),
(33,"SCOTCH BRITE"),
(34,"THANNIA"),
(35,"TRUPER"),
(36,"VARIAS"),
(37,"WIESE"),
(0,"OTRAS");

INSERT INTO catalogo_unidades_sat (clave,descripcion,abrev) VALUES 
("AB","Paq. a granel","PAG"),
("XBN","Paca","PC"),
("XVI","Frasco Peq.","FP");}

CREATE TABLE IF NOT EXISTS subcategorias(
        id SMALLINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        descripcion VARCHAR(30)
)ENGINE=INNODB;

INSERT INTO subcategorias VALUES
(1,"ADICIONALES"),
(2,"AUTOMOTRIZ"),
(3,"AZADORES & SOPLADORES"),
(4,"BOLSAS PARA BASURA"),
(5,"BOMBAS DESTAPACAÑOS"),
(6,"CEPILLOS"),
(7,"CESTOS DE BASURA"),
(8,"COLADORES DE PLASTICO"),
(9,"CORONA"),
(10,"CORPORAL"),
(11,"CUBETAS"),
(12,"DESPACHADOR"),
(13,"DESPACHADOR HIGIÉNICO"),
(14,"EMBUDOS"),
(15,"ESCOBAS"),
(16,"ESPECIALES"),
(17,"FIBRAS"),
(18,"FRANELAS"),
(19,"GANCHOS"),
(20,"HIGIÉNICO DOMÉSTICO"),
(21,"HIGIÉNICO INSTITUCIONAL"),
(22,"HOGAR"),
(23,"JABONERA"),
(24,"JALADORES & LIMPIADORES"),
(25,"JICARA"),
(26,"LAVANDERÍA"),
(27,"LIMPIEZA VARIOS"),
(28,"MECHUDOS "),
(29,"MOPS Y ARMAZONES"),
(30,"MULTIUSOS"),
(31,"NEWEN"),
(32,"PALANGANAS"),
(33,"PASTILLAS AROMATIZANTES"),
(34,"RECOGEDORES"),
(35,"REPUESTOS"),
(36,"SACUDIDORES"),
(37,"SANCHEZ & MARTÍN"),
(38,"SECADORES"),
(39,"SEÑALAMIENTOS"),
(40,"TAPETES"),
(41,"TENDEDEDORES Y PINZAS"),
(42,"TOALLERO"),
(43,"TRUPER"),
(44,"VENENOS PARA INSECTOS");

ALTER TABLE items add subcategorias_id SMALLINT UNSIGNED;
ALTER TABLE items add marcas_id SMALLINT UNSIGNED;
ALTER TABLE items add unidad_interna VARCHAR(10);

ALTER TABLE usuarios add enc varchar(50) DEFAULT NULL;







