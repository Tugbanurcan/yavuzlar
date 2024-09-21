<?php
session_start(); // Oturum başlatma

// Kullanıcının oturum açıp açmadığını kontrol et
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

// Kullanıcının ID'sini kontrol et
if (!isset($_SESSION['user_id'])) {
    echo "Kullanıcı ID'si bulunamadı.";
    exit();
}

// Veritabanı bağlantısı
$dsn = 'mysql:host=localhost;dbname=yemekdb'; // MySQL bilgilerini girin
$username = 'root';
$password = 'admin123';

try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Veritabanı bağlantısı başarısız: " . $e->getMessage();
    exit();
}

// Sepete eklenen yemekleri veritabanına kaydetmek için AJAX çağrısını işlemek
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['food_id'])) {
    $food_id = intval($_POST['food_id']);
    $user_id = $_SESSION['user_id']; // Oturum açmış kullanıcının ID'sini al

    // Sepet tablosuna yemek ekle
    $stmt = $pdo->prepare('INSERT INTO basket (food_id, user_id, quantity) VALUES (:food_id, :user_id, 1) ON DUPLICATE KEY UPDATE quantity = quantity + 1');
    $stmt->execute(['food_id' => $food_id, 'user_id' => $user_id]);

    echo "Yemek sepete eklendi!";
    exit();
}

// Sepetteki yemekleri almak
$foods = $pdo->query('SELECT * FROM food')->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Anasayfa</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="css/styles.css"> <!-- CSS dosyanızı ekleyin -->
    <script>
        function addToBasket(foodId) {
            const formData = new FormData();
            formData.append('food_id', foodId);

            fetch('anasayfa.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                alert(data); // Sepete ekleme işlemi tamamlandığında kullanıcıya bilgi ver
            });
        }
    </script>
</head>
<body>
    <div class="header">
        <div class="logo">
            <h2>Yemek Kapımda</h2>
            <ul>
                <li>
                    <button class="btn-17">
                        <a href="profile.php"></a>
                        <span class="user-profile">Hoşgeldiniz, <?php echo htmlspecialchars($_SESSION['user']); ?>!</span>
                    </button>
                </li>
                <li>
                     
                    <a href="cart.php" class="btn" id="sepet">
                        <span class="text"></span>
                        <i class="fa-sharp-duotone fa-solid fa-basket-shopping" style="--fa-primary-color: #eb0a0a; --fa-secondary-color: #eb0a0a;"></i>
                    </a>
                     
                </li>
            </ul>
        </div>
    </div>

     
     
    <div class="yan-menu">
    <ul>
        <li><a class="active dropdown" href="anasayfa.php">Yemekler<span>&rsaquo;</span></a>
            <ul class="submenu">
                <li><a href="#corba">Çorbalar</a></li>
                <li><a href="#ana-yemek">Ana Yemekler</a></li>
                <li><a href="#tatli">Tatlılar</a></li>
            </ul>
        </li>
        <li><a href="famous.php">Popüler Lezzetler</a></li>
        <li>
            <a href="#contact" class="dropdown">Firmalar<span>&rsaquo;</span></a>
            <ul class="submenu">
                <li><a href="#corba">McDonald's</a></li>
                <li><a href="#ana-yemek">Domino's Pizza</a></li>
                <li><a href="#tatli">Komegena Çiğköfte</a></li>
                <li><a href="#tatli">Öncü Döner</a></li>
            </ul>
        </li>
        <li><a href="#about">Restorantlar</a></li>
        <li><a href="#about">Özel Kampanyalar</a></li>
        <li><a href="#about">Yardım ve Destek</a></li>
    </ul>
    </div>


    <section class="promotion">
    <h2>İndirimli Yemekler</h2>
    <div class="menuler">
        <?php 
         
        $stmt = $pdo->prepare("SELECT * FROM food WHERE category = 'İndirimli'");
        $stmt->execute();
        $foods = $stmt->fetchAll();

        foreach ($foods as $food): ?>
        <div class="menu">
            <img src="resim/<?php echo htmlspecialchars($food['image_path']); ?>" alt="<?php echo htmlspecialchars($food['name']); ?>" width="400" height="200">
            <h3><?php echo htmlspecialchars($food['name']); ?></h3>
            <span><del><?php echo htmlspecialchars($food['price'] + $food['discount']); ?></del>&nbsp; <?php echo htmlspecialchars($food['price']); ?></span>
            <button class="orderbtn" onclick="addToBasket(<?php echo htmlspecialchars($food['id']); ?>)">Sepete Ekle</button>
        </div>
        <?php endforeach; ?>
    </div>
</section>

    <script src="script.js"></script>
</body>
</html>