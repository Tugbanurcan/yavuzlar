<?php
session_start(); // Oturum başlatma

 
// Veritabanı bağlantısı
$dsn = 'mysql:host=localhost;dbname=yemekdb'; // MySQL kullanıyorsanız bilgileri girin
$username = 'root';
$password = 'admin123';

try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Veritabanı bağlantısı başarısız: " . $e->getMessage();
    exit();
}

// Formdan gelen veriler
$name = $_POST['name'] ?? '';
$surname = $_POST['surname'] ?? '';
$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';
 
 

if ($name && $surname && $username && $password) {
    // Şifreyi Argon2id algoritması ile hash'leyelim
    $hashed_password = password_hash($password, PASSWORD_ARGON2ID);

    // Veritabanına ekleme
    $stmt = $pdo->prepare('INSERT INTO users ( name, surname, username, password) 
                           VALUES (:name, :surname, :username, :password)');
    $stmt->execute([
        
        'name' => $name,
        'surname' => $surname,
        'username' => $username,
        'password' => $hashed_password
    ]);

  
     // Başarı mesajını ilet
    header("Location: index.php?status=success");
    
    exit();
} else {
    echo "Lütfen tüm alanları doldurun!";
}
?>

