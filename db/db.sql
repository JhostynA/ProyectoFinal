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

CREATE TABLE tallas (
    id 				INT AUTO_INCREMENT PRIMARY KEY,
    secuencia_id 	INT,
    talla_s         INT NULL DEFAULT 0,  
    talla_m         INT NULL DEFAULT 0,  
    talla_l         INT NULL DEFAULT 0, 
    talla_xl        INT NULL DEFAULT 0, 
    cantidad 		INT NOT NULL,
    realizadas_s 	INT NOT NULL DEFAULT 0,
	realizadas_m 	INT NOT NULL DEFAULT 0,
	realizadas_l 	INT NOT NULL DEFAULT 0,
	realizadas_xl 	INT NOT NULL DEFAULT 0,
    FOREIGN KEY (secuencia_id) REFERENCES secuencias(id) ON DELETE CASCADE
);


CREATE TABLE kardex (
    id               INT AUTO_INCREMENT PRIMARY KEY,
    talla_id         INT NOT NULL, 
    fecha            DATE NOT NULL,
    cantidad         INT NOT NULL,
    talla ENUM		('S', 'M', 'L', 'XL') NOT NULL,
    FOREIGN KEY (talla_id) REFERENCES tallas(id) ON DELETE CASCADE
);

CREATE TABLE clientes


CREATE TABLE pdf_files (
    id INT AUTO_INCREMENT PRIMARY KEY,
    action_id INT NOT NULL,
    file_name VARCHAR(255) NOT NULL,
    file_path VARCHAR(255) NOT NULL,
    FOREIGN KEY (action_id) REFERENCES actions(id)
);


