<?php
session_start();
include 'db.php';   

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$total_score = $_SESSION['puan'] ?? 0;

// Kullanıcının sınavı tamamlayıp tamamlamadığını kontrol et
$stmt = $pdo->prepare("SELECT has_completed_exam FROM users WHERE user_id = ?");
$stmt->execute([$user_id]);
$user_data = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user_data && $user_data['has_completed_exam']) {
    // Kullanıcı sınavı zaten tamamlamışsa
    echo "<!DOCTYPE html>
    <html lang='tr'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <link rel='stylesheet' href='style.css'>
        <title>Sonuçlar</title>
    </head>
    <body>
        <div class='container'>
            <h2>Sınav Sonuçlarınız</h2>
        
            <p>Malesef bir kez çözme hakkınız var. Tekrar sınav çözemezsiniz.</p>
        </div>
    </body>
    </html>";
    exit();
}

try {
    // Puanı veritabanına kaydet veya güncelle
    $stmt = $pdo->prepare("INSERT OR REPLACE INTO scores (user_id, total_score) VALUES (?, ?)");
    $success = $stmt->execute([$user_id, $total_score]);

    // Kullanıcı tablosundaki has_completed_exam değerini güncelle
    $stmt = $pdo->prepare("UPDATE users SET has_completed_exam = 1 WHERE user_id = ?");
    $stmt->execute([$user_id]);

    // Puan güncellemeyi tamamladı, oturumu temizle
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
        <p>Sınavı tamamladınız. Tekrar sınav çözemezsiniz.</p>
    </div>
</body>
</html>
