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

DROP TABLE IF EXISTS pagos;
CREATE TABLE pagos (
    idpago              INT AUTO_INCREMENT PRIMARY KEY,
    idpersona           INT,
    idoperacion         INT,
    idop                INT,
    idsecuencia         INT,
    prendas_realizadas  INT,
    precio              DECIMAL(10,2),
    total_pago          DECIMAL(10,2),
    create_at           DATETIME NOT NULL DEFAULT NOW(),
    FOREIGN KEY (idpersona) REFERENCES personas(idpersona),
    FOREIGN KEY (idoperacion) REFERENCES operaciones(idoperacion),
    FOREIGN KEY (idop) REFERENCES actions(id),
    FOREIGN KEY (idsecuencia) REFERENCES secuencias(id)
);

DELIMITER $$
DROP PROCEDURE IF EXISTS spu_registrar_pago;
CREATE PROCEDURE spu_registrar_pago(
    IN _idpersona INT,
    IN _idoperacion INT,
    IN _idop INT,
    IN _idsecuencia INT,
    IN _prendas_realizadas INT
)
BEGIN
    DECLARE _precio DECIMAL(10,2);
    DECLARE _total_pago DECIMAL(10,2);
    DECLARE _nombre_persona VARCHAR(100);
    DECLARE _nombre_operacion VARCHAR(50);
    DECLARE _nombre_secuencia VARCHAR(50);
    DECLARE _existe_secuencia INT;

    -- Verificar que la persona exista y obtener su nombre
    SELECT CONCAT(nombres, ' ', apepaterno, ' ', apematerno) INTO _nombre_persona
    FROM personas
    WHERE idpersona = _idpersona;

    IF _nombre_persona IS NULL THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Persona no encontrada';
    END IF;

    -- Verificar que la operación exista y obtener el precio y nombre
    SELECT precio, operacion INTO _precio, _nombre_operacion
    FROM operaciones
    WHERE idoperacion = _idoperacion;

    IF _precio IS NULL THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Operación no encontrada';
    END IF;

    -- Verificar que la orden de producción exista
    IF NOT EXISTS (SELECT 1 FROM actions WHERE id = _idop) THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Orden de producción no encontrada';
    END IF;

    -- Verificar que la secuencia pertenece a la orden de producción seleccionada
    SELECT COUNT(*), numSecuencia INTO _existe_secuencia, _nombre_secuencia
    FROM secuencias
    WHERE id = _idsecuencia AND idop = _idop;

    IF _existe_secuencia = 0 THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'La secuencia no pertenece a la orden de producción seleccionada';
    END IF;

    -- Calcular el total del pago
    SET _total_pago = _prendas_realizadas * _precio;

    -- Insertar el pago en la tabla
    INSERT INTO pagos (idpersona, idoperacion, idop, idsecuencia, prendas_realizadas, precio, total_pago)
    VALUES (_idpersona, _idoperacion, _idop, _idsecuencia, _prendas_realizadas, _precio, _total_pago);
    
    -- Devolver los datos relevantes
    SELECT 
        _nombre_persona AS nombre_persona,
        _nombre_operacion AS nombre_operacion,
        _nombre_secuencia AS nombre_secuencia,
        _prendas_realizadas AS prendas_realizadas,
        _precio AS precio,
        _total_pago AS total_pago;
END $$
DELIMITER ;

CALL spu_registrar_pago(1, 5, 1, 1, 5000);

SELECT * from secuencias;
DELIMITER $$
DROP PROCEDURE IF EXISTS spu_listar_pagos;
CREATE PROCEDURE spu_listar_pagos()
BEGIN
    SELECT 
        P.idpago,
        CONCAT(PE.nombres, ' ', PE.apepaterno, ' ', PE.apematerno) AS nombre_persona,
        O.operacion AS nombre_operacion,
        A.nombre AS nombre_op,
        S.numSecuencia AS nombre_secuencia,
        P.prendas_realizadas,
        P.total_pago,  -- Se muestra el total del pago
        P.create_at
    FROM 
        pagos P
    JOIN 
        personas PE ON P.idpersona = PE.idpersona
    JOIN 
        operaciones O ON P.idoperacion = O.idoperacion
    JOIN 
        actions A ON P.idop = A.id
    JOIN 
        secuencias S ON P.idsecuencia = S.id;
END $$
DELIMITER ;

