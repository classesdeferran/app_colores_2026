<?php
session_start();
require_once 'conexion.php';

// Verificar que se accede por $_POST (por el formulario)
if (!$_POST) {
    $_SESSION['error_insert'] = "true";
    header('Location: main.php');
    exit();
}

// print_r($_POST);

$nombre_usuario = trim($_POST['nombre_i']);

$patron = '/^[ \p{L}0-9]+$/u';

if (empty($nombre_usuario) || !preg_match($patron, $nombre_usuario)) {
    $_SESSION['error_insert'] = "true";
    $_SESSION['nombre_invalido'] = $nombre_usuario;
    // echo 'error';
    header('Location: main.php');
    exit();
}

// 1. Definir la query
$select_usuario = 'SELECT id_user FROM users WHERE nombre_user = :nombre_usuario';
// 2. Preparar la respuesta
$respuesta = $pdo->prepare($select_usuario);
// 3. Realizar la petición
$respuesta->execute([
    ':nombre_usuario' => $nombre_usuario
]);
// 4. Recuperar los datos de la consulta
$resultado = $respuesta->fetch();

// Si el usuario ya existe NO hacemos el insert
if ($resultado) {
    $_SESSION['nombre_existe'] = true;
    header('Location: main.php');
    exit();
}

// 1. Definir la query
$select_color = 'SELECT id_color FROM colores WHERE nombre_color = :color';
// 2. Preparar la respuesta
$respuesta = $pdo->prepare($select_color);
// 3. Realizar la petición
$respuesta->execute([
    ':color' => $_POST['color_i']
]);
// 4. Recuperar los datos de la consulta
$resultado = $respuesta->fetch();

$id_color = '';

if ($resultado) {
    // si el color ya está en la BD
    $id_color = $resultado['id_color'];
} else {

    try {
        // Si no está los insertamos
        // 1. Definir la query
        $insert_color = 'INSERT INTO colores(nombre_color) VALUES(:color);';
        // 2. Preparar la respuesta
        $respuesta = $pdo->prepare($insert_color);
        // 3. Realizar la petición
        $respuesta->execute([
            ':color' => $_POST['color_i']
        ]);
        // El último id insertado en el objero pdo
        $id_color = $pdo->lastInsertId();
    } catch (PDOException $err) {
        echo "Error -> " . $err->getMessage();
    }
}

try {
    // Ya se puede hacer el insert del usuario
    $insert_user = 'INSERT INTO users(nombre_user, id_color) VALUES(:nombre_user, :id_color)';
    $insert = $pdo->prepare($insert_user);
    $insert->execute([
        'id_color' => $id_color,
        ':nombre_user' => $nombre_usuario
    ]);
} catch (PDOException $err) {
    echo "Error -> " . $err->getMessage();
}

session_destroy();
header('Location: main.php');
