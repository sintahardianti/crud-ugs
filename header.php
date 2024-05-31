<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<?php 
// Proses logout
if (isset($_GET['logout'])) {
    // Hapus semua sesi
    session_unset();
    session_destroy();
    
    // Arahkan pengguna ke halaman login atau halaman beranda
    header("Location: akun.php"); 
    exit();
} ?>


<body>
    <header>
        <nav class="navbar bg-primary">
            <div class="container-fluid d-flex align-items-center">
                <a class="navbar-brand" href="">
                    <img src="http://localhost/barang/logougs.png" alt="Logo UGS" height="50"
                        class="d-inline-block align-text-top" />
                    <span style="color:white">IT UNGGUL SEMESTA</span>
                </a>
            </div>
        </nav>
    </header>
    <style>
    .navbar-nav .nav-link:hover {
        background-color: #e0e0e0;
        /* Warna teks untuk tombol aktif */
    }

    .navbar-nav .nav-link {
        /* Warna teks default */
    }

    .navbar-nav .nav-link:active {
        background-color: #e0e0e0;
        /* Ubah latar belakang menjadi abu-abu saat tombol diklik */
        /* Warna teks tetap sama sebelum tombol diklik */
    }

    .navbar {
        border-bottom: 4px solid rgb(214, 214, 214);
    }

    span {
        padding-left: 30px;
        display: inline-block;
        vertical-align: middle;
        font-family: "trebuchet ms";
        text-align: justify;
        padding-top: 5px;
    }

    .nav-link {
        font-family: "trebuchet ms";
    }

    .collapse {
        padding-left: 20px;
    }

    .logout {
        align-content: right;
    }

    .justify-right {
        text-align: right;
    }
    </style>
    <a href="#">
        <nav class="navbar navbar-expand-sm bg-light">
            <button class="navbar-toggler d-lg-none" type="button" data-bs-toggle="collapse"
                data-bs-target="#collapsibleNavId" aria-controls="collapsibleNavId" aria-expanded="false"
                aria-label="Toggle navigation"></button>
            <div class="collapse navbar-collapse" id="collapsibleNavId">
                <ul class="navbar-nav me-auto mt-2 mt-lg-0">
                    <li class="nav-item">
                        <a id="barang-link"
                            class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>"
                            href="index.php" aria-current="page"> BARANG </a>
                    </li>
                    <li class="nav-item">
                        <a id="asset-link"
                            class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'asset.php' ? 'active' : ''; ?>"
                            href="asset.php" aria-current="page"> ASSET </a>
                    </li>
                </ul>
            </div>
            <div class="navbar-nav ml-auto">
                <!-- Menggunakan 'ml-auto' untuk membuat menu di pojok kanan -->
                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'akun.php' ? 'active' : ''; ?>"
                    href="?logout" aria-current="page" onclick="return confirm('Yakin ingin logout?')">LOGOUT</a>
            </div>
        </nav>
        </header>
</body>

</html>
