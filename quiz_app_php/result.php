<?php
session_start();
include 'db.php';   


if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$total_score = $_SESSION['puan'] ?? 0;

 

try {
    // Puanı veritabanına kaydet veya güncelle
    $stmt = $pdo->prepare("INSERT OR REPLACE INTO scores (user_id, total_score) VALUES (?, ?)");
    $success = $stmt->execute([$user_id, $total_score]);

     
    // Puan güncellemeyi tamamladık, oturumu temizle
    unset($_SESSION['puan']);
    unset($_SESSION['questions']);
} catch (PDOException $e) {
    echo "Veritabanı hatası: " . htmlspecialchars($e->getMessage()) . "<br>";
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Sonuçlar</title>
</head>
<body>
    <div class="container">
        <h2>Sonuçlarınız</h2>
        <p>Toplam Puanınız: <?= htmlspecialchars($total_score) ?></p>
    </div>
</body>
</html>
