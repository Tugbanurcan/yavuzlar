<?php
session_start();
include 'db.php';

// Oturumdaki kullanıcı bilgilerini kontrol et
if (isset($_SESSION['user_id'])) {
    header("Location: quiz.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Kullanıcı adı ve rol al ve oturumu başlat
    $username = trim($_POST['username']);
    $role = trim($_POST['role']);
    if (!empty($username) && !empty($role)) {
        // Kullanıcıyı veritabanına ekle
        $stmt = $db->prepare("INSERT INTO users (username, role) VALUES (?, ?)");
        $stmt->execute([$username, $role]);
        $_SESSION['user_id'] = $db->lastInsertId();
        $_SESSION['username'] = $username;
        $_SESSION['puan'] = 0;  // Puanı sıfırla
        $_SESSION['questions'] = []; // Soruları oturumda sakla
        header("Location: quiz.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Yarışma Başlangıcı</title>
</head>
<body>
    <div class="container">
        <h1>Yarışmaya Başla</h1>
        <form method="POST" action="start.php">
            <input type="text" name="username" placeholder="Kullanıcı Adı" required>
            <select name="role" required>
                <option value="">Rol Seçin</option>
                <option value="student">Öğrenci</option>
                <option value="teacher">Öğretmen</option>
                 
            </select>
            <button type="submit">Başla</button>
        </form>
    </div>
</body>
</html>
