<?php
session_start();
if (!isset($_SESSION['username'])) {
  header("Location: ../auth/login.php");
  exit();
}
$receiver = $_GET['to'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Chat | ReliefLink</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
  <link rel="stylesheet" href="styles.css">
</head>
<body>
<div class="chat-container">
  <div class="chat-header">
    <h4>Chat with <?= htmlspecialchars($receiver) ?></h4>
  </div>
  <div id="chat-box" class="chat-box"></div>
  <form id="message-form">
    <input type="hidden" name="receiver" value="<?= htmlspecialchars($receiver) ?>">
    <input type="text" name="message" placeholder="Type your message..." class="form-control" required>
    <button type="submit" class="btn btn-success mt-2 w-100">Send</button>
  </form>
</div>

<script>
const form = document.getElementById("message-form");
form.onsubmit = async (e) => {
  e.preventDefault();
  const data = new FormData(form);
  await fetch("send_message.php", { method: "POST", body: data });
  form.message.value = "";
  loadMessages();
};

async function loadMessages() {
  const res = await fetch("fetch_messages.php?with=<?= $receiver ?>");
  const html = await res.text();
  document.getElementById("chat-box").innerHTML = html;
}
setInterval(loadMessages, 2000);
loadMessages();
</script>
</body>
</html>
