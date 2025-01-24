<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $time = $_POST['time'];
    $user_id = $_SESSION['user_id'];

    $pdo = new PDO('mysql:host=localhost;dbname=memory_game', 'root', '');

    $stmt = $pdo->prepare("INSERT INTO scores (user_id, time) VALUES (:user_id, :time)");
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->bindParam(':time', $time, PDO::PARAM_INT);
    $stmt->execute();
}