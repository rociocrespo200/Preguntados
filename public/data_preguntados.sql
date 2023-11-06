CREATE DATABASE IF NOT EXISTS preguntados;
USE preguntados;

CREATE TABLE IF NOT exists Rol(
	id INT PRIMARY KEY auto_increment,
	tipo VARCHAR(50)
);

INSERT INTO Rol (tipo)
VALUES 
("USUARIO"), ("EDITOR"), ("ADMINISTRADOR");

CREATE TABLE usuario (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(255) NOT NULL,
    apellido VARCHAR(255) NOT NULL,
    anio_nacimiento INT NOT NULL,
    genero VARCHAR(255) NOT NULL,
    pais VARCHAR(255) NOT NULL,
    ciudad VARCHAR(255) NOT NULL,
    mail VARCHAR(255) NOT NULL,
    usuario VARCHAR(255) NOT NULL,
    clave VARCHAR(255) NOT NULL,
    puntos INT default 0,
    nivel INT default 1,
    id_rol int default 1,
    foto_perfil VARCHAR(255) NOT NULL,
    foto_qr VARCHAR(255),
    token VARCHAR(255),
	validado bool default false,
    foreign key (id_rol) references Rol(id)
);


-- SELECT ROW_NUMBER() OVER (ORDER BY U.puntos DESC) AS 'Posicion', 
--        U.id as 'id', 
--        U.usuario, 
--        U.nivel, 
--        U.puntos, 
--        COUNT(*) AS 'CantidaddePartidas', 
--        SUM(P.preguntasContestadas) AS 'CantidaddePreguntas' 
-- FROM usuario U 
-- JOIN partida P ON U.id = P.id_usuario 
-- GROUP BY U.id, U.usuario, U.nivel, U.puntos 
-- ORDER BY U.puntos DESC;


                                            
INSERT INTO usuario (nombre, apellido, anio_nacimiento, genero, pais, ciudad, mail, usuario, clave, foto_perfil, puntos, nivel)
VALUES
	('Rocio', 'Crespo', 2000, 'femenino',  'Argentina', 'Buenos aires', 'rocio@gmail.com', 'rocio123', '1234', 'profile.png',10700, 9),
     ('Jane', 'Smith', 1985,'masculino', 'Reino Unido', 'Londres', 'janesmith@example.com', 'janesmith456', 'contrasena456', 'profile.png',7820, 5),
    ('Juan', 'Pérez', 1988, 'masculino','España', 'Madrid', 'juanperez@example.com', 'juanperez789', 'clave789', 'profile.png',100, 1);
INSERT INTO usuario (nombre, apellido, anio_nacimiento,genero,  pais, ciudad, mail, usuario, clave, foto_perfil, id_rol)
VALUES
	('Rocio', 'Crespo', 2000, 'femenino', 'Argentina', 'Buenos aires', 'belen@gmail.com', 'belen123', '1234', 'profile.png',2);

CREATE TABLE IF NOT EXISTS Dificultad 
(
	id INT PRIMARY KEY auto_increment,
    valor INT,
    dificultad VARCHAR(50)
);
-- Insertar niveles de dificultad de ejemplo
INSERT INTO Dificultad (dificultad, valor) VALUES
    ('Fácil', 10),
    ('Moderado', 25),
    ('Difícil', 50);
    
CREATE TABLE partida (
    id INT AUTO_INCREMENT PRIMARY KEY,
    fecha DATETIME DEFAULT CURRENT_TIMESTAMP,
    id_usuario INT,
    preguntasContestadas INT default 1,
    puntos INT DEFAULT 0,
    id_dificultad INT DEFAULT 1,
    FOREIGN KEY (id_usuario) REFERENCES Usuario(id),
    FOREIGN KEY (id_dificultad) REFERENCES Dificultad(id)
);

INSERT INTO partida (id_usuario, preguntasContestadas) VALUES (1 ,4),(2, 5),(3, 8),(4, 2);
INSERT INTO partida (id_usuario, preguntasContestadas) VALUES (3 ,4),(2, 5),(3, 8),(1, 2);
INSERT INTO partida (id_usuario, preguntasContestadas) VALUES (1 ,7),(1, 5),(2, 8),(4, 12);
INSERT INTO partida (id_usuario, preguntasContestadas) VALUES (3 ,4),(2, 5),(1, 18),(4, 5);

-- SELECT *
-- FROM partida P
-- JOIN Dificultad D ON P.id_dificultad = D.id;

