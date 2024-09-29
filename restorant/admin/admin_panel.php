<?php
session_start();


if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Paneli</title>
</head>
<body>
    <h2>Admin Paneli</h2>
    <nav>
        <ul>
            <li><a href="customer_management.php">Müşteri Yönetimi</a></li>
            <li><a href="company_management.php">Firma Yönetimi</a></li>
            <li><a href="coupon_management.php">Kupon Yönetimi</a></li>
            <li><a href="logout.php">Çıkış Yap</a></li>
        </ul>
    </nav>
</body>
</html>