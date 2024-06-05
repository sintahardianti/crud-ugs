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

if ($op == 'delete') {
    if (isset($_GET['id'])) {
        $id   = $_GET['id'];
        $sql1 = "DELETE FROM asset WHERE id = '$id'";
        $q1   = mysqli_query($koneksi, $sql1);
        $result_delete = mysqli_query($koneksi, $sql1);
        if ($result_delete) {
            $sukses2 = "Berhasil hapus data";
            header("Location: asset.php");
            exit();
        } else {
            $error  = "Gagal menghapus data";
        }
    } else {
        $error = "ID Tidak Ditemukan";
    }
}
if ($op == 'edit') {
    if (isset($_GET['id'])) {
        $id   = $_GET['id'];
        $sql1 = "SELECT * FROM asset WHERE id = '$id'";
        $q1   = mysqli_query($koneksi, $sql1);

        if ($q1 && mysqli_num_rows($q1) > 0) {
            $r1          = mysqli_fetch_array($q1);
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
    $no_asset      = generateNoAsset();
    $date           = $_POST['date'];
    $user           = $_POST['user'];
    $department     = $_POST['department'];
    $item_list1     = $_POST['item_list1'];
    $note           = $_POST['note'];
    $item_list2     = isset($_POST['item_list2']) ? $_POST['item_list2'] : ''; // Mengecek dan memberikan nilai default jika kosong
    $item_list3     = isset($_POST['item_list3']) ? $_POST['item_list3'] : ''; 
    $item_list4     = isset($_POST['item_list4']) ? $_POST['item_list4'] : '';
    $item_list5     = isset($_POST['item_list5']) ? $_POST['item_list5'] : '';
    $item_list6     = isset($_POST['item_list6']) ? $_POST['item_list6'] : '';
    $item_list7     = isset($_POST['item_list7']) ? $_POST['item_list7'] : '';
    $item_list8     = isset($_POST['item_list8']) ? $_POST['item_list8'] : '';
    $item_list9     = isset($_POST['item_list9']) ? $_POST['item_list9'] : '';
    $item_list10    = isset($_POST['item_list10']) ? $_POST['item_list10'] : '';
 
    // Mengecek apakah semua variabel yang diperlukan terisi
    if ($date && $user && $department && $item_list1 ) { // Menghapus pengecekan untuk $item_list2, $item_list3, dan $images
        if ($op == 'edit') {
            $sql_update = "UPDATE asset SET date='$date', user='$user', department='$department', item_list1='$item_list1',  item_list2='$item_list2',item_list3='$item_list3',
            item_list4='$item_list4', item_list5='$item_list5', item_list6='$item_list6', item_list7='$item_list7', item_list8='$item_list8', item_list9='$item_list9', item_list10='$item_list10', note='$note' WHERE id='$id'";
            $result_update = mysqli_query($koneksi, $sql_update);
            if ($result_update) {
                $sukses = "Berhasil mengubah data";
            } else {
                // Gagal menjalankan query UPDATE
                $error = "Gagal mengubah data: " . mysqli_error($koneksi);
            }
        } else {
            $sql_insert = "INSERT INTO asset(date,no_asset, user, department, item_list1, item_list2, item_list3, item_list4, item_list5, 
            item_list6, item_list7, item_list8, item_list9, item_list10, note) VALUES ('$date','$no_asset', '$user', '$department', '$item_list1', 
            '$item_list2', '$item_list3','$item_list4', '$item_list5', '$item_list6', '$item_list7', '$item_list8', '$item_list9', '$item_list10', '$note')";
            $result_insert = mysqli_query($koneksi, $sql_insert);

            if ($result_insert) {
                $sukses = "Berhasil Memasukkan Data Baru";
                header("refresh:3;url=fasset.php");
            } else {
                // Gagal menjalankan query INSERT
                $error = "Gagal Memasukkan Data: " . mysqli_error($koneksi);
            }
        }
    } else {
        $error = "Silakan Lengkapi Data";
    }
}

function generateNoAsset()
{
    // FORMAT IT/TAHUN SEKARANG/0001
    // Misalnya, IT/2024/0001

    global $koneksi;


    // Query untuk mendapatkan kode terakhir dari database
    $sql = "SELECT RIGHT(no_asset, 6) AS kode FROM asset ORDER BY no_asset DESC LIMIT 1";
    $result = mysqli_query($koneksi, $sql);

    // Cek jika data ada
    if(mysqli_num_rows($result) > 0)
    {
        $row = mysqli_fetch_assoc($result);
        $lastKode = intval($row['kode']) + 1; // Tambah 1 ke kode terakhir
    }
    else
    {
        $lastKode = 1; // Jika tidak ada data, kode dimulai dari 1
    }

    // Format kode terakhir dengan nol di depannya
    $newKode = sprintf("%06d", $lastKode);

    return "IT-".$newKode; // Return kode baru
}

 
 ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"
        integrity="sha512-xxx" crossorigin="anonymous" />
    <title>Tanda Terima Asset</title>
    <script>
    // Mengunci tombol navigasi mundur dan maju
    history.pushState(null, null, location.href);
    window.onpopstate = function() {
        history.go(1);
    };
    </script>
</head>

<body>
    <div id="container">
        <?php include "layout/header.php" ?>
        <button class="btn btn-danger" onclick="window.location.href='asset.php';" id="kembali">Kembali</button>
        <!-- untuk memasukkan data -->
        <div class="container">
            <div class="card">
                <div class="card-header ">
                    Form Asset
                </div>
                <div class="card-body ">
                    <?php  
                if($error){
                ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo $error?>
                    </div>
                    <?php
                }
                ?>
                    <?php  
                if($sukses){
                ?>
                    <div class="alert alert-success" role="alert">
                        <?php echo $sukses?>
                    </div>
                    <?php
                }
                ?>
                    <form action="" method="POST">
                        <div class="mb-3 row">
                            <label for="date" class="col-sm-2 col-form-label">Date <icon style="color:red">*</icon>
                            </label>
                            <div class="col-sm-10">
                                <input type="date" class="form-control" id="date" name="date"
                                    value="<?php echo ($date != "") ? $date : date('Y-m-d') ?>">
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="user" class="col-sm-2 col-form-label">User<icon style="color:red">*</icon>
                            </label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="user" name="user"
                                    value="<?php echo $user ?>">
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="department" class="col-sm-2 col-form-label">Department <icon style="color:red">*
                                </icon></label>
                            <div class="col-sm-10">
                                <select class="form-control" id="department" name="department">
                                    <option value="" <?php if($department == "") echo "selected"; ?>>Select</option>
                                    <?php
                                        // Pilihan departemen
                                        $enum_values = array(
                                            'Board of Director',
                                            'Secretary',
                                            'Admin Marketing',
                                            'Sales Marketing',
                                            'Engineering',
                                            'Purchasing',
                                            'Finance',
                                            'Accounting',
                                            'HRD & GA',
                                            'Warehouse',
                                            'IT',
                                            'HSE',
                                            'Finance Group',
                                            'Security'
                                        );

                                        // Loop melalui setiap nilai enum dan buat opsi dalam dropdown
                                        foreach ($enum_values as $value) {
                                            // Jika nilai enum sama dengan nilai yang sudah dipilih sebelumnya, tambahkan atribut 'selected'
                                            $selected = ($value == $department) ? 'selected' : '';
                                            echo "<option value=\"$value\" $selected>$value</option>";
                                        }
                                        ?>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="item_list1" class="col-sm-2 col-form-label">Item List 1 <icon style="color:red">
                                    *</icon></label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="item_list1" name="item_list1"
                                    value="<?php echo $item_list1?>">
                            </div>
                        </div>
                        <?php for ($i = 2; $i <= 10; $i++): ?>
                        <div class="mb-3 row">
                            <label for="item_list<?php echo $i ?>" class="col-sm-2 col-form-label">Item List
                                <?php echo $i ?></label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="item_list<?php echo $i ?>"
                                    name="item_list<?php echo $i ?>" value="<?php echo ${'item_list' . $i} ?>">
                            </div>
                        </div>
                        <?php endfor; ?>
                        <div class="mb-3 row">
                            <label for="note" class="col-sm-2 col-form-label">Note</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="note" name="note"
                                    value="<?php echo $note?>">
                            </div>
                        </div>
                        <div class="col-12">
                            <input type="submit" name="simpan" value="Simpan Data" class="btn btn-primary">
                        </div>
                    </form>
                </div>
            </div>
        </div>
</body>
<style>
.card-header {
    background-color: #F1F1F1;
    font-family: "trebuchet ms";
    font-size: 20px;
}


.card {
    width: 100%;
    height: 100%;
}

#kembali {
    margin: 20px;
}
</style>

<script>
window.history.forward();

function noBack() {
    window.history.forward();
}
</script>

</html>
