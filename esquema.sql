/*
    Puedes copiar y pegar el contenido
    de este script directamente en la consola 
    de MySQL
*/

CREATE DATABASE IF NOT EXISTS usuarios_login;
USE usuarios_login;
/* Luego crea la tabla de los usuarios */
CREATE TABLE IF NOT EXISTS usuarios(
    id bigint unsigned not null auto_increment,
    correo varchar(255) not null unique, /*UNIQUE para evitar la duplicidad de usuarios*/
    palabra_secreta varchar(255) not null,
    primary key(id)
);

/* Nota: no borres la siguiente línea en blanco */
