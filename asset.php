<?php 

session_start();

// Pengecekan apakah pengguna sudah login
if (!isset($_SESSION["is_login"]) || !$_SESSION["is_login"]) {
    header("Location: akun.php");
    exit;
}

// Logout
if (isset($_GET['logout'])) {
    session_unset();
    session_destroy();
    echo '<script>window.location.replace("akun.php");</script>';
    exit();
}

$host       ="localhost";
$user       ="root";
$password   = "";
$db         = "latihan";
$koneksi    = mysqli_connect($host, $user, $password, $db);
if(!$koneksi){  //cek koneksi
    die("Tidak bisa koneksi ke database");
}

function select($query) {
    global $koneksi; // Pastikan variabel $koneksi sudah didefinisikan sebelumnya

    $result = mysqli_query($koneksi, $query);

    // Periksa apakah query berhasil dijalankan
    if (!$result) {
        die("Error executing query: " . mysqli_error($koneksi));
    }

    // Ambil hasil query dan masukkan ke dalam array
    $data = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }

    // Bebaskan memori hasil query
    mysqli_free_result($result);

    return $data;
}

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
$sukses     = "";
$error      = "";

if (isset($_GET['op'])) {
    $op = $_GET['op'];
} else {
    $op = "";
}
if($op == 'delete'){
    $id         = $_GET['id'];
    $sql1       = "DELETE FROM asset where id = '$id'";
    $q1         = mysqli_query($koneksi,$sql1);
    if($q1){
        header("Location: asset.php?sukses=1");
        exit();
    }else{
        header("Location: asset.php?error=1");
        exit();
    }
}


// Check for success or error messages from previous operations
if(isset($_GET['sukses'])) {
    $sukses = "Berhasil hapus data";
    header("refresh:3;url=asset.php");
} elseif(isset($_GET['error'])) {
    $error = "Gagal menghapus data";
}

if ($op == 'edit') {
    if (isset($_GET['id'])) {
        $id   = $_GET['id'];
        $sql1 = "SELECT * FROM asset WHERE id = '$id'";
        $q1   = mysqli_query($koneksi, $sql1);

        if ($q1 && mysqli_num_rows($q1) > 0) {
            $r1          = mysqli_fetch_array($q1);
            $no          = $r1['no'];
            $date        = $r1['date'];
            $user        = $r1['user'];
            $department  = $r1['department'];
            $item_list1  = $r1['item_list1'];
            $item_list2  = $r1['item_list2'];
            $item_list3  = $r1['item_list3'];
            $item_list4  = $r1['item_list4'];
            $item_list5  = $r1['item_list5'];
            $item_list6  = $r1['item_list6'];
            $item_list7  = $r1['item_list7'];
            $item_list8  = $r1['item_list8'];
            $item_list9  = $r1['item_list9'];
            $item_list10 = $r1['item_list10'];
            $note        = $r1['note'];
        } else {
            $error = "Data Tidak Ditemukan";
        }
    } else {
        $error = "ID Tidak Ditemukan";
    }
}

if (isset($_POST['simpan'])) {
    $date       = $_POST['date'];
    $user       = $_POST['user'];
    $department = $_POST['department'];
    $item_list1 = $_POST['item_list1'];
    $item_list2 = $_POST['item list2'];
    $item_list3 = $_POST['item_list3'];
    $item_list4 = $_POST['item_list4'];
    $item_list5 = $_POST['item_list5'];
    $item_list6 = $_POST['item_list6'];
    $item_list7 = $_POST['item_list7'];
    $item_list8 = $_POST['item_list8'];
    $item_list9 = $_POST['item_list9'];
    $item_list10 = $_POST['item_list10'];
    $note       = $_POST['note'];

    // Mengecek apakah semua variabel yang diperlukan terisi
    $sql_insert = "INSERT INTO asset(date, user, department, item_list1, item_list2, item_list3, item_list4, item_list5, 
    item_list6, item_list7, item_list8, item_list9, item_list10, note) VALUES ('$date', '$user', '$department', '$item_list1', 
    '$item_list2', '$item_list3','$item_list4', '$item_list5', '$item_list6', '$item_list7', '$item_list8', '$item_list9', '$item_list10', '$note')";
    $result_insert = mysqli_query($koneksi, $sql_insert);

    if ($result_insert) {
        $sukses = "Berhasil Memasukkan Data Baru";
    } else {
        // Gagal menjalankan query INSERT
        $error = "Gagal Memasukkan Data: " . mysqli_error($koneksi);
    }
}else{
    if($op == 'edit'){
    $sql_update = "UPDATE asset SET date='$date', user='$user', department='$department', item_list1='$item_list1',  item_list2='$item_list2',item_list3='$item_list3',
    item_list4='$item_list4', item_list5='$item_list5', item_list6='$item_list6', item_list7='$item_list7', item_list8='$item_list8', item_list9='$item_list9', item_list10='$item_list10', note='$note' WHERE id='$id'";
    $result_update = mysqli_query($koneksi, $sql_update);

    if ($result_update) {
        $sukses = "Data Berhasil Diupdate";
    } else {
        // Gagal menjalankan query UPDATE
        $error = "Gagal Memasukkan Data: " . mysqli_error($koneksi);
    }
    }else {
    $error = "Berhasil Memasukkan Data";
    }
}   
$halamanAktif = isset($_GET['halaman']) ? $_GET['halaman'] : 1;
    
