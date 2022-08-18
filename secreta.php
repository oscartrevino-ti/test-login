<?php
# Si no entiendes el código, primero mira a login.php

# Iniciar sesión para usar $_SESSION
session_start();

# Y ahora leer si NO hay algo llamado correo en la sesión,
# usando empty (vacío, ¿está vacío?)
# Recomiendo: https://parzibyte.me/blog/2018/08/09/isset-vs-empty-en-php/
if (empty($_SESSION["correo"])) {
    # Lo redireccionamos al formulario de inicio de sesión
    header("Location: login.html");
    # Y salimos del script
    exit();
}

# No hace falta un else, pues si el usuario no se loguea, todo lo de abajo no se ejecuta
echo "Soy un mensaje secreto.";
# Podemos recuperar datos de la sesión
echo "<br>Sé que tu correo es: <strong>" . $_SESSION["correo"] . "</strong>";
?>
<!-- Por cierto, también se puede usar HTML como en todos los scripts de PHP-->
<p>
    Hola mundo, soy un párrafo HTML que solamente pueden ver los usuarios logueados
</p>
<!-- Y aprovechando, le indicamos al usuario un enlace para salir-->
<a href="logout.php">Cerrar sesión</a>
