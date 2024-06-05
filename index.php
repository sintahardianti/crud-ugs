<?php
session_start();

include 'db.php';

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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['simpan'])) {
    $user = $conn->real_escape_string($_POST['user']);
    $periode = $conn->real_escape_string($_POST['periode']);

    // Fungsi untuk menghasilkan no_barang baru
    function generateNoBarang($conn) {
        $result = $conn->query("SELECT no_barang FROM `index` ORDER BY no_barang DESC LIMIT 1");
        if ($result && $row = $result->fetch_assoc()) {
            $lastNumber = intval(substr($row['no_barang'], 3));
        } else {
            $lastNumber = 0;
        }
        $newNumber = $lastNumber + 1;
        return 'IT-' . str_pad($newNumber, 6, '0', STR_PAD_LEFT);
    }

    $no_barang = generateNoBarang($conn);

    // Insert data into the `index` table
    $sql = "INSERT INTO `index` (no_barang, user, periode) VALUES ('$no_barang', '$user', '$periode')";
    if ($conn->query($sql) === TRUE) {
        header('Location: index.php');
        exit;
    } else {
        die("Query Error: " . $conn->error);
    }
}

// Jumlah data per halaman
$perPage = 8;

// Proses pencarian
$keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';
$whereClause = "";
if ($keyword !== '') {
    $keyword = $conn->real_escape_string($keyword); // Pastikan untuk menghindari SQL Injection
    $whereClause = "WHERE no_barang LIKE '%$keyword%' OR user LIKE '%$keyword%' OR periode LIKE '%$keyword%'";
}

// Menghitung jumlah total halaman
$sqlCount = "SELECT COUNT(*) as total FROM `index` $whereClause";
$resultCount = $conn->query($sqlCount);

if (!$resultCount) {
    die("Query Error: " . $conn->error);
}

$row = $resultCount->fetch_assoc();
$totalItems = $row['total'];
$totalPages = ceil($totalItems / $perPage);

// Mendapatkan halaman saat ini dari parameter GET, jika tidak ada default ke halaman 1
$currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($currentPage < 1) {
    $currentPage = 1;
} elseif ($currentPage > $totalPages) {
    $currentPage = $totalPages;
}

// Menghitung offset
$offset = ($currentPage - 1) * $perPage;
if ($offset < 0) {
    $offset = 0;
}

// Mengambil subset data untuk halaman saat ini
$sql = "SELECT * FROM `index` $whereClause LIMIT $offset, $perPage";
$result = $conn->query($sql);

if (!$result) {
    die("Query Error: " . $conn->error);
}

