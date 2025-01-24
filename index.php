<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'includes/database.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jeu de Memory</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="src/css/style.css">
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
            font-family: "Audiowide", serif;
            font-weight: 400;
            font-style: normal;
            color: #4a504f;
            background-image:linear-gradient(rgba(0, 0, 0, 0.2), rgba(0, 0, 0, 0.2)), url("src/images/Gif.gif") !important;
            background-attachment: fixed !important;
            background-size: cover !important;
            background-position: center !important;
            height: 100vh;
        }
        .play {
            color: #37d513;
            text-shadow: 2px 2px 0 black;
            font-family: "Audiowide", serif;
            font-weight: 400;
            font-style: normal;
        }
        .text-center {
            margin-bottom: 185px;
        }
        footer {
            background-position: center;
        }
    </style>
</head>
<body>
<header>
    <?php
    require_once 'MVC/views/navbar.php'
    ?>
</header>
<div class="container">
    <?php
    if(isset($_SESSION['user_id'])) {
        echo "<div class='alert alert-success'>Bienvenue, " . htmlspecialchars($_SESSION['username']) . "</div>";
    } else {
        echo "<div class='alert alert-info'>Connectez-vous pour commencer à jouer</div>";
    }
    ?>

    <div id="scoreboard">
        <h2>Meilleurs temps</h2>
        <?php
        $stmt = $pdo->query("SELECT users.username, scores.time FROM scores JOIN users ON scores.user_id = users.id ORDER BY scores.time DESC LIMIT 5");
        $topScores = $stmt->fetchAll();
        ?>
        <table class="table">
            <thead>
            <tr>
                <th>Joueur</th>
                <th>Temps</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach($topScores as $score): ?>
                <tr>
                    <td><?= htmlspecialchars($score['username']) ?></td>
                    <td><?= $score['time'] ?> secondes</td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <?php if(isset($_SESSION['user_id'])): ?>
        <div id="gameBoard" class="d-flex flex-wrap justify-content-center">
        </div>

        <div id="timer">Temps restant: 300s</div>
        <div id="progressBar" class="progress" style="height: 20px;">
            <div id="progress" class="progress-bar" style="width: 100%;"></div>
        </div>

        <button id="startGame" class="btn btn-primary">Démarrer le jeu</button>
    <?php else: ?>
        <div class="text-center">
            <p class="play">Veuillez vous connecter pour jouer</p>
            <a href="login.php" class="btn btn-primary me-2">Connexion</a>
            <a href="register.php" class="btn btn-secondary">Inscription</a>
        </div>
    <?php endif; ?>
</div>

<div class="text-center">
    <button id="restartGame" class="btn btn-primary" style="display:none;">Rejouer</button>
</div>
<footer>
    <?php
    require_once 'MVC/views/footer.php'
    ?>
</footer>
<script type="module" src="src/js/game.js"></script>
</body>
</html>