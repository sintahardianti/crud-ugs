<?php
// delete.php

// Konfigurasi database
$servername = "localhost";
$username = "root"; // Sesuaikan dengan username database Anda
$password = ""; // Sesuaikan dengan password database Anda
$dbname = "latihan";

// Membuat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

// Memeriksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Mendapatkan no_barang dari request
    $no_barang = $conn->real_escape_string($_POST['no_barang']);

    // Query untuk menghapus data
    $sql = "DELETE FROM `index` WHERE no_barang = '$no_barang'";

    if ($conn->query($sql) === TRUE) {
        echo "success";
    } else {
        echo "error: " . $conn->error;
    }
}

// Menutup koneksi
$conn->close();
?>
