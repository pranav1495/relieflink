<?php
include __DIR__ . '/../config/db.php';
header("Content-Type: application/json");

// Only accept POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(["status" => "error", "message" => "Invalid request method."]);
    exit();
}

// Collect and sanitize inputs
$name     = trim($_POST['name'] ?? '');
$email    = trim($_POST['email'] ?? '');
$phone    = trim($_POST['phone'] ?? '');
$location = trim($_POST['location'] ?? '');
$need     = trim($_POST['need'] ?? '');

// Check for required fields
if (!$name || !$email || !$phone || !$location || !$need) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "All fields are required."]);
    exit();
}

// Optionally validate email format
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Invalid email format."]);
    exit();
}

// Prepare and execute insert statement
$stmt = $conn->prepare(
    "INSERT INTO victims (name, email, phone, location, need, status) 
     VALUES (?, ?, ?, ?, ?, 0)" // 0 = Pending
);
$stmt->bind_param("sssss", $name, $email, $phone, $location, $need);

if (!$stmt->execute()) {
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => "Failed to save request."]);
    $stmt->close();
    $conn->close();
    exit();
}

// Send email notifications to volunteers
$volunteers = $conn->query("SELECT email FROM users WHERE role = 'volunteer' AND email IS NOT NULL");

$subject = "🚨 Emergency Help Request";
$message = <<<EOD
A new help request has been submitted:

Name: $name
Email: $email
Phone: $phone
Location: $location
Need: $need

Please log in to your dashboard to view and assist.

— Relief-Link
EOD;

$headers = "From: no-reply@relieflink.org\r\n";
$headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

while ($vol = $volunteers->fetch_assoc()) {
    @mail($vol['email'], $subject, $message, $headers);
}

// Respond success
echo json_encode(["status" => "success", "message" => "Request submitted and emails sent."]);

$stmt->close();
$conn->close();
?>
