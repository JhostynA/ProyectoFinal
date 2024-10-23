CREATE DATABASE linoFino;
USE linoFino;

CREATE TABLE operaciones
(
	idoperacion		INT PRIMARY KEY AUTO_INCREMENT,
    operacion		VARCHAR(50),
    precio			DECIMAL(6,3),
    CONSTRAINT uk_operacion_ope UNIQUE (operacion)
);

INSERT INTO operaciones (operacion,precio) VALUES
	('Cerrado de cuello',0.03),
    ('Bordeado de Cuello',0.07),
    ('Basta manga corta',0.12),
    ('Union hombro',0.07),
    ('Pegado de cuello',0.12),
    ('Recubierto de cuello',0.08),
    ('Pegado de tapete',0.12),
    ('Pegado de manga corta',0.12),
    ('Cerrado de costado',0.13),
    ('Atraque de puño',0.09),
    ('Basta de faldon',0.14),
    ('Parche',0.1),
    ('Limpieza',0.1),
    ('Inspeccion',0.12),
    ('Etiqueta',0.03),
    ('Costos fijos',0.336);

CREATE TABLE productividad (
    idproductividad 	INT PRIMARY KEY AUTO_INCREMENT,
    nombretarea 		VARCHAR(255) NOT NULL,  
    fechainicio 		DATE NOT NULL,           
    fechafinal			DATE NOT NULL,           
    totalprendas 		INT NOT NULL,           
    fecharegistro 		DATETIME NOT NULL DEFAULT NOW()  
);

CREATE TABLE personas (
    idpersona 		INT PRIMARY KEY AUTO_INCREMENT,
    apepaterno 		VARCHAR(20) 	NOT NULL,
    apematerno 		VARCHAR(20) 	NOT NULL,
    nombres 		VARCHAR(50) 	NOT NULL,
    fecharegistro 	DATETIME 		NOT NULL DEFAULT NOW(),
    fechabaja 		DATETIME 		NULL
);

INSERT INTO personas (apepaterno, apematerno, nombres)
VALUES 
('Aburto', 'Acevedo', 'Jhostyn'),
('Quispe', 'Huamán', 'Juan Carlos'),
('Gonzales', 'Cahuana', 'María Elena'),
('Soto', 'Yupanqui', 'José Antonio'),
('Rojas', 'Chávez', 'Ana Lucía'),
('Flores', 'Pachacutec', 'Luis Alberto');

CREATE TABLE colaboradores (
    idcolaboradores INT PRIMARY KEY AUTO_INCREMENT,
    idpersona 		INT,
    nomusuario 		VARCHAR(50) NOT NULL,
    passusuario 	VARCHAR(60) NOT NULL,
    fecharegistro 	DATETIME 	NOT NULL DEFAULT NOW(),
    fechabaja 		DATETIME 	NULL,
    CONSTRAINT uk_nomusuario_per UNIQUE (nomusuario),
    FOREIGN KEY (idpersona) REFERENCES personas(idpersona)
);



CREATE TABLE actions (
    id 					INT AUTO_INCREMENT PRIMARY KEY,
    nombre 				VARCHAR(255) NOT NULL,
    fecha_inicio 		DATE NOT NULL,
    fecha_entrega 		DATE NOT NULL,
    talla_s 			INT NOT NULL DEFAULT 0,
	talla_m 			INT NOT NULL DEFAULT 0,
	talla_l 			INT NOT NULL DEFAULT 0,
	talla_xl 			INT NOT NULL DEFAULT 0,
    cantidad_prendas 	INT NOT NULL,
    porcentaje 			FLOAT NOT NULL DEFAULT 0,
    created_at 			TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


CREATE TABLE secuencias (
    id                     INT AUTO_INCREMENT PRIMARY KEY,
    idop                   INT,
    numSecuencia           INT NOT NULL,
    fechaInicio            DATE NOT NULL,
    fechaFinal             DATE NOT NULL,
    prendasArealizar       INT NOT NULL,
    prendasFaltantes       INT NOT NULL,
    talla_s                INT NULL DEFAULT 0,  
    talla_m                INT NULL DEFAULT 0,  
    talla_l                INT NULL DEFAULT 0, 
    talla_xl               INT NULL DEFAULT 0, 
    FOREIGN KEY (idop) REFERENCES actions(id)
);                          

SELECT * FROM secuencias;

CREATE TABLE tallas (
    id 				INT AUTO_INCREMENT PRIMARY KEY,
    secuencia_id 	INT,
    talla 			ENUM('S', 'M', 'L', 'XL') NOT NULL,
    cantidad 		INT NOT NULL,
    realizadas 		INT NOT NULL DEFAULT 0,
    FOREIGN KEY (secuencia_id) REFERENCES secuencias(id) ON DELETE CASCADE
);




DELIMITER //
CREATE PROCEDURE actualizarPorcentaje
(
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
END //



DELIMITER $$
CREATE PROCEDURE spu_colaboradores_login(IN _nomusuario VARCHAR(50))
BEGIN
	SELECT
		COL.idcolaboradores,
        PER.apepaterno, PER.nombres,
        COL.nomusuario, COL.passusuario
		FROM colaboradores COL
        INNER JOIN personas PER ON PER.idpersona = COL.idcolaboradores
        WHERE COL.nomusuario = _nomusuario AND COL.fechabaja IS NULL;
END $$
DELIMITER $$

CREATE PROCEDURE spu_colaboradores_registrar
(
	IN _idpersona		INT,
    IN _nomusuario  	VARCHAR(50),
    IN _passusuario 	VARCHAR(60)
)
BEGIN
	INSERT INTO colaboradores (idpersona, nomusuario, passusuario) VALUES
		(_idpersona, _nomusuario, _passusuario);
	SELECT @@last_insert_id 'idcolaboradores';
END $$
DELIMITER $$

CALL spu_colaboradores_registrar(1,'JhostynA','$2y$10$shfcJOApvH8mxR/vm4PupOQ9b5v9vGBXMQnfwDKeJhbOuvWurw/qi');

DELIMITER $$
CREATE PROCEDURE spu_productividad_registrar(
    IN _nombre_tarea VARCHAR(255),
    IN _fecha_inicio DATE,
    IN _fecha_final DATE,
    IN _total_prendas INT
)
BEGIN
    INSERT INTO productividad (nombretarea, fechainicio, fechafinal, totalprendas)
    VALUES (_nombre_tarea, _fecha_inicio, _fecha_final, _total_prendas);
    
    SELECT @@last_insert_id AS idproductividad;  -- Retorna el ID del registro insertado
END $$
DELIMITER ;


DELIMITER $$
CREATE PROCEDURE VerificarOperacion
(
	IN _operacion	VARCHAR(50), 	
    IN _precio		DECIMAL(6,3)
    )
BEGIN
  SELECT COUNT(*) AS existe
  FROM operaciones
  WHERE operacion = _operacion AND precio = _precio;
END $$

DELIMITER $$
CREATE PROCEDURE VerificarPersona
(
	IN _apepaterno		VARCHAR(20), 	
    IN _apematerno		VARCHAR(20),
    IN _nombres			VARCHAR(50)
    )
BEGIN
  SELECT COUNT(*) AS existe
  FROM personas
  WHERE apepaterno = _apepaterno AND apematerno = _apematerno AND nombres = _nombres;
END $$