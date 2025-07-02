<?php
include __DIR__ . '/config/db.php';

$username = 'admin';
$password = 'admin';

$stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    echo "Entered Password: $password<br>";
    echo "Hashed Password from DB: " . $row['password_hash'] . "<br>";

    if (password_verify($password, $row['password_hash'])) {
        echo "<b>✅ Password is correct!</b>";
    } else {
        echo "<b>❌ Password is incorrect.</b>";
    }
} else {
    echo "❌ No user account found.";
}
