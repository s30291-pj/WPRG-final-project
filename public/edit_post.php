<?php
require_once __DIR__.'/../include/config.php';

if (empty($_SESSION['user_id'])) header('Location: login.php');

$id = $_REQUEST['id'] ?? null;
$stmt = $pdo->prepare('SELECT * FROM posts WHERE id=:id AND user_id=:u');
$stmt->execute([':id'=>$id,':u'=>$_SESSION['user_id']]);
$post = $stmt->fetch();

if (!$post) {
    $_SESSION['error'] = 'Brak uprawnień';
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD']==='POST') {
    $t = trim($_POST['title']);
    $c = trim($_POST['content']);
    $stmt = $pdo->prepare('UPDATE posts SET title=:t,content=:c WHERE id=:i');
    $stmt->execute([':t'=>$t,':c'=>$c,':i'=>$id]);
    $_SESSION['success'] = 'Zaktualizowano';
    header('Location: index.php');
    exit;
}
