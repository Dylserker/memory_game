<?php
session_start();
if ($_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_score'])) {
    $score_id = $_POST['score_id'];
    $stmt = $pdo->prepare("DELETE FROM scores WHERE id = ?");
    $stmt->execute([$score_id]);
}

$stmt = $pdo->query("SELECT scores.id, users.username, scores.time, scores.date FROM scores JOIN users ON scores.user_id = users.id ORDER BY scores.time ASC");
$scores = $stmt->fetchAll();
?>

<h2>Liste des scores</h2>
<table>
    <tr>
        <th>Nom d'utilisateur</th>
        <th>Temps</th>
        <th>Date</th>
        <th>Actions</th>
    </tr>
    <?php foreach ($scores as $score): ?>
        <tr>
            <td><?= htmlspecialchars($score['username']) ?></td>
            <td><?= $score['time'] ?> secondes</td>
            <td><?= $score['date'] ?></td>
            <td>
                <form method="POST">
                    <input type="hidden" name="score_id" value="<?= $score['id'] ?>">
                    <button type="submit" name="delete_score">Supprimer</button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
