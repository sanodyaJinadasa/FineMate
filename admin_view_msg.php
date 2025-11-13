<?php
session_start();
require 'db_connect.php';

$stmt = $pdo->prepare("SELECT * FROM contact_messages ORDER BY created_at DESC");
$stmt->execute(); 
$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
  <title>View Messages</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="assets/css/admin_view.css">
</head>
<body class="bg-light">
<?php include 'admin_header.php'; ?>
<div class="container mt-5">
  <h1 class="mb-4">Contact Messages</h1>

  <?php if (count($messages) > 0): ?>
    <table class="table table-bordered table-striped">
      <thead class="table-dark">
        <tr>
          <th>ID</th>
          <th>Name</th>
          <th>Email</th>
          <th>Message</th>
          <th>Date</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($messages as $msg): ?>
          <tr>
            <td><?= htmlspecialchars($msg['id']) ?></td>
            <td><?= htmlspecialchars($msg['name']) ?></td>
            <td><?= htmlspecialchars($msg['email']) ?></td>
            <td><?= nl2br(htmlspecialchars($msg['message'])) ?></td>
            <td><?= htmlspecialchars($msg['created_at']) ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php else: ?>
    <div class="alert alert-info">No messages found.</div>
  <?php endif; ?>
</div>
<?php include 'admin_footer.php'; ?>
</body>
</html>
