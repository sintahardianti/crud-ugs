<?php
session_start(); // Mulai sesi di awal halaman

$login_message = "";

// Lakukan koneksi ke database
$host       = "localhost";
$user       = "root";
$password   = "";
$db         = "akun";

// Lakukan koneksi ke database
$db = new mysqli($host, $user, $password, $db);

// Periksa koneksi
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

// Hapus cache
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

// Pastikan form login telah disubmit
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Lakukan query SQL untuk memeriksa apakah username dan password sesuai
    $sql = "SELECT * FROM users WHERE username='$username' AND password ='$password'";
    $result = $db->query($sql);

    // Periksa apakah ada baris hasil dari query
    if ($result->num_rows > 0) {
        // Jika ada, berarti login berhasil
        $data = $result->fetch_assoc();

        // Set sesi untuk menandai bahwa pengguna sudah login
        $_SESSION["username"] = $data["username"];
        $_SESSION["is_login"] = true;

        // Redirect pengguna ke halaman index.php setelah login berhasil
        header("location: index.php");
        exit();
    } else {
        // Jika tidak ada baris hasil, berarti login gagal
        $login_message = "Username atau password salah";
    }
}

// Proses logout jika ada permintaan GET 'logout'
if (isset($_GET['logout'])) {
    session_unset();
    session_destroy();
    // Mengarahkan kembali ke halaman login setelah logout
    header("Location: akun.php");
    // Menjalankan JavaScript untuk menghapus riwayat navigasi
    echo '<script>history.replaceState({}, "", location.href);</script>';
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Pengeluaran Barang</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script>
    // Mengatur riwayat navigasi agar hanya menunjuk pada halaman akun.php
    history.pushState(null, null, location.href);
    window.onpopstate = function() {
        history.go(1);
    };
    </script>
</head>

<style>
body,
html {
    margin: 0;
    padding: 0;
    height: 100%;
    display: flex;
    justify-content: center;
    /* Menengahkan elemen horizontal */
    align-items: center;
    /* Menengahkan elemen vertikal */
    background-color: darkslategray;
}

.header {
    text-align: center;
}

.text-vertical-center {
    display: flex;
    flex-direction: column;
    align-items: center;
}

h4 {
    color: white;
    font-family: 'Trebuchet MS';
    text-shadow: 1px 1px 2px black, 0 0 25px red, 0 0 5px darkblue;
}

.card {
    width: 600px;
    background-color: lightblue;
}

img {
    margin-top: 20px;
}
</style>

<body>
    <header id="top" class="header">
        <div class="card">
            <div class="text-vertical-center">
                <img src="http://localhost/barang/logougs.png" alt="Logo UGS" height="120px" />
                <h4>
                    Selamat Datang, silakan masuk
                </h4>
                <form action="" method="post">
                    <p style="text-align: center;">
                        Username
                        <br>
                        <input type="text" class="textinput" name="username" value>
                    </p>
                    <p style="text-align: center;">
                        Password
                        <br>
                        <input type="password" class="textinput" name="password" value>
                    </p>
                    <p style="text-align: center;">
                        <input type="submit" value="LOGIN" class="btnlogin" name="login">
                    </p>
                </form>
                <p><?php echo $login_message ?></p>
            </div>
        </div>
    </header>
</body>

</html>
