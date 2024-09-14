<?php
// Veritabanı bağlantısı
try {
    $pdo = new PDO('sqlite:admin.db');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Bağlantı hatası: " . $e->getMessage());
}

 
function setupDatabase($pdo){
    try {
        
        $pdo->exec("CREATE TABLE IF NOT EXISTS users (
            user_id INTEGER PRIMARY KEY AUTOINCREMENT,
            username TEXT NOT NULL UNIQUE,
            role TEXT CHECK(role IN ('student', 'teacher')) NOT NULL,
            password TEXT NOT NULL, 
            has_completed_exam INTEGER DEFAULT 0 CHECK(has_completed_exam IN (0, 1))
             
        )");

        
        $pdo->exec("CREATE TABLE IF NOT EXISTS scores (
            user_id INTEGER PRIMARY KEY,
            total_score INTEGER DEFAULT 0,
            FOREIGN KEY (user_id) REFERENCES users(user_id)
        )");

        
        $pdo->exec("CREATE TABLE IF NOT EXISTS questions (
            question_id INTEGER PRIMARY KEY AUTOINCREMENT,
            question TEXT NOT NULL,
            option1 TEXT NOT NULL,
            option2 TEXT NOT NULL,
            option3 TEXT NOT NULL,
            option4 TEXT NOT NULL,
            correct TEXT NOT NULL,
            difficulty INTEGER NOT NULL
        )");

         
 
    } catch (PDOException $e) {
        die("Veritabanı oluşturulamadı: " . $e->getMessage());
    }
}

// Veritabanını  oluştur
setupDatabase($pdo)
?>
