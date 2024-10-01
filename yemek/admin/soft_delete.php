<?php
session_start(); 

$host = 'db';   
$dbname = 'yemekdb';  
$username = 'root';  
$password = 'admin123'; 

$dsn = "mysql:host=$host;dbname=$dbname";

try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $id = $_GET['id'];

    // Kullanıcıyı soft silme
    $sql = "UPDATE users SET deleted_at = NOW() WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    
    $_SESSION['message'] = 'Kullanıcıyı başarıyla sildiniz.';
    header('Location: customer_management.php'); 
    exit();
} catch (PDOException $e) {
    echo "Hata: " . $e->getMessage();
}
?>

