<?php
require_once __DIR__.'/../include/config.php';

if (empty($_SESSION['user_id']) || empty($_GET['id'])) header('Location: index.php');

$id = $_GET['id'];
$stmt = $pdo->prepare('SELECT image_path FROM posts WHERE id=:i AND user_id=:u');
$stmt->execute([':i'=>$id,':u'=>$_SESSION['user_id']]);
$post = $stmt->fetch();

if ($post) {
    if ($post['image_path']) @unlink(UPLOAD_DIR . $post['image_path']);
    $pdo->prepare('DELETE FROM posts WHERE id=:i')->execute([':i'=>$id]);
    $_SESSION['success'] = 'Usunięto wpis';
}

header('Location: index.php');
