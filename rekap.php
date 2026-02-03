<?php
include 'config.php';

// Cek apakah admin sudah login
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

// Ambil filter tanggal jika ada, jika tidak kosongkan (tampilkan semua)
$filter_tgl = isset($_GET['tgl']) ? $_GET['tgl'] : '';
$where = "";
if ($filter_tgl != '') {
    $where = "WHERE tanggal = '$filter_tgl'";
}

$query = "SELECT * FROM absensi $where ORDER BY tanggal DESC, waktu DESC";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekap Absensi - Bintang Salsa</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; font-family: 'Poppins', sans-serif; }
        body { background: #f0f2f5; margin: 0; padding: 0; }
        
        nav { background: #ffffff; padding: 15px; display: flex; justify-content: center; gap: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); position: sticky; top: 0; z-index: 100; }
        nav a { text-decoration: none; color: #555; font-weight: 500; font-size: 14px; padding: 5px 10px; border-radius: 5px; transition: 0.3s; }
        nav a:hover { background: #f0f0f0; }
        nav a.active { color: #007bff; border-bottom: 2px solid #007bff; border-radius: 0; }
        nav a.logout { color: #dc3545; }

        .container { padding: 20px; max-width: 900px; margin: auto; }
        .card { background: #fff; padding: 25px; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        
        h2 { margin-top: 0; color: #333; font-size: 20px; text-align: center; }
        
        .filter-box { background: #f8f9fa; padding: 15px; border-radius: 10px; margin-bottom: 20px; display: flex; flex-wrap: wrap; gap: 10px; align-items: center; justify-content: center; }
        .filter-box input[type="date"] { padding: 8px; border-radius: 5px; border: 1px solid #ddd; outline: none; }
        .btn-cari { background: #007bff; color: white; border: none; padding: 8px 15px; border-radius: 5px; cursor: pointer; font-size: 14px; }
        .btn-reset { background: #6c757d; color: white; text-decoration: none; padding: 8px 15px; border-radius: 5px; font-size: 14px; }

        .table-responsive { overflow-x: auto; -webkit-overflow-scrolling: touch; }
        table { width: 100%; border-collapse: collapse; min-width: 600px; }
        th, td { padding: 12px 15px; text-align: left; font-size: 14px; border-bottom: 1px solid #eee; }
        th { background: #f8f9fa; color: #333; font-weight: 600; }
        
        .status { padding: 4px 8px; border-radius: 6px; font-size: 12px; font-weight: 600; }
        .status-hadir { background: #d4edda; color: #155724; }
        .status-izin { background: #fff3cd; color: #856404; }
        .status-sakit { background: #d1ecf1; color: #0c5460; }
        .status-alfa { background: #f8d7da; color: #721c24; }

        .btn-action { text-decoration: none; font-size: 11px; padding: 5px 8px; border-radius: 5px; color: #fff; margin-right: 5px; }
        .btn-edit { background: #ffc107; color: #212529; }
        .btn-hapus { background: #dc3545; }

        @media (max-width: 480px) {
            .container { padding: 10px; }
            .card { padding: 15px; }
            th, td { padding: 10px 8px; font-size: 13px; }
            .filter-box { flex-direction: column; width: 100%; }
            .filter-box input, .filter-box button, .filter-box a { width: 100%; text-align: center; }
        }
    </style>
</head>
<body>
    <nav>
        <a href="index.php">Absen</a>
        <a href="rekap.php" class="active">Rekap</a>
        <a href="logout.php" class="logout">Keluar</a>
    </nav>
    <div class="container">
        <div class="card">
            <h2>Riwayat</h2>
            
            <!-- Filter Tanggal -->
            <form method="GET" class="filter-box">
                <label style="font-size: 14px; color: #555;">Pilih Tanggal:</label>
                <input type="date" name="tgl" value="<?php echo $filter_tgl; ?>">
                <button type="submit" class="btn-cari">Cari Data</button>
                <?php if($filter_tgl != ''): ?>
                    <a href="rekap.php" class="btn-reset">Tampilkan Semua</a>
                <?php endif; ?>
            </form>

            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Tanggal & Jam</th>
                            <th>Keterangan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(mysqli_num_rows($result) > 0): ?>
                            <?php $no = 1; while($row = mysqli_fetch_assoc($result)): ?>
                            <tr>
                                <td><?php echo $no++; ?></td>
                                <td style="font-weight: 500; color: #333;"><?php echo $row['nama']; ?></td>
                                <td style="color: #444; font-size: 13px;">
                                    <strong><?php echo date('d-m-Y', strtotime($row['tanggal'])); ?></strong>
                                    <small style="display: block; color: #888;"><?php echo date('H:i', strtotime($row['waktu'])); ?> WIB</small>
                                </td>
                                <td>
                                    <span class="status status-<?php echo strtolower($row['keterangan']); ?>">
                                        <?php echo $row['keterangan']; ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="edit.php?id=<?php echo $row['id']; ?>" class="btn-action btn-edit">Edit</a>
                                    <a href="hapus.php?id=<?php echo $row['id']; ?>" class="btn-action btn-hapus" onclick="return confirm('Yakin ingin menghapus data ini?')">Hapus</a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" style="text-align:center; padding: 40px; color: #999;">Tidak ada data absensi.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>