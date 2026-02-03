<?php
include 'config.php';

if (isset($_SESSION['admin'])) {
    header("Location: index.php");
    exit;
}

$error = "";

if (isset($_POST['login'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    $result = mysqli_query($conn, "SELECT * FROM admin WHERE username = '$username'");
    if (mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);
        if (password_verify($password, $row['password'])) {
            $_SESSION['admin'] = $row['username'];
            header("Location: index.php");
            exit;
        }
    }
    $error = "❌ Username atau Password salah!";
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - Bintang Salsa</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; font-family: 'Poppins', sans-serif; }
        body { background: #f0f2f5; margin: 0; padding: 0; display: flex; align-items: center; justify-content: center; min-height: 100vh; }
        
        .card { background: #fff; padding: 30px; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); width: 100%; max-width: 350px; text-align: center; }
        
        h2 { margin-top: 0; color: #333; font-weight: 600; font-size: 22px; }
        p { color: #666; font-size: 14px; margin-bottom: 25px; }

        input, button { width: 100%; padding: 12px; margin: 8px 0; border-radius: 10px; border: 1px solid #ddd; font-size: 15px; outline: none; transition: 0.3s; }
        input:focus { border-color: #007bff; box-shadow: 0 0 0 3px rgba(0,123,255,0.1); }
        
        button { background: #007bff; color: #fff; border: none; font-weight: 600; cursor: pointer; margin-top: 15px; }
        button:hover { background: #0056b3; }

        .error { background: #f8d7da; color: #721c24; padding: 10px; border-radius: 10px; font-size: 13px; margin-bottom: 15px; border: 1px solid #f5c6cb; }
        
        @media (max-width: 480px) {
            .card { width: 90%; padding: 25px; }
        }
    </style>
</head>
<body>
    <div class="card">
        <h2>Admin Login</h2>
        <p>Bintang Salsa Grup</p>
        <?php if($error) echo "<div class='error'>$error</div>"; ?>
        <form method="POST">
            <input type="text" name="username" placeholder="Username" required autocomplete="off">
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" name="login">Masuk</button>
        </form>
    </div>
</body>
</html>