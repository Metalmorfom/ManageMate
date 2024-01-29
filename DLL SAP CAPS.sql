drop database Acis_ticket;
create database Acis_ticket;
use Acis_ticket;

-- Crear la tabla region
CREATE TABLE region (
    id_region INT NOT NULL AUTO_INCREMENT,
    nombre VARCHAR(100) NOT NULL,
    PRIMARY KEY (id_region)
);

-- Crear la tabla ciudad
CREATE TABLE ciudad (
    id_ciudad INT NOT NULL AUTO_INCREMENT,
    nombre VARCHAR(100) NOT NULL,
    id_region INT NOT NULL,
    PRIMARY KEY (id_ciudad),
    FOREIGN KEY (id_region) REFERENCES region (id_region)
);

-- Crear la tabla comuna
CREATE TABLE comuna (
    id_comuna INT NOT NULL AUTO_INCREMENT,
    nombre VARCHAR(100) NOT NULL,
    id_ciudad INT NOT NULL,
    PRIMARY KEY (id_comuna),
    FOREIGN KEY (id_ciudad) REFERENCES ciudad (id_ciudad)
);
-- Crear la tabla cod_telefono
CREATE TABLE cod_telefono (
    id_codigo INT NOT NULL AUTO_INCREMENT,
    codigo VARCHAR(50) NOT NULL,
    PRIMARY KEY (id_codigo)
);


-- Crear la tabla sucursal
CREATE TABLE sucursal (
    id_sucursal INT NOT NULL AUTO_INCREMENT,
    nombre VARCHAR(100) NOT NULL,
    direccion VARCHAR(100) NOT NULL,
    telefono INT,
    correo VARCHAR(50),
    id_codigo INT,
    id_comuna INT NOT NULL,
    tel_fijo INT,
    PRIMARY KEY (id_sucursal),
    UNIQUE KEY sucursal_correo__uk (correo),
    UNIQUE KEY sucursal_fijo__uk (tel_fijo),
    FOREIGN KEY (id_codigo) REFERENCES cod_telefono (id_codigo),
    FOREIGN KEY (id_comuna) REFERENCES comuna (id_comuna)
);

-- Crear la tabla empresa
CREATE TABLE empresa (
    rut_empresa VARCHAR(12) NOT NULL,
    nombre VARCHAR(100) NOT NULL,
    giro_comercial VARCHAR(100) NOT NULL,
    direc_casa_matriz VARCHAR(100) NOT NULL,
    telefono INT,
    correo VARCHAR(50) NOT NULL,
    tel_fijo INT,
    id_comuna INT NOT NULL,
    id_sucursal INT NOT NULL,
    id_codigo INT,
    PRIMARY KEY (rut_empresa),
    UNIQUE KEY correo__uk (correo),
    UNIQUE KEY empresa_fijo__ukv1 (tel_fijo),
    FOREIGN KEY (id_codigo) REFERENCES cod_telefono (id_codigo),
    FOREIGN KEY (id_comuna) REFERENCES comuna (id_comuna),
    FOREIGN KEY (id_sucursal) REFERENCES sucursal (id_sucursal)
);

-- Crear la tabla cliente
CREATE TABLE cliente (
    rut_cliente VARCHAR(100) NOT NULL,
    nombre VARCHAR(100) NOT NULL,
    s_nombre VARCHAR(100),
    apellidos VARCHAR(50) NOT NULL,
    correo VARCHAR(100) NOT NULL,
    rut_empresa VARCHAR(12) NOT NULL,
    telefono INT,
    id_codigo INT,
    PRIMARY KEY (rut_cliente),
    UNIQUE KEY cliente_correo__uk (correo),
    UNIQUE KEY cliente_telefono__uk (telefono),
    FOREIGN KEY (id_codigo) REFERENCES cod_telefono (id_codigo),
    FOREIGN KEY (rut_empresa) REFERENCES empresa (rut_empresa)
);




-- Crear la tabla rol
CREATE TABLE rol (
    id_rol INT NOT NULL AUTO_INCREMENT,
    nombre_rol VARCHAR(100) NOT NULL,
    PRIMARY KEY (id_rol)
);


-- Crear la tabla estado_ticket
CREATE TABLE estado_ticket (
    id_estado_tk INT NOT NULL AUTO_INCREMENT,
    nombre VARCHAR(50) NOT NULL,
   PRIMARY KEY (id_estado_tk)
);

-- Crear la tabla sub_estado
CREATE TABLE sub_estado (
    id_sub_estado INT NOT NULL AUTO_INCREMENT,
    nombre VARCHAR(100) NOT NULL,
    id_estado_tk INT NOT NULL,
    PRIMARY KEY (id_sub_estado),
    FOREIGN KEY (id_estado_tk) REFERENCES estado_ticket (id_estado_tk)
);



-- Crear la tabla estado_usuario
CREATE TABLE estado_usuario (
    id_estado INT NOT NULL AUTO_INCREMENT,
    nombre_estado VARCHAR(50) NOT NULL,
    PRIMARY KEY (id_estado)
);