CALL spu_listar_pagos;
SELECT * FROM pagos;
DELIMITER $$
DROP PROCEDURE IF EXISTS spu_actualizar_pago;
CREATE PROCEDURE spu_actualizar_pago(
    IN _idpago INT,
    IN _idpersona INT,
    IN _idoperacion INT,
    IN _idop INT,
    IN _idsecuencia INT,
    IN _prendas_realizadas INT
)
BEGIN
    DECLARE _precio DECIMAL(10,2);
    DECLARE _total_pago DECIMAL(10,2);
    
    -- Verificar que el pago existe
    IF NOT EXISTS (SELECT 1 FROM pagos WHERE idpago = _idpago) THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Pago no encontrado';
    END IF;

    -- Verificar que la persona existe
    SELECT COUNT(*) INTO @exist_persona
    FROM personas
    WHERE idpersona = _idpersona;

    IF @exist_persona = 0 THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Persona no encontrada';
    END IF;

    -- Verificar que la operación existe y obtener el precio
    SELECT precio INTO _precio
    FROM operaciones
    WHERE idoperacion = _idoperacion;

    IF _precio IS NULL THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Operación no encontrada';
    END IF;

    -- Verificar que la orden de producción existe
    IF NOT EXISTS (SELECT 1 FROM actions WHERE id = _idop) THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Orden de producción no encontrada';
    END IF;

    -- Verificar que la secuencia pertenece a la orden de producción
    IF NOT EXISTS (SELECT 1 FROM secuencias WHERE id = _idsecuencia AND idop = _idop) THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'La secuencia no pertenece a la orden de producción seleccionada';
    END IF;

    -- Calcular el total del pago
    SET _total_pago = _prendas_realizadas * _precio;

    -- Actualizar el registro de pago
    UPDATE pagos
    SET 
        idpersona = _idpersona,
        idoperacion = _idoperacion,
        idop = _idop,
        idsecuencia = _idsecuencia,
        prendas_realizadas = _prendas_realizadas,
        precio = _precio,
        total_pago = _total_pago
    WHERE idpago = _idpago;

    -- Devolver los datos actualizados
    SELECT 
        P.idpago,
        CONCAT(PE.nombres, ' ', PE.apepaterno, ' ', PE.apematerno) AS nombre_persona,
        O.operacion AS nombre_operacion,
        A.nombre AS nombre_op,
        S.numSecuencia AS nombre_secuencia,
        P.prendas_realizadas,
        P.total_pago,
        P.create_at
    FROM pagos P
    JOIN personas PE ON P.idpersona = PE.idpersona
    JOIN operaciones O ON P.idoperacion = O.idoperacion
    JOIN actions A ON P.idop = A.id
    JOIN secuencias S ON P.idsecuencia = S.id
    WHERE P.idpago = _idpago;
END $$
DELIMITER ;

CALL spu_actualizar_pago(1, 6, 3, 1, 1, 300);


DELIMITER $$
DROP PROCEDURE IF EXISTS spu_eliminar_pago;
CREATE PROCEDURE spu_eliminar_pago(
    IN _idpago INT
)
BEGIN
    DECLARE _nombre_persona VARCHAR(100);
    DECLARE _nombre_operacion VARCHAR(50);
    DECLARE _nombre_secuencia VARCHAR(50);
    DECLARE _prendas_realizadas INT;
    DECLARE _total_pago DECIMAL(10,2);
    
    -- Verificar que el pago existe y obtener los datos para devolver
    SELECT 
        CONCAT(PE.nombres, ' ', PE.apepaterno, ' ', PE.apematerno) AS nombre_persona,
        O.operacion AS nombre_operacion,
        S.numSecuencia AS nombre_secuencia,
        P.prendas_realizadas,
        P.total_pago
    INTO 
        _nombre_persona, _nombre_operacion, _nombre_secuencia, _prendas_realizadas, _total_pago
    FROM pagos P
    JOIN personas PE ON P.idpersona = PE.idpersona
    JOIN operaciones O ON P.idoperacion = O.idoperacion
    JOIN secuencias S ON P.idsecuencia = S.id
    WHERE P.idpago = _idpago;

    -- Verificar si se encontraron datos
    IF _nombre_persona IS NULL THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Pago no encontrado';
    END IF;

    -- Eliminar el registro de pago
    DELETE FROM pagos WHERE idpago = _idpago;

    -- Devolver los datos eliminados
    SELECT 
        _nombre_persona AS nombre_persona,
        _nombre_operacion AS nombre_operacion,
        _nombre_secuencia AS nombre_secuencia,
        _prendas_realizadas,
        _total_pago,
        'Pago eliminado correctamente' AS mensaje;
END $$
DELIMITER ;

CALL spu_eliminar_pago(1);

DELIMITER $$
DROP PROCEDURE IF EXISTS spu_buscar_personas;
CREATE PROCEDURE spu_buscar_personas(IN _query VARCHAR(50))
BEGIN
    SELECT * 
    FROM personas 
    WHERE CONCAT(nombres, ' ', apepaterno, ' ', apematerno) LIKE CONCAT('%', _query, '%');