/*
		--------- ACTUALIZAR PUNTOS ---------
UPDATE `preguntados`.`partida` SET `puntos` = '122' WHERE (`id` = '1');

		--------- ACTUALIZAR preguntasContestadas ---------
UPDATE `preguntados`.`partida` SET `preguntasContestadas` = '10' WHERE (`id` = '1');

		--------- ACTUALIZAR dificultad ---------
UPDATE `preguntados`.`partida` SET `dificultad` = 'Dificil' WHERE (`id` = '1');
*/

-- SELECT * FROM Usuario;

-- SELECT
--     P.id AS Pregunta_ID,
--     P.pregunta AS Pregunta,
--     MAX(CASE WHEN R.esCorrecta = 1 THEN R.respuesta END) AS Respuesta_correcta,
--     GROUP_CONCAT(CASE WHEN R.esCorrecta = 0 THEN R.respuesta END ORDER BY R.id) AS Respuestas_incorrectas,
--     C.categoria AS Categoria,
--     D.dificultad AS Nivel_de_Dificultad
-- FROM
--     Pregunta AS P
-- JOIN Respuesta AS R ON P.id = R.id_pregunta
-- JOIN Categoria AS C ON P.id_categoria = C.id
-- JOIN Dificultad AS D ON P.id_dificultad = D.id
-- GROUP BY P.id, P.pregunta, C.categoria, D.dificultad;


CREATE TABLE IF NOT EXISTS Categoria 
(
	id INT PRIMARY KEY auto_increment,
    categoria VARCHAR(50),
    imagen VARCHAR(50)
    
);

CREATE TABLE IF NOT EXISTS Pregunta (
id INT auto_increment PRIMARY KEY,
pregunta VARCHAR(200),
id_categoria INT,
id_dificultad INT,
habilitada bool default true,
FOREIGN KEY (id_categoria) REFERENCES Categoria(id),
FOREIGN KEY (id_dificultad) REFERENCES Dificultad(id)
);

CREATE TABLE IF NOT EXISTS Respuesta (
id INT auto_increment PRIMARY KEY,
respuesta VARCHAR(200),
esCorrecta bool,
id_pregunta INT,
FOREIGN KEY (id_pregunta) REFERENCES Pregunta(id)
);


CREATE TABLE partida_respuestas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_partida INT NOT NULL,
    id_respuesta INT NOT NULL,
	fecha DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_partida) REFERENCES partida(id),
    FOREIGN KEY (id_respuesta) REFERENCES respuesta(id)
);

CREATE TABLE partida_preguntas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_partida INT NOT NULL,
    id_pregunta INT NOT NULL,
	fecha DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_partida) REFERENCES partida(id),
    FOREIGN KEY (id_pregunta) REFERENCES pregunta(id)
);

CREATE TABLE reporte (
	    id INT AUTO_INCREMENT PRIMARY KEY,
        fecha DATETIME DEFAULT CURRENT_TIMESTAMP,
		id_pregunta INT NOT NULL,
		motivo VARCHAR(255) NOT NULL,
        id_usuario INT NOT NULL,
		FOREIGN KEY (id_pregunta) REFERENCES pregunta(id),
        FOREIGN KEY (id_usuario) REFERENCES usuario(id)
);



CREATE TABLE sugerencia (
	id INT auto_increment PRIMARY KEY,
	fecha DATETIME DEFAULT CURRENT_TIMESTAMP,
	id_usuario INT NOT NULL,
	pregunta VARCHAR(255) NOT NULL,
	id_categoria INT NOT NULL,
	id_dificultad INT NOT NULL,
	respuestaCorrecta VARCHAR(255) NOT NULL,
    respuestaIncorrecta1 VARCHAR(255) NOT NULL,
	respuestaIncorrecta2 VARCHAR(255),
    respuestaIncorrecta3 VARCHAR(255),
	FOREIGN KEY (id_categoria) REFERENCES Categoria(id),
	FOREIGN KEY (id_dificultad) REFERENCES Dificultad(id),
    FOREIGN KEY (id_usuario) REFERENCES usuario(id)
);



-- REGISTROS

-- Insertar categorías de ejemplo
INSERT INTO Categoria (categoria, imagen) VALUES
    ('Historia', "icon_historia.png"),
    ('Geografía', "icon_geografia.png"),
    ('Ciencia', "icon_ciencias.png"),
    ('Arte', "icon_arte.png"),
    ('Deportes', "icon_deportes.png"),
    ('Entretenimiento', "icon_entretenimiento.png");


-- Insertar 20 preguntas ficticias y sus respuestas
-- Pregunta 1
INSERT INTO Pregunta (id, pregunta, id_categoria, id_dificultad) VALUES
    (1, '¿Quién fue el primer presidente de Estados Unidos?', 1, 1);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (1, 'George Washington', 1, 1);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (2, 'Thomas Jefferson', 0, 1);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (3, 'John Adams', 0, 1);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (4, 'Abraham Lincoln', 0, 1);