-- Crear la tabla usuarios
CREATE TABLE usuarios (
    rut_user VARCHAR(12) NOT NULL,
    contrasenia VARCHAR(60) NOT NULL,
    nombre VARCHAR(30) NOT NULL,
    s_nombre VARCHAR(30),
    ap_paterno VARCHAR(30) NOT NULL,
    ap_materno VARCHAR(30) NOT NULL,
    correo VARCHAR(100) NOT NULL,
    fecha_creacion TIMESTAMP  DEFAULT CURRENT_TIMESTAMP NOT NULL,
    fecha_expiracion TIMESTAMP NOT NULL,
    id_rol INT NOT NULL,
    telefono VARCHAR(20),
    id_codigo INT,
    id_estado INT NOT NULL,
    users varchar(50),
    PRIMARY KEY (rut_user),
    UNIQUE KEY usuarios_telefono__uk (telefono),
    UNIQUE KEY usuarios_correo__uk (correo),
    FOREIGN KEY (id_codigo) REFERENCES cod_telefono (id_codigo),
    FOREIGN KEY (id_rol) REFERENCES rol (id_rol),
    FOREIGN KEY (id_estado) REFERENCES estado_usuario (id_estado)
);

CREATE TABLE permisos_indi (
    id_permiso_indi INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(255) NOT NULL,
    tipo VARCHAR(50) NOT NULL,
    detalle TEXT NOT NULL,
    UNIQUE KEY unique_nombre (nombre)
);

CREATE TABLE permisos_usuario (
    id_permiso_usuario INT AUTO_INCREMENT PRIMARY KEY,
    rut_user VARCHAR(12) NOT NULL,
    id_permiso_indi INT NOT NULL,
    FOREIGN KEY (rut_user) REFERENCES usuarios(rut_user),
    FOREIGN KEY (id_permiso_indi) REFERENCES permisos_indi(id_permiso_indi),
    UNIQUE KEY unique_user_permiso (rut_user, id_permiso_indi)
);








CREATE TABLE tokens (
    id INT AUTO_INCREMENT PRIMARY KEY,
    token VARCHAR(255) NOT NULL,
    fecha_expiracion DATETIME NOT NULL,
    tipo ENUM('verificacion', 'restauracion') NOT NULL,
    id_usuario VARCHAR(12),  -- Asegúrate de que este campo coincida con el tipo de dato de tu clave primaria en la tabla usuarios
    FOREIGN KEY (id_usuario) REFERENCES usuarios (rut_user)
);







CREATE TABLE PRIORIDAD ( 
id_prioridad INT NOT NULL AUTO_INCREMENT,
nombre varchar(100) NOT NULL,
PRIMARY KEY (id_prioridad)
);


CREATE TABLE AUTO_TICKET(
id_ticket_auto INT auto_increment,
Primary Key(id_ticket_auto)
);

-- Crear la tabla ticket
CREATE TABLE ticket (
    id_ticket VARCHAR(20),
    resumen VARCHAR(100) NOT NULL,
    descripcion VARCHAR(1000) NOT NULL,
    fecha_creacion TIMESTAMP NOT NULL,
    rut_user_generador VARCHAR(20) NOT NULL,  
    id_estado_tk INT NOT NULL,
    rut_empresa VARCHAR(20) NOT NULL,
    cliente_rut_cliente VARCHAR(100) NOT NULL,
    usuarios_rut_user VARCHAR(20)  ,
    Rut_usuario_afectado varchar(100),
    modelo varchar(100) NOT NULL,
    id_prioridad INT NOT NULL,
    Nombre_user_completo_afectado varchar (100),
    Mandante_afectado varchar(100),
    Cargo_afectado varchar(100),
    
    interno boolean,
    lavado_equipo boolean,
    UPW boolean,
    PRB boolean,
    PRD boolean,
    QAS boolean,
    
    Creacion boolean,
    desvinculacion boolean,
    homologacion boolean,
    reseteo boolean,
    PRIMARY KEY (id_ticket),
    FOREIGN KEY (rut_empresa) REFERENCES empresa (rut_empresa),
    FOREIGN KEY (id_estado_tk) REFERENCES estado_ticket (id_estado_tk),
    FOREIGN KEY (rut_user_generador) REFERENCES usuarios (rut_user),
    FOREIGN KEY (usuarios_rut_user) REFERENCES usuarios (rut_user),
    FOREIGN KEY (cliente_rut_cliente) REFERENCES cliente (rut_cliente),
    FOREIGN KEY (id_prioridad) REFERENCES PRIORIDAD (id_prioridad)
    
    
);


CREATE TABLE archivo_adjunto (
    id_archivo INT NOT NULL AUTO_INCREMENT,
    nombre_archivo VARCHAR(255) NOT NULL,
    ruta_archivo VARCHAR(255) NOT NULL,
    id_ticket Varchar(20) NOT NULL,
    fecha_subida TIMESTAMP NOT NULL,
    sector Varchar(20) NOT NULL,
    nombre_gcp varchar(500) NOT NULL,
    PRIMARY KEY (id_archivo),
    FOREIGN KEY (id_ticket) REFERENCES ticket (id_ticket)
);



