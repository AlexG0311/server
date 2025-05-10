<?php
// Cargar el archivo .env
require_once __DIR__ . '/../vendor/autoload.php'; // Ajusta según la ubicación de vendor

// Carga el .env desde el directorio actual
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Obtener las variables de entorno
$host = $_ENV['DB_HOST'];
$dbname = $_ENV['DB_NAME'];
$username = $_ENV['DB_USER'];
$password = $_ENV['DB_PASS'];
$port = $_ENV['DB_PORT'] ?: 3306;

// Intentar la conexión
$conexion = mysqli_connect($host, $username, $password, $dbname, $port);

if (!$conexion) {
    die("Error de conexión: " . mysqli_connect_error());
}
?>