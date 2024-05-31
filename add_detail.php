<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $no_barang = $_POST['no_barang'] ?? '';
    $nama_barang = $_POST['nama_barang'] ?? '';
    $jumlah = $_POST['jumlah'] ?? 0;
    $keterangan2 = $_POST['ket2'] ?? '';

    // Cek apakah no_barang valid
    if (empty($no_barang)) {
        die("Nomor barang tidak valid");
    }

    // Hitung jumlah barang saat ini untuk no_barang tersebut
    $sql_count = "SELECT COUNT(*) as total FROM detail WHERE no_barang = ?";
    $stmt_count = $conn->prepare($sql_count);
    $stmt_count->bind_param("s", $no_barang);
    $stmt_count->execute();
    $result_count = $stmt_count->get_result();
    $row_count = $result_count->fetch_assoc();
    $current_count = $row_count['total'];

    if ($current_count >= 10) {
        die("Tidak dapat menambahkan lebih dari 10 barang untuk nomor ini");
    }

    // Insert detail barang baru
    $sql_insert = "INSERT INTO detail (no_barang, nama_barang, jumlah, keterangan2) VALUES (?, ?, ?, ?)";
    $stmt_insert = $conn->prepare($sql_insert);
    $stmt_insert->bind_param("ssis", $no_barang, $nama_barang, $jumlah, $keterangan2);

    if ($stmt_insert->execute()) {
        header("Location: detail_index.php?no_barang=" . urlencode($no_barang));
        exit();
    } else {
        die("Gagal menambahkan detail barang");
    }
}
?>
