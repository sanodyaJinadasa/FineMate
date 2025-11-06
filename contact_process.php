<?php
require 'db_connect.php'; // your PDO connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $message = trim($_POST['message']);

    if (empty($name) || empty($email) || empty($message)) {
        echo "<script>
                alert('Please fill in all fields.');
                window.history.back();
              </script>";
        exit;
    }

    try {
        $stmt = $pdo->prepare("
            INSERT INTO contact_messages (name, email, message)
            VALUES (?, ?, ?)
        ");
        $stmt->execute([$name, $email, $message]);

        echo "<script>
                alert('Message sent successfully! Thank you for contacting us.');
                window.location.href = 'home_page.php';
              </script>";
    } catch (PDOException $e) {
        echo "<script>
                alert('Error: Could not send message. Please try again later.');
                window.history.back();
              </script>";
    }
}
?>
