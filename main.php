<?php

session_start();
$error_insert = $_SESSION['error_insert'] ?? '';
unset($_SESSION['error_insert']);

$nombre_invalido = $_SESSION['nombre_invalido'] ?? '';
$nombre_existe = $_SESSION['nombre_existe'] ?? false;
$cambio_bd = $_SESSION['cambio_bd'] ?? false;
$insert_user = $_SESSION['old_user'] ?? '';
$insert_color = $_SESSION['old_color'] ?? '#FFFFFF';
$delete_id = $_SESSION['delete_id'] ?? '';

$mostrar_toast = $_SESSION['mostrar_toast'] ?? false;
unset($_SESSION['mostrar_toast']);

$mensaje = $_SESSION['mensaje'] ?? '';
unset($_SESSION['mensaje']);

// token CSRF
if (empty($_SESSION['session_token'])) {
    $_SESSION['session_token'] = bin2hex(random_bytes(64));
}




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



unset($_SESSION['nombre_invalido']);
unset($_SESSION['nombre_existe']);
unset($_SESSION['cambio_bd']);
unset($_SESSION['old_user']);
unset($_SESSION['old_color']);
unset($_SESSION['delete_dialog'])


?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Colores</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="css/style.css">
    <script src="js/script.js" defer></script>
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
                    <div class="acciones">

                        <form action="main.php" method="post">

                            <!-- Construir los inputs con los datos de $usuario (la fila de la consulta) -->
                            <?php foreach ($usuario as $clave => $valor) : ?>
                                <input type="hidden" name="<?= $clave ?>" value="<?= $valor ?>">
                            <?php endforeach; ?>

                            <!-- Campo oculto para identificar este formulario respecto al de insertar -->
                            <input type="hidden" name="accion_update" value="editar">

                            <button>
                                <i class="bi bi-pencil-fill color_adaptado"></i>
                            </button>
                        </form>


                        <form action="delete.php" method="POST" class="delete-data">
                            <input type="hidden" name="id_user" value="<?= $usuario['id_user'] ?>">
                            <input type="hidden" name="userName" value="<?= $usuario['nombre_user'] ?>">
                            <button type="submit" title="Eliminar usuario"><i class="bi bi-person-x-fill color_adaptado"></i></button>
                        </form>

                    </div>
                </div>

            <?php endforeach; ?>

        </section>
        <!-- =================================================================================== -->
        <!-- MODO INSERCIÓN -->
        <!-- =================================================================================== -->
        <?php if (!isset($_POST['accion_update']) || $_POST['accion_update'] !== "editar") : ?>
            <section>
                <h2>Dinos tu color preferido</h2>
                <form action="insert.php" method="post">
                    <input type="hidden" name="count_users" value="<?= count($resultado) ?>">
                    <input type="hidden" name="session_token" value="<?= $_SESSION['session_token'] ?>">

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
                        <button type="submit">Aceptar datos</button>
                        <button type="reset">Borrar formulario</button>
                    </div>
                </form>
            </section>

        <?php else : ?>
        <!-- =================================================================================== -->
        <!-- MODO EDICIÓN -->
        <!-- =================================================================================== -->
            <section>
                <h2>Actualiza los datos</h2>
                <form action="update.php" method="post">
                    <input type="hidden" name="id_user" value="<?= $_POST['id_user'] ?>">
                    <div class="div_update">
                        <div>
                            <label for="nombre_u">Nombre:</label>
                            <input type="text" id='nombre_u' name='nombre_u'
                                value="<?= $_POST['nombre_user'] ?>">
                        </div>
                        <div>
                            <label for="color_u">Color:</label>
                            <input type="color" id='color_u' name='color_u'
                                value="<?= $_POST['nombre_color'] ?>">
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
                        <button type="submit">Confirmar modificación</button>
                        <button type="button" onclick="window.location.href='main.php'">Cancelar actualización</button>
                        <button type="reset">Borrar formulario</button>
                    </div>
                </form>
            </section>
        <?php endif; ?>
    </main>

    <dialog id="dialog_delete">
        <h2>Confirmar eliminación del usuario "<span id='confirm_name_user'></span>"</h2>
        <p>¿Está seguro que desea borrar los datos? Esta acción no se puede deshacer.</p>
        <div>
            <button id="delete_no" type="button">Cancelar</button>
            <button id="delete_si" type="button">Eliminar</button>
        </div>
    </dialog>


    <?php if ($mostrar_toast) : ?>

        <div id="toast">
            <p><?= $mensaje ?></p>
        </div>

        <script>
            // Mostrar el div anterior 3 segundos
            setTimeout( () => {
                const toast = document.getElementById('toast')
                if(toast) {
                    toast.style.transition = "opacity 1000ms linear"
                    toast.style.opacity = "0"
                    setTimeout(()=> toast.remove(), 1000)
                }
            }, 3000)
        </script>
    <?php endif; ?>

</body>

</html>

<?php

$pdo = null;
