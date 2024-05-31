<?php
include 'db.php';

// Periksa apakah parameter detail_id ada dan merupakan angka
if (isset($_GET['detail_id']) && is_numeric($_GET['detail_id'])) {
    // Ambil nilai detail_id dari URL
    $detailId = (int)$_GET['detail_id'];

    // Lindungi dari serangan SQL Injection menggunakan prepared statement
    $stmt = $conn->prepare("DELETE FROM detail WHERE id=?");
    $stmt->bind_param("i", $detailId);

    // Eksekusi pernyataan
    if ($stmt->execute()) {
        // Jika penghapusan berhasil, redirect kembali ke halaman sebelumnya
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    } else {
        // Jika terjadi kesalahan saat menjalankan pernyataan, tampilkan pesan kesalahan
        die("Query Error: " . $stmt->error);
    }
} else {
    // Jika parameter detail_id tidak ada atau tidak valid, tampilkan pesan kesalahan
    die("Invalid or missing detail_id parameter.");
}
?>
