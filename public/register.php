<?php
require_once __DIR__.'/../include/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = trim($_POST['login']);
    $email = trim($_POST['email']);
    $pass = $_POST['password'];

    if (!$login || !$email || !$pass) {
        $_SESSION['error'] = 'Wszystkie pola są wymagane';
        header('Location: register.php');
        exit;
    }

    $stmt = $pdo->prepare('SELECT id FROM users WHERE username = :u OR email = :e');
    $stmt->execute([':u'=>$login,':e'=>$email]);

    if ($stmt->fetch()) {
        $_SESSION['error'] = 'Login lub email już zajęty';
        header('Location: register.php');
        exit;
    }

    $hash = password_hash($pass, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare('INSERT INTO users(username,email,password,verified,created_at) VALUES(:u,:e,:p,0,NOW())');
    $stmt->execute([':u'=>$login,':e'=>$email,':p'=>$hash]);
    $id = $pdo->lastInsertId();
    $_SESSION['success'] = 'Zarejestrowano. Kliknij link weryfikacyjny';
    $_SESSION['verify_link'] = "verify.php?id=$id";
    
    header('Location: register.php');
    exit;
}

require 'header.php';
?>

<?php if (!empty($_SESSION['verify_link'])): ?>
    <div class="alert alert-info">Link weryfikacyjny: <a href="<?php echo $_SESSION['verify_link']; ?>"><?php echo $_SESSION['verify_link']; ?></a><?php unset($_SESSION['verify_link']); ?></div>
<?php endif; ?>

<div class="d-flex flex-column align-self-center border border-1 rounded-3 bg-body mt-4 w-100">
    <header class="d-flex justify-content-between p-4 border-bottom bg-light bg-opacity-75 rounded-top-3"><h3>ZAREJESTRUJ SIĘ</h3></header>
    <div class="d-flex flex-row p-3 gap-3">
        <div class="flex-grow-1 mt-2">
            <form method="post" class="d-flex flex-column p-5">
                <small>Wprowadź swoje dane poniżej</small>
                <h4 class="mb-4">Fajnie, że dołączasz!</h4>
                <div class="input-group mb-2"><span class="input-group-text"><i class="fa-solid fa-user"></i></span><input name="login" class="form-control" placeholder="Login" required></div>
                <div class="input-group mb-2"><span class="input-group-text"><i class="fa-solid fa-envelope"></i></span><input name="email" class="form-control" placeholder="Email" required></div>
                <div class="input-group mb-3"><span class="input-group-text"><i class="fa-solid fa-lock"></i></span><input type="password" name="password" class="form-control" placeholder="Hasło" required></div>
                <div class="d-flex align-items-start mb-3"><input id="accept-tos" type="checkbox" class="form-check-input me-2" required><label for="accept-tos">*Akceptuję <a href="#" class="link-danger">regulamin</a></label></div>
                <button type="submit" class="btn btn-outline-primary w-100 mb-3">ZAREJESTRUJ SIĘ</button>
                <small>Posiadasz już konto? <a href="login.php">Zaloguj się...</a></small>
            </form>
        </div>
        <div class="rounded-4" style="width:40%;height:500px;background-color:rgb(224,216,216)">
            <img src="https://picsum.photos/1200/600" class="rounded-4" style="object-fit:cover;width:100%;height:100%;filter:brightness(0.8)">
        </div>
    </div>
</div>
<?php require 'footer.php'; ?>
