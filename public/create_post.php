<?php
require_once __DIR__.'/../include/config.php';

if (empty($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$title = trim($_POST['title']);
$content = trim($_POST['content']);

if (!$title || !$content) {
    $_SESSION['error'] = 'Tytuł i opis są wymagane';
    header('Location: index.php');
    exit;
}

$imagePath = null;
if (!empty($_FILES['image']['name'])) {
    $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
    $file = uniqid() . ".$ext";
    if (!move_uploaded_file($_FILES['image']['tmp_name'], UPLOAD_DIR . $file)) {
        $_SESSION['error'] = 'Błąd zapisu pliku';
        header('Location: index.php');
        exit;
    }
    $imagePath = $file;
}

$stmt = $pdo->prepare('INSERT INTO posts(user_id,title,content,image_path,created_at) VALUES(:u,:t,:c,:i,NOW())');
$stmt->execute([
    ':u'=>$_SESSION['user_id'],
    ':t'=>$title,
    ':c'=>$content,
    ':i'=>$imagePath
]);

$_SESSION['success'] = 'Dodano wpis';

header('Location: index.php');
