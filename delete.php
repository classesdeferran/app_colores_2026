<?php

session_start();
require_once 'conexion.php';

// Verificar que se accede por $_POST (por el formulario)
if (!$_GET) {
    $_SESSION['error_insert'] = "true";
    header('Location: main.php');
    exit();
}

echo $_GET['id'];

$sql_delete = 'DELETE FROM users WHERE id_user = :id';
// 2. Preparar la respuesta
        $respuesta = $pdo->prepare($sql_delete);
        // 3. Realizar la petición
        $respuesta->execute([
            ':id' => $_GET['id']
        ]);


session_destroy();
header('Location: main.php');       