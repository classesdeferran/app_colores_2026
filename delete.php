<?php

session_start();
require_once 'conexion.php';

// Verificar que se accede por $_POST (por el formulario)
if (!$_POST) {
    $_SESSION['error_insert'] = "true";
    header('Location: main.php');
    exit();
}



$sql_delete = 'DELETE FROM users WHERE id_user = :id';
// 2. Preparar la respuesta
$respuesta = $pdo->prepare($sql_delete);
// 3. Realizar la petición
$respuesta->execute([
    ':id' => $_POST['id_user']
]);

$pdo = null;
session_destroy();
header('Location: main.php');
