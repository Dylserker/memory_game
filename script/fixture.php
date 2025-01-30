<?php
require_once '../includes/database.php';
require_once '../vendor/autoload.php';

$faker = Faker\Factory::create();

for ($i = 0; $i < 100; $i++) {
    $username = $faker->userName;
    $password = password_hash('password123', PASSWORD_BCRYPT);
    $role = $faker->randomElement(['user', 'admin']);

    $stmt = $pdo->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
    $stmt->execute([$username, $password, $role]);
}

echo "100 utilisateurs générés avec succès.";