if(isset($_POST['filter'])) {
    $tgl_awal   = strip_tags($_POST['tgl_awal']. "00:00:00");
    $tgl_akhir  = strip_tags($_POST['tgl_akhir']. "23:59:59");

    // Query filter data
    $data_barang = select("SELECT * FROM asset WHERE date BETWEEN '$tgl_awal' AND '$tgl_akhir' ORDER BY id DESC ");
} else {
    // Query tampil data dengan pagination
    $jumlahDataPerhalaman = 8;
    $awalData = ($halamanAktif - 1) * $jumlahDataPerhalaman; // Menghitung baris awal data

    // Mengambil jumlah data tanpa menggunakan count()
    $jumlahData = select("SELECT COUNT(*) as total FROM asset")[0]['total'];

    $jumlahHalaman = ceil($jumlahData / $jumlahDataPerhalaman);

    // Query data sesuai halaman yang dipilih
    $data_barang = select("SELECT * FROM asset ORDER BY id DESC LIMIT $awalData, $jumlahDataPerhalaman");
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <title>Tanda Terima Asset</title>
    <script>
    // Mengunci riwayat navigasi agar hanya menunjuk pada halaman asset.php
    history.pushState(null, null, location.href);
    window.onpopstate = function() {
        history.go(1);
    };
    </script>

</head>
<?php include "layout/header.php" ?>

<body>
    <div class="card">
        <div class="card-header text-dark">
            <h3 class="judul">Tanda Terima Asset</h3>
            <div class="container-fluid">
                <div class="d-flex justify-content-between align-items-center">
                    <button class="btn btn-success" id="new"
                        onclick="window.location.href='fasset.php';">Tambah</button>
                    <form class="d-flex" action="asset.php" method="GET">
                        <form method="GET" action="asset.php" id="search-form">
                            <input type="text" name="keyword"
                                value="<?= isset($_GET['keyword']) ? $_GET['keyword'] : ''; ?>"
                                placeholder="Cari Barang" id="keyword" class="form-control">
                            <button class="btn btn-primary ms-1" type="submit" name="cari"
                                id="tombol-cari">Cari</button>
                            <?php if(isset($_GET['keyword']) && !empty($_GET['keyword'])) : ?>
                            <a href="asset.php" class="btn btn-sm btn-danger mx-1" id="clear-search"><i
                                    class="fas fa-times-circle"></i></a>
                            <?php endif; ?>
                        </form>
                        <script>
                        // sript untuk Menambahkan event listener pada kolom input untuk mengontrol tampilan ikon fas fa-times-circle.
                        //Jika kolom input kosong, ikon disembunyikan (display: 'none').
                        //Jika kolom input memiliki nilai, ikon ditampilkan (display: 'inline')
                        document.getElementById('keyword').addEventListener('input', function() {
                            if (this.value === '') {
                                // Remove the 'keyword' parameter from the URL
                                const url = new URL(window.location.href);
                                url.searchParams.delete('keyword');
                                window.location.href = url.toString();
                            }
                        });

                        document.getElementById('keyword').addEventListener('input', function() {
                            const clearSearch = document.getElementById('clear-search');
                            if (this.value === '' && clearSearch) {
                                clearSearch.style.display = 'none';
                            } else if (clearSearch) {
                                clearSearch.style.display = 'inline';
                            }
                        });
                        </script>
                    </form>
                </div>
            </div>
        </div>
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr class="thead">
                        <th scope="col" class="no">No</th>
                        <th scope="col" class="no">No Asset</th>
                        <th scope="col" class="date">Date</th>
                        <th scope="col" class="user">User</th>
                        <th scope="col" class="department">Department</th>
                        <th scope="col" class="itemlist1">Item List</th>
                        <th scope="col" class="aksi">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        //sukses hapus data
                        if (isset($_GET['sukses'])) {
                            $sukses = "Berhasil hapus data";
                        }
                    ?>
                    <?php if($sukses): ?>
                    <div class="alert alert-success" role="alert">
                        <?php echo $sukses; ?>
                    </div>
                    <?php endif; ?>
                    <?php
                    $keyword = isset($_GET['keyword']) ? $_GET['keyword'] : ''; // Ambil kata kunci pencarian dari URL
                    $limit = 8; // Jumlah data per halaman
                    $offset = ($halamanAktif - 1) * $limit; // Menghitung offset berdasarkan halaman aktif

                    // Kueri SQL awal untuk mengambil data dari tabel asset
                    $sql2 = "SELECT * FROM asset";

                    // Jika ada kata kunci pencarian, tambahkan klausa WHERE untuk menyaring data berdasarkan inisial
                    if (!empty($keyword)) {
                        // Gunakan klausa WHERE untuk menyaring data berdasarkan berbagai kolom
                        $sql2 .= " WHERE no_asset LIKE '%$keyword%' OR item_list1 LIKE '%$keyword%' OR item_list2 LIKE '%$keyword%' 
                        OR item_list3 LIKE '%$keyword%' OR department LIKE '%$keyword%' OR user LIKE '%$keyword%' OR item_list4 LIKE '%$keyword%'
                        OR item_list5 LIKE '%$keyword%' OR item_list6 LIKE '%$keyword%' OR item_list7 LIKE '%$keyword%' 
                        OR item_list8 LIKE '%$keyword%' OR item_list9 LIKE '%$keyword%' OR item_list10 LIKE '%$keyword%' OR note LIKE '%$keyword%'";
                    }

                    // Tambahkan klausa ORDER BY dan LIMIT OFFSET ke kueri SQL
                    $sql2 .= " ORDER BY id DESC LIMIT $limit OFFSET $offset";

                    // Eksekusi kueri SQL
                    $q2 = mysqli_query($koneksi, $sql2);

                    // Periksa apakah ada data yang ditemukan
                    $adaData = mysqli_num_rows($q2) > 0;

                    // Menghitung nilai awal variabel $no berdasarkan offset
                    $no = $offset + 1;

                    // Loop untuk menampilkan data
                    while ($r2 = mysqli_fetch_array($q2)) {
                        // Ambil data dari setiap baris hasil kueri
                        $no_asset   = $r2['no_asset'];
                        $date       = $r2['date'];
                        $user       = $r2['user'];
                        $department = $r2['department'];
                        $item_list1 = $r2['item_list1'];
                        $item_list2 = $r2['item_list2'];
                        $item_list3 = $r2['item_list3'];
                        $item_list4 = $r2['item_list4'];
                        $item_list5 = $r2['item_list5'];
                        $item_list6 = $r2['item_list6'];
                        $item_list7 = $r2['item_list7'];
                        $item_list8 = $r2['item_list8'];
                        $item_list9 = $r2['item_list9'];
                        $item_list10 = $r2['item_list10'];
                        $note       = $r2['note'];
                        // Tampilkan data sesuai kebutuhan
                    ?>
                    <tr class="isi">
                        <td scope="row" class="text-center"><?php echo $no++ ;?></td>
                        <td scope="row" class="text-center"><?php echo $no_asset ;?></td>
                        <td scope="row" class="text-center"><?php echo $date ?></td>
                        <td scope="row" class="text-center"><?php echo $user ?></td>
                        <td scope="row" class="text-center"><?php echo $department ?></td>
                        <td scope="row" class="text-center">
                            <?php echo $item_list1?><br><?php echo $item_list2?><br><?php echo $item_list3 ?></td>
                        <td scope="row" class="aksi">
                            <a href="fasset.php?op=edit&id=<?php echo $r2['id']; ?>" class="btn btn-warning"><i
                                    class="fas fa-pencil-alt"></i></a>
                            <a href="asset.php?op=delete&id=<?php echo $r2['id']; ?>"
                                onclick="return confirm('yakin mau hapus data?')" class="btn btn-danger"><i
                                    class="fas fa-trash-alt"></i></a>
                            <a href="passet.php?id=<?php echo $r2['id']; ?>" class="btn btn-success" target="_blank"><i
                                    class="fas fa-print"></i></a>
                        </td>
                    </tr>
                    <?php 
                        }
                        if (!$adaData) {
                            echo "<tr><td colspan='7' class='text-center'>Data Tidak Ditemukan</td></tr>";
                        }
                    ?>
                </tbody>
            </table>
        </div>
        <nav>
            <ul class="pagination justify-content-end" style="margin-right:20px;">
                <?php if($halamanAktif > 1) : ?>
                <li class="page-item">
                    <a class="page-link" href="?halaman=<?= $halamanAktif - 1 ?>">Previous</a>
                </li>
                <?php endif; ?>
                <?php for($i = 1; $i <= $jumlahHalaman; $i++) : ?>
                <li class="page-item <?= ($i == $halamanAktif) ? 'active' : '' ; ?>">
                    <a class="page-link" href="?halaman=<?= $i;?>"><?= $i;?></a>
                </li>
                <?php endfor; ?>
                <?php if($halamanAktif < $jumlahHalaman) : ?>
                <li class="page-item">
                    <a class="page-link" href="?halaman=<?= $halamanAktif + 1 ?>">Next</a>
                </li>
                <?php endif; ?>
            </ul>
        </nav>
    </div>
</body>

<style>
.judul {
    font-size: 25px;
    padding: 5px;
    text-decoration: thistle;
    font-family: 'Trebuchet MS';
}

.date {
    width: 8%;
    text-align: center;
}

.itemlist1,
.aksi {
    width: 12%;
    text-align: center;
}

.thead {
    text-align: center;
}

.department,
.user {
    width: 11%;
    text-align: center;
}


.no {
    width: 4%;
}

.isi {
    justify-content: center;
    /* Mengatur teks ke tengah secara horizontal */
    align-items: center;
}
</style>
