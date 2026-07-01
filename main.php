<?php

session_start();
$error_insert = $_SESSION['error_insert'] ?? '';
$nombre_invalido = $_SESSION['nombre_invalido'] ?? '';
$nombre_existe = $_SESSION['nombre_existe'] ?? false;
$cambio_bd = $_SESSION['cambio_bd'] ?? false;
$insert_user = $_SESSION['old_user'] ?? '';
$insert_color = $_SESSION['old_color'] ?? '#FFF';

// token CSRF
$_SESSION['session_token'] = bin2hex(random_bytes(64));

// echo "error ".$error_insert;
// echo "<br>";

/*
Formas de importar ficheros en PHP
include
include_once
require
require_once
*/

require_once 'conexion.php';

// 1. Definir la query
$sql = 'SELECT u.id_user, u.nombre_user, c.nombre_color FROM users u JOIN colores c USING(id_color);';

// 2. Preparar la respuesta
$respuesta = $pdo->prepare($sql);

// 3. Realizar la petición
$respuesta->execute();

// 4. Recuperar los datos de la consulta
$resultado = $respuesta->fetchAll();
// $resultado es un array que contiene arrays asociativos que son las filas de la tabla

// print_r($resultado);


unset($_SESSION['error_insert']);
unset($_SESSION['nombre_invalido']);
unset($_SESSION['nombre_existe']);
unset($_SESSION['cambio_bd']);
unset($_SESSION['old_user']);
unset($_SESSION['old_color']);


?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Colores</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script> -->
    <link rel="stylesheet" href="css/style.css">
</head>

<body>


    <header>
        <h1>¿Cuál es tu color preferido?</h1>
        <?php if ($cambio_bd) : ?>
            <p class="mensaje">
                ATENCIÓN : se han producido cambios en la base de datos
            </p>
        <?php endif; ?>
    </header>
    <main>
        <section>
            <h2>Usuarios</h2>

            <?php foreach ($resultado as $usuario) : ?>
                <div class="user" style="background-color: <?= $usuario['nombre_color'] ?>" ;>
                    <div>
                        <p class="color_adaptado">Usuario: <?= $usuario['nombre_user'] ?>, color: <?= substr($usuario['nombre_color'], 1)   ?></p>
                    </div>
                    <div>
                        <a href="main.php?nombre=<?= $usuario['nombre_user'] ?>&color=<?= substr($usuario['nombre_color'], 1) ?>&id=<?= $usuario['id_user'] ?>">
                            <i class="bi bi-pencil-fill color_adaptado"></i>
                        </a>
                        <a href="delete.php?id=<?= $usuario['id_user'] ?>"><i class="bi bi-person-x-fill color_adaptado"></i></a>


                    </div>
                </div>

            <?php endforeach; ?>

        </section>
        <?php if (!$_GET) : ?>
            <section>
                <h2>Dinos tu color preferido</h2>
                <form action="insert.php" method="post">
                    <input type="hidden" name="count_users" value="<?= count($resultado) ?>">
                    <input type="hidden" name="session_token" value="<?= "AA". $_SESSION['session_token'] ?>">

                    <div style="display:none">
                        <label for="user-name"></label>
                        <input type="text" id="user-name" name="user-name">
                    </div>

                    <div class="div_insert">
                        <div>
                            <label for="nombre_i">Nombre:</label>
                            <input type="text" id='nombre_i' name='nombre_i'
                                value="<?= $insert_user ?? $nombre_invalido ?>">
                        </div>
                        <div>
                            <label for="color_i">Color:</label>
                            <input type="color" id='color_i' name='color_i'
                                value='<?= $insert_color ?>'>
                        </div>
                    </div>
                    <div class="error_insert">
                        <?php if ($error_insert == "true") : ?>
                            <p>Error en los datos</p>
                        <?php endif; ?>
                        <?php if ($nombre_existe) : ?>
                            <p>El usuario ya existe</p>
                        <?php endif; ?>
                    </div>

                    <div class="buttons">
                        <button type="submit">Enviar datos</button>
                        <button type="reset">Borrar formulario</button>
                    </div>
                </form>
            </section>

        <?php else : ?>
            <section>
                <h2>Actualiza los datos</h2>
                <form action="update.php" method="post">
                    <div class="div_update">
                        <div>
                            <label for="nombre_u">Nombre:</label>
                            <input type="text" id='nombre_u' name='nombre_u'
                                value="<?= $_GET['nombre'] ?>">
                        </div>
                        <div>
                            <label for="color_u">Color:</label>
                            <input type="color" id='color_u' name='color_u'
                                value="<?= '#' . $_GET['color'] ?>">
                        </div>
                    </div>
                    <div class="error_insert">
                        <?php if ($error_insert == "true") : ?>
                            <p>Error en los datos</p>
                        <?php endif; ?>
                        <?php if ($nombre_existe) : ?>
                            <p>El usuario ya existe</p>
                        <?php endif; ?>
                    </div>

                    <div class="buttons">
                        <button type="submit">Enviar datos</button>
                        <button type="reset">Borrar formulario</button>
                    </div>
                </form>
            </section>
        <?php endif; ?>

    </main>
</body>

</html>

<?php

$pdo = null;
session_destroy();