<?php
session_start(); 
include 'db_connection.php';  


$name = $_POST['name'] ?? '';
$surname = $_POST['surname'] ?? '';
$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';
 
 

if ($name && $surname && $username && $password) {
    
    $hashed_password = password_hash($password, PASSWORD_ARGON2ID);

    
    $stmt = $pdo->prepare('INSERT INTO users ( name, surname, username, password) 
                           VALUES (:name, :surname, :username, :password)');
    $stmt->execute([
        
        'name' => $name,
        'surname' => $surname,
        'username' => $username,
        'password' => $hashed_password
    ]);

   
    
    header("Location: index.php?status=success");
    
    exit();
} else {
    echo "Lütfen tüm alanları doldurun!";
}
?>

