<?php
require_once __DIR__.'/../include/config.php';

if (!empty($_GET['id'])) {
    $stmt = $pdo->prepare('UPDATE users SET verified=1 WHERE id=:id');
    $stmt->execute([':id'=>$_GET['id']]);
    if ($stmt->rowCount()) {
        $_SESSION['success'] = 'Konto zweryfikowane, możesz się zalogować.';
    } else {
        $_SESSION['error'] = 'Nieprawidłowy identyfikator';
    }
}

header('Location: login.php');

exit;
