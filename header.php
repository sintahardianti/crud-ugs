<?php

if (isset($_GET['logout'])) {
    session_unset();
    session_destroy();
    header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
    header("Cache-Control: post-check=0, pre-check=0", false);
    header("Pragma: no-cache");
    header("Location: akun.php");
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script>
    // Mengunci tombol navigasi mundur dan maju
    window.history.pushState(null, null, window.location.href);
    window.onpopstate = function() {
        window.history.pushState(null, null, window.location.href);
    };

    // Merefresh riwayat navigasi saat halaman dimuat
    window.onload = function() {
        if (performance.navigation.type === 1) {
            // Jika halaman direfresh
            window.history.replaceState(null, null, window.location.href);
        } else {
            // Jika halaman dibuka pertama kali
            window.history.pushState(null, null, window.location.href);
        }
    };
    </script>
</head>

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
            <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'akun.php' ? 'active' : ''; ?>"
                href="?logout" aria-current="page" onclick="return confirm('Yakin ingin logout?')">LOGOUT</a>

        </div>
    </nav>
</body>

</html>
