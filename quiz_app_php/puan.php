<?php
session_start();
include 'db.php';   

// Kullanıcı oturumunun açık olduğundan emin olun
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

try {
     
    $stmt = $pdo->prepare("
        SELECT users.username, COALESCE(scores.total_score, 0) AS total_score
        FROM users
        LEFT JOIN scores ON users.user_id = scores.user_id
        WHERE users.role = 'student'   
        ORDER BY total_score DESC
    ");
    $stmt->execute();
    $scores_data = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Veritabanı hatası: " . htmlspecialchars($e->getMessage()) . "<br>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Öğrenci Puanları</title>
</head>
<body>
    <div class="container">
        <a href="student.php"><button class="large-button">Öğrenci Paneline Dön</button></a>
        <h2>Öğrenci Puanları</h2>
        <?php if ($scores_data): ?>
            <table>
                <thead>
                    <tr>
                        <th>Kullanıcı Adı</th>
                        <th>Toplam Puan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($scores_data as $score): ?>
                        <tr>
                            <td><?= htmlspecialchars($score['username']) ?></td>
                            <td><?= htmlspecialchars($score['total_score']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Hiç öğrenci puanı bulunamadı.</p>
        <?php endif; ?>
    </div>
</body>
</html>
