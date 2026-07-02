<?php
session_start();
require_once 'conexion.php';

// echo "Soy UPDATE";

// Resolver la actualización de los datos

// Requisitos:

//  * Verifica si otro usuario no ha modificado antes esos datos.

//  * Resuélvelo con un procedimiento almacenado.

//  * Pueden ser modificados el nombre del usuario, el color y las dos cosas.

$_SESSION['mostrar_toast'] = true;
$_SESSION['mensaje'] = "Actualización realizada correctamente";
header('Location: main.php');
$pdo = null;