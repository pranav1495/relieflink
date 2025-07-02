<?php
include '../config/db.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_type = $_POST['user_type'] ?? '';
    $username  = $_POST['username'] ?? '';
    $password  = $_POST['password'] ?? '';

    if (empty($user_type) || empty($username) || empty($password)) {
        echo json_encode(["status" => "error", "message" => "All fields required"]);
        exit;
    }

    $table = $user_type === 'admin' ? 'admins' : 'volunteers_login';
    $id_col = $user_type === 'admin' ? 'admin_id' : 'volunteer_id';

    $stmt = $conn->prepare("SELECT * FROM $table WHERE $id_col = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        if (password_verify($password, $row['password_hash'])) {
            echo json_encode(["status" => "success", "message" => "Login successful", "role" => $user_type]);
        } else {
            echo json_encode(["status" => "error", "message" => "Incorrect password"]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Account not found"]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request"]);
}
?>
