<?php
// Mulai sesi
session_start();

// Sambungkan ke database
$host = "localhost";
$user = "root";
$password = "";
$dbname = "latihan";

// Buat koneksi
$conn = new mysqli($host, $user, $password, $dbname);

// Periksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Periksa apakah parameter 'no_barang' ada di URL
if (!isset($_GET['no_barang'])) {
    die("Nomor Barang tidak disediakan");
}

// Bersihkan nilai 'no_barang' untuk mencegah SQL Injection
$no_barang = $conn->real_escape_string($_GET['no_barang']);

// Kueri untuk mendapatkan 'index' berdasarkan 'no_barang'
$sql_index = "SELECT `no_barang`, `user`, `periode`, `keterangan` FROM `index` WHERE `no_barang` = ?";
$stmt = $conn->prepare($sql_index);
if(!$stmt) {
    die("Kesalahan dalam menyiapkan pernyataan SQL: " . $conn->error);
}

// Bind parameter
$stmt->bind_param("s", $no_barang);

// Jalankan pernyataan yang disiapkan
$stmt->execute();

// Dapatkan hasil dari kueri
$result_index = $stmt->get_result();
if ($result_index->num_rows > 0) {
    $row_index = $result_index->fetch_assoc();
    $no_barang = $row_index['no_barang'];
    $user = $row_index['user'];
    $periode = $row_index['periode'];
    $keterangan = $row_index['keterangan'];

    // Kueri untuk mendapatkan detail barang dari tabel detail
    $sql_detail = "SELECT * FROM `detail` WHERE `no_barang` = ?";
    $stmt_detail = $conn->prepare($sql_detail);
    if(!$stmt_detail) {
        die("Kesalahan dalam menyiapkan pernyataan SQL: " . $conn->error);
    }

    // Bind parameter
    $stmt_detail->bind_param("s", $no_barang);

    // Jalankan pernyataan yang disiapkan untuk detail
    $stmt_detail->execute();

    // Dapatkan hasil dari kueri detail
    $result_detail = $stmt_detail->get_result();

    if ($result_detail->num_rows > 0) {
        ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Pengeluaran Barang</title>
</head>

<body>
    <header class="header1">
        <p style="margin-bottom: 0; margin-top: 0;">PT. Unggul Semesta
        <h3 style="margin-bottom: 0; margin-top: 0; margin-left: 50px;">BUKTI PENGELUARAN BARANG</h3>
        </p>
    </header>
    <br>
    <div class="container">
        <table>
            <tr>
                <th colspan="2" style="text-align:center;">No Barang: <?= $no_barang ?></th>
                <th colspan="2" style="text-align:center;">Periode: <?= $periode ?></th>
            </tr>
            <tr>
                <th style="width:4%">No.</th>
                <th style="width:45%">Nama Barang</th>
                <th style="width:20%">Jumlah</th>
                <th>Keterangan</th>
            </tr>
            <?php 
    $rowCount = 10; // Set jumlah baris yang diinginkan
    
    // Looping sebanyak jumlah baris yang diinginkan
    for ($i = 1; $i <= $rowCount; $i++) { 
        if ($result_detail->num_rows > 0 && $row_detail = $result_detail->fetch_assoc()) {
            // Jika masih ada data detail barang
    ?>
            <tr>
                <td><?= $i ?></td>
                <td><?= htmlspecialchars($row_detail['nama_barang']) ?? '' ?></td>
                <td><?= htmlspecialchars($row_detail['jumlah']) ?? '' ?></td>
                <td><?= htmlspecialchars($row_detail['keterangan2']) ?? '' ?></td>
            </tr>
            <?php 
        } else {
            // Jika tidak ada data detail barang atau sudah mencapai 10 baris
    ?>
            <tr>
                <td><?= $i ?></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <?php 
        }
    } 
    ?>
        </table>

        <br>
        <table border="1" class="t2">
            <tr>
                <td colspan="2" class="t2"><span>Yang Mengeluarkan</span></td>
                <td colspan="2" class="t2"><span>Mengetahui</span></td>
                <td colspan="1" class="t2"><span>Yang Menerima</span></td>
            </tr>
            <tr>
                <td>Karyawan ybs</td>
                <td>Kepala Divisi</td>
                <td>HRD & GA</td>
                <td>Security</td>
                <td></td>
            </tr>
            <tr>
                <td class="ttd"></td>
                <td class="ttd"></td>
                <td class="ttd"></td>
                <td class="ttd"></td>
                <td class="ttd"></td>
            </tr>
            <tr>
                <td colspan="1" class="nama" style="text-align:center;">Sinta</td>
                <td colspan="1" class="nama" style="text-align:center;"></td>
                <td colspan="1" class="nama" style="text-align:center;"></td>
                <td colspan="1" class="nama" style="text-align:center;"></td>
                <td colspan="1" class="nama" style="text-align:center;"><?= $user ?></td>
            </tr>
        </table>
        </table>
        <h4>DIVISI IT/UGS</h4>
    </div>
</body>

<style>
<style>.header1 {
    font-family: 'Trebuchet MS';
    margin: auto;
}

h3 {
    text-decoration: underline;
    font-family: 'Trebuchet MS';
    padding-left: 270px;
}

table,
th,
td {
    border: 1px solid black;
    padding-left: 4px;
    border-collapse: collapse;
    text-align: center;
    height: 22px;
    width: 905px;
    font-family: 'Trebuchet MS';
}

.ttd {
    padding: 10px;
    height: 75px;
}

h4 {
    font-size: 10px;
    font-family: 'Trebuchet MS';
    margin-top: 5px;
}

.t2 {
    margin-top: 30px;
    text-align: center;
}

tr.tr1 {
    text-align: left;
}
</style>
</style>

</html>

<?php
    } else {
        echo "Tidak ada detail tersedia.";
    }
} else {
    die("Nomor Barang tidak valid");
}

$stmt->close(); // Tutup pernyataan yang disiapkan untuk index
$stmt_detail->close(); // Tutup pernyataan yang disiapkan untuk detail
$conn->close(); // Tutup koneksi database
?>
