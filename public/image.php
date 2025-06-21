<?php
require_once __DIR__.'/../include/config.php';

if (empty($_GET['id'])) exit;

$stmt = $pdo->prepare('SELECT image_path FROM posts WHERE id=:id');
$stmt->execute([':id'=>$_GET['id']]);
$row = $stmt->fetch();

if (!$row || !$row['image_path']) exit;

$path = UPLOAD_DIR . $row['image_path'];

if (!file_exists($path)) exit;

$finfo = finfo_open(FILEINFO_MIME_TYPE);
$mime = finfo_file($finfo, $path);

header('Content-Type:' . $mime);
readfile($path);
