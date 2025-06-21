<?php
require_once __DIR__.'/../include/config.php';

if (empty($_SESSION['user_id']) || empty($_GET['post_id'])) {
    header('Location: index.php');
    exit;
}

$post = (int)$_GET['post_id'];
$stmt = $pdo->prepare('SELECT id FROM likes WHERE user_id=:u AND post_id=:p');
$stmt->execute([':u'=>$_SESSION['user_id'], ':p'=>$post]);

if ($stmt->fetch()) {
    $pdo->prepare('DELETE FROM likes WHERE user_id=:u AND post_id=:p')
        ->execute([':u'=>$_SESSION['user_id'], ':p'=>$post]);
} else {
    $pdo->prepare('INSERT INTO likes(user_id,post_id) VALUES(:u,:p)')
        ->execute([':u'=>$_SESSION['user_id'], ':p'=>$post]);
}

header('Location: index.php#post-' . $post);
exit;
