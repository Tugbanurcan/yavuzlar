<?php
session_start(); 

$host = 'localhost';   
$dbname = 'yemekdb';  
$username = 'root';  
$password = 'admin123'; 

try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $id = $_GET['id'];

    // Kullanıcıyı geri yükleme (silinen işaretini kaldırma)
    $sql = "UPDATE users SET deleted_at = NULL WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    $_SESSION['message'] = 'Kullanıcı başarıyla geri yüklendi.';
    header('Location: customer_management.php'); 
} catch (PDOException $e) {
    echo "Hata: " . $e->getMessage();
}
?>