-- Pregunta 2
INSERT INTO Pregunta (id, pregunta, id_categoria, id_dificultad) VALUES
    (2, '¿Cuál es el río más largo del mundo?', 2, 2);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (5, 'El río Nilo', 1, 2);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (6, 'El río Amazonas', 0, 2);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (7, 'El río Yangtsé', 0, 2);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (8, 'El río Misisipi', 0, 2);

-- Pregunta 3
INSERT INTO Pregunta (id, pregunta, id_categoria, id_dificultad) VALUES
    (3, '¿Cuál es el símbolo químico del oxígeno?', 3, 1);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (9, 'O', 1, 3);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (10, 'Ox', 0, 3);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (11, 'O2', 0, 3);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (12, 'O3', 0, 3);

-- Pregunta 4
INSERT INTO Pregunta (id, pregunta, id_categoria, id_dificultad) VALUES
    (4, '¿Quién pintó la Mona Lisa?', 4, 2);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (13, 'Leonardo da Vinci', 1, 4);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (14, 'Pablo Picasso', 0, 4);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (15, 'Vincent van Gogh', 0, 4);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (16, 'Michelangelo', 0, 4);

-- Pregunta 5
INSERT INTO Pregunta (id, pregunta, id_categoria, id_dificultad) VALUES
    (5, '¿En qué deporte se utiliza una raqueta?', 5, 1);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (17, 'Tenis', 1, 5);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (18, 'Fútbol', 0, 5);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (19, 'Baloncesto', 0, 5);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (20, 'Voleibol', 0, 5);

-- Pregunta 6
INSERT INTO Pregunta (id, pregunta, id_categoria, id_dificultad) VALUES
    (6, '¿En qué año ocurrió la Revolución Rusa?', 1, 2);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (21, '1917', 1, 6);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (22, '1905', 0, 6);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (23, '1920', 0, 6);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (24, '1933', 0, 6);

-- Pregunta 7
INSERT INTO Pregunta (id, pregunta, id_categoria, id_dificultad) VALUES
    (7, '¿Cuál es el país más grande del mundo en términos de área?', 2, 1);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (25, 'Rusia', 1, 7);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (26, 'China', 0, 7);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (27, 'Estados Unidos', 0, 7);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (28, 'Canadá', 0, 7);

-- Pregunta 8
INSERT INTO Pregunta (id, pregunta, id_categoria, id_dificultad) VALUES
    (8, '¿Cuál es el planeta más cercano al sol?', 3, 1);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (29, 'Mercurio', 1, 8);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (30, 'Venus', 0, 8);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (31, 'Tierra', 0, 8);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (32, 'Marte', 0, 8);

-- Pregunta 9
INSERT INTO Pregunta (id, pregunta, id_categoria, id_dificultad) VALUES
    (9, '¿Quién escribió la obra "La Odisea"?', 4, 2);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (33, 'Homero', 1, 9);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (34, 'Virgilio', 0, 9);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (35, 'Dante Alighieri', 0, 9);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (36, 'Shakespeare', 0, 9);

-- Pregunta 10
INSERT INTO Pregunta (id, pregunta, id_categoria, id_dificultad) VALUES
    (10, '¿En qué deporte se utiliza un octágono como ring?', 5, 3);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (37, 'Artes marciales mixtas (MMA)', 1, 10);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (38, 'Boxeo', 0, 10);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (39, 'Lucha libre', 0, 10);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (40, 'Sumo', 0, 10);

-- Pregunta 11
INSERT INTO Pregunta (id, pregunta, id_categoria, id_dificultad) VALUES
    (11, '¿Cuál es el gas más abundante en la atmósfera de la Tierra?', 3, 2);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (41, 'Nitrógeno', 1, 11);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (42, 'Oxígeno', 0, 11);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (43, 'Dióxido de carbono', 0, 11);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (44, 'Argón', 0, 11);

-- Pregunta 12
INSERT INTO Pregunta (id, pregunta, id_categoria, id_dificultad) VALUES
    (12, '¿En qué año se fundó la Organización de las Naciones Unidas (ONU)?', 2, 2);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (45, '1945', 1, 12);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (46, '1950', 0, 12);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (47, '1939', 0, 12);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (48, '1961', 0, 12);

-- Pregunta 13
INSERT INTO Pregunta (id, pregunta, id_categoria, id_dificultad) VALUES
    (13, '¿Quién escribió la obra "1984"?', 4, 2);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (49, 'George Orwell', 1, 13);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (50, 'Aldous Huxley', 0, 13);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (51, 'Ray Bradbury', 0, 13);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (52, 'Philip K. Dick', 0, 13);

