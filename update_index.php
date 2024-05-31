<?php
include 'db.php';

$sukses = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $no_barang = $_POST['no_barang'] ?? '';
    $user = $_POST['user'] ?? '';
    $periode = $_POST['periode'] ?? '';

    // Pastikan semua data yang diperlukan tidak kosong
    if (empty($no_barang) || empty($user) || empty($periode)) {
        die("Semua data harus diisi");
    }

    // Update data di tabel index
    $sql_update = "UPDATE `index` SET user = ?, periode = ? WHERE no_barang = ?";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bind_param("sss", $user, $periode, $no_barang);

    if ($stmt_update->execute()) {
        $sukses = true;
        header("Location: detail_index.php?no_barang=" . urlencode($no_barang) . "&sukses=1");
        exit();
    } else {
        die("Gagal mengupdate data");
    }
}
?>
