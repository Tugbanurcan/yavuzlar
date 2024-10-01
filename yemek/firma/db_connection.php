<?php
$host = 'db';   
$dbname = 'yemekdb';  
$username = 'root';  
$password = 'admin123';  

try {
    
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
     
    die("Veritabanı bağlantısı sağlanamadı: " . $e->getMessage());
}
?>