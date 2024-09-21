<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="css\style.css">
    
</head>
<body>
    <div class="header">
        <div class="logo">
            <h2>Yemek Kapımda</h2>
            <ul>
             
                <button class="btn-17"id= "modal_ac1">
                    <span class="text-container">
                        <span class="text">Kayıt ol</span>
                    </span>
                </button>

                <button class="btn-17 button" id= "modal_ac">
                    <span class="text-container">
                        <span class="text" >Giriş Yap</span>
                    </span>
                </button>
            </ul>
        </div>
    </div>

     
    <div class="hero">
        <h1>Acıktığında bir tıkla yanında!</h1>
        
    </div>

     <div class="modal">
        <form action="login.php" method="post">
            <h3 id="modal-kapat">x</h3>
            
            <label>E-posta:</label> 
            <input type="email" name="email" required><br>
            <label>şifre:</label> 
            <input type="password" name="password" required><br>
            <button type="submit" id="form-login" class="button1">Giriş Yap</button>

        </form>
     </div>
      
     <!-- Kayıt Modalı -->
    <div class="modal1">
        <form action="register.php" method="post">
            <h3 id="modal-kapat1" >x</h3>
            <label>İsminiz:</label> 
            <input type="text" name="name" required><br>
            <label>Soyadınız:</label> 
            <input type="text" name="surname" required><br>
            <label>E-posta:</label> 
            <input type="email" name="username" required><br>
            <label>Şifre:</label> 
            <input type="password" name="password" required><br>
            <button type="submit" class="button1" id="form-register">Kayıt Ol</button>
        </form>
    </div>
    
    <?php
    if (isset($_GET['status']) && $_GET['status'] == 'success') {
        echo "<p style='color: green;'>Kayıt başarılı! Giriş yapabilirsiniz.</p>";
    }
    ?>

    <script src="script.js"></script>


    
</body>
</html>
 