CREATE TABLE archivos_temporales (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre_archivo VARCHAR(255) NOT NULL,
    nombre_temporal VARCHAR(255) NOT NULL,
    fecha_subida DATETIME NOT NULL,
    id_ticket Varchar(20), -- Si deseas asociar los archivos temporales a tickets
    usuario_subida VARCHAR(100), -- Si deseas registrar quién subió el archivo
    sector Varchar(20) NOT NULL
    -- Otras columnas según tus necesidades --
);





-- Crear la tabla historico
CREATE TABLE historico (
    id_historico INT NOT NULL AUTO_INCREMENT,
    fecha_hora TIMESTAMP NOT NULL,
    estado_anterior INT ,
    estado_actual INT,
    motivo varchar(50),
    rut_usuario_asignado varchar(50),
    adjuntos varchar(500),
    rut_empresa varchar(20),
    nombre_gcp varchar(500),
    
    rut_user VARCHAR(12) NOT NULL,
    id_ticket Varchar(20) NOT NULL,
    tipo_historico varchar(50) NOT NULL,
    PRIMARY KEY (id_historico),
    FOREIGN KEY (rut_user) REFERENCES usuarios (rut_user),
    FOREIGN KEY (id_ticket) REFERENCES ticket (id_ticket)
);

-- Crear la tabla notas_trab
CREATE TABLE notas_trab (
    id_notas INT NOT NULL AUTO_INCREMENT,
    fecha_hora TIMESTAMP NOT NULL,
    descripcion VARCHAR(1000) NOT NULL,
    rut_user VARCHAR(12) NOT NULL,
    id_ticket Varchar(20) NOT NULL,
    PRIMARY KEY (id_notas),
    FOREIGN KEY (rut_user) REFERENCES usuarios (rut_user),
    FOREIGN KEY (id_ticket) REFERENCES ticket (id_ticket)
);

-- Crear la tabla resumen_ot
CREATE TABLE resumen_ot (
    id_resumen INT NOT NULL AUTO_INCREMENT,
    semana VARCHAR(10) NOT NULL,
    fecha_inicio_sem DATE NOT NULL,
    fecha_fin DATE NOT NULL,
    total_tickets INT NOT NULL,
    cant_assing INT NOT NULL,
    cant_cerrado INT NOT NULL,
    cant_abiertos INT NOT NULL,
    cant_progreso INT NOT NULL,
    cant_no_assing INT NOT NULL,
    PRIMARY KEY (id_resumen)
);




-- Inserciones para la tabla region
-- Inserciones para la tabla region
INSERT INTO region (nombre) VALUES
('Región Metropolitana'),
('Región de Valparaíso'),
('Región del Biobío'),
('Región de Atacama'),
('Región de Coquimbo'),
('Región de Los Lagos'),
('Región de Antofagasta'),
('Región del Maule'),
('Región de La Araucanía'),
('Región de Magallanes');


-- Inserciones para la tabla ciudad
-- Inserciones para la tabla ciudad
INSERT INTO ciudad (nombre, id_region) VALUES
('Santiago', 1),
('Valparaíso', 2),
('Concepción', 3),
('Copiapó', 4),
('La Serena', 5),
('Puerto Montt', 6),
('Antofagasta', 7),
('Talca', 8),
('Temuco', 9),
('Punta Arenas', 10);



-- Inserciones para la tabla comuna
INSERT INTO comuna (nombre, id_ciudad) VALUES
('Providencia', 1),
('Viña del Mar', 2),
('Talcahuano', 3),
('Caldera', 4),
('Coquimbo', 5),
('Puerto Varas', 6),
('Mejillones', 7),
('Curicó', 8),
('Villarrica', 9),
('Porvenir', 10);


-- Inserciones para la tabla cod_telefono
-- Inserciones para la tabla cod_telefono
INSERT INTO cod_telefono (codigo) VALUES
('Chile +56'),
('Argentina +54'),
('Bolivia +591'),
('Brasil +55'),
('Colombia +57'),
('Ecuador +593'),
('Guyana +592'),
('Paraguay +595'),
('Perú +51'),
('Surinam +597');



-- Inserciones para la tabla sucursal
INSERT INTO sucursal (nombre, direccion, telefono, correo, id_codigo, id_comuna, tel_fijo) VALUES
('Sucursal Centro', 'Av. Principal 123', 22334455, 'sucursal1@example.com', 1, 1, 77665544),
('Sucursal Costera', 'Av. Costanera 456', 32223344, 'sucursal2@example.com', 2, 2, 88776655),
('Sucursal Norte', 'Calle Norte 789', 21112233, 'sucursal3@example.com', 3, 3, 99887766),
('Sucursal Sur', 'Calle Sur 456', 34445566, 'sucursal4@example.com', 4, 4, 66554433),
('Sucursal Este', 'Calle Este 789', 45556677, 'sucursal5@example.com', 5, 5, 33445566),
('Sucursal Oeste', 'Calle Oeste 123', 56667788, 'sucursal6@example.com', 6, 6, 22334455),
('Sucursal Céntrica', 'Av. Central 456', 67778899, 'sucursal7@example.com', 7, 7, 11223344),
('Sucursal Playa', 'Av. Playa 789', 78889900, 'sucursal8@example.com', 8, 8, 99887700),
('Sucursal Montaña', 'Av. Montaña 123', 89990011, 'sucursal9@example.com', 9, 9, 88776611),
('Sucursal Selva', 'Av. Selva 456', 10001122, 'sucursal10@example.com', 10, 10, 77665511);



