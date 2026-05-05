<?php
$env = parse_ini_file(__DIR__ . '/../.env');

$type    = $env['DB_TYPE'];
$server  = $env['DB_SERVER'];
$db      = $env['DB_NAME'];
$port    = $env['DB_PORT'];
$charset = $env['DB_CHARSET'];
$username = $env['DB_USERNAME'];
$password = $env['DB_PASSWORD'];

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

$dsn = "$type:host=$server;dbname=$db;port=$port;charset=$charset";
try {
    $pdo = new PDO($dsn, $username, $password, $options);
} catch (PDOException $e) {
    die("Database connection failed. Please try again later.");
}