<?php
include 'config.php';

// Cek apakah admin sudah login
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    mysqli_query($conn, "DELETE FROM absensi WHERE id = '$id'");
}

header("Location: rekap.php");
exit;
?>