-- Inserciones para la tabla empresa
INSERT INTO empresa (rut_empresa, nombre, giro_comercial, direc_casa_matriz, telefono, correo, tel_fijo, id_comuna, id_sucursal, id_codigo) VALUES
('315', 'Oficina Los Jardines - Unimarc', 'Tecnología', 'Matriz 1', 22334455, 'empresaA@example.com', 77665544, 1, 1, 1),
('287', 'Sede El Almendro - OK Market', 'Servicios', 'Matriz 2', 32223344, 'empresaB@example.com', 88776655, 2, 2, 2),
('468', 'Oficina La Brisas - Telemercados', 'Transporte', 'Matriz Grupo DAP', 12345678, 'contacto@grupodap.cl', 87654321, 3, 3, 3),
('529', 'Sede Los Cerezos - Alvi', 'Turismo', 'Matriz Antarctica21', 23456789, 'contacto@antarctica21.cl', 98765432, 4, 4, 4),
('110', 'Oficina Las Flores - Mayorista 10', 'Comercial', 'Matriz Artel', 34567890, 'contacto@artel.cl', 10987654, 5, 5, 5),
('134', 'Sede Vista Alegre - Mayorsa', 'Industrial', 'Matriz Bbosch', 45678901, 'contacto@bbosch.cl', 21098765, 6, 6, 6),
('456', 'Oficina El Trébol - Maxi Ahorro', 'Agrícola', 'Matriz Coagra', 56789012, 'contacto@coagra.cl', 32109876, 7, 7, 7),
('196', 'Sede Los Portales - Unimarc', 'Alimentos', 'Matriz Coexca', 67890123, 'contacto@coexca.cl', 43210987, 8, 8, 8),
('217', 'Sede Valle del Sol - Mayorsa', 'Agropecuaria', 'Matriz Cooprinsem', 78901234, 'contacto@cooprinsem.cl', 54321098, 9, 9, 9),
('132', 'Oficina Costa Dorada - Maxi Ahorro', 'Tecnología', 'Matriz Defontana', 89012345, 'contacto@defontana.cl', 65432109, 10, 10, 10);





-- Inserciones para la tabla cliente
INSERT INTO cliente (rut_cliente, nombre, s_nombre, apellidos, correo, rut_empresa, telefono, id_codigo) VALUES
('12345678-9', 'Ana', 'María', 'Pérez Gómez','anaperez@example.com', '315', 22334455, 1),
 ('23456789-0', 'Luis', NULL, 'Martínez Fernández', 'luismartinez@example.com', '287', 32223344, 2),
 ('34567890-1', 'Pedro', 'Carlos', 'González Sánchez',  'pedrogonzalez@example.com', '468', 21112233, 3),
 ('45678901-2', 'Isabel', NULL, 'Hernández Pérez',  'isabelhernandez@example.com', '529', 34445566, 4),
 ('56789012-3', 'Carmen', 'Antonia', 'Soto Gómez',  'carmensoto@example.com', '110', 45556677, 5),
 ('67890123-4', 'Juan', NULL, 'López Martínez', 'juanlopez@example.com', '134', 56667788, 6),
 ('78901234-5', 'María', 'Isidora Rodríguez',  'Fernández', 'mariaisidora@example.com', '456', 67778899, 7),
 ('89012345-6', 'Andrés', NULL, 'Torres Hernández', 'andrestorres@example.com', '196', 78889900, 8),
 ('90123456-7', 'Antonio', 'José', 'Muñoz Gómez',  'antoniomunoz@example.com', '217', 89990011, 9),
 ('01234567-8', 'Carolina', NULL, 'Silva Martínez',  'carolinasilva@example.com', '132', 10001122, 10),
 

('123456789-0', 'Santiago', NULL, 'Gutiérrez Mendoza', 'Cuentafolla@hotmail.com', '315', 77665544, 1),
('987654321-0', 'Luciana', NULL, 'Torres Paredes', 'representante-empresab@example.com', '287', 88776655, 2),
('111111111-1', 'Mateo', NULL, 'Vásquez Fuentes', 'representante-grupodap@example.com', '468', 87654321, 3),
('222222222-2', 'Carolina', NULL, 'Miranda Carvajal', 'representante-antarctica21@example.com', '529', 98765432, 4),
('333333333-3', 'Diego', NULL, 'Cabrera Sanz', 'representante-artel@example.com', '110', 10987654, 5),
('444444444-4', 'Natalia', NULL, 'Sánchez Quiroz', 'representante-bbosch@example.com', '134', 21098765, 6),
('555555555-5', 'Yessenia', NULL, 'santander hernandez', 'representante-coagra@example.com', '456', 32109876, 7),
('666666666-6', 'Yessica', NULL, 'Artigas hernandez', 'representante-coexca@example.com', '196', 43210987, 8),
('777777777-7', 'Matias', NULL, 'Sanhueza macaya', 'representante-cooprinsem@example.com', '217', 54321098, 9),
('888888888-8', 'Nestor', NULL, 'Pachero Ramiderez', 'representante-defontana@example.com', '132', 65432109, 10);


   