-- Pregunta 14
INSERT INTO Pregunta (id, pregunta, id_categoria, id_dificultad) VALUES
    (14, '¿Cuál es la fórmula química del agua?', 3, 1);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (53, 'H2O', 1, 14);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (54, 'CO2', 0, 14);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (55, 'O2', 0, 14);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (56, 'N2', 0, 14);

-- Pregunta 15
INSERT INTO Pregunta (id, pregunta, id_categoria, id_dificultad) VALUES
    (15, '¿Cuál es el deporte más popular en Brasil?', 5, 1);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (57, 'Fútbol', 1, 15);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (58, 'Voleibol', 0, 15);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (59, 'Baloncesto', 0, 15);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (60, 'Tenis', 0, 15);


-- Pregunta 16
INSERT INTO Pregunta (id, pregunta, id_categoria, id_dificultad) VALUES
    (16, '¿Cuál es la capital de Japón?', 2, 1);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (61, 'Tokio', 1, 16);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (62, 'Kioto', 0, 16);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (63, 'Osaka', 0, 16);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (64, 'Nagoya', 0, 16);

-- Pregunta 17
INSERT INTO Pregunta (id, pregunta, id_categoria, id_dificultad) VALUES
    (17, '¿Cuál es el planeta más grande del sistema solar?', 3, 2);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (65, 'Júpiter', 1, 17);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (66, 'Saturno', 0, 17);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (67, 'Neptuno', 0, 17);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (68, 'Urano', 0, 17);

-- Pregunta 18
INSERT INTO Pregunta (id, pregunta, id_categoria, id_dificultad) VALUES
    (18, '¿Quién escribió "El gran Gatsby"?', 4, 2);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (69, 'F. Scott Fitzgerald', 1, 18);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (70, 'Ernest Hemingway', 0, 18);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (71, 'John Steinbeck', 0, 18);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (72, 'William Faulkner', 0, 18);

-- Pregunta 19
INSERT INTO Pregunta (id, pregunta, id_categoria, id_dificultad) VALUES
    (19, '¿Cuál es el metal más abundante en la corteza terrestre?', 3, 1);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (73, 'Aluminio', 1, 19);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (74, 'Hierro', 0, 19);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (75, 'Oro', 0, 19);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (76, 'Plata', 0, 19);

-- Pregunta 20
INSERT INTO Pregunta (id, pregunta, id_categoria, id_dificultad) VALUES
    (20, '¿Quién es conocido como el "Rey del Pop"?', 5, 2);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (77, 'Michael Jackson', 1, 20);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (78, 'Elvis Presley', 0, 20);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (79, 'Prince', 0, 20);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (80, 'Madonna', 0, 20);

-- Pregunta 21
INSERT INTO Pregunta (id, pregunta, id_categoria, id_dificultad) VALUES
    (21, '¿Cuál es el símbolo químico del hidrógeno?', 3, 1);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (81, 'H', 1, 21);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (82, 'He', 0, 21);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (83, 'O', 0, 21);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (84, 'N', 0, 21);

-- Pregunta 22
INSERT INTO Pregunta (id, pregunta, id_categoria, id_dificultad) VALUES
    (22, '¿En qué año se produjo la caída del Muro de Berlín?', 1, 2);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (85, '1989', 1, 22);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (86, '1991', 0, 22);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (87, '1995', 0, 22);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (88, '1997', 0, 22);

-- Pregunta 23
INSERT INTO Pregunta (id, pregunta, id_categoria, id_dificultad) VALUES
    (23, '¿Quién escribió "Don Quijote de la Mancha"?', 4, 2);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (89, 'Miguel de Cervantes', 1, 23);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (90, 'García Márquez', 0, 23);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (91, 'Pablo Neruda', 0, 23);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (92, 'William Shakespeare', 0, 23);

-- Pregunta 24
INSERT INTO Pregunta (id, pregunta, id_categoria, id_dificultad) VALUES
    (24, '¿Cuál es el océano más grande del mundo?', 2, 1);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (93, 'Océano Pacífico', 1, 24);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (94, 'Océano Atlántico', 0, 24);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (95, 'Océano Índico', 0, 24);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (96, 'Océano Ártico', 0, 24);

-- Pregunta 25
INSERT INTO Pregunta (id, pregunta, id_categoria, id_dificultad) VALUES
    (25, '¿Cuál es el metal líquido a temperatura ambiente?', 3, 2);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (97, 'Mercurio', 1, 25);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (98, 'Plomo', 0, 25);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (99, 'Hierro', 0, 25);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (100, 'Aluminio', 0, 25);
