<?php
include __DIR__ . '/config/db.php';

$newPassword = 'admin';
$newHash = password_hash($newPassword, PASSWORD_DEFAULT);
$username = 'admin';

$stmt = $conn->prepare("UPDATE users SET password_hash = ? WHERE username = ?");
$stmt->bind_param("ss", $newHash, $username);

if ($stmt->execute()) {
    echo "✅ Password for user '$username' reset to 'admin' successfully.";
} else {
    echo "❌ Failed to reset password.";
}
