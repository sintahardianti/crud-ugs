<?php
// Mulai sesi
session_start();

// Sambungkan ke database
include 'db.php';

// Include TCPDF library
require_once('tcpdf/tcpdf.php');

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
        // Buat objek PDF
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // Setel informasi dokumen
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('PT. Unggul Semesta');
        $pdf->SetTitle('Bukti Pengeluaran Barang');
        $pdf->SetSubject('PDF Bukti Pengeluaran Barang');
        $pdf->SetKeywords('TCPDF, PDF, Bukti, Pengeluaran, Barang');

        // Hapus header dan footer default
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);

        // Setel margin
        $pdf->SetMargins(PDF_MARGIN_LEFT,PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetMargins(15, 0, 15);
        // Tambahkan halaman
        $pdf->AddPage();

        // Mulai buffering konten HTML
        ob_start();
        ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Pengeluaran Barang</title>
</head>
<style>
.header1 {
    font-family: 'Trebuchet MS';
    margin-top: 0px;
}

h3 {
    text-decoration: underline;
    text-align: center;
    padding: 0px;
    margin: 0px;
    font-size: 14px;
}

table,
th,
td {
    border: 1px solid black;
    border-collapse: collapse;
    text-align: center;
    font-family: 'Trebuchet MS';
}

.ttd {
    height: 60px;
}

h4 {
    font-size: 8px;
}

.t2 {
    text-align: center;
}

tr.tr1 {
    text-align: left;
}
</style>

<body>
    <header class="header1" style="margin-top: 0;">
        <p style="font-size:8px;">PT. Unggul Semesta</p>
        <h3 style="">BUKTI PENGELUARAN BARANG</h3>
    </header>
    <div class="container">
        <table style="width: 100%; margin-top: 10px;">
            <tr>
                <th colspan="1" style="text-align:center;">No Barang: <?= $no_barang ?></th>
                <th colspan="1" style="text-align:center;">Periode: <?= $periode ?></th>
            </tr>
            <tr style="height:0px;font-weight:bold;text-align:center;">
                <th style="width:6%">No.</th>
                <th style="width:44%">Nama Barang</th>
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
    </div>
    <div>
        <table border="1" class="t2" style="width: 100%;">
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
        <h4>DIVISI IT/UGS</h4>
    </div>
</body>

</html>

<?php
        // Akhiri buffering dan ambil konten HTML
        $html = ob_get_clean();

        // Konversi HTML menjadi PDF
        $pdf->writeHTML($html, true, false, true, false, '');

        // Output PDF ke browser
        $pdf->Output('bukti_pengeluaran_barang.pdf', 'I');
    } else {
        echo "Tidak ada detail tersedia.";
    }
} else {
    die("Data tidak valid atau tidak ditemukan");
}

$stmt->close(); // Tutup pernyataan yang disiapkan untuk index
$stmt_detail->close(); // Tutup pernyataan yang disiapkan untuk detail
$conn->close(); // Tutup koneksi database
?>
