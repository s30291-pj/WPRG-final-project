<?php

require_once __DIR__.'/User.php';
require_once __DIR__.'/Post.php';
require_once __DIR__.'/Comment.php';
require_once __DIR__.'/Account.php';
require_once __DIR__.'/Database.php';

require_once __DIR__.'/../config/Config.php';

$db = new Database(DB_HOST, DB_NAME, DB_USER, DB_PASS);

function getDatabase(): Database
{
    global $db;
    return $db;
}

function getAllPosts() {
    $db = getDatabase();
    $rows = $db->fetchAll('SELECT * FROM posts ORDER BY created_at DESC');
    $posts = [];
    foreach ($rows as $row) {
        $user = getUserByUuid($row['user_uuid']);
        $comments = getCommentsByPostId($row['uuid']);
        $post = new Post(
            $row['title'] ?? '',
            $row['image_url'] ?? '',
            $row['content'] ?? '',
            $user,
            $comments
        );
        if (isset($row['status'])) {
            $post->setStatus(PostStatus::from($row['status']));
        }
        $posts[] = $post;
    }
    return $posts;
}

function getPostById($uuid) {
    $db = getDatabase();
    $row = $db->fetch('SELECT * FROM posts WHERE uuid = ?', [$uuid]);
    if (!$row) return null;
    $user = getUserByUuid($row['user_uuid']);
    $comments = getCommentsByPostId($row['uuid']);
    $post = new Post(
        $row['title'] ?? '',
        $row['image_url'] ?? '',
        $row['content'] ?? '',
        $user,
        $comments
    );
    if (isset($row['status'])) {
        $post->setStatus(PostStatus::from($row['status']));
    }
    return $post;
}

function getAllUsers() {
    $db = getDatabase();
    $rows = $db->fetchAll('SELECT * FROM users');
    $users = [];
    foreach ($rows as $row) {
        $users[] = new User(
            $row['uuid'],
            $row['name'],
            UserStatus::from($row['status'])
        );
    }
    return $users;
}

function getUserByUuid($uuid) {
    $db = getDatabase();
    $row = $db->fetch('SELECT * FROM users WHERE uuid = ?', [$uuid]);
    if (!$row) return null;
    return new User(
        $row['uuid'],
        $row['name'],
        UserStatus::from($row['status'])
    );
}

function getAllComments() {
    $db = getDatabase();
    $rows = $db->fetchAll('SELECT * FROM comments ORDER BY created_at DESC');
    $comments = [];
    foreach ($rows as $row) {
        $user = getUserByUuid($row['user_uuid']);
        $comment = new Comment(
            $row['uuid'],
            $user,
            $row['content'],
            CommentStatus::from($row['status'])
        );
        $comments[] = $comment;
    }
    return $comments;
}

function getCommentsByPostId($postUuid) {
    $db = getDatabase();
    $rows = $db->fetchAll('SELECT * FROM comments WHERE post_uuid = ? ORDER BY created_at ASC', [$postUuid]);
    $comments = [];
    foreach ($rows as $row) {
        $user = getUserByUuid($row['user_uuid']);
        $comment = new Comment(
            $row['uuid'],
            $user,
            $row['content'],
            CommentStatus::from($row['status'])
        );
        $comments[] = $comment;
    }
    return $comments;
}

function getAllAccounts() {
    $db = getDatabase();
    $rows = $db->fetchAll('SELECT * FROM accounts');
    $accounts = [];
    foreach ($rows as $row) {
        $accounts[] = new Account(
            $row['login'],
            $row['email'],
            Role::from($row['role']),
            $row['password_hash'],
            AccountStatus::from($row['status'])
        );
    }
    return $accounts;
}

function getAccountByLogin($login) {
    $db = getDatabase();
    $row = $db->fetch('SELECT * FROM accounts WHERE login = ?', [$login]);
    if (!$row) return null;
    return new Account(
        $row['login'],
        $row['email'],
        Role::from($row['role']),
        $row['password_hash'],
        AccountStatus::from($row['status'])
    );
}

?>



