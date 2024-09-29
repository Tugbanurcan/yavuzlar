<?php
session_start();
include 'db_connection.php'; 


$errors = [];


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    
    $query = $conn->prepare("SELECT * FROM users WHERE username = :username");
    $query->bindParam(':username', $username);
    $query->execute();
    $user = $query->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        // Kullanıcıyı oturumda tut
        $_SESSION['user_id'] = $user['id'];
        
        header("Location: firma_dashboard.php");
        exit;
    } else {
        $errors[] = "Geçersiz kullanıcı adı veya şifre.";
    }
}

// Kayıt Ol işlemi
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
    $name = $_POST['name'];
    $surname = $_POST['surname'];
    $username = $_POST['reg_username'];
    $password = $_POST['reg_password'];
    $company_name = $_POST['company_name']; 

    // Kullanıcıdan alınan şirket adı ile company_id'yi bul
    $query = $conn->prepare("SELECT id FROM company WHERE name = :company_name");
    $query->bindParam(':company_name', $company_name);
    $query->execute();
    $company = $query->fetch(PDO::FETCH_ASSOC);

    if (!$company) {
        $errors[] = "Geçersiz şirket adı.";
    } else {
        $company_id = $company['id'];

        // Aynı kullanıcı adının olup olmadığını kontrol et
        $query = $conn->prepare("SELECT * FROM users WHERE username = :username");
        $query->bindParam(':username', $username);
        $query->execute();

        if ($query->rowCount() > 0) {
            $errors[] = "Bu kullanıcı adı zaten alınmış.";
        } else {
            // Şifreyi Argon2id ile şifrele
            $hashedPassword = password_hash($password, PASSWORD_ARGON2ID);

            // Yeni kullanıcıyı veritabanına ekle
            $query = $conn->prepare("INSERT INTO users (company_id, role, name, surname, username, password) 
                                    VALUES (:company_id, 'firma', :name, :surname, :username, :password)");
            $query->bindParam(':company_id', $company_id);
            $query->bindParam(':name', $name);
            $query->bindParam(':surname', $surname);
            $query->bindParam(':username', $username);
            $query->bindParam(':password', $hashedPassword);

            if ($query->execute()) {
                $_SESSION['user_id'] = $conn->lastInsertId(); // Son eklenen kullanıcı ID'si
                header("Location: firma_dashboard.php"); // Başarılı kayıt sonrası yönlendirme
                exit;
                
            } else {
                $errors[] = "Kayıt sırasında bir hata oluştu.";
            }
        }
    }
}

?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Firma Girişi ve Kayıt Ol</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .form-container {
            display: flex;
            justify-content: center;
            margin-top: 50px;
        }
        .form-wrapper {
            width: 400px;
        }
        .hidden {
            display: none;
        }
    </style>
</head>
<body>
<div class="container form-container">
    <div class="form-wrapper">
        <h2 class="text-center">Firma Giriş / Kayıt</h2>

        <!-- Hata Mesajları -->
        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <?php foreach ($errors as $error): ?>
                    <p><?php echo $error; ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <!-- Giriş Yap Formu -->
        <div id="loginForm">
            <h4>Giriş Yap</h4>
            <form action="firma_login_register.php" method="POST">
                <div class="mb-3">
                    <label for="username" class="form-label">Kullanıcı Adı</label>
                    <input type="text" class="form-control" name="username" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Şifre</label>
                    <input type="password" class="form-control" name="password" required>
                </div>
                <button type="submit" name="login" class="btn btn-primary">Giriş Yap</button>
            </form>
            <p class="mt-3">Hesabınız yok mu? <a href="javascript:void(0)" onclick="toggleForms()">Kayıt Ol</a></p>
        </div>

        <!-- Kayıt Ol Formu -->
        <div id="registerForm" class="hidden">
            <h4>Kayıt Ol</h4>
            <form action="firma_login_register.php" method="POST">
                <div class="mb-3">
                    <label for="name" class="form-label">Ad</label>
                    <input type="text" class="form-control" name="name" required>
                </div>
                <div class="mb-3">
                    <label for="surname" class="form-label">Soyad</label>
                    <input type="text" class="form-control" name="surname" required>
                </div>
                <div class="mb-3">
                    <label for="reg_username" class="form-label">Kullanıcı Adı</label>
                    <input type="text" class="form-control" name="reg_username" required>
                </div>
                <div class="mb-3">
                    <label for="reg_password" class="form-label">Şifre</label>
                    <input type="password" class="form-control" name="reg_password" required>
                </div>
                <div class="mb-3">
                    <label for="company_name" class="form-label">Firma Adı</label>
                    <input type="text" class="form-control" name="company_name" required>
                </div>
                <button type="submit" name="register" class="btn btn-success">Kayıt Ol</button>
            </form>
            <p class="mt-3">Zaten hesabınız var mı? <a href="javascript:void(0)" onclick="toggleForms()">Giriş Yap</a></p>
        </div>
    </div>
</div>

<script>
    function toggleForms() {
        var loginForm = document.getElementById("loginForm");
        var registerForm = document.getElementById("registerForm");
        loginForm.classList.toggle("hidden");
        registerForm.classList.toggle("hidden");
    }
</script>
</body>
</html>