-- Inserciones para la tabla rol
INSERT INTO rol (nombre_rol) VALUES

('Administrador'),
('Técnico de Soporte'),
('Supervisor');



-- Inserciones para la tabla estado_ticket
INSERT INTO estado_ticket (nombre) VALUES
     ('Nuevo'),
    ('En Progreso'),
    ('pendiente'),
    ('Resuelto'),
    ('Cancelado');


-- Inserciones para la tabla sub_estado
INSERT INTO sub_estado (nombre,id_estado_tk) VALUES


('Esperando respuesta cliente', 2),
('Esperando piezas', 2),
('En revisión', 2),
('Esperando aprobación', 2),
('Esperando a terceros', 2),
('En pausa', 3),
('Requiere seguimiento', 3),
('Reasignado', 3),
('Cerrado por sistema', 4),
('Cerrado por usuario', 4),
('Solución temporal', 4),
('Reabierto', 4),
('Escalado', 5),
('No resuelto', 5),
('Duplicado', 5),
('Invalidado', 5),
('Cerrado sin acción', 5);





-- Inserciones para la tabla estado_usuario
INSERT INTO estado_usuario (nombre_estado) VALUES
('Activo'),
('Inactivo'),
('Bloqueado'),
('Verificar Email'),
('Suspensión temporal'),
('Eliminado'),
('Cuenta Nueva'),
('Pendiente de revisión'),
('Premium o Suscripción'),
('Expirado'),
('Restablecimiento de contraseña'),
('Baneado');


-- Inserciones para la tabla usuarios
INSERT INTO usuarios (rut_user, contrasenia, nombre, s_nombre, ap_paterno, ap_materno, correo, fecha_creacion, fecha_expiracion, id_rol, telefono, id_codigo, id_estado,users) VALUES
('18.822.185-8', '$2y$10$7Mi.cUExtwwjtTCjcxB4oOwNmX.67jyCIC8bPxm1f2gksdOdUgT2K', 'Gado', 'Gabriel', 'Briones', 'Aravena', 'ElIncreibleCorreo@hotmail.com', NOW(), '2035-12-31 23:59:59', 1, '9 20 10 25 34', 1, 1,'GadoAdministrador'),
('johndoe', '$2y$10$7Mi.cUExtwwjtTCjcxB4oOwNmX.67jyCIC8bPxm1f2gksdOdUgT2K', 'John', 'A.', 'Doe', 'Smith', 'john@gmail.com', NOW(), '2035-12-31 23:59:59', 1, '9 20 11 25 34', 1, 1,null),
('alicesmith', '$2y$10$7Mi.cUExtwwjtTCjcxB4oOwNmX.67jyCIC8bPxm1f2gksdOdUgT2K', 'Alice', 'M.', 'Smith', 'Johnson', 'alice@gmail.com', NOW(), '2023-11-10 23:59:59', 2, '1 20 10 25 34', 2, 1,null),
('23456789-K', '$2y$10$7Mi.cUExtwwjtTCjcxB4oOwNmX.67jyCIC8bPxm1f2gksdOdUgT2K', 'Carlos', 'Eduardo', 'García', 'López', 'carlos@example.com', NOW(), '2035-12-31', 3, '9 30 40 50 60', 3, 2,null),
('87654321-J', '$2y$10$7Mi.cUExtwwjtTCjcxB4oOwNmX.67jyCIC8bPxm1f2gksdOdUgT2K', 'María', 'Isabel', 'Fernández', 'Vega', 'maria@example.com', NOW(), '2035-12-31', 2, '9 15 25 35 45', 4, 3,null),
('91234567-L', '$2y$10$7Mi.cUExtwwjtTCjcxB4oOwNmX.67jyCIC8bPxm1f2gksdOdUgT2K', 'Roberto', 'José', 'Martinez', 'Rodriguez', 'Cuentafolla@hotmail.com', NOW(), '2035-12-31', 1, '9 22 33 44 55', 5, 1,'gadosegundario'),
('89101112-M', '$2y$10$7Mi.cUExtwwjtTCjcxB4oOwNmX.67jyCIC8bPxm1f2gksdOdUgT2K', 'Lucía', 'Ana', 'Gomez', 'Díaz', 'lucia@example.com', NOW(), '2035-12-31', 3, '9 44 55 66 77', 6, 2,null);



CREATE OR REPLACE VIEW View_Users AS
SELECT MSQL.User, MSQL.Host, MSQL.Authentication_String, u.rut_user, u.nombre,
 u.s_nombre, u.ap_paterno, u.ap_materno, u.correo, u.fecha_creacion, u.fecha_expiracion, 
 u.id_rol, u.telefono, u.id_codigo, u.id_estado, u.users
 FROM mysql.user AS MSQL  JOIN usuarios AS u ON u.users = MSQL.User;


