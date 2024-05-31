<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari formulir
    $id = $_POST['id'] ?? null;
    $no_barang = $_POST['no_barang'] ?? null;
    $nama_barang = $_POST['nama_barang'] ?? null;
    $jumlah = $_POST['jumlah'] ?? null;
    $keterangan2 = $_POST['ket2'] ?? null;

    // Validasi data
    if (!$id || !$no_barang || !$nama_barang || !$jumlah || !$keterangan2) {
        die("Data tidak valid");
    }

    // Persiapkan dan jalankan query untuk memperbarui detail barang
    $sql = "UPDATE detail SET nama_barang = ?, jumlah = ?, keterangan2 = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Error: " . $conn->error);
    }
    $stmt->bind_param("sisi", $nama_barang, $jumlah, $keterangan2, $id);
    if ($stmt->execute()) {
        $stmt->close(); // Tutup statement
        // Redirect kembali ke halaman detail dengan parameter sukses
        header("Location: detail.php?no_barang=" . $no_barang . "&sukses=1");
        exit();
    } else {
        $stmt->close(); // Tutup statement
        die("Error: " . $conn->error);
    }
}
?><?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari formulir
    $id = $_POST['id'] ?? null;
    $no_barang = $_POST['no_barang'] ?? null;
    $nama_barang = $_POST['nama_barang'] ?? null;
    $jumlah = $_POST['jumlah'] ?? null;
    $keterangan2 = $_POST['ket2'] ?? null;

    // Validasi data
    if (!$id || !$no_barang || !$nama_barang || !$jumlah || !$keterangan2) {
        die("Data tidak valid");
    }

    // Persiapkan dan jalankan query untuk memperbarui detail barang
    $sql = "UPDATE detail SET nama_barang = ?, jumlah = ?, keterangan2 = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Error: " . $conn->error);
    }
    $stmt->bind_param("sisi", $nama_barang, $jumlah, $keterangan2, $id);
    if ($stmt->execute()) {
        $stmt->close(); // Tutup statement
        // Redirect kembali ke halaman detail dengan parameter sukses
        header("Location: detail.php?no_barang=" . $no_barang . "&sukses=1");
        exit();
    } else {
        $stmt->close(); // Tutup statement
        die("Error: " . $conn->error);
    }
}
?>
