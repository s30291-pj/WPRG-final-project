<?php
    session_start();
    
    define('DB_HOST', 'localhost');
    define('DB_NAME', 'blog');
    define('DB_USER', 'root');
    define('DB_PASS', '');
    define('UPLOAD_DIR', __DIR__ . '/../uploads/');

    try {
        $pdo = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME.';charset=utf8', DB_USER, DB_PASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        die('Błąd połączenia z bazą: ' . $e->getMessage());
    }
?>