CREATE TABLE debug_trigger_log (
    id INT AUTO_INCREMENT PRIMARY KEY,
    idop INT,
    total_prendas_realizadas INT,
    total_prendas_a_realizar INT,
    porcentaje_calculado FLOAT,
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

DELIMITER //
CREATE TRIGGER actualizar_porcentaje_trigger
AFTER UPDATE ON secuencias
FOR EACH ROW
BEGIN
    DECLARE total_prendas_realizadas INT DEFAULT 0;
    DECLARE total_prendas_a_realizar INT DEFAULT 0;
    DECLARE porcentaje_avance FLOAT DEFAULT 0;

    SELECT SUM(prendasArealizar - prendasFaltantes)
    INTO total_prendas_realizadas
    FROM secuencias
    WHERE idop = NEW.idop;

    SELECT cantidad_prendas
    INTO total_prendas_a_realizar
    FROM actions
    WHERE id = NEW.idop;

    INSERT INTO debug_trigger_log (idop, total_prendas_realizadas, total_prendas_a_realizar)
    VALUES (NEW.idop, total_prendas_realizadas, total_prendas_a_realizar);

    IF total_prendas_a_realizar > 0 THEN
      SET porcentaje_avance = FLOOR((total_prendas_realizadas * 100) / total_prendas_a_realizar);

        UPDATE debug_trigger_log
        SET porcentaje_calculado = porcentaje_avance
        WHERE id = LAST_INSERT_ID();

        UPDATE actions
        SET porcentaje = porcentaje_avance
        WHERE id = NEW.idop;
    END IF;
END;
//
DELIMITER ;

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
    
    SELECT @@last_insert_id AS idproductividad; 
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

CREATE TABLE pagos (
    idpago              INT PRIMARY KEY AUTO_INCREMENT,
    idpersona           INT,
    idoperacion         INT,
    prendas_realizadas  INT,
    precio_operacion    DECIMAL(6,3),
    total_pago          DECIMAL(10,2),
    fecha_pago          DATE NOT NULL,
    FOREIGN KEY (idpersona) REFERENCES personas(idpersona),
    FOREIGN KEY (idoperacion) REFERENCES operaciones(idoperacion)
);


DROP PROCEDURE IF EXISTS registrarPago;
CREATE PROCEDURE registrarPago(
    IN _idpersona INT,
    IN _idoperacion INT,
    IN _prendas_realizadas INT
)
BEGIN
    DECLARE _precio_operacion DECIMAL(6,3);
    DECLARE _total_pago DECIMAL(10,2);
    DECLARE _nombre_trabajador VARCHAR(100);
    DECLARE _nombre_operacion VARCHAR(50);

    -- Validar que el idpersona y idoperacion existen
    IF NOT EXISTS (SELECT 1 FROM personas WHERE idpersona = _idpersona) THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Persona no encontrada';
    END IF;

    IF NOT EXISTS (SELECT 1 FROM operaciones WHERE idoperacion = _idoperacion) THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Operación no encontrada';
    END IF;

    -- Obtener el precio de la operación
    SELECT precio INTO _precio_operacion
    FROM operaciones
    WHERE idoperacion = _idoperacion;

    -- Calcular el total del pago
    SET _total_pago = _prendas_realizadas * _precio_operacion;

    -- Obtener el nombre del trabajador y la operación
    SELECT CONCAT(nombres, ' ', apepaterno, ' ', apematerno) INTO _nombre_trabajador
    FROM personas
    WHERE idpersona = _idpersona;

    SELECT operacion INTO _nombre_operacion
    FROM operaciones
    WHERE idoperacion = _idoperacion;

    -- Insertar el pago
    INSERT INTO pagos (idpersona, idoperacion, prendas_realizadas, precio_operacion, total_pago, fecha_pago)
    VALUES (_idpersona, _idoperacion, _prendas_realizadas, _precio_operacion, _total_pago, CURDATE());

    -- Devolver el nombre del trabajador y la operación
    SELECT _nombre_trabajador AS nombre_trabajador, _nombre_operacion AS nombre_operacion;
END $$
DELIMITER ;

CREATE PROCEDURE buscarPersonas(IN searchTerm VARCHAR(255))
BEGIN
    SELECT idpersona, nombres, apepaterno, apematerno
    FROM personas
    WHERE nombres LIKE CONCAT('%', searchTerm, '%')
       OR apepaterno LIKE CONCAT('%', searchTerm, '%')
       OR apematerno LIKE CONCAT('%', searchTerm, '%')
END $$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE buscarOperaciones(IN searchTerm VARCHAR(255))
BEGIN
    SELECT idoperacion, operacion, precio
    FROM operaciones
    WHERE operacion LIKE CONCAT('%', searchTerm, '%');
END $$
DELIMITER ;

CREATE TABLE apoyos (
    idapoyo     INT AUTO_INCREMENT PRIMARY KEY,
    ape_paterno VARCHAR(50) NOT NULL,
    ape_materno VARCHAR(50) NOT NULL,
    nombres     VARCHAR(50) NOT NULL,
    documento   VARCHAR(15) NOT NULL,
    create_at   DATETIME DEFAULT NOW(),
    deleted_at  DATETIME DEFAULT NULL,
    eliminado   TINYINT DEFAULT 0
);

DROP PROCEDURE IF EXISTS spu_registrar_apoyo;
DELIMITER //
CREATE PROCEDURE spu_registrar_apoyo 
(
    IN ape_paterno VARCHAR(50),
    IN ape_materno VARCHAR(50),
    IN nombres     VARCHAR(50),
    IN documento   VARCHAR(15)
)
BEGIN
    DECLARE existing_id INT;

    -- Verificar si el registro ya existe y está eliminado
    SELECT idapoyo INTO existing_id
    FROM apoyos
    WHERE ape_paterno = ape_paterno 
      AND ape_materno = ape_materno 
      AND nombres = nombres 
      AND documento = documento 
      AND eliminado = 1; -- Verificar estado eliminado

    IF existing_id IS NOT NULL THEN
        -- Reincorporar el registro
        UPDATE apoyos
        SET eliminado = 0, -- Cambiar estado a activo
            deleted_at = NULL -- Reiniciar fecha de eliminación
        WHERE idapoyo = existing_id;
        
        SELECT 'Reincorporado' AS mensaje;
    ELSE
        -- Insertar nuevo registro
        INSERT INTO apoyos (ape_paterno, ape_materno, nombres, documento, create_at)
        VALUES (ape_paterno, ape_materno, nombres, documento, NOW());
        
        SELECT 'Registrado' AS mensaje;
    END IF;
END //
DELIMITER ;

-- Procedimiento para listar los apoyos (sin incluir los eliminados)
DROP PROCEDURE IF EXISTS spu_listar_apoyos;
DELIMITER //
CREATE PROCEDURE spu_listar_apoyos()
BEGIN
    SELECT 
        ape_paterno AS "Apellido Paterno",
        ape_materno AS "Apellido Materno",
        nombres AS "Nombres",
        documento AS "Documento",
        create_at AS "Fecha de Creación"
    FROM apoyos
    WHERE eliminado = 0;  -- Solo muestra los registros no eliminados
END //
DELIMITER;

-- Procedimiento para actualizar un apoyo
DROP PROCEDURE IF EXISTS spu_actualizar_apoyo;
DELIMITER //
CREATE PROCEDURE spu_actualizar_apoyo 
(
    IN idapoyo     INT,
    IN ape_paterno VARCHAR(50),
    IN ape_materno VARCHAR(50),
    IN nombres     VARCHAR(50),
    IN documento   VARCHAR(15)
)
BEGIN
    UPDATE apoyos
    SET ape_paterno = ape_paterno,
        ape_materno = ape_materno,
        nombres     = nombres,
        documento   = documento,
        create_at   = NOW()
    WHERE idapayo = idapoyo AND eliminado = 0;  -- Solo actualiza si no está eliminado
    
    -- Mostrar los datos actualizados
    SELECT 
        ape_paterno AS "Apellido Paterno",
        ape_materno AS "Apellido Materno",
        nombres AS "Nombres",
        documento AS "Documento",
        create_at AS "Fecha de Actualización"
    FROM apoyos
    WHERE idapoyo = idapoyo;
END //
DELIMITER;

-- Procedimiento para eliminar un apoyo (lógica)
DROP PROCEDURE IF EXISTS spu_eliminar_apoyo;
DELIMITER //
CREATE PROCEDURE spu_eliminar_apoyo 
(
    IN idapoyo INT
)
BEGIN
    UPDATE apoyos
    SET 
        eliminado = 1,         -- Cambia el estado a eliminado
        deleted_at = NOW()     -- Establece la fecha de eliminación
    WHERE idapoyo = idapoyo;

    -- Mostrar los datos del apoyo después de la eliminación lógica
    SELECT 
        ape_paterno AS "Apellido Paterno",
        ape_materno AS "Apellido Materno",
        nombres AS "Nombres",
        documento AS "Documento",
        create_at AS "Fecha de Creación",
        deleted_at AS "Fecha de Eliminación" -- Este campo mostrará cuándo fue eliminado
    FROM apoyos
    WHERE idapoyo = idapoyo;
END //
DELIMITER //