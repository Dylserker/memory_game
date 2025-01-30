<?php
session_start();
require_once 'includes/database.php';
require_once 'MVC/controllers/AuthController.php';

if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $result = register(
        $_POST['username'],
        $_POST['password'],
        $_POST['confirm_password']
    );

    if ($result['success']) {
        $_SESSION['user_id'] = $result['user_id'];
        $_SESSION['username'] = $_POST['username'];
        header('Location: index.php');
        exit();
    } else {
        $errors = $result['errors'];
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Inscription</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Audiowide&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: "Audiowide", serif;
            font-weight: 400;
            font-style: normal;
        }
        body {
            color: #4a504f;
            background-image: url("src/images/Gif.gif") !important;
            background-attachment: fixed !important;
            background-size: cover !important;
            background-position: center !important;
            font-family: "Audiowide", serif;
            font-weight: 400;
            font-style: normal;
        }
        label {
            color: #37d513;
            text-shadow: 2px 2px 0 black;
            font-family: "Audiowide", serif;
            font-weight: 400;
            font-style: normal;
        }
        form {
            margin-bottom: 420px;
        }
    </style>
</head>
<body>
<header>
    <?php
    require_once 'MVC/views/navbar.php'
    ?>
</header>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger">
                    <?php foreach ($errors as $error): ?>
                        <p><?= htmlspecialchars($error) ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <form method="POST">
                <div class="mb-3">
                    <label>Nom d'utilisateur</label>
                    <input type="text" name="username" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Mot de passe</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Confirmer le mot de passe</label>
                    <input type="password" name="confirm_password" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary">Inscription</button>
            </form>
        </div>
    </div>
</div>
<footer>
    <?php
    require_once 'MVC/views/footer.php'
    ?>
</footer>
</body>
</html>
