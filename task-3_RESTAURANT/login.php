<?php
session_start(); // Oturumu başlatıyoruz

// Veritabanı bağlantısı
$dsn = 'mysql:host=localhost;dbname=yemekdb'; // MySQL bilgilerini girin
$username_db = 'root'; // Veritabanı kullanıcı adı
$password_db = 'admin123'; // Veritabanı şifresi

try {
    $pdo = new PDO($dsn, $username_db, $password_db);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Veritabanı bağlantısı başarısız: " . $e->getMessage();
    exit();
}

// Formdan gelen verileri kontrol et
if (isset($_POST['email']) && isset($_POST['password'])) {
    // POST verilerini al
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (!empty($email) && !empty($password)) {
        // E-posta ile kullanıcıyı bul
        $stmt = $pdo->prepare('SELECT * FROM users WHERE username = :email');
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Şifreyi doğrula
        if ($user && password_verify($password, $user['password'])) {
            // Giriş başarılı, oturumu başlat
            $_SESSION['user'] = $user['name'];
            $_SESSION['user_id'] = $user['id']; // Kullanıcı ID'sini oturum değişkenine ekleyin
            header("Location: anasayfa.php");
            exit();
        } else {
            echo "Hatalı e-posta veya şifre!";
        }
    } else {
        echo "E-posta veya şifre boş olamaz!";
    }
} else {
    echo "Lütfen e-posta ve şifrenizi girin!";
}
?>