INSERT INTO permisos_indi (id_permiso_indi,nombre, tipo, detalle) VALUES
    (1,'Leer ordenes', 'Lectura', 'Permite al usuario vizualizar ordenes de trabajo en la aplicación.'),
    (2,'Crear ordenes', 'Escritura', 'Permite al usuario crear ordenes de trabajo.'),
    (3,'Editar Ordenes ', 'Actulizacion', 'Permite al usuario realizar modificaciones a ordenes existentes.'),
    (4,'Adm_usuarios', 'Gestor usuario', 'Permite al usuario administrar otros usuarios en la aplicación.'),
    (5,'Adm_registro', 'Gestor usuario', 'Permite al usuario dar de alta nuevos usuarios aplicación.'),
    (6,'Adm_roles', 'Gestor usuario', 'permite al usuario cambiar roles y permisos de los usuarios en la aplicacion.'),
    (7,'Historico', 'Lectura', 'Permite al usuario vizualizar historico de ordenes.'),
    (8,'Leer notas', 'Lectura', 'permite al usuario leer notas de trabajo.'),
    (9,'Crear notas', 'Escritura', 'permite al usuario crear notas de trabajo.'),
    (10,'resumenes', 'Lectura', 'permite al usuario leer graficos resumen.'),
    (11,'Conectarse', 'Login', 'permite al usuario poder conectarse a la aplicacion.');



INSERT INTO permisos_usuario (rut_user, id_permiso_indi) VALUES
( '18.822.185-8', 1),
('18.822.185-8' , 2),
('18.822.185-8' , 3),
('18.822.185-8' , 4),
('18.822.185-8' , 5),
('18.822.185-8' , 6),
( '18.822.185-8', 7),
( '18.822.185-8', 8),
( '18.822.185-8', 9),
( '18.822.185-8', 10),
('18.822.185-8' , 11);


-- inserciones para la tabla prioridad 
INSERT INTO PRIORIDAD (nombre) VALUES
('Alta'),
('Media'),
('Baja');

-- Auto_ticket
INSERT INTO AUTO_TICKET(id_ticket_auto) VALUES 
(1),
(2),
(3),
(4),
(5),
(6),
(7),
(8),
(9),
(10),
(11),
(12),
(13),
(14),
(15);

