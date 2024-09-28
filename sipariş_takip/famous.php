<?php
session_start();  

 
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

 
if (!isset($_SESSION['user_id'])) {
    echo "Kullanıcı ID'si bulunamadı.";
    exit();
}

 
$dsn = 'mysql:host=localhost;dbname=yemekdb';  
$username = 'root';
$password = 'admin123';

try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Veritabanı bağlantısı başarısız: " . $e->getMessage();
    exit();
}

 
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['food_id'])) {
    $food_id = intval($_POST['food_id']);
    $user_id = $_SESSION['user_id'];

    
    $stmt = $pdo->prepare('INSERT INTO basket (food_id, user_id, quantity) VALUES (:food_id, :user_id, 1) ON DUPLICATE KEY UPDATE quantity = quantity + 1');
    $stmt->execute(['food_id' => $food_id, 'user_id' => $user_id]);

    echo "Yemek sepete eklendi!";
    exit();
}

 
$stmt = $pdo->prepare("SELECT * FROM food WHERE category = 'famous'");
$stmt->execute();
$foods = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Popüler Lezzetler</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="css/styles.css">  
    <script>
        function addToBasket(foodId) {
            const formData = new FormData();
            formData.append('food_id', foodId);

            fetch('famous.php', {   
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                alert(data);  
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
                    <span class="user-profile"><?php echo htmlspecialchars($_SESSION['user']); ?>!</span>
                </button>
            </li>
            <li>
                <a href="cart.php" class="btn" id="sepet">
                    <i class="fa-solid fa-basket-shopping"></i>
                </a>
            </li>
        </ul>
    </div>
</div>

<div class="yan-menu">
    <ul>
        <li><a class="dropdown" href="anasayfa.php">Yemekler<span>&rsaquo;</span></a>
            <ul class="submenu">
                <li><a href="corba.php">Çorbalar</a></li>
                <li><a href="ana.php">Ana Yemekler</a></li>
                <li><a href="tatli.php">Tatlılar</a></li>
            </ul>
        </li>
        <li><a href="famous.php" class="active">Popüler Lezzetler</a></li>
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

<section class="famous">
    <h2>Popüler Lezzetler</h2>
    <div class="menuler">
        <?php foreach ($foods as $food): ?>
        <div class="menu">
            <img src="resim/<?php echo htmlspecialchars($food['image_path']); ?>" alt="<?php echo htmlspecialchars($food['name']); ?>" width="350" height="200">
            <h3><?php echo htmlspecialchars($food['name']); ?></h3>
            <span><?php echo htmlspecialchars($food['price']); ?> TL</span>
            <button class="orderbtn" onclick="addToBasket(<?php echo $food['id']; ?>)">Sepete Ekle</button>
        </div>
        <?php endforeach; ?>

         
    </div>
</section>

</body>
</html>
