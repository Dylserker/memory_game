<?php
require_once '../includes/database.php';
require_once '../vendor/autoload.php';

$stmt = $pdo->query("SELECT id FROM users");
$users = $stmt->fetchAll(PDO::FETCH_COLUMN);

if (empty($users)) {
    die("Aucun utilisateur trouvé. Veuillez d'abord générer des utilisateurs.");
}

foreach ($users as $user_id) {
    $time = rand(10, 300);
    $stmt = $pdo->prepare("INSERT INTO scores (user_id, time) VALUES (?, ?)");
    $stmt->execute([$user_id, $time]);
}

echo "Scores générés avec succès pour tous les utilisateurs.";