-- Pregunta 26
INSERT INTO Pregunta (id, pregunta, id_categoria, id_dificultad) VALUES
    (26, '¿Cuál es el océano más profundo del mundo?', 2, 3);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (101, 'Océano Pacífico', 0, 26);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (102, 'Océano Atlántico', 0, 26);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (103, 'Océano Índico', 0, 26);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (104, 'Océano Ártico', 0, 26);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (105, 'Océano Pacífico', 1, 26);

-- Pregunta 27
INSERT INTO Pregunta (id, pregunta, id_categoria, id_dificultad) VALUES
    (27, '¿Cuál es el país más poblado del mundo?', 2, 2);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (106, 'China', 1, 27);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (107, 'India', 0, 27);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (108, 'Estados Unidos', 0, 27);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (109, 'Brasil', 0, 27);

-- Pregunta 28
INSERT INTO Pregunta (id, pregunta, id_categoria, id_dificultad) VALUES
    (28, '¿Quién fue el primer hombre en la luna?', 3, 1);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (110, 'Neil Armstrong', 1, 28);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (111, 'Buzz Aldrin', 0, 28);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (112, 'Yuri Gagarin', 0, 28);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (113, 'John Glenn', 0, 28);

-- Pregunta 29
INSERT INTO Pregunta (id, pregunta, id_categoria, id_dificultad) VALUES
    (29, '¿Cuál es el río más largo de América del Norte?', 2, 2);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (114, 'Río Misisipi', 0, 29);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (115, 'Río Colorado', 0, 29);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (116, 'Río San Lorenzo', 0, 29);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (117, 'Río Missouri', 1, 29);

-- Pregunta 30
INSERT INTO Pregunta (id, pregunta, id_categoria, id_dificultad) VALUES
    (30, '¿Cuál es el elemento químico más abundante en el universo?', 3, 3);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (118, 'Hidrógeno', 1, 30);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (119, 'Oxígeno', 0, 30);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (120, 'Helio', 0, 30);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (121, 'Carbono', 0, 30);

-- Pregunta 31
INSERT INTO Pregunta (id, pregunta, id_categoria, id_dificultad) VALUES
    (31, '¿Cuál es el animal más grande del mundo?', 3, 2);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (122, 'Ballena azul', 1, 31);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (123, 'Elefante', 0, 31);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (124, 'Jirafa', 0, 31);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (125, 'Tiburón', 0, 31);

-- Pregunta 32
INSERT INTO Pregunta (id, pregunta, id_categoria, id_dificultad) VALUES
    (32, '¿En qué año se fundó la Organización de las Naciones Unidas (ONU)?', 2, 2);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (126, '1945', 1, 32);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (127, '1950', 0, 32);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (128, '1939', 0, 32);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (129, '1961', 0, 32);

-- Pregunta 33
INSERT INTO Pregunta (id, pregunta, id_categoria, id_dificultad) VALUES
    (33, '¿Cuál es el tercer planeta del sistema solar?', 3, 1);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (130, 'Tierra', 1, 33);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (131, 'Marte', 0, 33);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (132, 'Venus', 0, 33);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (133, 'Júpiter', 0, 33);

-- Pregunta 34
INSERT INTO Pregunta (id, pregunta, id_categoria, id_dificultad) VALUES
    (34, '¿Cuál es la capital de Italia?', 2, 1);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (134, 'Roma', 1, 34);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (135, 'Milán', 0, 34);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (136, 'Florencia', 0, 34);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (137, 'Venecia', 0, 34);

-- Pregunta 35
INSERT INTO Pregunta (id, pregunta, id_categoria, id_dificultad) VALUES
    (35, '¿Cuál es el deporte más popular en la India?', 5, 2);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (138, 'Críquet', 1, 35);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (139, 'Fútbol', 0, 35);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (140, 'Hockey', 0, 35);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (141, 'Tenis', 0, 35);

-- Pregunta 36
INSERT INTO Pregunta (id, pregunta, id_categoria, id_dificultad) VALUES
    (36, '¿Cuál es el gas más abundante en la atmósfera de la Tierra?', 3, 1);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (142, 'Nitrógeno', 1, 36);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (143, 'Oxígeno', 0, 36);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (144, 'Dióxido de carbono', 0, 36);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (145, 'Argón', 0, 36);

-- Pregunta 37
INSERT INTO Pregunta (id, pregunta, id_categoria, id_dificultad) VALUES
    (37, '¿Cuál es el país más grande del mundo en términos de área?', 2, 1);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (146, 'Rusia', 1, 37);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (147, 'China', 0, 37);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (148, 'Estados Unidos', 0, 37);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (149, 'Canadá', 0, 37);

