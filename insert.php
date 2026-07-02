<?php
session_start();
require_once 'conexion.php';

// Verificar que se accede por $_POST (por el formulario)
if (!$_POST) {
    $_SESSION['error_insert'] = "true";
    header('Location: main.php');
    exit();
}

// Verificación del Honeypot (señuelo)
if (!empty($_POST['user-name'])) {
    echo "Solicitud recibida";
    http_response_code(200);
    header('Location: main.php');
    exit();
}

// Verificación del session-token
if (!isset($_POST['session_token'])) {
    header('Location: main.php');
    exit();
}

if (!hash_equals($_SESSION['session_token'], $_POST['session_token'])) {
    // echo "token falsificado";
    header('Location: main.php');
    exit();
}


$select_count = "SELECT COUNT(*) as count_users FROM users";
$count_users = $pdo->prepare($select_count);
$count_users->execute();
$count_users = $count_users->fetch();
// $count_users es un array asociativo con una única columna: count_users


$nombre_usuario = trim($_POST['nombre_i']);

if ($count_users['count_users'] != $_POST['count_users']) {
    // Si ha cambiado la cantidad de usuarios no realizamos 
    // el insert, pero advertimos al usuario
    $_SESSION['cambio_bd'] = true;
    $_SESSION['old_user'] = $nombre_usuario;
    $_SESSION['old_color'] = $_POST['color_i'];
    header('Location: main.php');
    exit();
}

// print_r($_POST);


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
        ':id_color' => $id_color,
        ':nombre_user' => $nombre_usuario
    ]);
} catch (PDOException $err) {
    echo "Error -> " . $err->getMessage();
}

$pdo = null;
session_destroy();
header('Location: main.php');
