<?php

/*
include
include_once
require
require_once
*/

require_once 'conexion.php';

// Definir la query
$sql = 'SELECT u.nombre_user, c.nombre_color FROM users u JOIN colores c USING(id_color);';

// Preparar la respuesta
$respuesta = $pdo->prepare($sql);

// Realizar la petición
$respuesta-> execute();


?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Colores</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <h1>¿Cuál es tu color preferido?</h1>
    </header>
    <main>
        <section>
            <h2>Usuarios</h2>


        </section>
                <section>
            <h2>Dinos tu color preferido</h2>
            <form action="insert.php" method="post">
                <div>
                    <label for="nombre_i">Nombre:</label>
                    <input type="text" id='nombre_i' name='nombre_i' >
                </div>
                <div>
                    <label for="color_i">Tu color preferido:</label>
                    <input type="text" id='color_i' name='color_i' >
                </div>
                <div>
                    <button type="submit">Enviar datos</button>
                    <button type="reset">Borrar formulario</button>
                </div>
            </form>
        </section>

    </main>
</body>
</html>