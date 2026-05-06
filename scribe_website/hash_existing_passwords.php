<?php
require_once 'includes/database-connection.php';

// Get all users
$stmt = $pdo->query("SELECT username, password FROM Users");
$users = $stmt->fetchAll();

foreach ($users as $user) {
    $username = $user['username'];
    $plainPassword = $user['password'];

    // Skip already-hashed passwords (important!)
    if (password_get_info($plainPassword)['algo'] !== 0) {
        continue;
    }

    // Hash the plaintext password
    $hashed = password_hash($plainPassword, PASSWORD_DEFAULT);

    // Update database
    $update = $pdo->prepare("UPDATE Users SET password = ? WHERE username = ?");
    $update->execute([$hashed, $username]);

    echo "Updated: $username <br>";
}

echo "Done.";
