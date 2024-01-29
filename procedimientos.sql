use Acis_ticket;

DELIMITER //

DROP PROCEDURE IF EXISTS crear_usuario_ope;

CREATE PROCEDURE crear_usuario_ope(
    IN p_rut_user VARCHAR(255),
    IN p_contrasenia VARCHAR(255),
    IN p_nombre VARCHAR(255),
    IN p_s_nombre VARCHAR(255),
    IN p_ap_paterno VARCHAR(255),
    IN p_ap_materno VARCHAR(255),
    IN p_correo VARCHAR(255),
    IN p_fecha_creacion DATETIME,
    IN p_fecha_expiracion DATETIME,
    IN p_id_rol INT,
    IN p_telefono VARCHAR(255),
    IN p_id_codigo INT,
    IN p_id_estado INT,
    IN p_users VARCHAR(255)
)
BEGIN
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        -- Manejar excepciones aquí
    END;

    START TRANSACTION;

    INSERT INTO usuarios (rut_user, contrasenia, nombre, s_nombre, ap_paterno, ap_materno, correo, fecha_creacion, fecha_expiracion, id_rol, telefono, id_codigo, id_estado, users) 
    VALUES (p_rut_user, p_contrasenia, p_nombre, p_s_nombre, p_ap_paterno, p_ap_materno, p_correo, p_fecha_creacion, p_fecha_expiracion, p_id_rol, p_telefono, p_id_codigo, p_id_estado, p_users);

    SET @sql = CONCAT('CREATE USER ', QUOTE(p_users), ' IDENTIFIED BY ', QUOTE(p_contrasenia));
    PREPARE stmt FROM @sql;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;

    SET @sql = CONCAT('GRANT SELECT ON `acis_ticket`.* TO ', QUOTE(p_users));
    PREPARE stmt FROM @sql;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
     -- Aplicar los cambios de privilegios
    FLUSH PRIVILEGES;

    COMMIT;
END //

DELIMITER ;

-- elminar usuario de las dos tablas
SET SQL_SAFE_UPDATES = 0;
SET FOREIGN_KEY_CHECKS = 0;
-- Realizar la eliminación
DELETE FROM usuarios WHERE users = 'userTest';
-- Reactivar la verificación de clave foránea
SET FOREIGN_KEY_CHECKS = 1;
SET SQL_SAFE_UPDATES = 1;
DROP USER 'userTest'@'%';
-- 


DELIMITER //
DROP function IF EXISTS FN_generarNuevoTicket;

CREATE FUNCTION FN_generarNuevoTicket() RETURNS VARCHAR(15) DETERMINISTIC
BEGIN
    DECLARE maxId INT;
    DECLARE nextId INT;
    DECLARE formattedId VARCHAR(15);

    -- Consulta SQL para obtener el último ticket creado
    SELECT MAX(id_ticket_auto) INTO maxId FROM AUTO_TICKET;

    IF maxId IS NULL THEN
        SET maxId = 0;
    END IF;

    SET nextId = maxId + 1;
    SET formattedId = CONCAT('REQ', LPAD(nextId, 11, '0'));

    -- Preparar la consulta de inserción
    INSERT INTO AUTO_TICKET (id_ticket_auto) VALUES (nextId);

    IF ROW_COUNT() > 0 THEN
        -- Inserción exitosa
        RETURN formattedId;
    ELSE
        -- Error en la inserción
        RETURN CONCAT('Error al insertar el ticket: ', LAST_ERROR());
    END IF;
END //

DELIMITER ;


DELIMITER //
drop procedure IF EXISTS obtenerPrioridades;

CREATE PROCEDURE obtenerPrioridades()
BEGIN
    -- Consulta SQL para obtener las opciones de prioridad de la tabla "prioridad"
    SELECT id_prioridad as correl, nombre as atrib FROM PRIORIDAD;
END //

DELIMITER ;




select * from archivos_temporales;

select * from archivo_adjunto;

select * from historico;

SELECT * FROM mysql.user;

select * from View_Users;


