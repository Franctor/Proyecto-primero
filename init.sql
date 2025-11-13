SET NAMES utf8mb4;
SET CHARACTER SET utf8mb4;
SET character_set_connection = utf8mb4;
SET character_set_server = utf8mb4;
SET character_set_database = utf8mb4;
SET collation_connection = utf8mb4_unicode_ci;
SET collation_server = utf8mb4_unicode_ci;

-- MySQL Workbench Forward Engineering
SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema mydb
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema mydb
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `mydb` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `mydb` ;

-- -----------------------------------------------------
-- Table `mydb`.`rol`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`rol` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `rol_name` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `rol_name_UNIQUE` (`rol_name` ASC) VISIBLE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`provincia`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`provincia` (
  `id` INT NOT NULL,
  `nombre_prov` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`localidad`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`localidad` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `nombre_loc` VARCHAR(45) NOT NULL,
  `provincia_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_localidad_provincia1_idx` (`provincia_id` ASC) VISIBLE,
  CONSTRAINT `fk_localidad_provincia1`
    FOREIGN KEY (`provincia_id`)
    REFERENCES `mydb`.`provincia` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`usuario`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`usuario` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `nombre_usuario` VARCHAR(255) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `rol_id` INT NOT NULL,
  `localidad_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `nombre_usuario_UNIQUE` (`nombre_usuario` ASC) VISIBLE,
  INDEX `fk_usuario_rol1_idx` (`rol_id` ASC) VISIBLE,
  INDEX `fk_usuario_localidad1_idx` (`localidad_id` ASC) VISIBLE,
  CONSTRAINT `fk_usuario_rol1`
    FOREIGN KEY (`rol_id`)
    REFERENCES `mydb`.`rol` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_usuario_localidad1`
    FOREIGN KEY (`localidad_id`)
    REFERENCES `mydb`.`localidad` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`empresa`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`empresa` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(45) NOT NULL,
  `telefono` INT NOT NULL,
  `direccion` VARCHAR(255) NOT NULL,
  `nombre_persona` VARCHAR(45) NOT NULL,
  `telefono_persona` VARCHAR(45) NOT NULL,
  `logo` VARCHAR(255) NOT NULL,
  `verificada` TINYINT NOT NULL DEFAULT 0,
  `descripcion` VARCHAR(500) NOT NULL,
  `usuario_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `nombre_UNIQUE` (`nombre` ASC) VISIBLE,
  UNIQUE INDEX `telefono_UNIQUE` (`telefono` ASC) VISIBLE,
  INDEX `fk_empresa_usuario1_idx` (`usuario_id` ASC) VISIBLE,
  CONSTRAINT `fk_empresa_usuario1`
    FOREIGN KEY (`usuario_id`)
    REFERENCES `mydb`.`usuario` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`alumno`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`alumno` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(45) NOT NULL,
  `apellido` VARCHAR(45) NOT NULL,
  `telefono` INT NOT NULL,
  `direccion` VARCHAR(255) NOT NULL,
  `foto` VARCHAR(255) NOT NULL,
  `cv` VARCHAR(255) NOT NULL,
  `activo` TINYINT NOT NULL,
  `usuario_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `telefono_UNIQUE` (`telefono` ASC) VISIBLE,
  INDEX `fk_alumno_usuario1_idx` (`usuario_id` ASC) VISIBLE,
  CONSTRAINT `fk_alumno_usuario1`
    FOREIGN KEY (`usuario_id`)
    REFERENCES `mydb`.`usuario` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`familia`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`familia` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`ciclo`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`ciclo` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(255) NOT NULL,
  `nivel` VARCHAR(45) NOT NULL,
  `familia_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_ciclo_familia1_idx` (`familia_id` ASC) VISIBLE,
  CONSTRAINT `fk_ciclo_familia1`
    FOREIGN KEY (`familia_id`)
    REFERENCES `mydb`.`familia` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`alumnos_ciclos`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`alumnos_ciclos` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `fecha_inicio` DATE NULL,
  `alumno_id` INT NOT NULL,
  `ciclo_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_alumnos_ciclos_alumno1_idx` (`alumno_id` ASC) VISIBLE,
  INDEX `fk_alumnos_ciclos_ciclo1_idx` (`ciclo_id` ASC) VISIBLE,
  CONSTRAINT `fk_alumnos_ciclos_alumno1`
    FOREIGN KEY (`alumno_id`)
    REFERENCES `mydb`.`alumno` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_alumnos_ciclos_ciclo1`
    FOREIGN KEY (`ciclo_id`)
    REFERENCES `mydb`.`ciclo` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`oferta`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`oferta` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `fecha_oferta` DATETIME NOT NULL,
  `fecha_fiin_oferta` DATETIME NOT NULL,
  `empresa_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_oferta_empresa1_idx` (`empresa_id` ASC) VISIBLE,
  CONSTRAINT `fk_oferta_empresa1`
    FOREIGN KEY (`empresa_id`)
    REFERENCES `mydb`.`empresa` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`solicitud`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`solicitud` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `fecha_solicitud` DATETIME NOT NULL,
  `finalizado` TINYINT NOT NULL DEFAULT 0,
  `alumno_id` INT NOT NULL,
  `oferta_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_solicitud_alumno1_idx` (`alumno_id` ASC) VISIBLE,
  INDEX `fk_solicitud_oferta1_idx` (`oferta_id` ASC) VISIBLE,
  CONSTRAINT `fk_solicitud_alumno1`
    FOREIGN KEY (`alumno_id`)
    REFERENCES `mydb`.`alumno` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_solicitud_oferta1`
    FOREIGN KEY (`oferta_id`)
    REFERENCES `mydb`.`oferta` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`ofertas_ciclos`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`ofertas_ciclos` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `required` TINYINT NULL,
  `ciclo_id` INT NOT NULL,
  `oferta_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_ofertas_ciclos_ciclo1_idx` (`ciclo_id` ASC) VISIBLE,
  INDEX `fk_ofertas_ciclos_oferta1_idx` (`oferta_id` ASC) VISIBLE,
  CONSTRAINT `fk_ofertas_ciclos_ciclo1`
    FOREIGN KEY (`ciclo_id`)
    REFERENCES `mydb`.`ciclo` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_ofertas_ciclos_oferta1`
    FOREIGN KEY (`oferta_id`)
    REFERENCES `mydb`.`oferta` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`token`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`token` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `valor` VARCHAR(255) NOT NULL,
  `fecha_creacion` DATETIME NULL,
  `fecha_expiracion` DATETIME NULL,
  `activo` TINYINT NULL DEFAULT 1,
  `usuario_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_token_usuario1_idx` (`usuario_id` ASC) VISIBLE,
  UNIQUE INDEX `valor_UNIQUE` (`valor` ASC) VISIBLE,
  CONSTRAINT `fk_token_usuario1`
    FOREIGN KEY (`usuario_id`)
    REFERENCES `mydb`.`usuario` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
-- End of MySQL Workbench Forward Engineering
insert into rol (rol_name) values
('admin'),
('alumno'),
('empresa');

INSERT INTO provincia (id, nombre_prov) VALUES
(2, 'Albacete'),
(3, 'Alicante/Alacant'),
(4, 'Almería'),
(1, 'Araba/Álava'),
(33, 'Asturias'),
(5, 'Ávila'),
(6, 'Badajoz'),
(7, 'Balears, Illes'),
(8, 'Barcelona'),
(48, 'Bizkaia'),
(9, 'Burgos'),
(10, 'Cáceres'),
(11, 'Cádiz'),
(39, 'Cantabria'),
(12, 'Castellón/Castelló'),
(51, 'Ceuta'),
(13, 'Ciudad Real'),
(14, 'Córdoba'),
(15, 'Coruña, A'),
(16, 'Cuenca'),
(20, 'Gipuzkoa'),
(17, 'Girona'),
(18, 'Granada'),
(19, 'Guadalajara'),
(21, 'Huelva'),
(22, 'Huesca'),
(23, 'Jaén'),
(24, 'León'),
(27, 'Lugo'),
(25, 'Lleida'),
(28, 'Madrid'),
(29, 'Málaga'),
(52, 'Melilla'),
(30, 'Murcia'),
(31, 'Navarra'),
(32, 'Ourense'),
(34, 'Palencia'),
(35, 'Palmas, Las'),
(36, 'Pontevedra'),
(26, 'Rioja, La'),
(37, 'Salamanca'),
(38, 'Santa Cruz de Tenerife'),
(40, 'Segovia'),
(41, 'Sevilla'),
(42, 'Soria'),
(43, 'Tarragona'),
(44, 'Teruel'),
(45, 'Toledo'),
(46, 'Valencia/València'),
(47, 'Valladolid'),
(49, 'Zamora'),
(50, 'Zaragoza');

INSERT INTO localidad (nombre_loc, provincia_id) VALUES
-- Albacete
('Albacete', 2),
('Hellín', 2),
('Villarrobledo', 2),
('La Roda', 2),
('Almansa', 2),
('Caudete', 2),

-- Alicante/Alacant
('Alicante/Alacant', 3),
('Elche/Elx', 3),
('Benidorm', 3),
('Elda', 3),
('Denia', 3),
('Villena', 3),

-- Almería
('Almería', 4),
('Roquetas de Mar', 4),
('El Ejido', 4),
('Níjar', 4),
('Adra', 4),
('Vícar', 4),

-- Araba/Álava
('Vitoria-Gasteiz', 1),
('Amurrio', 1),
('Laudio/Llodio', 1),
('Salvatierra/Agurain', 1),
('Alegría-Dulantzi', 1),
('Oyón-Oion', 1),

-- Asturias
('Oviedo', 33),
('Gijón', 33),
('Avilés', 33),
('Mieres', 33),
('Langreo', 33),
('Siero', 33),

-- Ávila
('Ávila', 5),
('Arévalo', 5),
('Arenas de San Pedro', 5),
('Cebreros', 5),
('Las Navas del Marqués', 5),
('El Barco de Ávila', 5),

-- Badajoz
('Badajoz', 6),
('Mérida', 6),
('Don Benito', 6),
('Villanueva de la Serena', 6),
('Zafra', 6),
('Almendralejo', 6),

-- Balears, Illes
('Palma', 7),
('Manacor', 7),
('Inca', 7),
('Ciutadella de Menorca', 7),
('Ibiza/Eivissa', 7),
('Mahón/Maó', 7),

-- Barcelona
('Barcelona', 8),
('L\'Hospitalet de Llobregat', 8),
('Terrassa', 8),
('Badalona', 8),
('Sabadell', 8),
('Mataró', 8),

-- Bizkaia
('Bilbao', 48),
('Barakaldo', 48),
('Getxo', 48),
('Portugalete', 48),
('Santurtzi', 48),
('Durango', 48),

-- Burgos
('Burgos', 9),
('Miranda de Ebro', 9),
('Aranda de Duero', 9),
('Lerma', 9),
('Briviesca', 9),
('Medina de Pomar', 9),

-- Cáceres
('Cáceres', 10),
('Plasencia', 10),
('Navalmoral de la Mata', 10),
('Coria', 10),
('Trujillo', 10),
('Miajadas', 10),

-- Cádiz
('Cádiz', 11),
('Jerez de la Frontera', 11),
('Algeciras', 11),
('San Fernando', 11),
('El Puerto de Santa María', 11),
('Chiclana de la Frontera', 11),

-- Cantabria
('Santander', 39),
('Torrelavega', 39),
('Castro Urdiales', 39),
('Camargo', 39),
('Piélagos', 39),
('Laredo', 39),

-- Castellón/Castelló
('Castellón de la Plana', 12),
('Villarreal/Vila-real', 12),
('Burriana', 12),
('Onda', 12),
('Benicarló', 12),
('Vinaròs', 12),

-- Ceuta
('Ceuta', 51),

-- Ciudad Real
('Ciudad Real', 13),
('Puertollano', 13),
('Tomelloso', 13),
('Valdepeñas', 13),
('Alcázar de San Juan', 13),
('Manzanares', 13),

-- Córdoba
('Córdoba', 14),
('Lucena', 14),
('Puente Genil', 14),
('Montilla', 14),
('Cabra', 14),
('Priego de Córdoba', 14),

-- Coruña, A
('A Coruña', 15),
('Santiago de Compostela', 15),
('Ferrol', 15),
('Narón', 15),
('Oleiros', 15),
('Carballo', 15),

-- Cuenca
('Cuenca', 16),
('Tarancón', 16),
('Motilla del Palancar', 16),
('San Clemente', 16),
('Mota del Cuervo', 16),
('Horcajo de Santiago', 16),

-- Gipuzkoa
('Donostia/San Sebastián', 20),
('Irun', 20),
('Eibar', 20),
('Zarautz', 20),
('Arrasate/Mondragón', 20),
('Tolosa', 20),

-- Girona
('Girona', 17),
('Figueres', 17),
('Olot', 17),
('Blanes', 17),
('Lloret de Mar', 17),
('Banyoles', 17),

-- Granada
('Granada', 18),
('Motril', 18),
('Almuñécar', 18),
('Loja', 18),
('Baza', 18),
('Guadix', 18),

-- Guadalajara
('Guadalajara', 19),
('Azuqueca de Henares', 19),
('El Casar', 19),
('Cabanillas del Campo', 19),
('Sigüenza', 19),
('Marchamalo', 19),

-- Huelva
('Huelva', 21),
('Lepe', 21),
('Moguer', 21),
('Isla Cristina', 21),
('Ayamonte', 21),
('Almonte', 21),

-- Huesca
('Huesca', 22),
('Monzón', 22),
('Barbastro', 22),
('Jaca', 22),
('Fraga', 22),
('Binéfar', 22),

-- Jaén
('Jaén', 23),
('Linares', 23),
('Andújar', 23),
('Úbeda', 23),
('Martos', 23),
('Baeza', 23),

-- León
('León', 24),
('Ponferrada', 24),
('San Andrés del Rabanedo', 24),
('La Bañeza', 24),
('Astorga', 24),
('Villablino', 24),

-- Lugo
('Lugo', 27),
('Monforte de Lemos', 27),
('Vilalba', 27),
('Sarria', 27),
('Burela', 27),
('Ribadeo', 27),

-- Lleida
('Lleida', 25),
('Balaguer', 25),
('Tàrrega', 25),
('Mollerussa', 25),
('La Seu d’Urgell', 25),
('Cervera', 25),

-- Madrid
('Madrid', 28),
('Móstoles', 28),
('Alcalá de Henares', 28),
('Fuenlabrada', 28),
('Getafe', 28),
('Leganés', 28),

-- Málaga
('Málaga', 29),
('Marbella', 29),
('Fuengirola', 29),
('Benalmádena', 29),
('Vélez-Málaga', 29),
('Estepona', 29),

-- Melilla
('Melilla', 52),

-- Murcia
('Murcia', 30),
('Cartagena', 30),
('Lorca', 30),
('Molina de Segura', 30),
('Alcantarilla', 30),
('Cieza', 30),

-- Navarra
('Pamplona/Iruña', 31),
('Tudela', 31),
('Barañáin', 31),
('Estella-Lizarra', 31),
('Ansoáin', 31),
('Burlada/Burlata', 31),

-- Ourense
('Ourense', 32),
('Verín', 32),
('O Barco de Valdeorras', 32),
('Xinzo de Limia', 32),
('Barbadás', 32),
('Celanova', 32),

-- Palencia
('Palencia', 34),
('Aguilar de Campoo', 34),
('Guardo', 34),
('Venta de Baños', 34),
('Dueñas', 34),
('Cervera de Pisuerga', 34),

-- Palmas, Las
('Las Palmas de Gran Canaria', 35),
('Telde', 35),
('Arrecife', 35),
('San Bartolomé de Tirajana', 35),
('Arucas', 35),
('Puerto del Rosario', 35),

-- Pontevedra
('Pontevedra', 36),
('Vigo', 36),
('Vilagarcía de Arousa', 36),
('Redondela', 36),
('Marín', 36),
('Cangas', 36),

-- Rioja, La
('Logroño', 26),
('Calahorra', 26),
('Arnedo', 26),
('Haro', 26),
('Alfaro', 26),
('Nájera', 26),

-- Salamanca
('Salamanca', 37),
('Béjar', 37),
('Ciudad Rodrigo', 37),
('Santa Marta de Tormes', 37),
('Villamayor', 37),
('Guijuelo', 37),

-- Santa Cruz de Tenerife
('Santa Cruz de Tenerife', 38),
('San Cristóbal de La Laguna', 38),
('Arona', 38),
('Adeje', 38),
('Los Realejos', 38),
('Puerto de la Cruz', 38),

-- Segovia
('Segovia', 40),
('Cuéllar', 40),
('El Espinar', 40),
('San Ildefonso', 40),
('Carbonero el Mayor', 40),
('Cantalejo', 40),

-- Sevilla
('Sevilla', 41),
('Dos Hermanas', 41),
('Alcalá de Guadaíra', 41),
('Utrera', 41),
('Mairena del Aljarafe', 41),
('Écija', 41),

-- Soria
('Soria', 42),
('Almazán', 42),
('Ólvega', 42),
('El Burgo de Osma', 42),
('San Esteban de Gormaz', 42),
('Golmayo', 42),

-- Tarragona
('Tarragona', 43),
('Reus', 43),
('Cambrils', 43),
('Salou', 43),
('Valls', 43),
('Tortosa', 43),

-- Teruel
('Teruel', 44),
('Alcañiz', 44),
('Andorra', 44),
('Calamocha', 44),
('Utrillas', 44),
('Mora de Rubielos', 44),

-- Toledo
('Toledo', 45),
('Talavera de la Reina', 45),
('Illescas', 45),
('Seseña', 45),
('Torrijos', 45),
('Sonseca', 45),

-- Valencia/València
('Valencia', 46),
('Gandía', 46),
('Torrent', 46),
('Paterna', 46),
('Sagunto/Sagunt', 46),
('Alzira', 46),

-- Valladolid
('Valladolid', 47),
('Medina del Campo', 47),
('Laguna de Duero', 47),
('Tordesillas', 47),
('Arroyo de la Encomienda', 47),
('Peñafiel', 47),

-- Zamora
('Zamora', 49),
('Benavente', 49),
('Toro', 49),
('Morales del Vino', 49),
('Fuentesaúco', 49),
('Villalpando', 49),

-- Zaragoza
('Zaragoza', 50),
('Calatayud', 50),
('Ejea de los Caballeros', 50),
('Utebo', 50),
('Tarazona', 50),
('La Almunia de Doña Godina', 50);

INSERT INTO familia (nombre) VALUES
('Actividades Físicas y Deportivas'),
('Administración y Gestión'),
('Agraria'),
('Artes Gráficas'),
('Comercio y Marketing'),
('Edificación y Obra Civil'),
('Electricidad y Electrónica'),
('Energía y Agua'),
('Fabricación Mecánica'),
('Hostelería y Turismo'),
('Imagen Personal'),
('Imagen y Sonido'),
('Industrias Alimentarias'),
('Informática y Comunicaciones'),
('Instalación y Mantenimiento'),
('Madera, Mueble y Corcho'),
('Marítimo Pesquera'),
('Química'),
('Sanidad'),
('Seguridad y Medio Ambiente'),
('Servicios Socioculturales y a la Comunidad'),
('Textil, Confección y Piel'),
('Transporte y Mantenimiento de Vehículos'),
('Vidrio y Cerámica');

INSERT INTO ciclo (nombre, nivel, familia_id) VALUES
-- Actividades Físicas y Deportivas (1)
('Técnico Superior en Enseñanza y Animación Sociodeportiva', 'superior', 1),
('Técnico Superior en Acondicionamiento Físico', 'superior', 1),

-- Administración y Gestión (2)
('Técnico Superior en Asistencia a la Dirección', 'superior', 2),
('Técnico Superior en Administración y Finanzas', 'superior', 2),

-- Agraria (3)
('Técnico Superior en Paisajismo y Medio Rural', 'superior', 3),
('Técnico Superior en Gestión Forestal y del Medio Natural', 'superior', 3),
('Técnico Superior en Ganadería y Asistencia en Sanidad Animal', 'superior', 3),

-- Artes Gráficas (4)
('Técnico Superior en Diseño y Gestión de la Producción Gráfica', 'superior', 4),
('Técnico Superior en Diseño y Edición de Publicaciones Impresas y Multimedia', 'superior', 4),

-- Comercio y Marketing (5)
('Técnico Superior en Transporte y Logística', 'superior', 5),
('Técnico Superior en Marketing y Publicidad', 'superior', 5),
('Técnico Superior en Gestión de Ventas y Espacios Comerciales', 'superior', 5),
('Técnico Superior en Comercio Internacional', 'superior', 5),

-- Edificación y Obra Civil (6)
('Técnico Superior en Proyectos de Edificación', 'superior', 6),
('Técnico Superior en Proyectos de Obra Civil', 'superior', 6),
('Técnico Superior en Organización y Control de Obras de Construcción', 'superior', 6),

-- Electricidad y Electrónica (7)
('Técnico Superior en Sistemas Electrotécnicos y Automatizados', 'superior', 7),
('Técnico Superior en Sistemas de Telecomunicaciones e Informáticos', 'superior', 7),
('Técnico Superior en Mantenimiento Electrónico', 'superior', 7),
('Técnico Superior en Automatización y Robótica Industrial', 'superior', 7),
('Técnico Superior en Electromedicina Clínica', 'superior', 7),

-- Energía y Agua (8)
('Técnico Superior en Eficiencia Energética y Energía Solar Térmica', 'superior', 8),
('Técnico Superior en Energías Renovables', 'superior', 8),
('Técnico Superior en Gestión del Agua', 'superior', 8),

-- Fabricación Mecánica (9)
('Técnico Superior en Construcciones Metálicas', 'superior', 9),
('Técnico Superior en Diseño en Fabricación Mecánica', 'superior', 9),
('Técnico Superior en Programación de la Producción en Fabricación Mecánica', 'superior', 9),
('Técnico Superior en Programación de la Producción en Moldeo de Metales y Polímeros', 'superior', 9),
('Técnico Superior en Óptica de Anteojería (LOGSE)', 'superior', 9),

-- Hostelería y Turismo (10)
('Técnico Superior en Gestión de Alojamientos Turísticos', 'superior', 10),
('Técnico Superior en Agencias de Viajes y Gestión de Eventos', 'superior', 10),
('Técnico Superior en Guía, Información y Asistencias Turísticas', 'superior', 10),
('Técnico Superior en Dirección de Cocina', 'superior', 10),
('Técnico Superior en Dirección de Servicios en Restauración', 'superior', 10),

-- Imagen Personal (11)
('Técnico Superior en Estética Integral y Bienestar', 'superior', 11),
('Técnico Superior en Estilismo y Dirección de Peluquería', 'superior', 11),
('Técnico Superior en Caracterización y Maquillaje Profesional', 'superior', 11),
('Técnico Superior en Asesoría de Imagen Personal y Corporativa', 'superior', 11),
('Técnico Superior en Termalismo y Bienestar', 'superior', 11),

-- Imagen y Sonido (12)
('Técnico Superior en Realización de Proyectos Audiovisuales y Espectáculos', 'superior', 12),
('Técnico Superior en Sonido para Audiovisuales y Espectáculos', 'superior', 12),
('Técnico Superior en Iluminación, Captación y Tratamiento de Imagen', 'superior', 12),
('Técnico Superior en Producción de Audiovisuales y Espectáculos', 'superior', 12),
('Técnico Superior en Animaciones 3D, Juegos y Entornos Interactivos', 'superior', 12),

-- Industrias Alimentarias (13)
('Técnico Superior en Vitivinicultura', 'superior', 13),
('Técnico Superior en Procesos y Calidad en la Industria Alimentaria', 'superior', 13),

-- Informática y Comunicaciones (14)
('Técnico Superior en Administración de Sistemas Informáticos en Red', 'superior', 14),
('Técnico Superior en Desarrollo de Aplicaciones Multiplataforma', 'superior', 14),
('Técnico Superior en Desarrollo de Aplicaciones Web', 'superior', 14),

-- Instalación y Mantenimiento (15)
('Técnico Superior en Desarrollo de Proyectos de Instalaciones Térmicas y de Fluidos', 'superior', 15),
('Técnico Superior en Mecatrónica Industrial', 'superior', 15),
('Técnico Superior en Mantenimiento de Instalaciones Térmicas y de Fluidos', 'superior', 15),
('Técnico Superior en Prevención de Riesgos Profesionales (LOGSE)', 'superior', 15),

-- Madera, Mueble y Corcho (16)
('Técnico Superior en Diseño y Amueblamiento', 'superior', 16),

-- Marítimo Pesquera (17)
('Técnico Superior en Transporte Marítimo y Pesca de Altura', 'superior', 17),
('Técnico Superior en Acuicultura', 'superior', 17),
('Técnico Superior en Organización del Mantenimiento de Maquinaria de Buques y Embarcaciones', 'superior', 17),

-- Química (18)
('Técnico Superior en Laboratorio de Análisis y de Control de Calidad', 'superior', 18),
('Técnico Superior en Química Industrial', 'superior', 18),
('Técnico Superior en Fabricación de Productos Farmacéuticos, Biotecnológicos y Afines', 'superior', 18),

-- Sanidad (19)
('Técnico Superior en Audiología Protésica', 'superior', 19),
('Técnico Superior en Radioterapia y Dosimetría', 'superior', 19),
('Técnico Superior en Laboratorio Clínico y Biomédico', 'superior', 19),
('Técnico Superior en Imagen para el Diagnóstico y Medicina Nuclear', 'superior', 19),
('Técnico Superior en Higiene Bucodental', 'superior', 19),
('Técnico Superior en Documentación y Administración Sanitarias', 'superior', 19),
('Técnico Superior en Anatomía Patológica y Citodiagnóstico', 'superior', 19),
('Técnico Superior en Prótesis Dentales', 'superior', 19),
('Técnico Superior en Ortoprótesis y Productos de Apoyo', 'superior', 19),
('Técnico Superior en Dietética (LOGSE)', 'superior', 19),

-- Seguridad y Medio Ambiente (20)
('Técnico Superior en Educación y Control Ambiental', 'superior', 20),
('Técnico Superior en Coordinación de Emergencias y Protección Civil', 'superior', 20),
('Técnico Superior en Química y Salud Ambiental', 'superior', 20),

-- Servicios Socioculturales y a la Comunidad (21)
('Técnico Superior en Educación Infantil', 'superior', 21),
('Técnico Superior en Animación Sociocultural y Turística', 'superior', 21),
('Técnico Superior en Promoción de Igualdad de Género', 'superior', 21),
('Técnico Superior en Integración Social', 'superior', 21),
('Técnico Superior en Mediación Comunicativa', 'superior', 21),
('Técnico Superior en Formación para la Movilidad Segura y Sostenible', 'superior', 21),

-- Textil, Confección y Piel (22)
('Técnico Superior en Patronaje y Moda', 'superior', 22),
('Técnico Superior en Vestuario a Medida y de Espectáculos', 'superior', 22),

-- Transporte y Mantenimiento de Vehículos (23)
('Técnico Superior en Automoción', 'superior', 23),
('Técnico Superior en Mantenimiento de Sistemas Electrónicos y Aviónicos en Aeronaves', 'superior', 23);


INSERT INTO ciclo (nombre, nivel, familia_id) VALUES
-- Actividades Físicas y Deportivas (1)
('Técnico en Actividades Ecuestres', 'medio', 1),
('Técnico en Guía en el Medio Natural y de Tiempo Libre', 'medio', 1),

-- Administración y Gestión (2)
('Técnico en Gestión Administrativa', 'medio', 2),

-- Agraria (3)
('Técnico en Producción Agroecológica', 'medio', 3),
('Técnico en Producción Agropecuaria', 'medio', 3),
('Técnico en Jardinería y Floristería', 'medio', 3),
('Técnico en Aprovechamiento y Conservación del Medio Natural', 'medio', 3),

-- Artes Gráficas (4)
('Técnico en Impresión Gráfica', 'medio', 4),
('Técnico en Preimpresión Digital', 'medio', 4),

-- Comercio y Marketing (5)
('Técnico en Actividades Comerciales', 'medio', 5),
('Técnico en Comercialización de Productos Alimentarios', 'medio', 5),

-- Edificación y Obra Civil (6)
('Técnico en Obras de Interior, Decoración y Rehabilitación', 'medio', 6),
('Técnico en Construcción', 'medio', 6),

-- Electricidad y Electrónica (7)
('Técnico en Instalaciones Eléctricas y Automáticas', 'medio', 7),
('Técnico en Instalaciones de Telecomunicaciones', 'medio', 7),

-- Energía y Agua (8)
('Técnico en Redes y Estaciones de Tratamiento de Aguas', 'medio', 8),

-- Fabricación Mecánica (9)
('Técnico en Mecanizado', 'medio', 9),
('Técnico en Soldadura y Calderería', 'medio', 9),
('Técnico en Conformado por Moldeo de Metales y Polímeros', 'medio', 9),

-- Hostelería y Turismo (10)
('Técnico en Servicios en Restauración', 'medio', 10),
('Técnico en Cocina y Gastronomía', 'medio', 10),

-- Imagen Personal (11)
('Técnico en Estética y Belleza', 'medio', 11),
('Técnico en Peluquería y Cosmética Capilar', 'medio', 11),

-- Imagen y Sonido (12)
('Técnico en Vídeo Disc-jockey y Sonido', 'medio', 12),

-- Industrias Alimentarias (13)
('Técnico en Panadería, Repostería y Confitería', 'medio', 13),
('Técnico en Aceites de Oliva y Vinos', 'medio', 13),
('Técnico en Elaboración de Productos Alimenticios', 'medio', 13),

-- Industrias Extractivas (nuevo)
('Técnico en Piedra Natural', 'medio', 13),
('Técnico en Excavaciones y Sondeos', 'medio', 13),

-- Informática y Comunicaciones (14)
('Técnico en Sistemas Microinformáticos y Redes', 'medio', 14),

-- Instalación y Mantenimiento (15)
('Técnico en Instalaciones Frigoríficas y de Climatización', 'medio', 15),
('Técnico en Instalaciones de Producción de Calor', 'medio', 15),
('Técnico en Mantenimiento Electromecánico', 'medio', 15),

-- Madera, Mueble y Corcho (16)
('Técnico en Carpintería y Mueble', 'medio', 16),
('Técnico en Instalación y Amueblamiento', 'medio', 16),

-- Marítimo Pesquera (17)
('Técnico en Cultivos Acuícolas', 'medio', 17),
('Técnico en Navegación y Pesca de Litoral', 'medio', 17),
('Técnico en Mantenimiento y Control de la Maquinaria de Buques y Embarcaciones', 'medio', 17),
('Técnico en Operaciones Subacuáticas e Hiperbáricas', 'medio', 17),

-- Química (18)
('Técnico en Planta Química', 'medio', 18),
('Técnico en Operaciones de Laboratorio', 'medio', 18),

-- Sanidad (19)
('Técnico en Farmacia y Parafarmacia', 'medio', 19),
('Técnico en Emergencias Sanitarias', 'medio', 19),
('Técnico en Cuidados Auxiliares de Enfermería (LOGSE)', 'medio', 19),

-- Seguridad y Medio Ambiente (20)
('Técnico en Emergencias y Protección Civil', 'medio', 20),

-- Servicios Socioculturales y a la Comunidad (21)
('Técnico en Atención a Personas en Situación de Dependencia', 'medio', 21),

-- Textil, Confección y Piel (22)
('Técnico en Confección y Moda', 'medio', 22),
('Técnico en Calzado y Complementos de Moda', 'medio', 22),

-- Transporte y Mantenimiento de Vehículos (23)
('Técnico en Carrocería', 'medio', 23),
('Técnico en Electromecánica de Vehículos Automóviles', 'medio', 23),
('Técnico en Electromecánica de Maquinaria', 'medio', 23),
('Técnico en Conducción de Vehículos de Transporte por Carretera', 'medio', 23),
('Técnico en Mantenimiento de Material Rodante Ferroviario', 'medio', 23),
('Técnico en Montaje de Estructuras e Instalación de Sistemas Aeronáuticos', 'medio', 23);


INSERT INTO ciclo (nombre, nivel, familia_id) VALUES
-- Actividades Físicas y Deportivas (1)
('Técnico Básico en Acceso y Conservación en Instalaciones Deportivas', 'básico', 1),

-- Administración y Gestión (2)
('Técnico Básico en Servicios Administrativos', 'básico', 2),

-- Agraria (3)
('Técnico Básico en Actividades Agropecuarias', 'básico', 3),
('Técnico Básico en Aprovechamientos Forestales', 'básico', 3),
('Técnico Básico en Agro-jardinería y Composiciones Florales', 'básico', 3),

-- Artes Gráficas (4)
('Técnico Básico en Artes Gráficas', 'básico', 4),

-- Comercio y Marketing (5)
('Técnico Básico en Servicios Comerciales', 'básico', 5),

-- Edificación y Obra Civil (6)
('Técnico Básico en Reforma y Mantenimiento de Edificios', 'básico', 6),

-- Electricidad y Electrónica (7)
('Técnico Básico en Instalaciones Electrotécnicas y Mecánica', 'básico', 7),
('Técnico Básico en Electricidad y Electrónica', 'básico', 7),

-- Fabricación Mecánica (9)
('Técnico Básico en Fabricación y Montaje', 'básico', 9),
('Técnico Básico en Fabricación de Elementos Metálicos', 'básico', 9),

-- Hostelería y Turismo (10)
('Técnico Básico en Actividades de Panadería y Pastelería', 'básico', 10),
('Técnico Básico en Alojamiento y Lavandería', 'básico', 10),
('Técnico Básico en Cocina y Restauración', 'básico', 10),

-- Imagen Personal (11)
('Técnico Básico en Peluquería y Estética', 'básico', 11),

-- Industrias Alimentarias (13)
('Técnico Básico en Industrias Alimentarias', 'básico', 13),

-- Informática y Comunicaciones (14)
('Técnico Básico en Informática de Oficina', 'básico', 14),
('Técnico Básico en Informática y Comunicaciones', 'básico', 14),

-- Instalación y Mantenimiento (15)
('Técnico Básico en Mantenimiento de Viviendas', 'básico', 15),

-- Madera, Mueble y Corcho (16)
('Técnico Básico en Carpintería y Mueble', 'básico', 16),

-- Marítimo Pesquera (17)
('Técnico Básico en Actividades Marítimo-Pesqueras', 'básico', 17),
('Técnico Básico en Mantenimiento de Embarcaciones Deportivas y de Recreo', 'básico', 17),

-- Servicios Socioculturales y a la Comunidad (21)
('Técnico Básico en Actividades Domésticas y Limpieza de Edificios', 'básico', 21),

-- Textil, Confección y Piel (22)
('Técnico Básico en Arreglo y Reparación de Artículos Textiles y de Piel', 'básico', 22),
('Técnico Básico en Tapicería y Cortinaje', 'básico', 22),

-- Transporte y Mantenimiento de Vehículos (23)
('Técnico Básico en Mantenimiento de Vehículos', 'básico', 23),

('Técnico Básico en Vidriería y Alfarería', 'básico', 24);


INSERT INTO ciclo (nombre, nivel, familia_id) VALUES
-- Comercio y Marketing (5)
('Máster de Formación Profesional en Posicionamiento en buscadores (SEO/SEM) y comunicación en redes sociales', 'especialización', 5),
('Máster de Formación Profesional en Comercio electrónico', 'especialización', 5),

-- Electricidad y Electrónica (7)
('Máster de Formación Profesional en Ciberseguridad en Entornos de las Tecnologías de Operación', 'especialización', 7),
('Especialista en Instalación y mantenimiento de sistemas conectados a internet (IoT)', 'especialización', 7),
('Especialista en Implementación de redes 5G', 'especialización', 7),

-- Energía y Agua (8)
('Máster de Formación Profesional en Auditoría Energética', 'especialización', 8),

-- Fabricación Mecánica (9)
('Máster de Formación Profesional en Fabricación Aditiva', 'especialización', 9),

-- Hostelería y Turismo (10)
('Especialista en Panadería y Bollería Artesanales', 'especialización', 10),

-- Imagen y Sonido (12)
('Máster de Formación Profesional en Audiodescripción y Subtitulación', 'especialización', 12),

-- Informática y Comunicaciones (14)
('Máster de Formación Profesional en Ciberseguridad en Entornos de las Tecnologías de la Información', 'especialización', 14),
('Máster de Formación Profesional en Desarrollo de videojuegos y realidad virtual', 'especialización', 14),
('Máster de Formación Profesional en Inteligencia Artificial y Big Data', 'especialización', 14),

-- Instalación y Mantenimiento (15)
('Máster de Formación Profesional en Digitalización del Mantenimiento Industrial', 'especialización', 15),
('Máster de Formación Profesional en Fabricación Inteligente', 'especialización', 15),
('Máster de Formación Profesional en Modelado de la información de la construcción (BIM)', 'especialización', 15),

-- Química (18)
('Máster de Formación Profesional en Cultivos Celulares', 'especialización', 18),

-- Transporte y Mantenimiento de Vehículos (23)
('Especialista en Mantenimiento de vehículos híbridos y eléctricos', 'especialización', 23),
('Máster de Formación Profesional en Aeronaves Pilotadas de Forma Remota-Drones', 'especialización', 23),
('Máster de Formación Profesional en Mantenimiento y seguridad en sistemas de vehículos híbridos y eléctricos', 'especialización', 23),
('Máster de Formación Profesional en Mantenimiento avanzado de sistemas de material rodante ferroviario', 'especialización', 23);