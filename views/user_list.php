<?php
session_start();
require_once 'includes/database.php';

if ($_SESSION['role'] !== 'admin') {
    header('Location: index.php');
    exit();
}

$stmt = $pdo->query("SELECT * FROM users");
$users = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Utilisateurs</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
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
        height: max-content;
    }
    .reg {
        color: #37d513;
        text-shadow: 2px 2px 0 black;
        font-family: "Audiowide", serif;
        font-weight: 400;
        font-style: normal;
    }
    .container {
        margin-bottom: 200px;
    }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="index.php">Memory Game</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto d-flex align-items-center">
                <?php if(!isset($_SESSION['user_id'])): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="login.php">Connexion</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="register.php">Inscription</a>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <span class="navbar-text me-3">
                            Bonjour, <?= htmlspecialchars($_SESSION['username']) ?>
                        </span>
                    </li>
                    <?php if(isset($_SESSION['role']) && $_SESSION['role'] == 'admin'): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="user_list.php">Liste des utilisateurs</a>
                        </li>
                    <?php endif; ?>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">Déconnexion</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-5">
    <h2 class="reg">Liste des Utilisateurs</h2>
    <table class="table">
        <thead>
        <tr class="reg">
            <th>ID</th>
            <th>Nom d'utilisateur</th>
            <th>Rôle</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($users as $user): ?>
            <tr>
                <td class="reg"><?= htmlspecialchars($user['id']) ?></td>
                <td class="reg"><?= htmlspecialchars($user['username']) ?></td>
                <td class="reg"><?= htmlspecialchars($user['role']) ?></td>
                <td>
                    <form method="POST" action="MVC/controllers/delete_user.php" style="display:inline;" onsubmit="return confirmDelete();">
                        <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                        <button type="submit" class="btn btn-danger">Supprimer</button>
                    </form>
                    <a href="edit_user.php?id=<?= $user['id'] ?>" class="btn btn-warning">Modifier</a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
<footer>
    <?php
    require_once 'MVC/views/footer.php'
    ?>
</footer>
<script>
    function confirmDelete() {
        return confirm("Êtes-vous sûr de vouloir supprimer cet utilisateur ?");
    }
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>