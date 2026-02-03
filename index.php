<?php
include 'config.php';

// Cek apakah sudah login sebelum bisa isi absen
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

$message = "";

if (isset($_POST['absen'])) {
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $keterangan = $_POST['keterangan'];
    $tanggal = date('Y-m-d');
    $waktu = date('H:i:s');

    $query = "INSERT INTO absensi (nama, tanggal, waktu, keterangan) VALUES ('$nama', '$tanggal', '$waktu', '$keterangan')";
    if (mysqli_query($conn, $query)) {
        $message = "<div class='alert success'>✅ Absensi berhasil dicatat!</div>";
    } else {
        $message = "<div class='alert danger'>❌ Error: " . mysqli_error($conn) . "</div>";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Absensi Bintang Salsa</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; font-family: 'Poppins', sans-serif; }
        body { background: #f0f2f5; margin: 0; padding: 0; display: flex; flex-direction: column; min-height: 100vh; }
        
        nav { background: #ffffff; padding: 15px; display: flex; justify-content: center; gap: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); position: sticky; top: 0; z-index: 100; }
        nav a { text-decoration: none; color: #555; font-weight: 500; font-size: 14px; padding: 5px 10px; border-radius: 5px; transition: 0.3s; }
        nav a:hover { background: #f0f0f0; }
        nav a.active { color: #007bff; border-bottom: 2px solid #007bff; border-radius: 0; }
        nav a.logout { color: #dc3545; }

        .container { flex: 1; display: flex; align-items: center; justify-content: center; padding: 20px; }
        .card { background: #fff; padding: 30px; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); width: 100%; max-width: 400px; text-align: center; }
        
        h2 { margin-top: 0; color: #333; font-weight: 600; }
        p { color: #666; font-size: 14px; margin-bottom: 25px; }

        input, select, button { width: 100%; padding: 12px; margin: 8px 0; border-radius: 10px; border: 1px solid #ddd; font-size: 15px; outline: none; transition: 0.3s; }
        input:focus, select:focus { border-color: #007bff; box-shadow: 0 0 0 3px rgba(0,123,255,0.1); }
        
        button { background: #007bff; color: #fff; border: none; font-weight: 600; cursor: pointer; margin-top: 15px; box-shadow: 0 4px 6px rgba(0,123,255,0.2); }
        button:hover { background: #0056b3; transform: translateY(-1px); }
        button:active { transform: translateY(0); }

        .alert { padding: 12px; border-radius: 10px; margin-bottom: 20px; font-size: 14px; }
        .success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .danger { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }

        @media (max-width: 480px) {
            .card { padding: 20px; }
            h2 { font-size: 20px; }
        }
    </style>
</head>
<body>
    <nav>
        <a href="index.php" class="active">Absen</a>
        <a href="rekap.php">Rekap</a>
        <a href="logout.php" class="logout">Keluar</a>
    </nav>
    <div class="container">
        <div class="card">
            <h2>Bintang Salsa</h2>
            <p>Form Absensi Team Odol</p>
            <?php echo $message; ?>
            <form method="POST">
                <input type="text" name="nama" placeholder="Nama Lengkap" required autocomplete="off">
                <select name="keterangan" required>
                    <option value="Hadir">Hadir</option>
                </select>
                <button type="submit" name="absen">Kirim Absensi</button>
            </form>
        </div>
    </div>
</body>
</html>