-- Pregunta 38
INSERT INTO Pregunta (id, pregunta, id_categoria, id_dificultad) VALUES
    (38, '¿Cuál es el planeta más cercano al sol?', 3, 1);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (150, 'Mercurio', 1, 38);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (151, 'Venus', 0, 38);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (152, 'Tierra', 0, 38);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (153, 'Marte', 0, 38);

-- Pregunta 39
INSERT INTO Pregunta (id, pregunta, id_categoria, id_dificultad) VALUES
    (39, '¿Quién escribió la obra "La Odisea"?', 4, 2);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (154, 'Homero', 1, 39);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (155, 'Virgilio', 0, 39);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (156, 'Dante Alighieri', 0, 39);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (157, 'Shakespeare', 0, 39);

-- Pregunta 40
INSERT INTO Pregunta (id, pregunta, id_categoria, id_dificultad) VALUES
    (40, '¿En qué deporte se utiliza un octágono como ring?', 5, 3);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (158, 'Artes marciales mixtas (MMA)', 1, 40);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (159, 'Boxeo', 0, 40);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (160, 'Lucha libre', 0, 40);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (161, 'Sumo', 0, 40);

-- Pregunta 41
INSERT INTO Pregunta (id, pregunta, id_categoria, id_dificultad) VALUES
    (41, '¿Cuál es el río más largo del mundo?', 2, 2);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (162, 'Río Amazonas', 1, 41);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (163, 'Río Nilo', 0, 41);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (164, 'Río Mississippi', 0, 41);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (165, 'Río Yangtsé', 0, 41);

-- Pregunta 42
INSERT INTO Pregunta (id, pregunta, id_categoria, id_dificultad) VALUES
    (42, '¿Cuál es el compuesto químico que forma la capa de ozono en la atmósfera?', 3, 3);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (166, 'Clorofluorocarbonos (CFC)', 1, 42);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (167, 'Dióxido de carbono (CO2)', 0, 42);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (168, 'Metano (CH4)', 0, 42);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (169, 'Ozono (O3)', 0, 42);

-- Pregunta 43
INSERT INTO Pregunta (id, pregunta, id_categoria, id_dificultad) VALUES
    (43, '¿En qué año se proclamó la independencia de Estados Unidos?', 2, 2);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (170, '1776', 1, 43);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (171, '1789', 0, 43);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (172, '1800', 0, 43);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (173, '1824', 0, 43);

-- Pregunta 44
INSERT INTO Pregunta (id, pregunta, id_categoria, id_dificultad) VALUES
    (44, '¿Cuál es la moneda oficial de Japón?', 2, 1);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (174, 'Yen', 1, 44);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (175, 'Dólar', 0, 44);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (176, 'Euro', 0, 44);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (177, 'Won', 0, 44);

-- Pregunta 45
INSERT INTO Pregunta (id, pregunta, id_categoria, id_dificultad) VALUES
    (45, '¿Quién escribió la obra "Cien años de soledad"?', 4, 2);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (178, 'Gabriel García Márquez', 1, 45);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (179, 'Pablo Neruda', 0, 45);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (180, 'Jorge Luis Borges', 0, 45);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (181, 'Mario Vargas Llosa', 0, 45);

-- Pregunta 46
INSERT INTO Pregunta (id, pregunta, id_categoria, id_dificultad) VALUES
    (46, '¿Cuál es el símbolo químico del oxígeno?', 3, 1);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (182, 'O', 1, 46);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (183, 'H', 0, 46);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (184, 'He', 0, 46);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (185, 'N', 0, 46);

-- Pregunta 47
INSERT INTO Pregunta (id, pregunta, id_categoria, id_dificultad) VALUES
    (47, '¿Cuál es el monumento más famoso de Egipto?', 1, 2);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (186, 'Las Pirámides de Giza', 1, 47);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (187, 'La Esfinge de Giza', 0, 47);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (188, 'El Templo de Karnak', 0, 47);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (189, 'El Valle de los Reyes', 0, 47);

-- Pregunta 48
INSERT INTO Pregunta (id, pregunta, id_categoria, id_dificultad) VALUES
    (48, '¿Cuál es el planeta más pequeño del sistema solar?', 3, 1);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (190, 'Mercurio', 1, 48);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (191, 'Venus', 0, 48);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (192, 'Marte', 0, 48);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (193, 'Tierra', 0, 48);

-- Pregunta 49
INSERT INTO Pregunta (id, pregunta, id_categoria, id_dificultad) VALUES
    (49, '¿Cuál es la montaña más alta del mundo?', 2, 2);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (194, 'Monte Everest', 1, 49);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (195, 'Monte Kilimanjaro', 0, 49);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (196, 'Monte Aconcagua', 0, 49);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (197, 'Monte McKinley (Denali)', 0, 49);

