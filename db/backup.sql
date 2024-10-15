/*
SQLyog Ultimate v12.5.1 (64 bit)
MySQL - 10.4.32-MariaDB : Database - linofino
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`linofino` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */;

USE `linofino`;

/*Table structure for table `actions` */

DROP TABLE IF EXISTS `actions`;

CREATE TABLE `actions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) NOT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_entrega` date NOT NULL,
  `cantidad_prendas` int(11) NOT NULL,
  `porcentaje` float NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `actions` */

insert  into `actions`(`id`,`nombre`,`fecha_inicio`,`fecha_entrega`,`cantidad_prendas`,`porcentaje`,`created_at`) values 
(1,'357825','2024-10-12','2024-10-24',200,0,'2024-10-11 13:01:23');

/*Table structure for table `colaboradores` */

DROP TABLE IF EXISTS `colaboradores`;

CREATE TABLE `colaboradores` (
  `idcolaboradores` int(11) NOT NULL AUTO_INCREMENT,
  `idpersona` int(11) DEFAULT NULL,
  `nomusuario` varchar(50) NOT NULL,
  `passusuario` varchar(60) NOT NULL,
  `fecharegistro` datetime NOT NULL DEFAULT current_timestamp(),
  `fechabaja` datetime DEFAULT NULL,
  PRIMARY KEY (`idcolaboradores`),
  UNIQUE KEY `uk_nomusuario_per` (`nomusuario`),
  KEY `idpersona` (`idpersona`),
  CONSTRAINT `colaboradores_ibfk_1` FOREIGN KEY (`idpersona`) REFERENCES `personas` (`idpersona`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `colaboradores` */

insert  into `colaboradores`(`idcolaboradores`,`idpersona`,`nomusuario`,`passusuario`,`fecharegistro`,`fechabaja`) values 
(1,1,'JhostynA','$2y$10$shfcJOApvH8mxR/vm4PupOQ9b5v9vGBXMQnfwDKeJhbOuvWurw/qi','2024-10-11 12:54:45',NULL);

/*Table structure for table `operaciones` */

DROP TABLE IF EXISTS `operaciones`;

CREATE TABLE `operaciones` (
  `idoperacion` int(11) NOT NULL AUTO_INCREMENT,
  `operacion` varchar(50) DEFAULT NULL,
  `precio` decimal(6,3) DEFAULT NULL,
  PRIMARY KEY (`idoperacion`),
  UNIQUE KEY `uk_operacion_ope` (`operacion`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `operaciones` */

insert  into `operaciones`(`idoperacion`,`operacion`,`precio`) values 
(1,'Cerrado de cuello',0.030),
(2,'Bordeado de Cuello',0.070),
(3,'Basta manga corta',0.120),
(4,'Union hombro',0.070),
(5,'Pegado de cuello',0.120),
(6,'Recubierto de cuello',0.080),
(7,'Pegado de tapete',0.120),
(8,'Pegado de manga corta',0.120),
(9,'Cerrado de costado',0.130),
(10,'Atraque de puño',0.090),
(11,'Basta de faldon',0.140),
(12,'Parche',0.100),
(13,'Limpieza',0.100),
(14,'Inspeccion',0.120),
(15,'Etiqueta',0.030),
(16,'Costos fijos',0.336);

/*Table structure for table `personas` */

DROP TABLE IF EXISTS `personas`;

CREATE TABLE `personas` (
  `idpersona` int(11) NOT NULL AUTO_INCREMENT,
  `apepaterno` varchar(20) NOT NULL,
  `apematerno` varchar(20) NOT NULL,
  `nombres` varchar(50) NOT NULL,
  `fecharegistro` datetime NOT NULL DEFAULT current_timestamp(),
  `fechabaja` datetime DEFAULT NULL,
  PRIMARY KEY (`idpersona`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `personas` */

insert  into `personas`(`idpersona`,`apepaterno`,`apematerno`,`nombres`,`fecharegistro`,`fechabaja`) values 
(1,'Aburto','Acevedo','Jhostyn','2024-10-11 12:54:44',NULL);

/*Table structure for table `productividad` */

DROP TABLE IF EXISTS `productividad`;

CREATE TABLE `productividad` (
  `idproductividad` int(11) NOT NULL AUTO_INCREMENT,
  `nombretarea` varchar(255) NOT NULL,
  `fechainicio` date NOT NULL,
  `fechafinal` date NOT NULL,
  `totalprendas` int(11) NOT NULL,
  `fecharegistro` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`idproductividad`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `productividad` */

/*Table structure for table `secuencias` */

DROP TABLE IF EXISTS `secuencias`;

CREATE TABLE `secuencias` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idop` int(11) DEFAULT NULL,
  `numSecuencia` int(11) NOT NULL,
  `fechaInicio` date NOT NULL,
  `fechaFinal` date NOT NULL,
  `prendasArealizar` int(11) NOT NULL,
  `prendasFaltantes` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idop` (`idop`),
  CONSTRAINT `secuencias_ibfk_1` FOREIGN KEY (`idop`) REFERENCES `actions` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `secuencias` */

insert  into `secuencias`(`id`,`idop`,`numSecuencia`,`fechaInicio`,`fechaFinal`,`prendasArealizar`,`prendasFaltantes`) values 
(1,1,1,'2024-10-16','2024-10-18',50,50);

/*Table structure for table `tallas` */

DROP TABLE IF EXISTS `tallas`;

CREATE TABLE `tallas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `secuencia_id` int(11) DEFAULT NULL,
  `talla` enum('S','M','L','XL') NOT NULL,
  `cantidad` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `secuencia_id` (`secuencia_id`),
  CONSTRAINT `tallas_ibfk_1` FOREIGN KEY (`secuencia_id`) REFERENCES `secuencias` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `tallas` */

insert  into `tallas`(`id`,`secuencia_id`,`talla`,`cantidad`) values 
(1,1,'S',20),
(2,1,'L',30);

/* Procedure structure for procedure `actualizarPorcentaje` */

/*!50003 DROP PROCEDURE IF EXISTS  `actualizarPorcentaje` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `actualizarPorcentaje`(
	IN action_id INT, 
    IN nuevo_porcentaje INT
)
BEGIN
    IF nuevo_porcentaje BETWEEN 0 AND 100 THEN
        UPDATE actions
        SET porcentaje = nuevo_porcentaje
        WHERE id = action_id;
    ELSE
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'El porcentaje debe estar entre 0 y 100';
    END IF;
END */$$
DELIMITER ;

/* Procedure structure for procedure `spu_colaboradores_login` */

/*!50003 DROP PROCEDURE IF EXISTS  `spu_colaboradores_login` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `spu_colaboradores_login`(IN _nomusuario VARCHAR(50))
BEGIN
	SELECT
		COL.idcolaboradores,
        PER.apepaterno, PER.nombres,
        COL.nomusuario, COL.passusuario
		FROM colaboradores COL
        INNER JOIN personas PER ON PER.idpersona = COL.idcolaboradores
        WHERE COL.nomusuario = _nomusuario AND COL.fechabaja IS NULL;
END */$$
DELIMITER ;

/* Procedure structure for procedure `spu_colaboradores_registrar` */

/*!50003 DROP PROCEDURE IF EXISTS  `spu_colaboradores_registrar` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `spu_colaboradores_registrar`(
	IN _idpersona		INT,
    IN _nomusuario  	VARCHAR(50),
    IN _passusuario 	VARCHAR(60)
)
BEGIN
	INSERT INTO colaboradores (idpersona, nomusuario, passusuario) VALUES
		(_idpersona, _nomusuario, _passusuario);
	SELECT @@last_insert_id 'idcolaboradores';
END */$$
DELIMITER ;

/* Procedure structure for procedure `spu_productividad_registrar` */

/*!50003 DROP PROCEDURE IF EXISTS  `spu_productividad_registrar` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `spu_productividad_registrar`(
    IN _nombre_tarea VARCHAR(255),
    IN _fecha_inicio DATE,
    IN _fecha_final DATE,
    IN _total_prendas INT
)
BEGIN
    INSERT INTO productividad (nombretarea, fechainicio, fechafinal, totalprendas)
    VALUES (_nombre_tarea, _fecha_inicio, _fecha_final, _total_prendas);
    
    SELECT @@last_insert_id AS idproductividad;  -- Retorna el ID del registro insertado
END */$$
DELIMITER ;

/* Procedure structure for procedure `VerificarOperacion` */

/*!50003 DROP PROCEDURE IF EXISTS  `VerificarOperacion` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `VerificarOperacion`(
	IN _operacion	VARCHAR(50), 	
    IN _precio		DECIMAL(6,3)
    )
BEGIN
  SELECT COUNT(*) AS existe
  FROM operaciones
  WHERE operacion = _operacion AND precio = _precio;
END */$$
DELIMITER ;

/* Procedure structure for procedure `VerificarPersona` */

/*!50003 DROP PROCEDURE IF EXISTS  `VerificarPersona` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `VerificarPersona`(
	IN _apepaterno		VARCHAR(20), 	
    IN _apematerno		VARCHAR(20),
    IN _nombres			VARCHAR(50)
    )
BEGIN
  SELECT COUNT(*) AS existe
  FROM personas
  WHERE apepaterno = _apepaterno AND apematerno = _apematerno AND nombres = _nombres;
END */$$
DELIMITER ;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
