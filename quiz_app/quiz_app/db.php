<?php
try {
    $db = new PDO('sqlite:adminn.db');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    
    $db->exec("CREATE TABLE IF NOT EXISTS users (id INTEGER PRIMARY KEY, username TEXT, role TEXT)");
    $db->exec("CREATE TABLE IF NOT EXISTS questions (id INTEGER PRIMARY KEY, question TEXT, option1 TEXT, option2 TEXT, option3 TEXT, option4 TEXT, correct INTEGER, difficulty TEXT)");
    $db->exec("CREATE TABLE IF NOT EXISTS submissions (id INTEGER PRIMARY KEY, user_id INTEGER, question_id INTEGER, points INTEGER, FOREIGN KEY(user_id) REFERENCES users(id))");

     
} catch (PDOException $e) {
    echo "Veritabanı hatası: " . $e->getMessage();
    exit;  
}
?>