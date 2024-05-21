<?php 
$host       ="localhost";
$user       ="root";
$password   = "";
$db         = "latihan";

$koneksi    = mysqli_connect($host,$user,$password,$db);
$sql1       = "SELECT * FROM asset ";
$q1         = mysqli_query($koneksi,$sql1);

if(!$koneksi){  
    die("Tidak bisa koneksi ke database");
}

// Tangkap data 
if(isset($_POST['printData'])) {
    $selectedIds = $_POST['printData'];
    $selectedIds = explode(',', $selectedIds); // Ubah string menjadi array

    // Query data dari database berdasarkan id yang dipilih
    $sql = "SELECT * FROM asset WHERE id IN (" . implode(',', $selectedIds) . ")";
    $result = mysqli_query($koneksi, $sql);
    
    if (!$result) {
        die("Query error: " . mysqli_error($koneksi));
    }
}
$no_asset         = "";
$no         = "";
$date       = "";
$user       = "";
$department = "";
$item_list1 = "";
$item_list2 = "";
$item_list3 = "";
$item_list4 = "";
$item_list5 = "";
$item_list6 = "";
$item_list7 = "";
$item_list8 = "";
$item_list9 = "";
$item_list10 = "";
$note       = "";

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Tanda Terima</title>
</head>

<style>
th {
    background-color: #dedede;
    color: #333333;
    font-weight: bold;
    height: 15px;
}

.table2,
tr,
.thead2 {
    border: 1px solid black;
    padding: 0px;
    border-collapse: collapse;
    margin-left: 2px;

}

.header1 {
    font-family: 'Trebuchet MS';
    margin: auto;
}

h3 {
    text-decoration: underline;
    font-family: 'Trebuchet MS';
    padding-left: 320px;
}

.container .tr1 {
    padding-left: 20px;
}
</style>
<header class="header1">
    <p style="margin-bottom: 0; margin-top: 0;">PT. Unggul Semesta
    <h3 style="margin-bottom: 0; margin-top: 0; margin-left: 50px;">TANDA TERIMA ASSET</h3>
    </p>
</header>
<?php 
    // Periksa apakah ada parameter ID yang dikirimkan melalui URL
    if(isset($_GET['id'])) {
        // Tangkap ID dari parameter URL
        $id = $_GET['id'];
        
        // Query untuk mengambil data berdasarkan ID yang dikirimkan
        $sql = "SELECT * FROM asset WHERE id = '$id'";
        $result = mysqli_query($koneksi, $sql);

        // Periksa apakah ada data yang ditemukan
        if(mysqli_num_rows($result) > 0) {
            ?>

<body>
    <div class="container">
        <table border="1">
            <?php 
                $i = 1;
                while($r1 = mysqli_fetch_assoc($result)){
                ?>
            <th colspan="2" style="text-align:center;"><?php echo $r1['no_asset'] ?></th>
            </tr>
            <tr>
                <th style="width:20%">Date</th>
                <td>
                    <?php echo $r1 ['date'] ?>
                </td>
            </tr>
            <tr>
                <th>User</th>
                <td><?php echo $r1 ['user'] ?></td>
            </tr>
            <tr>
                <th>Department</th>
                <td><?php echo $r1 ['department'] ?></td>
            </tr>
            <tr>
                <th rowspan="10">Item List</th>
                <td>1. <?php echo $r1 ['item_list1'] ?></td>
            </tr>
            <tr>
                <td>2. <?php echo $r1 ['item_list2'] ?></td>
            </tr>
            <tr>
                <td>3. <?php echo $r1 ['item_list3'] ?></td>
            </tr>
            <tr>
                <td>4. <?php echo $r1 ['item_list4'] ?></td>
            </tr>
            <tr>
                <td>5. <?php echo $r1 ['item_list5'] ?></td>
            </tr>
            <tr>
                <td>6. <?php echo $r1 ['item_list6'] ?></td>
            </tr>
            <tr>
                <td>7. <?php echo $r1 ['item_list7'] ?></td>
            </tr>
            <tr>
                <td>8. <?php echo $r1 ['item_list8'] ?></td>
            </tr>
            <tr>
                <td>9. <?php echo $r1 ['item_list9'] ?></td>
            </tr>
            <tr>
                <td>10. <?php echo $r1 ['item_list10'] ?></td>
            </tr>
            <tr>
                <th>Note</th>
                <td><?php echo $r1 ['note'] ?></td>
            </tr>
            <table border="1" class="t2">
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
                    <td colspan="1" class="nama" style="text-align:center;"><?php echo $r1 ['user'] ?></td>
                </tr>
            </table>
            <?php } ?>
        </table>
        <h4>DIVISI IT/UGS</h4>
        </table>
        <?php 
         } else {
            // Jika tidak ada data yang ditemukan berdasarkan ID
            echo "<p>Data tidak ditemukan</p>";
        }
    } else {
        // Jika tidak ada parameter ID yang dikirimkan melalui URL
        echo "<p>Tidak ada data yang dipilih</p>";
    }
    ?>
</body>
<style>
.t2 {
    margin-top: 50px;
    text-align: center;
}

tr.tr1 {
    text-align: left;
}

table,
th,
td {
    border: 1px solid black;
    padding-left: 4px;
    border-collapse: collapse;
    text-align: left;
    height: 22px;
    width: 905px;
    font-family: 'Trebuchet MS';
}


.ttd {
    padding: 10px;
    height: 65px;
}

h4 {
    font-size: 10px;
    font-family: 'Trebuchet MS';
    margin-top: 5px;
}
</style>
<script>
// Fungsi untuk mencetak data yang dipilih
function printSelectedData() {
    var selectedData = document.querySelectorAll('input[name="printData"]:checked');
    var selectedIds = [];
    selectedData.forEach(function(checkbox) {
        selectedIds.push(checkbox.value);
    });

    // Jika tidak ada data yang dipilih, tampilkan pesan peringatan
    if (selectedIds.length === 0) {
        alert("TIDAK ADA DATA YANG DIPILIH");
        return;
    }

    // Kirim data ke halaman passet.php dan buka di jendela baru
    var form = document.createElement('form');
    form.setAttribute('method', 'post');
    form.setAttribute('action', 'passet.php');
    form.setAttribute('target', '_blank'); // Buka di jendela baru

    var hiddenField = document.createElement('input');
    hiddenField.setAttribute('type', 'hidden');
    hiddenField.setAttribute('name', 'printData');
    hiddenField.setAttribute('value', selectedIds.join(','));

    form.appendChild(hiddenField);
    document.body.appendChild(form);
    form.submit();
}
</script>

</html>
