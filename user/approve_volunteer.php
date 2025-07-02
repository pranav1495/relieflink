<?php
// approve_volunteer.php
session_start();
include __DIR__ . '/../config/db.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

if (!isset($_GET['id'])) {
    echo "Invalid request.";
    exit();
}

$enquiry_id = intval($_GET['id']);

// Get enquiry details
$enquiry = $conn->prepare("SELECT * FROM venquiry WHERE id = ? AND status = 'pending'");
$enquiry->bind_param("i", $enquiry_id);
$enquiry->execute();
$result = $enquiry->get_result();

if ($result->num_rows === 0) {
    echo "Volunteer enquiry not found or already processed.";
    exit();
}

$data = $result->fetch_assoc();

// Insert into users table
$stmt = $conn->prepare("INSERT INTO users (username, password_hash, role, full_name, email, phone, photo) VALUES (?, ?, 'volunteer', ?, ?, ?, ?)");
$stmt->bind_param(
    "ssssss",
    $data['username'],
    $data['password'],
    $data['name'],
    $data['email'],
    $data['phone'],
    $data['photo']
);

if ($stmt->execute()) {
    // Update venquiry status
    $conn->query("UPDATE venquiry SET status = 'approved' WHERE id = $enquiry_id");
    header("Location: ../user/admin/volunteers.php?success=approved");
} else {
    echo "Failed to approve: " . $stmt->error;
}
