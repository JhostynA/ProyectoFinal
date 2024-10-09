USE linoFino;

INSERT INTO personas (apepaterno, apematerno, nombres) 
	VALUES
		('Aburto', 'Acevedo', 'Jhostyn');
        
SELECT * FROM personas;
        
CALL spu_colaboradores_registrar(1,'JhostynA','$2y$10$shfcJOApvH8mxR/vm4PupOQ9b5v9vGBXMQnfwDKeJhbOuvWurw/qi');

CALL spu_colaboradores_login('JhostynA');