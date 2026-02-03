<?php
include 'config.php';

// Cek apakah admin sudah login
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

$id = $_GET['id'];
$result = mysqli_query($conn, "SELECT * FROM absensi WHERE id = '$id'");
$data = mysqli_fetch_assoc($result);

if (!$data) {
    header("Location: rekap.php");
    exit;
}

$message = "";

if (isset($_POST['update'])) {
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $keterangan = $_POST['keterangan'];

    $query = "UPDATE absensi SET nama = '$nama', keterangan = '$keterangan' WHERE id = '$id'";
    if (mysqli_query($conn, $query)) {
        header("Location: rekap.php");
        exit;
    } else {
        $message = "<div class='alert danger'>❌ Gagal mengupdate data.</div>";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Absensi - Bintang Salsa</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; font-family: 'Poppins', sans-serif; }
        body { background: #f0f2f5; margin: 0; padding: 0; display: flex; align-items: center; justify-content: center; min-height: 100vh; }
        .card { background: #fff; padding: 30px; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); width: 100%; max-width: 400px; text-align: center; }
        h2 { margin-top: 0; color: #333; font-weight: 600; }
        input, select, button { width: 100%; padding: 12px; margin: 8px 0; border-radius: 10px; border: 1px solid #ddd; font-size: 15px; outline: none; }
        button { background: #ffc107; color: #212529; border: none; font-weight: 600; cursor: pointer; margin-top: 15px; }
        .btn-batal { display: block; margin-top: 10px; text-decoration: none; color: #666; font-size: 14px; }
        .alert { padding: 12px; border-radius: 10px; margin-bottom: 20px; font-size: 14px; background: #f8d7da; color: #721c24; }
    </style>
</head>
<body>
    <div class="card">
        <h2>Edit Data</h2>
        <?php echo $message; ?>
        <form method="POST">
            <input type="text" name="nama" value="<?php echo $data['nama']; ?>" required>
            <select name="keterangan" required>
                <option value="Hadir" <?php if($data['keterangan'] == 'Hadir') echo 'selected'; ?>>Hadir</option>
                <option value="Izin" <?php if($data['keterangan'] == 'Izin') echo 'selected'; ?>>Izin</option>
                <option value="Sakit" <?php if($data['keterangan'] == 'Sakit') echo 'selected'; ?>>Sakit</option>
                <option value="Alfa" <?php if($data['keterangan'] == 'Alfa') echo 'selected'; ?>>Alfa</option>
            </select>
            <button type="submit" name="update">Update Data</button>
            <a href="rekap.php" class="btn-batal">Batal</a>
        </form>
    </div>
</body>
</html>