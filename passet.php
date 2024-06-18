<?php 
$host       ="localhost";
$user       ="root";
$password   = "";
$db         = "latihan";

$koneksi    = mysqli_connect($host,$user,$password,$db);

if(!$koneksi){  
    die("Tidak bisa koneksi ke database");
}

require_once('tcpdf/tcpdf.php'); // Include TCPDF library

// Tangkap data 
if(isset($_GET['id'])) {
    // Tangkap ID dari parameter URL
    $id = $_GET['id'];
    
    // Query untuk mengambil data berdasarkan ID yang dikirimkan
    $sql = "SELECT * FROM asset WHERE id = '$id'";
    $result = mysqli_query($koneksi, $sql);

    // Periksa apakah ada data yang ditemukan
    if(mysqli_num_rows($result) > 0) {
        // Generate PDF using TCPDF based on fetched data
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Your Name');
        $pdf->SetTitle('TANDA TERIMA ASSET');
        $pdf->SetSubject('TANDA TERIMA ASSET');
        $pdf->SetKeywords('TCPDF, PDF, example, test, guide');
        // Hapus header dan footer default
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);

        // Setel margin
        $pdf->SetMargins(15, 0, 15); // Contoh: margin kiri 15mm, atas 10mm, kanan 15mm

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
    <title>Tanda Terima Asset</title>
</head>
<style>
.ttd {
    height: 45px;
}

h3 {
    margin: 0;
    text-align: center;
    text-decoration: underline;
}
</style>

<body>
    <header class="header1">
        <p style="margin-bottom: 0; margin-top: 0; font-size:8px;">PT. Unggul Semesta</p>
        <h3 style="font-size:13px;">TANDA TERIMA ASSET</h3>

    </header>
    <div class="container">
        <table border="1" style="">
            <?php 
                    while($r1 = mysqli_fetch_assoc($result)){
                    ?>
            <tr>
                <th colspan="1" style="text-align:center;"><?php echo $r1['no_asset'] ?></th>
            </tr>
            <tr>
                <th style="width:20%;font-weight:bold;">Date</th>
                <td><?php echo $r1['date'] ?></td>
            </tr>
            <tr>
                <th style="font-weight:bold;">User</th>
                <td><?php echo $r1['user'] ?></td>
            </tr>
            <tr>
                <th style="font-weight:bold;">Department</th>
                <td><?php echo $r1['department'] ?></td>
            </tr>
            <tr>
                <th rowspan="10" style="font-weight:bold;">Item List</th>
                <td>1. <?php echo $r1['item_list1'] ?></td>
            </tr>
            <tr>
                <td>2. <?php echo $r1['item_list2'] ?></td>
            </tr>
            <tr>
                <td>3. <?php echo $r1['item_list3'] ?></td>
            </tr>
            <tr>
                <td>4. <?php echo $r1['item_list4'] ?></td>
            </tr>
            <tr>
                <td>5. <?php echo $r1['item_list5'] ?></td>
            </tr>
            <tr>
                <td>6. <?php echo $r1['item_list6'] ?></td>
            </tr>
            <tr>
                <td>7. <?php echo $r1['item_list7'] ?></td>
            </tr>
            <tr>
                <td>8. <?php echo $r1['item_list8'] ?></td>
            </tr>
            <tr>
                <td>9. <?php echo $r1['item_list9'] ?></td>
            </tr>
            <tr>
                <td>10. <?php echo $r1['item_list10'] ?></td>
            </tr>
            <tr>
                <th style="font-weight:bold;">Note</th>
                <td><?php echo $r1['note'] ?></td>
            </tr>
        </table>
    </div>
    <div>
        <table border="1" style="margin:0px;text-align:center;">
            <tr>
                <td colspan="1" class="t2"><span>Diserahkan Oleh,</span></td>
                <td colspan="1" class="t2"><span>Diterima Oleh,</span></td>
            </tr>
            <tr>
                <td class="ttd"></td>
                <td class="ttd"></td>
            </tr>
            <tr>
                <td colspan="1" class="nama" style="text-align:center;">Sinta</td>
                <td colspan="1" class="nama" style="text-align:center;"><?php echo $r1['user'] ?></td>
            </tr>
        </table>
        <?php } ?>
        <h4 style="font-size:8px;">DIVISI IT/UGS</h4>
    </div>
</body>

</html>
<?php
        $html = ob_get_clean(); // Get the buffered HTML content
        $pdf->writeHTML($html, true, false, true, false, '');

        // Output the PDF
        $pdf->Output('tanda_terima_asset.pdf', 'I');
        exit;
    } else {
        echo "<p>Data tidak ditemukan</p>";
    }
} else {
    echo "<p>Tidak ada data yang dipilih</p>";
}
?>
