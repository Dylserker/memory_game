<?php
require_once 'includes/database.php';

function register($username, $password, $confirm_password) {
    global $pdo;
    $errors = [];

    if (empty($username)) $errors[] = "Nom d'utilisateur requis";
    if (strlen($username) < 3) $errors[] = "Nom d'utilisateur trop court";
    if (strlen($password) < 6) $errors[] = "Mot de passe trop court";
    if ($password !== $confirm_password) $errors[] = "Mots de passe différents";

    if (empty($errors)) {
        try {
            $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
            $stmt->execute([$username]);

            if ($stmt->fetch()) {
                $errors[] = "Nom d'utilisateur déjà utilisé";
            } else {
                $hashed_password = password_hash($password, PASSWORD_BCRYPT);

                $stmt = $pdo->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, 'user')");
                $stmt->execute([$username, $hashed_password]);

                return ['success' => true, 'user_id' => $pdo->lastInsertId()];
            }
        } catch (PDOException $e) {
            $errors[] = "Erreur d'inscription : " . $e->getMessage();
        }
    }

    return ['success' => false, 'errors' => $errors];
}

function login($username, $password) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            return ['success' => true, 'user' => $user];
        } else {
            return ['success' => false, 'errors' => ['Identifiants incorrects']];
        }
    } catch (PDOException $e) {
        return ['success' => false, 'errors' => ['Erreur de connexion']];
    }
}