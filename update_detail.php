<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari formulir
    $id = htmlspecialchars($_POST['id'] ?? '');
    $no_barang = htmlspecialchars($_POST['no_barang'] ?? '');
    $nama_barang = htmlspecialchars($_POST['nama_barang'] ?? '');
    $jumlah = (int)($_POST['jumlah'] ?? 0);
    $keterangan2 = htmlspecialchars($_POST['ket2'] ?? '');

    // Validasi data
    if (empty($id) || empty($no_barang) || empty($nama_barang) || $jumlah <= 0 || empty($keterangan2)) {
        echo "Data tidak valid";
        exit();
    }

    // Persiapkan dan jalankan query untuk memperbarui detail barang
    $sql = "UPDATE detail SET nama_barang = ?, jumlah = ?, keterangan2 = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        echo "Error: " . $conn->error;
        exit();
    }

    $stmt->bind_param("sisi", $nama_barang, $jumlah, $keterangan2, $id);
    if ($stmt->execute()) {
        $stmt->close(); // Tutup statement
        $conn->close(); // Tutup koneksi
        echo "success"; // Menambahkan output success untuk ditangani oleh JavaScript
        exit();
    } else {
        $stmt->close(); // Tutup statement
        echo "Error: " . $stmt->error;
        $conn->close(); // Tutup koneksi
        exit();
    }
}
?>
