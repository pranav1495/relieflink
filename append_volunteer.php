<?php
include __DIR__ . '/config/db.php';

$username = 'akash123';
$password = '123';
$role = 'volunteer';
$display_name = 'Akash B';  // Optional: If you have a name column

$password_hash = password_hash($password, PASSWORD_DEFAULT);

$stmt = $conn->prepare("INSERT INTO users (username, password_hash, role) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $username, $password_hash, $role);

if ($stmt->execute()) {
    echo "✅ Volunteer '$username' added successfully.";
} else {
    echo "❌ Failed to add volunteer: " . $stmt->error;
}
?>
