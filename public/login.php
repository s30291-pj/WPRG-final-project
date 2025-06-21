<?php
require_once __DIR__.'/../include/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = trim($_POST['login']);
    $pass = $_POST['password'];
    $stmt = $pdo->prepare('SELECT * FROM users WHERE username = :u OR email = :u');
    $stmt->execute([':u'=>$login]);
    $user = $stmt->fetch();

    if (!$user || !password_verify($pass, $user['password'])) {
        $_SESSION['error'] = 'Nieprawidłowe dane';
        header('Location: login.php');
        exit;
    }

    if (!$user['verified']) {
        $_SESSION['error'] = 'Konto niezweryfikowane';
        header('Location: login.php');
        exit;
    }

    $_SESSION['user_id'] = $user['id'];
    $_SESSION['username'] = $user['username'];

    header('Location: index.php');
    exit;
}

require 'header.php';
?>

<div class="d-flex flex-column align-self-center border border-1 rounded-3 bg-body w-100">
    <header class="d-flex justify-content-between p-4 border-bottom bg-light bg-opacity-75 rounded-top-3"><h3>ZALOGUJ SIĘ</h3></header>
    <div class="d-flex flex-row p-3 gap-3">
        <div class="flex-grow-1 mt-4">
            <form method="post" class="d-flex flex-column p-5">
                <small>Wprowadź swoje dane poniżej</small>
                <h4 class="mb-4">Witaj ponownie na stronie!</h4>
                <div class="input-group mb-2"><span class="input-group-text"><i class="fa-solid fa-user"></i></span><input name="login" class="form-control" placeholder="Login" required></div>
                <div class="input-group mb-3"><span class="input-group-text"><i class="fa-solid fa-lock"></i></span><input type="password" name="password" class="form-control" placeholder="Hasło" required></div>
                <button type="submit" class="btn btn-outline-primary w-100 mb-3">ZALOGUJ SIĘ</button>
                <small>Nie posiadasz jeszcze konta? <a href="register.php">Zarejestruj się...</a></small>
            </form>
        </div>
        <div class="rounded-4" style="width:40%;height:500px;background-color:rgb(224,216,216)">
            <img src="https://picsum.photos/1200/600" class="rounded-4" style="object-fit:cover;width:100%;height:100%;filter:brightness(0.8)">
        </div>
    </div>
</div>
<?php require 'footer.php'; ?>
