<?php
/**
 * Algunas funciones que ayudan a la gestión
 * de usuarios y también interactúan con la base de datos
 * @author parzibyte
 */
# Incluir lo de la BD, podría ser con un autoload pero eso es más avanzado
# Mira la explicación de PDO: 
# https://parzibyte.me/blog/2019/02/16/php-pdo-parte-2-iterar-cursor-comprobar-si-elemento-existe/
include_once "base_de_datos.php";

function login($correo, $palabraSecreta)
{
    # Primero obtener usuario...
    $posibleUsuarioRegistrado = obtenerUsuarioPorCorreo($correo);
    if ($posibleUsuarioRegistrado === false) {
        # Si no existe, salimos y regresamos false
        return false;
    }
    # Esto se ejecuta en caso de que exista
    # Comprobar contraseñas
    # Sacar el hash que tenemos en la BD
    $palabraSecretaDeBaseDeDatos = $posibleUsuarioRegistrado->palabra_secreta;
    $coinciden = coincidenPalabrasSecretas($palabraSecreta, $palabraSecretaDeBaseDeDatos);
    # Si no coinciden, salimos de una vez
    if (!$coinciden) {
        return false;
    }

    # En caso de que sí hayan coincidido iniciamos sesión pasando el objeto
    iniciarSesion($posibleUsuarioRegistrado);
    # Y regresamos true ;)
    return true;
}

function usuarioExiste($correo)
{
    $base_de_datos = obtenerBaseDeDatos();
    $sentencia = $base_de_datos->prepare("SELECT correo FROM usuarios WHERE correo = ? LIMIT 1;");
    $sentencia->execute([$correo]);
    return $sentencia->rowCount() > 0;
}

function obtenerUsuarioPorCorreo($correo)
{
    $base_de_datos = obtenerBaseDeDatos();
    $sentencia = $base_de_datos->prepare("SELECT correo, palabra_secreta FROM usuarios WHERE correo = ? LIMIT 1;");
    $sentencia->execute([$correo]);
    return $sentencia->fetchObject();
}

function registrarUsuario($correo, $palabraSecreta)
{
    # NUNCA guardes contraseñas en texto plano
    $palabraSecreta = hashearPalabraSecreta($palabraSecreta);
    $base_de_datos = obtenerBaseDeDatos();
    $sentencia = $base_de_datos->prepare("INSERT INTO usuarios(correo, palabra_secreta) values(?, ?)");
    return $sentencia->execute([$correo, $palabraSecreta]);
}


function iniciarSesion($usuario)
{
    // Se encarga de poner los datos dentro de la sesión
    session_start();
    # Y poner los datos, no recomiendo poner la contraseña
    $_SESSION["correo"] = $usuario->correo;
}

# Para las contraseñas mira lo siguiente
# https://parzibyte.me/blog/2017/11/13/cifrando-comprobando-contrasenas-en-php/

function coincidenPalabrasSecretas($palabraSecreta, $palabraSecretaDeBaseDeDatos)
{
    return password_verify($palabraSecreta, $palabraSecretaDeBaseDeDatos);
}

function hashearPalabraSecreta($palabraSecreta)
{
    return password_hash($palabraSecreta, PASSWORD_BCRYPT);
}