-- Inserciones de tickets relacionados con trabajos de mecánica
INSERT INTO ticket (id_ticket, resumen, descripcion, fecha_creacion, rut_user_generador, id_estado_tk, rut_empresa, cliente_rut_cliente, usuarios_rut_user, Rut_usuario_afectado, modelo, id_prioridad, Nombre_user_completo_afectado, Mandante_afectado, Cargo_afectado, interno, lavado_equipo, UPW, PRB, PRD, QAS, Creacion, desvinculacion, homologacion, reseteo)
VALUES
('REQ00000000001', 'Cambio de aceite y filtro', 'Realizar cambio de aceite y filtro de aceite en un Toyota Corolla.', '2023-11-25 08:30:00', '18.822.185-8', 4, '315', '123456789-0', 'johndoe', 'Toyota', 'Corolla', 1, 'Serie1', 'NroParte1', 'SerieComponente1', TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE),
('REQ00000000002', 'Revisión de frenos', 'Realizar revisión de frenos en un Ford Mustang debido a ruidos inusuales.', '2023-11-25 09:45:00', 'johndoe', 4, '287', '987654321-0', 'alicesmith', 'Ford', 'Mustang', 2, 'Serie2', 'NroParte2', 'SerieComponente2', FALSE, TRUE, FALSE, TRUE, FALSE, TRUE, FALSE, TRUE, FALSE, TRUE),
('REQ00000000003', 'Cambio de neumáticos', 'Reemplazar los neumáticos desgastados en un Honda Civic.', '2023-11-25 10:30:00', 'alicesmith', 4, '468', '111111111-1', '18.822.185-8', 'Honda', 'Civic', 3, 'Serie3', 'NroParte3', 'SerieComponente3', TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE),
('REQ00000000004', 'Reparación de motor', 'Realizar reparación del motor en un Chevrolet Malibu debido a una falla en el sistema.', '2023-11-25 11:15:00', '18.822.185-8', 4, '529', '222222222-2', 'johndoe', 'Chevrolet', 'Malibu', 1, 'Serie4', 'NroParte4', 'SerieComponente4', FALSE, TRUE, FALSE, TRUE, FALSE, TRUE, FALSE, TRUE, FALSE, TRUE),
('REQ00000000005', 'Alineación y balanceo', 'Hacer alineación y balanceo en un BMW X5 para corregir problemas de manejo.', '2023-11-25 12:00:00', 'johndoe', 1, '110', '333333333-3', 'alicesmith', 'BMW', 'X5', 2, 'Serie5', 'NroParte5', 'SerieComponente5', TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE),
('REQ00000000006', 'Cambio de bujías', 'Realizar cambio de bujías en un Toyota RAV4 para mejorar el rendimiento del motor.', '2023-11-25 13:15:00', 'alicesmith', 1, '134', '444444444-4', '18.822.185-8', 'Toyota', 'RAV4', 3, 'Serie6', 'NroParte6', 'SerieComponente6', TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE),
('REQ00000000007', 'Reparación de frenos', 'Reparar frenos en un Ford F-150 debido a una pérdida de frenado.', '2023-11-25 14:30:00', '18.822.185-8', 2, '456', '555555555-5', 'johndoe', 'Ford', 'F-150', 1, 'Serie7', 'NroParte7', 'SerieComponente7', FALSE, TRUE, FALSE, TRUE, FALSE, TRUE, FALSE, TRUE, FALSE, TRUE),
('REQ00000000008', 'Cambio de aceite y filtro', 'Realizar cambio de aceite y filtro de aceite en un Honda CR-V.', '2023-11-25 15:45:00', 'johndoe', 1, '196', '666666666-6', 'alicesmith', 'Honda', 'CR-V', 2, 'Serie8', 'NroParte8', 'SerieComponente8', TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE),
('REQ00000000009', 'Reparación de suspensión', 'Reparar la suspensión en un Chevrolet Silverado debido a vibraciones.', '2023-11-25 16:30:00', 'alicesmith', 2, '217', '777777777-7', '18.822.185-8', 'Chevrolet', 'Silverado', 3, 'Serie9', 'NroParte9', 'SerieComponente9', TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE),
('REQ00000000010', 'Cambio de pastillas de freno', 'Realizar cambio de pastillas de freno en un BMW Serie 3.', '2023-11-25 17:15:00', '18.822.185-8', 1, '132', '888888888-8', 'johndoe', 'BMW', 'Serie 3', 1, 'Serie10', 'NroParte10', 'SerieComponente10', TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE),
('REQ00000000011', 'Revisión de transmisión', 'Hacer una revisión de la transmisión en un Toyota Camry debido a problemas de cambio.', '2023-11-25 18:00:00', 'johndoe', 4, '315', '123456789-0', 'alicesmith', 'Toyota', 'Camry', 2, 'Serie11', 'NroParte11', 'SerieComponente11', TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE),
('REQ00000000012', 'Cambio de amortiguadores', 'Realizar cambio de amortiguadores en un Ford Focus para mejorar el confort de conducción.', '2023-11-25 19:15:00', 'alicesmith', 1, '287', '987654321-0', '18.822.185-8', 'Ford', 'Focus', 3, 'Serie12', 'NroParte12', 'SerieComponente12', TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE),
('REQ00000000013', 'Reparación de dirección', 'Reparar la dirección en un Honda Accord debido a problemas de dirección asistida.', '2023-11-25 20:30:00', '18.822.185-8', 2, '468', '111111111-1', 'johndoe', 'Honda', 'Accord', 1, 'Serie13', 'NroParte13', 'SerieComponente13', TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE),
('REQ00000000014', 'Cambio de bujías', 'Realizar cambio de bujías en un Chevrolet Camaro para mejorar el rendimiento del motor.', '2023-11-25 21:45:00', 'johndoe', 1, '529', '222222222-2', 'alicesmith', 'Chevrolet', 'Camaro', 2, 'Serie14', 'NroParte14', 'SerieComponente14', TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE),
('REQ00000000015', 'Alineación y balanceo', 'Hacer alineación y balanceo en un BMW Serie 5 para corregir problemas de manejo.', '2023-11-25 22:30:00', 'alicesmith', 1, '110', '333333333-3', '18.822.185-8', 'BMW', 'Serie 5', 3, 'Serie15', 'NroParte15', 'SerieComponente15', TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE);



-- Inserciones para la tabla resumen_ot
INSERT INTO resumen_ot (semana, fecha_inicio_sem, fecha_fin, total_tickets, cant_assing, cant_cerrado, cant_abiertos, cant_progreso, cant_no_assing) VALUES
('Semana 1', '2023-01-01', '2023-01-07', 15, 5, 3, 4, 2, 1),
('Semana 2', '2023-01-08', '2023-01-14', 20, 6, 5, 7, 1, 1),
('Semana 3', '2023-01-15', '2023-01-21', 18, 3, 8, 6, 1, 0);



-- Agrega aquí los otros registros

