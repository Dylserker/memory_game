<?php
session_start();
require_once 'includes/database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

$limit = 15;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$stmt = $pdo->prepare("SELECT users.username, scores.time, scores.date FROM scores JOIN users ON scores.user_id = users.id ORDER BY scores.date DESC LIMIT :limit OFFSET :offset");
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$scores = $stmt->fetchAll();


$totalStmt = $pdo->query("SELECT COUNT(*) FROM scores");
$totalScores = $totalStmt->fetchColumn();
$totalPages = ceil($totalScores / $limit);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Scores</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
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
        .pagination {
            background-position: center;
            margin-top: 20px;
            margin-left: 20px;
        }
    </style>
</head>
<header>
    <?php
    require_once 'MVC/views/navbar.php'
    ?>
</header>
<body>
<div class="container mt-5">
    <h2>Liste des Scores</h2>
    <table class="table">
        <thead>
        <tr>
            <th>Nom d'utilisateur</th>
            <th>Temps</th>
            <th>Date</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($scores as $score): ?>
            <tr>
                <td><?= htmlspecialchars($score['username']) ?></td>
                <td><?= $score['time'] ?> secondes</td>
                <td><?= $score['date'] ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <nav>
        <ul class="pagination">
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <li class="page-item <?= $i === $page ? 'active' : '' ?>">
                    <a class="page-link" href="score_list.php?page=<?= $i ?>"><?= $i ?></a>
                </li>
            <?php endfor; ?>
        </ul>
    </nav>
</div>
<footer>
    <?php require_once 'MVC/views/footer.php' ?>
</footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
