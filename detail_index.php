<?php
include 'db.php';
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

$no_barang = $_GET['no_barang'] ?? null;
$sukses = $_GET['sukses'] ?? null;

if (!$no_barang) {
    die("Data tidak ditemukan");
}

$sql = "SELECT * FROM `index` WHERE no_barang = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $no_barang);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Data tidak ditemukan");
}

$item = $result->fetch_assoc();

// Ambil data detail dari database
$sql_detail = "SELECT * FROM detail WHERE no_barang = ?";
$stmt_detail = $conn->prepare($sql_detail);
$stmt_detail->bind_param("s", $no_barang);
$stmt_detail->execute();
$result_detail = $stmt_detail->get_result();

$details = [];
while ($row_detail = $result_detail->fetch_assoc()) {
    $details[] = $row_detail;
}

$stmt_detail->close();

$detail_count = count($details);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Barang</title>
    <link rel="stylesheet" href="styles.css">
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
    <div class="container">
        <div class="card">
            <div class="card-header">
                Edit Barang
            </div>
            <div class="card-body">
                <?php if ($sukses): ?>
                <div class="alert alert-success" role="alert">
                    Data berhasil diperbarui!
                </div>
                <script>
                setTimeout(function() {
                    window.location.href = window.location.href.split('?')[0] +
                        '?no_barang=<?= htmlspecialchars($no_barang) ?>';
                }, 2000);
                </script>
                <?php endif; ?>
                <form method="POST" action="update_index.php">
                    <input type="hidden" name="no_barang" value="<?= htmlspecialchars($no_barang) ?>">
                    <div class="input-group mb-3">
                        <label for="user" class="input-group-text">User:</label>
                        <input type="text" id="user" name="user" class="form-control"
                            value="<?= htmlspecialchars($item['user']) ?>" required>
                    </div>
                    <div class="input-group mb-3">
                        <label for="periode" class="input-group-text">Periode:</label>
                        <input type="date" id="periode" name="periode" class="form-control"
                            value="<?= htmlspecialchars($item['periode']) ?>" required>
                    </div>
                    <div style="text-align:left;">
                        <input type="submit" name="simpan" value="Update Data" class="btn btn-primary"
                            onclick="return confirm('yakin mau update data?')">
                    </div>
                </form>
            </div>
        </div>
        <br>
        <div class="container" style="width: 95%;">
            <h4>Detail Barang</h4>
            <div class="d-flex justify-content-end">
                <?php if ($detail_count < 10) { ?>
                <button id="addDetailBtn" class="btn btn-success">Tambah Barang <i class="fas fa-plus"></i></button>
                <?php } else { ?>
                <button id="addDetailBtn" class="btn btn-success" disabled>Maksimum 10 Barang Tercapai</button>
                <?php } ?>
            </div>
            <br>
            <table id="detailTable" class="table">
                <div id="successMessage" style="display: none;" class="alert alert-success" role="alert">
                    Data berhasil diperbarui!
                </div>
                <div id="deleteSuccessMessage" style="display: none;" class="alert alert-success" role="alert">
                    Berhasil menghapus data!
                </div>
                <thead>
                    <tr style="text-align:center;">
                        <th>No.</th>
                        <th>Nama Barang</th>
                        <th>Jumlah</th>
                        <th>Keterangan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    if (!empty($details)) {
                        foreach ($details as $detailIndex => $detail) { ?>
                    <tr>
                        <td><?= $detailIndex + 1 ?></td>
                        <td><?= htmlspecialchars($detail['nama_barang']) ?></td>
                        <td><?= htmlspecialchars($detail['jumlah']) ?></td>
                        <td><?= htmlspecialchars($detail['keterangan2']) ?></td>
                        <td>
                            <button class="btn btn-warning btn-edit" data-id="<?= $detail['id'] ?>"
                                data-nama="<?= htmlspecialchars($detail['nama_barang']) ?>"
                                data-jumlah="<?= htmlspecialchars($detail['jumlah']) ?>"
                                data-keterangan2="<?= htmlspecialchars($detail['keterangan2']) ?>">Edit</button>
                            <button class="btn btn-danger btn-delete" data-id="<?= $detail['id'] ?>"
                                data-no-barang="<?= $no_barang ?>">Hapus</button>
                        </td>
                    </tr>
                    <?php } 
                    } else {
                        echo "<tr><td colspan='5'>Tidak ada detail tersedia.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
            <br>
            <div class="d-flex justify-content-end">
                <div class="d-flex justify-content-end">
                    <?php
                    // Mengirim informasi tentang detail yang tersedia ke halaman print2.php
                    $detail_available = ($result_detail->num_rows > 0) ? 'true' : 'false';
                    ?>
                    <a href="javascript:void(0);" id="print" class="btn btn-success"
                        data-detail-available="<?php echo $detail_available; ?>">Print <i class="fas fa-print"></i></a>
                </div>

                <script>
                document.getElementById('print').addEventListener('click', function(event) {
                    var detailAvailable = this.getAttribute('data-detail-available');
                    if (detailAvailable === 'false') {
                        alert('Detail Tidak Tersedia');
                    } else {
                        window.open(
                            'print2.php?no_barang=<?php echo htmlspecialchars($no_barang); ?>&detail_available=true',
                            '_blank');
                    }
                });
                </script>
            </div>
        </div>
    </div>
    <!-- Popup Form for Adding and Editing Details -->
    <div id="popupForm" class="popup" style="display:none;">
        <div class="popup-content">
            <span class="close">&times;</span>
            <div class="card mt-4">
                <div class="card-header">
                    <span id="popupTitle">Tambah Barang</span>
                </div>
                <br>
                <div class="card-body">
                    <form id="addDetailForm" method="POST" action="add_detail.php" autocomplete="off">
                        <input type="hidden" name="id" id="detail_id">
                        <input type="hidden" name="no_barang" value="<?= htmlspecialchars($no_barang) ?>">
                        <div class="mb-3">
                            <label for="nama_barang" class="form-label">Nama Barang:</label>
                            <input type="text" class="form-control" id="nama_barang" name="nama_barang" required>
                        </div>
                        <div class="mb-3">
                            <label for="jumlah" class="form-label">Jumlah:</label>
                            <input type="number" class="form-control" id="jumlah" name="jumlah" required min="1">
                        </div>
                        <div class="mb-3">
                            <label for="ket2" class="form-label">Keterangan:</label>
                            <input type="text" class="form-control" id="ket2" name="ket2">
                        </div>
                        <br>
                        <div class="row justify-content-end">
                            <button type="submit" name="simpan" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const addDetailBtn = document.getElementById('addDetailBtn');
        const popupForm = document.getElementById('popupForm');
        const popupTitle = document.getElementById('popupTitle');
        const detailCloseBtn = popupForm.querySelector('.close');
        const form = document.getElementById('addDetailForm');
        const detailIdInput = document.getElementById('detail_id');
        const namaBarangInput = document.getElementById('nama_barang');
        const jumlahInput = document.getElementById('jumlah');
        const ket2Input = document.getElementById('ket2');
        const btnEdits = document.querySelectorAll('.btn-edit');
        const successMessage = document.getElementById('successMessage');
        const deleteSuccessMessage = document.getElementById(
            'deleteSuccessMessage'); // Tambahkan elemen pesan sukses hapus

        // Function to show success message
        function showSuccessMessage(messageElement) {
            messageElement.style.display = 'block';
            setTimeout(function() {
                messageElement.style.display = 'none';
            }, 1000); // Hide after 3 seconds
        }

        // Script for handling delete buttons
        var deleteButtons = document.querySelectorAll(".btn-delete");
        deleteButtons.forEach(function(button) {
            button.addEventListener("click", function(event) {
                event.preventDefault();
                var confirmation = confirm('Yakin mau hapus data?');
                if (confirmation) {
                    var detailId = this.getAttribute('data-id');
                    var noBarang = this.getAttribute('data-no-barang');
                    fetch('delete_detail.php?no_barang=' + noBarang + '&detail_id=' +
                            detailId, {
                                method: 'GET'
                            })
                        .then(function(response) {
                            if (response.ok) {
                                // Tampilkan pesan berhasil hapus
                                showSuccessMessage(deleteSuccessMessage);

                                // Refresh halaman setelah penghapusan berhasil
                                setTimeout(function() {
                                    location.reload();
                                }, 1000); // Refresh setelah 3 detik
                            } else {
                                console.error('Error:', response.statusText);
                                // Handle error here if needed
                            }
                        })
                        .catch(function(error) {
                            console.error('Error:', error);
                        });
                }
            });
        });
        // End of delete buttons script

        addDetailBtn.addEventListener('click', () => {
            popupTitle.textContent = 'Tambah Barang';
            form.action = 'add_detail.php';
            detailIdInput.value = '';
            namaBarangInput.value = '';
            jumlahInput.value = '';
            ket2Input.value = '';
            popupForm.style.display = 'block';
            successMessage.style.display = 'none'; // Hide success message
        });

        btnEdits.forEach(btn => {
            btn.addEventListener('click', (e) => {
                const id = btn.getAttribute('data-id');
                const nama = btn.getAttribute('data-nama');
                const jumlah = btn.getAttribute('data-jumlah');
                const keterangan2 = btn.getAttribute('data-keterangan2');
                popupTitle.textContent = 'Edit Barang';
                form.action = 'update_detail.php';
                detailIdInput.value = id;
                namaBarangInput.value = nama;
                jumlahInput.value = jumlah;
                ket2Input.value = keterangan2;
                popupForm.style.display = 'block';
                successMessage.style.display = 'none'; // Hide success message
            });
        });

        form.addEventListener('submit', (e) => {
            e.preventDefault(); // Prevent default form submission
            const formData = new FormData(form);
            fetch(form.action, {
                    method: 'POST',
                    body: formData
                })
                .then(response => {
                    if (response.ok) {
                        popupForm.style.display = 'none'; // Close popup
                        successMessage.style.display = 'block'; // Show success message
                        setTimeout(() => {
                            location.reload(); // Refresh the page after 3 seconds
                        }, 1000);
                    } else {
                        // Handle error here if needed
                        console.error('Error:', response.statusText);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        });

        detailCloseBtn.addEventListener('click', () => {
            popupForm.style.display = 'none';
        });

        window.addEventListener('click', (event) => {
            if (event.target === popupForm) {
                popupForm.style.display = 'none';
            }
        });
    });
    </script>
</body>

</html>
