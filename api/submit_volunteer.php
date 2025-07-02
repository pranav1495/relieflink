<?php
include '../includes/db.php';

$name = $_POST['name'];
$phone = $_POST['phone'];
$location = $_POST['location'];
$resource = $_POST['resource'];

$stmt = $conn->prepare("INSERT INTO volunteers (name, phone, location, resource) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $name, $phone, $location, $resource);
if ($stmt->execute()) {
  echo "Thank you for volunteering!";
} else {
  echo "Error submitting your contribution.";
}
?>
