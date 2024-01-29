use mysql;
use Acis_ticket;

select *  FROM mysql.user;



CREATE VIEW View_Users AS
SELECT MSQL.User, MSQL.Host, MSQL.Authentication_String, u.rut_user, u.nombre,
 u.s_nombre, u.ap_paterno, u.ap_materno, u.correo, u.fecha_creacion, u.fecha_expiracion, 
 u.id_rol, u.telefono, u.id_codigo, u.id_estado, u.users
 FROM mysql.user AS MSQL  JOIN usuarios AS u ON u.users = MSQL.User;


CREATE USER 'GadoAdministrador'@'%' IDENTIFIED BY 'asd123';
GRANT ALL PRIVILEGES ON *.* TO 'GadoAdministrador'@'%' WITH GRANT OPTION;

CREATE USER 'MasterLogin'@'%' IDENTIFIED BY 'Darketon12';
GRANT USAGE  ON Acis_ticket.* TO 'MasterLogin'@'%';
GRANT SELECT ON Acis_ticket.* TO 'MasterLogin'@'%';
GRANT DELETE ON Acis_ticket.tokens TO 'MasterLogin'@'%';
GRANT INSERT ON Acis_ticket.tokens TO 'MasterLogin'@'%';
GRANT EXECUTE ON PROCEDURE acis_ticket.CambiarContrasenia TO 'MasterLogin'@'%';

CREATE USER 'gadosegundario'@'%' IDENTIFIED BY 'Darketon12';
GRANT USAGE  ON Acis_ticket.* TO 'gadosegundario'@'%';
GRANT INSERT ON Acis_ticket.tokens TO 'gadosegundario'@'%';
GRANT SELECT ON Acis_ticket.ticket TO 'gadosegundario'@'%';
GRANT SELECT ON Acis_ticket.empresa TO 'gadosegundario'@'%';
GRANT SELECT ON Acis_ticket.usuarios TO 'gadosegundario'@'%';
GRANT SELECT ON Acis_ticket.estado_ticket TO 'gadosegundario'@'%';

CREATE ROLE rol_Nuevo_user;
GRANT USAGE  ON Acis_ticket.* TO rol_Nuevo_user;
GRANT SELECT ON Acis_ticket.* TO rol_Nuevo_user;
GRANT INSERT ON Acis_ticket.* TO rol_Nuevo_user;



-- Verifica si el usuario tiene permiso SELECT en la tabla 
SELECT COUNT(*) AS tiene_permiso
FROM information_schema.table_privileges
WHERE TABLE_SCHEMA = 'acis_ticket'
  AND TABLE_NAME = 'estado_ticket'
  AND PRIVILEGE_TYPE = 'SELECT'
 AND grantee = "'gadosegundario'@'%'"; -- Cambia "%"" si el usuario tiene un host específico



CREATE USER 'MasterGuard'@'%' IDENTIFIED BY 'Darketon12';
GRANT USAGE  ON Acis_ticket.* TO 'MasterGuard'@'%';
GRANT SELECT ON Acis_ticket.permisos_usuario TO 'MasterGuard'@'%';


CREATE USER 'Admin'@'%' IDENTIFIED BY 'Darketon12';
GRANT USAGE  ON Acis_ticket.* TO 'MasterLogin'@'%';
GRANT SELECT ON Acis_ticket.* TO 'MasterLogin'@'%';

UPDATE mysql.user
SET Authentication_String = '$2y$10$7Mi.cUExtwwjtTCjcxB4oOwNmX.67jyCIC8bPxm1f2gksdOdUgT2K'
where user = 'GadoAdmin';

SET SQL_SAFE_UPDATES = 0;

FLUSH PRIVILEGES;


SELECT User, Host, Db, Select_priv, Insert_priv, Update_priv
FROM mysql.db
WHERE Db = 'mysql' AND (Select_priv = 'Y' OR Insert_priv = 'Y' OR Update_priv = 'Y');

FLUSH PRIVILEGES;

GRANT ALL PRIVILEGES ON *.* TO 'GadoBacan'@'%' WITH GRANT OPTION;
FLUSH PRIVILEGES;


GRANT ALL PRIVILEGES ON *.* TO 'Gadobacan'@'%' WITH GRANT OPTION;


SELECT * FROM mysql.user;

SELECT User, Host, Account_locked FROM mysql.user;


GRANT ALTER ON mysql.* TO 'Gadobacan'@'%';


SHOW GRANTS FOR 'userTest';

FLUSH PRIVILEGES;


SELECT host, user, plugin FROM mysql.user WHERE user='GadoAdmin';

ALTER USER 'admin'@'%' IDENTIFIED BY 'asd123';

ALTER USER 'Gadobacan'@'%' IDENTIFIED WITH caching_sha2_password BY 'Darketon12';
ALTER USER 'GadoAdmin'@'%' IDENTIFIED WITH 'mysql_native_password'  BY 'asd123';
use mysql;

DROP USER 'GadoAdministrador'@'%';
CREATE USER 'GadoAdministrador'@'%' IDENTIFIED BY 'asd123';
GRANT ALL PRIVILEGES ON *.* TO 'GadoAdministrador'@'%' WITH GRANT OPTION;



FLUSH PRIVILEGES;

ALTER USER 'GadoAdmin@%' IDENTIFIED BY 'asd123';

SELECT CURRENT_USER();

select *  FROM mysql.user

SHOW ENGINE INNODB STATUS
SELECT p.id_permiso_indi, p.nombre, p.tipo, p.detalle
        FROM permisos_indi p
        INNER JOIN permisos_usuario pu ON p.id_permiso_indi = pu.id_permiso_indi
        WHERE pu.rut_user = '18.822.185-8'
        
        
        SELECT e.rut_empresa FROM cliente c 
        RIGHT JOIN empresa e ON c.rut_empresa = e.rut_empresa WHERE e.rut_empresa = "110"
  