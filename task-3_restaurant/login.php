<?php
session_start();  

 
$dsn = 'mysql:host=localhost;dbname=yemekdb';  
$username_db = 'root';  
$password_db = 'admin123';  

try {
    $pdo = new PDO($dsn, $username_db, $password_db);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Veritabanı bağlantısı başarısız: " . $e->getMessage();
    exit();
}
 
if (isset($_POST['email']) && isset($_POST['password'])) {
     
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (!empty($email) && !empty($password)) {
         
        $stmt = $pdo->prepare('SELECT * FROM users WHERE username = :email');
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
 
        if ($user && password_verify($password, $user['password'])) {
            
            $_SESSION['user'] = $user['name'];
            $_SESSION['user_id'] = $user['id'];  
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