-- Pregunta 50
INSERT INTO Pregunta (id, pregunta, id_categoria, id_dificultad) VALUES
    (50, '¿Cuál es el estado más grande de Estados Unidos por área?', 1, 2);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (198, 'Alaska', 1, 50);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (199, 'Texas', 0, 50);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (200, 'California', 0, 50);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (201, 'Montana', 0, 50);

-- Pregunta 51
INSERT INTO Pregunta (id, pregunta, id_categoria, id_dificultad) VALUES
    (51, '¿Cuál es el océano más cálido del mundo?', 2, 3);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (202, 'Océano Índico', 1, 51);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (203, 'Océano Atlántico', 0, 51);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (204, 'Océano Pacífico', 0, 51);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (205, 'Océano Ártico', 0, 51);

-- Pregunta 52
INSERT INTO Pregunta (id, pregunta, id_categoria, id_dificultad) VALUES
    (52, '¿Quién pintó la Mona Lisa?', 4, 2);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (206, 'Leonardo da Vinci', 1, 52);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (207, 'Pablo Picasso', 0, 52);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (208, 'Vincent van Gogh', 0, 52);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (209, 'Michelangelo', 0, 52);

-- Pregunta 53
INSERT INTO Pregunta (id, pregunta, id_categoria, id_dificultad) VALUES
    (53, '¿Cuál es el elemento químico más denso?', 3, 3);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (210, 'Osmio', 1, 53);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (211, 'Platino', 0, 53);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (212, 'Iridio', 0, 53);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (213, 'Plomo', 0, 53);

-- Pregunta 54
INSERT INTO Pregunta (id, pregunta, id_categoria, id_dificultad) VALUES
    (54, '¿Cuál es el país más grande de América del Sur?', 1, 1);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (214, 'Brasil', 1, 54);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (215, 'Argentina', 0, 54);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (216, 'Perú', 0, 54);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (217, 'Colombia', 0, 54);

-- Pregunta 55
INSERT INTO Pregunta (id, pregunta, id_categoria, id_dificultad) VALUES
    (55, '¿Cuál es el planeta más alejado del sol en el sistema solar?', 3, 2);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (218, 'Neptuno', 1, 55);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (219, 'Urano', 0, 55);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (220, 'Plutón', 0, 55);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (221, 'Saturno', 0, 55);

-- Pregunta 56
INSERT INTO Pregunta (id, pregunta, id_categoria, id_dificultad) VALUES
    (56, '¿En qué año se llevó a cabo la Revolución Rusa?', 2, 2);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (222, '1917', 1, 56);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (223, '1905', 0, 56);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (224, '1923', 0, 56);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (225, '1930', 0, 56);

-- Pregunta 57
INSERT INTO Pregunta (id, pregunta, id_categoria, id_dificultad) VALUES
    (57, '¿Cuál es el gas más abundante en la atmósfera de Marte?', 3, 2);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (226, 'Dióxido de carbono (CO2)', 1, 57);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (227, 'Nitrógeno', 0, 57);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (228, 'Oxígeno', 0, 57);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (229, 'Hidrógeno', 0, 57);

-- Pregunta 58
INSERT INTO Pregunta (id, pregunta, id_categoria, id_dificultad) VALUES
    (58, '¿Cuál es el animal más rápido del mundo?', 3, 2);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (230, 'Guepardo', 1, 58);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (231, 'Peregrino', 0, 58);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (232, 'León', 0, 58);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (233, 'Águila', 0, 58);

-- Pregunta 59
INSERT INTO Pregunta (id, pregunta, id_categoria, id_dificultad) VALUES
    (59, '¿Cuál es el instrumento musical más grande de la familia de las cuerdas?', 5, 3);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (234, 'Contrabajo', 1, 59);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (235, 'Violín', 0, 59);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (236, 'Viola', 0, 59);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (237, 'Cello', 0, 59);

-- Pregunta 60
INSERT INTO Pregunta (id, pregunta, id_categoria, id_dificultad) VALUES
    (60, '¿Quién escribió la novela "Don Quijote de la Mancha"?', 4, 1);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (238, 'Miguel de Cervantes', 1, 60);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (239, 'Federico García Lorca', 0, 60);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (240, 'Lope de Vega', 0, 60);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (241, 'Gustavo Adolfo Bécquer', 0, 60);

-- Pregunta 61 (Verdadero o Falso)
INSERT INTO Pregunta (id, pregunta, id_categoria, id_dificultad) VALUES
    (61, 'El agua hierve a 100 grados Celsius a nivel del mar.', 3, 2);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (242, 'Verdadero', 1, 61);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (243, 'Falso', 0, 61);