END $$
DELIMITER ;

CALL spu_buscar_personas ('q');

DELIMITER $$
DROP PROCEDURE IF EXISTS spu_listar_personas;
CREATE PROCEDURE spu_listar_personas(IN _query VARCHAR(50))
BEGIN
    -- Listar todas las personas cuyo nombre o apellidos coincidan con el parámetro de búsqueda
    SELECT idpersona, nombres, apematerno, apepaterno
    FROM personas
    WHERE CONCAT(nombres, ' ', apepaterno, ' ', apematerno) LIKE CONCAT('%', _query, '%')
    ORDER BY nombres, apepaterno, apematerno;  -- Opcional: Ordenar por nombre y apellidos
END $$
DELIMITER ;

CALL spu_listar_personas ('q');

DELIMITER $$
DROP PROCEDURE IF EXISTS spu_listar_operaciones;
CREATE PROCEDURE spu_listar_operaciones(IN _query VARCHAR(50))
BEGIN
    -- Listar todas las operaciones cuyo nombre coincida con el parámetro de búsqueda
    SELECT idoperacion, operacion, precio
    FROM operaciones
    WHERE operacion LIKE CONCAT('%', _query, '%')
    ORDER BY operacion;  -- Opcional: Ordenar por nombre de operación
END $$
DELIMITER ;

CALL spu_listar_operaciones('p');

CALL spu_listar_op(1);

DELIMITER $$
DROP PROCEDURE IF EXISTS spu_buscar_op;
CREATE PROCEDURE spu_buscar_op(IN _query VARCHAR(255))
BEGIN
    SELECT 
    id, nombre 
    FROM actions 
    WHERE nombre LIKE CONCAT('%', _query, '%')
    ORDER BY nombre;
END;
DELIMITER ;

CALL spu_buscar_op(1);

DELIMITER $$
DROP PROCEDURE IF EXISTS spu_buscar_secuencias_por_op;
CREATE PROCEDURE spu_buscar_secuencias_por_op(IN _op_nombre VARCHAR(50))
BEGIN
    -- Buscar las secuencias asociadas a la OP seleccionada y solo devolver el nombre de la secuencia
    SELECT 
    S.id AS idsecuencia, 
    S.numSecuencia AS nombre_secuencia
    FROM secuencias S
    JOIN actions A ON S.idop = A.id  -- Relación con la OP
    WHERE A.nombre LIKE CONCAT('%', _op_nombre, '%')  -- Filtra por el nombre de la OP
    ORDER BY S.numSecuencia;  -- Ordena las secuencias por su número
END $$
DELIMITER ;

SELECT * from secuencias;
CALL spu_buscar_secuencias_por_op('123')

DELIMITER $$
-- buscado de 
DROP PROCEDURE IF EXISTS spu_listar_secuencias_por_op;
CREATE PROCEDURE spu_listar_secuencias_por_op(IN _op_nombre VARCHAR(50))
BEGIN
    -- Buscar las secuencias asociadas a las OPs cuyo nombre contenga el parámetro _op_nombre
    SELECT S.id AS secuenciaid, 
    idop, nombre, numSecuencia  -- Muestra el número de secuencia
    FROM secuencias S
    JOIN actions A ON S.idop = A.id  -- Relación con la OP
    WHERE A.nombre LIKE CONCAT('%', _op_nombre, '%')  -- Búsqueda parcial por nombre de la OP
    ORDER BY S.numSecuencia;  -- Ordena las secuencias por su número
END $$
DELIMITER ;
select * from actions;
call spu_listar_secuencias_por_op('1');
DELIMITER $$
DROP PROCEDURE IF EXISTS spu_listar_secuencias;
CREATE PROCEDURE spu_listar_secuencias(IN _query VARCHAR(50))
BEGIN
    -- Listar el ID y número de secuencia de las secuencias que correspondan a la OP especificada
    -- y que contengan el término de búsqueda en numSecuencia
    SELECT id, numSecuencia
    FROM secuencias
    WHERE numSecuencia LIKE CONCAT('%', _query, '%')
    ORDER BY numSecuencia;  -- Opcional: Ordenar las secuencias por numSecuencia
END $$
DELIMITER ;

CALL spu_listar_secuencias('1');
select * from pagos;

DELIMITER $$
DROP PROCEDURE IF EXISTS spu_buscar_secuencias;
CREATE PROCEDURE spu_buscar_secuencias(IN _query VARCHAR(255))
BEGIN
    SELECT id, numSecuencia 
    FROM secuencias 
    WHERE numSecuencia LIKE CONCAT('%', _query, '%')
    ORDER BY nombre;
END;
DELIMITER ;
