<?php

session_start();
$error_insert = $_SESSION['error_insert'] ?? '';
$nombre_invalido = $_SESSION['nombre_invalido'] ?? '';
$nombre_existe = $_SESSION['nombre_existe'] ?? false;

// echo "error ".$error_insert;
// echo "<br>";





/*
include
include_once
require
require_once
*/

require_once 'conexion.php';

<<<<<<< HEAD
// 1. Definir la query
$sql = 'SELECT u.nombre_user, c.nombre_color FROM users u JOIN colores c USING(id_color);';

// 2. Preparar la respuesta
$respuesta = $pdo->prepare($sql);

// 3. Realizar la petición
$respuesta-> execute();

// 4. Recuperar los datos de la consulta
$resultado = $respuesta->fetchAll();

// print_r($resultado);


unset($_SESSION['error_insert']);
unset($_SESSION['nombre_invalido']);
=======
// Definir la query
$sql = 'SELECT u.nombre_user, c.nombre_color FROM users u JOIN colores c USING(id_color);';

// Preparar la respuesta
$respuesta = $pdo->prepare($sql);

// Realizar la petición
$respuesta-> execute();

>>>>>>> c28832e60446742cc550af9d8315aab86a456a1e

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
<<<<<<< HEAD
            <?php foreach($resultado as $usuario) : ?>
                <div class="user" style="background-color: <?= $usuario['nombre_color'] ?>" ;>
                    <p>Usuario: <?= $usuario['nombre_user'] ?>, color: <?= $usuario['nombre_color'] ?></p>
                </div>

            <?php endforeach; ?>
=======

>>>>>>> c28832e60446742cc550af9d8315aab86a456a1e

        </section>
                <section>
            <h2>Dinos tu color preferido</h2>
            <form action="insert.php" method="post">
                <div class="div_insert">
                    <div>
                        <label for="nombre_i">Nombre:</label>
                        <input type="text" id='nombre_i' name='nombre_i' 
                        value = <?= $nombre_invalido ?>>
                    </div>
                    <div>
                        <label for="color_i">Color:</label>
                        <input type="color" id='color_i' name='color_i' >
                    </div>
                </div>
                <div class="error_insert">
                    <?php if($error_insert == "true") : ?>
                    <p>Error en los datos</p>
                    <?php endif; ?>
                    <?php if($nombre_existe) : ?>
                    <p>El usuario ya existe</p>
                    <?php endif; ?>
                </div>

                <div class="buttons">
                    <button type="submit">Enviar datos</button>
                    <button type="reset">Borrar formulario</button>
                </div>
            </form>
        </section>

    </main>
</body>
</html>