-- Pregunta 62 (Verdadero o Falso)
INSERT INTO Pregunta (id, pregunta, id_categoria, id_dificultad) VALUES
    (62, 'La Mona Lisa fue pintada por Pablo Picasso.', 4, 2);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (244, 'Falso', 1, 62);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (245, 'Verdadero', 0, 62);

-- Pregunta 63 (Verdadero o Falso)
INSERT INTO Pregunta (id, pregunta, id_categoria, id_dificultad) VALUES
    (63, 'El Sol es un planeta del sistema solar.', 3, 2);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (246, 'Falso', 1, 63);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (247, 'Verdadero', 0, 63);

-- Pregunta 64 (Verdadero o Falso)
INSERT INTO Pregunta (id, pregunta, id_categoria, id_dificultad) VALUES
    (64, 'El idioma chino mandarín utiliza el alfabeto latino.', 4, 2);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (248, 'Falso', 1, 64);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (249, 'Verdadero', 0, 64);

-- Pregunta 65 (Verdadero o Falso)
INSERT INTO Pregunta (id, pregunta, id_categoria, id_dificultad) VALUES
    (65, 'El río Nilo es el más largo del mundo.', 2, 2);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (250, 'Falso', 1, 65);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (251, 'Verdadero', 0, 65);

-- Pregunta 66 (Verdadero o Falso)
INSERT INTO Pregunta (id, pregunta, id_categoria, id_dificultad) VALUES
    (66, 'Los seres humanos tienen tres ojos.', 3, 3);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (252, 'Falso', 1, 66);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (253, 'Verdadero', 0, 66);

-- Pregunta 67 (Verdadero o Falso)
INSERT INTO Pregunta (id, pregunta, id_categoria, id_dificultad) VALUES
    (67, 'El símbolo químico del oro es "Go".', 3, 3);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (254, 'Falso', 1, 67);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (255, 'Verdadero', 0, 67);

-- Pregunta 68 (Verdadero o Falso)
INSERT INTO Pregunta (id, pregunta, id_categoria, id_dificultad) VALUES
    (68, 'La Gran Muralla China es visible desde el espacio.', 1, 2);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (256, 'Falso', 1, 68);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (257, 'Verdadero', 0, 68);

-- Pregunta 69 (Verdadero o Falso)
INSERT INTO Pregunta (id, pregunta, id_categoria, id_dificultad) VALUES
    (69, 'El símbolo químico del hierro es "Fe".', 3, 1);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (258, 'Verdadero', 1, 69);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (259, 'Falso', 0, 69);

-- Pregunta 70 (Verdadero o Falso)
INSERT INTO Pregunta (id, pregunta, id_categoria, id_dificultad) VALUES
    (70, 'La Tierra es plana.', 3, 3);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (260, 'Falso', 1, 70);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (261, 'Verdadero', 0, 70);

-- Pregunta 71 (Verdadero o Falso)
INSERT INTO Pregunta (id, pregunta, id_categoria, id_dificultad) VALUES
    (71, 'El Sol gira alrededor de la Tierra.', 3, 3);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (262, 'Falso', 1, 71);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (263, 'Verdadero', 0, 71);

-- Pregunta 72 (Verdadero o Falso)
INSERT INTO Pregunta (id, pregunta, id_categoria, id_dificultad) VALUES
    (72, 'El ajedrez se juega con tres jugadores.', 5, 3);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (264, 'Falso', 1, 72);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (265, 'Verdadero', 0, 72);

-- Pregunta 73 (Verdadero o Falso)
INSERT INTO Pregunta (id, pregunta, id_categoria, id_dificultad) VALUES
    (73, 'El diamante es el mineral más duro de la Tierra.', 3, 2);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (266, 'Verdadero', 1, 73);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (267, 'Falso', 0, 73);

-- Pregunta 74 (Verdadero o Falso)
INSERT INTO Pregunta (id, pregunta, id_categoria, id_dificultad) VALUES
    (74, 'El alfabeto inglés tiene 26 letras.', 4, 1);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (268, 'Verdadero', 1, 74);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (269, 'Falso', 0, 74);

-- Pregunta 75 (Verdadero o Falso)
INSERT INTO Pregunta (id, pregunta, id_categoria, id_dificultad) VALUES
    (75, 'El Monte Kilimanjaro es la montaña más alta de América del Norte.', 2, 3);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (270, 'Falso', 1, 75);
INSERT INTO Respuesta (id, respuesta, esCorrecta, id_pregunta) VALUES
    (271, 'Verdadero', 0, 75);

INSERT INTO reporte (id_pregunta , motivo, id_usuario) VALUES
    ('2', 'Es incorrecto el enunciado pancho', 4),
    ('12', 'No me gusta la pregunta', 3),
    ('8', 'Muy dificil para el nivel', 1);

SELECT * FROM usuario;