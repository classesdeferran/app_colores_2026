DROP DATABASE IF EXISTS colores;
CREATE DATABASE IF NOT EXISTS colores;
USE colores;

CREATE TABLE IF NOT EXISTS colores (
	id_color INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
	nombre_color VARCHAR(100) NOT NULL UNIQUE
);

CREATE TABLE IF NOT EXISTS users(
id_user INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
nombre_user VARCHAR(100) NOT NULL UNIQUE,
id_color INT NOT NULL
);

INSERT INTO colores(nombre_color) VALUES ('rojo'),('verde'),('azul');
INSERT INTO users(nombre_user, id_color) VALUES
('Batman', (SELECT id_color FROM colores WHERE nombre_color = 'azul')),
('Robin', (SELECT id_color FROM colores WHERE nombre_color = 'verde'));

UPDATE colores set nombre_color = "#FF0000" WHERE nombre_color = 'rojo';
UPDATE colores set nombre_color = "#00FF00" WHERE nombre_color = 'verde';
UPDATE colores set nombre_color = "#0000FF" WHERE nombre_color = 'azul';

CREATE USER IF NOT EXISTS 'colores_admin'@'localhost' 
IDENTIFIED BY 'admin';

GRANT ALL PRIVILEGES ON colores.* TO 'colores_admin'@'localhost';

ALTER TABLE users ADD COLUMN pass VARCHAR(50);
UPDATE users SET pass = '1234' WHERE id_user = 1;
UPDATE users SET pass = 'abcd' WHERE id_user = 2;

SELECT * FROM users WHERE nombre_user = "Cleopatra";
SELECT * FROM users WHERE nombre_user = "Batman";