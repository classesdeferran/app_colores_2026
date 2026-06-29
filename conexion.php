<?php

// MySQli
// PDO

// DSN => Data Source Name
$server_name = '127.0.0.1';
$server_name = 'localhost';
$database = 'colores';
$port = '3307';
$charset = 'utf8mb4';
$user = 'colores_admin';
$pass = 'admin';

$dsn = "mysql:host=$server_name;port=$port;dbname=$database;charset=$charset;";

$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // activa el modo de errores
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // devolver arrays asociativos
    PDO::ATTR_EMULATE_PREPARES => false
];

try {

    $pdo = new PDO ($dsn, $user, $pass, $options);

    // echo "Conexión establecida";
    /*
    foreach( $pdo -> query('SELECT * FROM users;') as $fila) {
        echo $fila['nombre_user'].'<br>';
    }
    echo "FIN.";
    */

} catch (PDOException $err) {
    echo "Error en la conexión: ".$err->getMessage();
}