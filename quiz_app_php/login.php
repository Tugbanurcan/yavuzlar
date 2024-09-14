<?php
include 'db.php';
session_start();

$error = ''; 
$role = '';

// Eğer form gönderildiyse
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'] ?? '';
    $role = $_POST['role'] ?? '';
    $password = $_POST['password'] ?? '';

    
    if (empty($username) || empty($role) || empty($password)) {
        $error = "Kullanıcı adı ve rol gerekli!";
    } else {
        if ($role == 'teacher') {
            
            try {
                $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? AND role = ?");
                $stmt->execute([$username, $role]);
                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($user) {
                    
                    if (empty($password)) {
                        $error = "Şifre gerekli!";
                    } else {
                        
                        if ($password === $user['password']) {
                            $_SESSION['user_id'] = $user['user_id']; // user_id'yi oturumda sakla
                            $_SESSION['username'] = $username;
                            $_SESSION['role'] = $role;
                            header("Location: admin.php");
                            exit();
                        } else {
                            $error = "Yanlış şifre!";
                        }
                    }
                } else {
                    $error = "Kullanıcı bulunamadı!";
                }
            } catch (PDOException $e) {
                $error = "Veritabanı hatası: " . $e->getMessage();
            }
        } else if ($role == 'student') {
            
            try {
                $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? AND role = ?");
                $stmt->execute([$username, $role]);
                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                if (!$user) {
                    // Öğrenci veritabanında yoksa, yeni kullanıcı olarak ekle
                    $stmt = $pdo->prepare("INSERT INTO users (username, role,password) VALUES (?, ? , ?)");
                    $stmt->execute([$username, $role, $password]);

                    // Yeni eklenen kullanıcının user_id'sini al
                    $user_id = $pdo->lastInsertId();
                     
                } else {
                    // Eğer öğrenci zaten varsa, mevcut user_id'yi al
                    $user_id = $user['user_id'];
                }

                // Öğrenciyi oturumda sakla ve yönlendir
                $_SESSION['user_id'] = $user_id; // user_id'yi oturumda sakla
                $_SESSION['username'] = $username;
                $_SESSION['role'] = $role;
                $_SESSION['password'] = $password;
                header("Location: student.php");
                exit();
            } catch (PDOException $e) {
                $error = "Veritabanı hatası: " . $e->getMessage();
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Hoşgeldiniz! Bize kendinizi tanıtınız...</title>
</head>
<body>
    <div class="container">
        <h1>Hoşgeldiniz! Bize kendinizi tanıtınız...</h1>
        <!-- Hata mesajını göster -->
        <?php if (!empty($error)) : ?>
            <div class="error" style="color: red;"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <input type="text" name="username" placeholder="Kullanıcı Adı" required>
            <select name="role" id="role" required>
                <option value="">Rol Seçin</option>
                <option value="student" >Öğrenci</option>
                <option value="teacher" >Öğretmen</option>
            </select>
            
                <input type="password" name="password" placeholder="Şifre" required>
             
            <button type="submit">Başla</button>
        </form>
    </div>
</body>
</html>