INSERT INTO historico (fecha_hora, estado_anterior, estado_actual, motivo, rut_usuario_asignado, adjuntos, rut_empresa, rut_user, id_ticket, tipo_historico) VALUES
    ('2022-10-26 10:45:00', 1, 2, 'Cliente proporcionó más detalles', 'alicesmith', 'adjunto9.pdf', '18.822.185-8', '18.822.185-8', 'REQ00000000002', 'creacion'), 
    ('2023-12-15 10:00:00', 1, 2, 'Problema solucionado', '18.822.185-8', 'adjunto1.pdf', '123456789-0', 'johndoe', 'REQ00000000002', 'cambio'),
    ('2022-12-17 11:15:00', 1, 2, 'Se asignó técnico para el trabajo', '18.822.185-8', NULL, '123456789-0', 'johndoe', 'REQ00000000002', 'Asignación'),
    ('2023-12-19 15:45:00', 1, 2, 'Nuevas instrucciones del cliente', '18.822.185-8', 'adjunto4.pdf', '110', 'johndoe', 'REQ00000000001', 'cambio'),
    ('2023-12-20 08:30:00', 1, 2, 'Problema solucionado', 'alicesmith', 'adjunto5.jpg', '134', '18.822.185-8', 'REQ00000000001', 'Cierre'),
    ('2023-12-21 12:00:00', 1, 2, 'En revisión', 'Cliente proporcionó más detalles', 'adjunto1.pdf', '456', 'johndoe', 'REQ00000000001', 'Asignación'),
    ('2023-12-22 09:30:00', 1, 2, 'Se asignó técnico para el trabajo', 'alicesmith', 'adjunto6.doc', '196', '18.822.185-8', 'REQ00000000001', 'Asignación'),
    ('2023-12-23 14:15:00', 1, 2, 'Trabajo finalizado y probado', '18.822.185-8', 'adjunto7.pdf', '217', 'johndoe', 'REQ00000000001', 'creacion'),
    ('2023-12-24 11:30:00', 1, 2, 'Solicitud de soporte recibida', 'alicesmith', NULL, '132', '18.822.185-8', 'REQ00000000001', 'Asignación'),
    ('2023-12-25 16:00:00', 1, 2, 'Problema solucionado', '18.822.185-8', 'adjunto8.jpg', '132', 'johndoe', 'REQ00000000001', 'creacion'),
    ('2023-12-16 14:30:00', 1, 2, 'En revisión', 'Cliente proporcionó más detalles', 'adjunto2.jpg', '287', '18.822.185-8', 'REQ00000000003', 'cambio'),
    ('2023-12-27 13:20:00', 1, 2, 'Se asignó técnico para el trabajo', '18.822.185-8', 'adjunto10.jpg', '121212121-1', 'johndoe', 'REQ00000000001', 'Asignación'),
    ('2023-12-28 09:10:00', 1, 2, 'Trabajo finalizado y probado', 'alicesmith', 'adjunto11.doc', '131313131-2', '18.822.185-8', 'REQ00000000001', 'adjunto'),
    ('2023-12-18 09:00:00', 1, 2, 'Trabajo finalizado y probado', '18.822.185-8', 'adjunto3.doc', '529', 'alicesmith', 'REQ00000000001', 'adjunto'),
    ('2023-12-29 14:50:00', 1, 2, 'Solicitud de soporte recibida', '18.822.185-8', NULL, '141414141-3', 'johndoe', 'REQ00000000001', 'Asignación');




-- Inserciones para la tabla notas_trab
INSERT INTO notas_trab (fecha_hora, descripcion, rut_user, id_ticket) VALUES
('2023-01-02 11:30:00', 'Se está trabajando en la solución del problema de red.', 'johndoe', 'REQ00000000001'),
('2023-01-03 15:00:00', 'Hemos identificado el problema en el software y estamos trabajando en ello.', 'alicesmith', 'REQ00000000002'),
('2023-01-05 12:00:00', 'Se ha cerrado el ticket después de solucionar el problema.', 'johndoe', 'REQ00000000001');



DELIMITER //

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

    -- Crear el usuario
    SET @sql = CONCAT('CREATE USER ', QUOTE(p_users), ' IDENTIFIED BY ', QUOTE(p_contrasenia));
    PREPARE stmt FROM @sql;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;

    -- Asignar el rol al usuario
    SET @sql = CONCAT('GRANT rol_Nuevo_user TO ', QUOTE(p_users));
    PREPARE stmt FROM @sql;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;

  
    -- Aplicar los cambios de privilegios
    FLUSH PRIVILEGES;

    COMMIT;
END //

DELIMITER ;




DELIMITER //


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


CREATE PROCEDURE obtenerPrioridades()
BEGIN
    -- Consulta SQL para obtener las opciones de prioridad de la tabla "prioridad"
    SELECT id_prioridad as correl, nombre as atrib FROM PRIORIDAD;
END //

DELIMITER ;



DELIMITER //

CREATE TRIGGER borra_historico_despues_de_borrar_adjunto
AFTER DELETE ON archivo_adjunto FOR EACH ROW
BEGIN
    DELETE FROM historico WHERE nombre_gcp = OLD.nombre_gcp;
END;
//

DELIMITER ;

drop procedure CambiarContrasenia
DELIMITER //

CREATE PROCEDURE CambiarContrasenia(IN usuario VARCHAR(255), IN nueva_contrasenia VARCHAR(255))
BEGIN
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        -- Manejar errores aquí si es necesario
        ROLLBACK;
        RESIGNAL; -- Vuelve a lanzar el error original para diagnóstico
    END;

    START TRANSACTION;

    -- Verificar si el usuario existe en la base de datos
    SET @usuario_existe = (SELECT COUNT(*) FROM mysql.user WHERE user = usuario);
    IF @usuario_existe > 0 THEN
        -- Cambiar la contraseña del usuario
        SET @sql = CONCAT('ALTER USER ', QUOTE(usuario), '@''%'' IDENTIFIED BY ', QUOTE(nueva_contrasenia));
        PREPARE stmt FROM @sql;
        EXECUTE stmt;
        DEALLOCATE PREPARE stmt;
        COMMIT;
    ELSE
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'El usuario no existe';
    END IF;
END;
//
DELIMITER ;

