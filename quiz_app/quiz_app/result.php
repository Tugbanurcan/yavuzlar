<?php
session_start();
include 'db.php';

 
if (!isset($_SESSION['user_id'])) {
    header("Location: start.php");
    exit();
}

// Kullanıcının puanını al
$puan = $_SESSION['puan'] ?? 0;

 
$user_id = $_SESSION['user_id'];
$questions = $_SESSION['questions'] ?? [];
$statement = $db->prepare("INSERT INTO submissions (user_id, question_id, points) VALUES (?, ?, ?)");
foreach ($questions as $question) {
    $statement->execute([$user_id, $question['id'], $puan]);
}

// Oturumu temizle
session_unset();
session_destroy();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Sonuç</title>
</head>
<body>
    <div class="container">
        <h2>Yarışma Sonucu</h2>
        <p>Toplam Puanınız: <?= htmlspecialchars($puan) ?></p>
        <a href="start.php"><button class="large-button">Yarışmaya Yeniden Başla</button></a>
    </div>
</body>
</html>