$currentData = $result->fetch_all(MYSQLI_ASSOC);
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bukti Pengeluaran Barang</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script>
    // Mengunci tombol navigasi mundur dan maju
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
            <h3 class="judul">Bukti Pengeluaran Barang</h3>
            <div class="container-fluid">
                <div class="d-flex justify-content-between align-items-center">
                    <button id="addBtn" class="btn btn-success" style="margin-left:0px;">Tambah</button>
                    <!-- Form Pencarian -->
                    <form class="d-flex" action="index.php" method="GET" style="height:38px;">
                        <input type="text" name="keyword" value="<?= htmlspecialchars($keyword) ?>"
                            placeholder="Cari Barang" id="keyword" class="form-control">
                        <button class="btn btn-primary ms-1" type="submit" name="cari" id="tombol-cari"
                            style="height:38px;">Cari</button>
                        <?php if ($keyword !== '') : ?>
                        <a href="index.php" class="btn btn-sm btn-danger mx-1" id="clear-search" style="height:38px;"><i
                                class="fas fa-times-circle"></i></a>
                        <?php endif; ?>
                    </form>

                    <script>
                    document.getElementById('keyword').addEventListener('input', function() {
                        const keyword = this.value.trim();
                        const clearSearch = document.getElementById('clear-search');

                        if (keyword === '') {
                            // Remove the 'keyword' parameter from the URL
                            const url = new URL(window.location.href);
                            url.searchParams.delete('keyword');
                            window.location.href = url.toString();

                            // Hide the clear search button
                            if (clearSearch) {
                                clearSearch.style.display = 'none';
                            }
                        } else {
                            // Show the clear search button
                            if (clearSearch) {
                                clearSearch.style.display = 'inline';
                            }
                        }
                    });
                    </script>

                </div>
            </div>
        </div>
        <div class="card-body">
            <div id="deleteSuccessMessage" style="display: none;" class="alert alert-success" role="alert">
                Berhasil menghapus data!
            </div>
            <table class="table" id="barangTable">
                <thead>
                    <tr class="thead">
                        <th scope="col" class="no">No.</th>
                        <th scope="col" class="no_barang">No Barang</th>
                        <th scope="col" class="user">User</th>
                        <th scope="col" class="date">Periode</th>
                        <th scope="col" class="aksi">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($currentData)): ?>
                    <tr>
                        <td colspan="5" class="text-center">Data Tidak Ditemukan</td>
                    </tr>
                    <?php else: ?>
                    <?php foreach ($currentData as $index => $item): ?>
                    <tr class="isi">
                        <td><?= $offset + $index + 1 ?></td>
                        <td><?= htmlspecialchars($item['no_barang']) ?></td>
                        <td><?= htmlspecialchars($item['user']) ?></td>
                        <td><?= htmlspecialchars($item['periode']) ?></td>
                        <td class="text-center">
                            <a href="detail_index.php?no_barang=<?= urlencode($item['no_barang']) ?>"
                                class="btn btn-warning">Detail</a>
                            <button class="btn btn-danger"
                                onclick="deleteData('<?= urlencode($item['no_barang']) ?>', this)">Hapus</button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
            <nav>
                <ul class="pagination justify-content-end" style="margin-right:20px;">
                    <?php if($currentPage > 1) : ?>
                    <li class="page-item">
                        <a class="page-link" href="?page=<?= $currentPage - 1 ?>">Previous</a>
                    </li>
                    <?php endif; ?>
                    <?php for($i = 1; $i <= $totalPages; $i++) : ?>
                    <li class="page-item <?= ($i == $currentPage) ? 'active' : '' ; ?>">
                        <a class="page-link" href="?page=<?= $i;?>"><?= $i;?></a>
                    </li>
                    <?php endfor; ?>
                    <?php if($currentPage < $totalPages) : ?>
                    <li class="page-item">
                        <a class="page-link" href="?page=<?= $currentPage + 1 ?>">Next</a>
                    </li>
                    <?php endif; ?>
                </ul>
            </nav>

        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const addBtn = document.getElementById('addBtn');
        const popupForm = document.getElementById('popupForm');
        const closeBtn = popupForm.querySelector('.close');

        addBtn.addEventListener('click', () => {
            popupForm.style.display = 'block';
        });

        closeBtn.addEventListener('click', () => {
            popupForm.style.display = 'none';
        });

        window.addEventListener('click', (event) => {
            if (event.target == popupForm) {
                popupForm.style.display = 'none';
            }
        });

        // Fungsi untuk mendapatkan tanggal hari ini (format YYYY-MM-DD)
        function getCurrentDate() {
            const today = new Date();
            const year = today.getFullYear();
            const month = String(today.getMonth() + 1).padStart(2, '0'); // Bulan dimulai dari 0
            const day = String(today.getDate()).padStart(2, '0');
            return `${year}-${month}-${day}`;
        }

        // Atur nilai elemen input dengan tanggal hari ini
        document.getElementById('periode').value = getCurrentDate();
    });

    function deleteData(no_barang, button) {
        if (confirm('Apakah Anda yakin ingin menghapus data ini?')) {
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "delete_index.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    // Tampilkan pesan sukses
                    document.getElementById('deleteSuccessMessage').style.display = 'block';

                    // Hapus baris dari tabel
                    var row = button.parentNode.parentNode;
                    row.parentNode.removeChild(row);

                    // Sembunyikan pesan setelah beberapa detik
                    setTimeout(function() {
                        document.getElementById('deleteSuccessMessage').style.display = 'none';
                    }, 2000); // 2 detik
                }
            };
            xhr.send("no_barang=" + no_barang);
        }
    }
    </script>
    <div id="popupForm" class="popup">
        <div class="popup-content">
            <span class="close">&times;</span>
            <div class="card mt-4" id="card">
                <div class="card-header">
                    Tambah User
                </div>
                <br>
                <div class="card-body" id="card-body">
                    <form method="POST" action="index.php">
                        <div class="mb-3">
                            <label for="user" class="form-label">User:</label>
                            <input type="text" class="form-control" id="user" name="user" required>
                        </div>
                        <div class="mb-3">
                            <label for="periode" class="form-label">Periode:</label>
                            <input type="date" class="form-control" id="periode" name="periode" required>
                        </div>
                        <div class="row">
                            <div class="justify content">
                                <input type="submit" name="simpan" value="Simpan" class="btn btn-primary">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</body>

<style>
body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
    margin: 0;
    padding: 0;
}

.popup {
    display: none;
    position: fixed;
    z-index: 1;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(0, 0, 0, 0.4);

}

.popup-content {
    background-color: #fff;
    margin: 8% auto;
    padding: 20px;
    border: 1px solid #888;
    width: 60%;
    /* Adjust width as needed */
    border-radius: 10px;
    padding-bottom: 3%;
    padding-top: 0;
}

.popup-content form>div {
    display: grid;
    grid-template-columns: 20% 80%;
    /* Adjust column widths as needed */
    padding: 10px;
    /* Adjust gap between columns as needed */

}

.close {
    color: #aaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
}

.close:hover,
.close:focus {
    color: black;
    text-decoration: none;
    cursor: pointer;
}

.card-header {
    font-size: 1.25rem;
    font-weight: bold;
    font-family: 'Trebuchet MS';
}

.btn-primary {
    background-color: #007bff;
    border-color: #007bff;
}

.btn-primary:hover {
    background-color: #0056b3;
    border-color: #004085;
}


tbody {
    text-align: center;
}

.judul {
    font-size: 25px;
    padding: 10px;
    text-decoration: thistle;
    font-family: 'Trebuchet MS';
}

.date {
    width: 15%;
    text-align: center;
}

.itemlist1,
.aksi {
    width: 20%;
    text-align: center;
}

.thead {
    text-align: center;
}


.user {
    width: 25%;
    text-align: center;
}

.no_barang {
    width: 10%;
}

.ket {
    width: 20%;
}

.no {
    width: 6%;
}

.isi {
    justify-content: center;
    /* Mengatur teks ke tengah secara horizontal */
    align-items: center;
}

.card {
    width: 100%;
    height: 100%;
}
</style>

</html>
