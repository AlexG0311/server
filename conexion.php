<?php
require_once __DIR__ . '/vendor/autoload.php'; // Ajuste: Quitamos "../"

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$host = $_ENV['DB_HOST'];
$dbname = $_ENV['DB_NAME'];
$username = $_ENV['DB_USER'];
$password = $_ENV['DB_PASS'];
$port = $_ENV['DB_PORT'] ?: 3306;

$conexion = mysqli_connect($host, $username, $password, $dbname, $port);

if (!$conexion) {
    die("Error de conexión: " . mysqli_connect_error());
}
?>