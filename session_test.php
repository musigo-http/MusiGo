<?php
session_start();

// Identifiant de l'utilisateur
$userId = 'user123'; // Remplacez par l'identifiant réel de l'utilisateur
$deviceId = session_id(); // Utiliser l'ID de session comme identifiant d'appareil

// Chemin vers le fichier de sessions
$sessionsFile = 'sessions.json';

// Charger les sessions existantes
if (file_exists($sessionsFile)) {
    $sessions = json_decode(file_get_contents($sessionsFile), true);
} else {
    $sessions = [];
}

// Vérifier le nombre de sessions actives pour cet utilisateur
$activeSessions = 0;
foreach ($sessions as $session) {
    if ($session['user_id'] === $userId) {
        $activeSessions++;
    }
}

// Vérifier si l'utilisateur est déjà connecté avec deux appareils
if ($activeSessions >= 2) {
    echo "Deux appareils sont déjà connectés au même compte.";
} else {
    // Ajouter ou mettre à jour la session de l'utilisateur
    $sessions[] = [
        'user_id' => $userId,
        'device_id' => $deviceId,
        'created_at' => time(),
    ];

    // Enregistrer les sessions mises à jour dans le fichier
    file_put_contents($sessionsFile, json_encode($sessions));
    echo "Connexion réussie sur un nouvel appareil.";
}
?>