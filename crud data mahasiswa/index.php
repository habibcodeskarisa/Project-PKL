<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "mahasiswauam";

$koneksi = mysqli_connect($host, $user, $pass, $db);
if (!$koneksi) {
    die("Tidak bisa terkoneksi ke database");
}

$nim      = "";
$nama     = "";
$alamat   = "";
$fakultas = "";
$sukses   = "";
$error    = "";
$q        = ""; // untuk mendefinisikan variabel $q

if (isset($_GET['op'])) {
    $op = $_GET['op'];
} else {
    $op = "";
}

if ($op == 'delete') {
    $id   = $_GET['id'];
    $sql  = "DELETE FROM mahasiswa WHERE id = '$id'";
    $q    = mysqli_query($koneksi, $sql);
    $pesan = $q ? "Sukses berhasil hapus data" : "Gagal melakukan delete data";
    $sukses = $pesan;
}

if ($op == 'edit') {
    $id_edit = isset($_POST['id']) ? $_POST['id'] : '';
    $id   = $_GET['id'];
    $sql  = "SELECT * FROM mahasiswa WHERE id = '$id'";
    $q    = mysqli_query($koneksi, $sql);
    $r    = mysqli_fetch_array($q);

    if ($r) {
        $nim      = $r['nim'];
        $nama     = $r['nama'];
        $alamat   = $r['alamat'];
        $fakultas = $r['fakultas'];
    } else {
        $error = "Data tidak ditemukan";
    }
}

if (isset($_POST['simpan'])) {
    $nim      = $_POST['nim'];
    $nama     = $_POST['nama'];
    $alamat   = $_POST['alamat'];
    $fakultas = $_POST['fakultas'];
    $id_edit  = $_POST['id']; 

    if ($nim && $nama && $alamat && $fakultas) {
        if (!empty($id_edit)) { 
            $sql = "UPDATE mahasiswa SET nim = '$nim', nama='$nama', alamat = '$alamat', fakultas='$fakultas' WHERE id = '$id_edit'";
            $q   = mysqli_query($koneksi, $sql);
            $pesan = $q ? "Data berhasil diupdate" : "Data gagal diupdate";
            $sukses = $pesan;
        } else {
            $error = "ID tidak valid"; 
        }
    } else {
        $error = "Silahkan masukkan semua data";
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Mahasiswa UAM</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        crossorigin="anonymous">
    <link rel="shortcut icon" href="unnamed.png" type="image/x-icon">
    <!-- Bootstrap JavaScript and jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous">
    </script>
    <style>
    body {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        /* overflow: hidden; */
    }

    .header-name {
        margin: -2px;
        background: #212529;
        display: flex;
        justify-content: space-between;
    }


    @import url('https://fonts.googleapis.com/css2?family=Oswald:wght@600&family=Poppins&display=swap');
    .header-name span {
        margin: auto;
        color: white;
        font-size: 25px;
        font-family: 'Poppins', sans-serif;
    }

    .card-body {
        margin: -16px;
    }

    .mx-auto {
        width: 95vw;
        height: 90vh;
        /* background: silver; */
        /* box-shadow: 0 0 5px rgba(0, 0, 0, 0.5); */
    }

    .search-form {
        display: grid;
        place-content: center;
    }

    .search-input {
        margin-right: 8px;
    }

    .input-group {
        width: 300px;
        margin-left: 60rem;
    }

    .header-name .btn-success {
        margin: 10px;
        padding: 0 15px;
        font-size: 14px;
    }

    .table-secondary tr {
        text-align: center;
    }
    
    .wrap-table-data {
    text-align: center;
}

    .card-footer {
            display: flex;
            justify-content: flex-end;
    }

    .wrap-table-data tr:hover {
        background-color: #E2E3E5;
        transition: 0.1s all ease-in-out;
    }

    .wrap-table-data tr td .atur-select-checkbox {
        margin: 13px 0 0 -350px;
        transform: scale(1.4);
    }
    </style>
</head>

<body>

<div class="mx-auto">
        <!-- untuk memasukan data -->
        <div class="mx-auto">
            <!-- <div class="card-body"> -->
            <?php
                if ($error) {
                    ?>
            <div class="alert alert-danger" role="alert">
                <?php echo $error ?>
            </div>
            <?php
                header("refresh:2;url=index.php");
            }

            if ($sukses) {
                ?>
            <div class="alert alert-success" role="alert">
                <?php echo $sukses ?>
            </div>
            <?php
                header("refresh:2;url=index.php");
            }
            ?>
            <!-- Modal Edit Data -->
            <?php
$sql_edit = "SELECT * FROM mahasiswa ORDER BY id DESC";
$q_edit   = mysqli_query($koneksi, $sql_edit);
while ($r_edit = mysqli_fetch_array($q_edit)) {
    $id_edit       = $r_edit['id'];
    $nim_edit      = $r_edit['nim'];
    $nama_edit     = $r_edit['nama'];
    $alamat_edit   = $r_edit['alamat'];
    $fakultas_edit = $r_edit['fakultas'];

    switch ($fakultas_edit) {
        case 'saintek':
            $fakultas_display_edit = 'Sains dan Teknologi';
            break;
        case 'ekobis':
            $fakultas_display_edit = 'Ekonomi dan Bisnis';
            break;
        case 'ilkes':
            $fakultas_display_edit = 'Ilmu Kesehatan';
            break;
        default:
            $fakultas_display_edit = $fakultas_edit;
            break;
    }
?>

            <div class="modal fade" id="modalEditData<?php echo $id_edit ?>" tabindex="-1"
                aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Edit Data Mahasiswa</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="" method="POST">
                                <div class="mb-3 row">
                                    <label for="nim" class="col-sm-2 col-form-label">NIM</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="nim" name="nim" value="<?php echo $nim_edit ?>">
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label for="nama" class="col-sm-2 col-form-label">Nama</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="nama" name="nama" value="<?php echo $nama_edit ?>">
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label for="alamat" class="col-sm-2 col-form-label">Alamat</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="alamat" name="alamat" value="<?php echo $alamat_edit ?>">
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label for="fakultas" class="col-sm-2 col-form-label">Fakultas</label>
                                    <div class="col-sm-10">
                                        <select class="form-control" name="fakultas" id="fakultas">
                                            <option value="saintek"
                                                <?php echo ($fakultas_edit == 'saintek') ? 'selected' : ''; ?>>Sains dan
                                                Teknologi</option>
                                            <option value="ekobis"
                                                <?php echo ($fakultas_edit == 'ekobis') ? 'selected' : ''; ?>>Ekonomi
                                                dan Bisnis</option>
                                            <option value="ilkes"
                                                <?php echo ($fakultas_edit == 'ilkes') ? 'selected' : ''; ?>>Ilmu
                                                Kesehatan</option>
                                        </select>
                                    </div>
                                </div>
                                <input type="hidden" name="id" value="<?php echo $id_edit ?>">
                                <div class="col-12">
                                    <input type="submit" name="simpan" value="✅ Simpan Data" class="btn btn-primary" />
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <?php
}
?>

            <!-- Modal Tambah Data -->
            <div class="modal fade" id="modalTambahData" tabindex="-1" aria-labelledby="exampleModalLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">➕ Tambah Data Mahasiswa</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="" method="POST">
                                <div class="mb-3 row">
                                    <label for="nim" class="col-sm-2 col-form-label">NIM</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="nim" name="nim" value="">
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label for="nama" class="col-sm-2 col-form-label">Nama</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="nama" name="nama" value="">
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label for="alamat" class="col-sm-2 col-form-label">Alamat</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="alamat" name="alamat" value="">
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label for="fakultas" class="col-sm-2 col-form-label">Fakultas</label>
                                    <div class="col-sm-10">
                                        <select class="form-control" name="fakultas" id="fakultas">
                                            <option value="saintek">Sains dan Teknologi</option>
                                            <option value="ekobis">Ekonomi dan Bisnis</option>
                                            <option value="ilkes">Ilmu Kesehatan</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <input type="submit" name="simpan" value="✅ Simpan Data" class="btn btn-primary" />
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- untuk mengeluarkan data -->
            <div class="mx-auto ">
                <div class="card kertu-as ">
                    <div class="card-header header-name">
                        <img src="https://uam.ac.id/wp-content/uploads/2022/07/logo_horizontal-1024x410.png" width="150px" height="60px">
                        <span>Data Fakultas UAM</span>
                        <form class="search-form" method="GET" action="index.php">
                            <div class="input-group">
                                <input type="text" class="form-control search-input" placeholder="Search NIM/Nama...."
                                    name="q" value="<?php echo isset($_GET['q']) ? $_GET['q'] : ''; ?>">
                                <button class="btn btn-primary" type="submit">🔎 Cari</button>
                            </div>
                        </form>
                        <button type="button" class="btn btn-success" data-bs-toggle="modal"
                            data-bs-target="#modalTambahData">➕ Tambah Data</button>
                    </div>
                </div>

                <div class="card-body card-wrap">
                    <table class="table">
                        <thead class="table-secondary">
                            <tr>
                                <th scope="col">No</th>
                                <th scope="col">NIM</th>
                                <th scope="col">Nama</th>
                                <th scope="col">Alamat</th>
                                <th scope="col">Fakultas</th>
                                <th scope="col">Aksi</th>
                                <th class="card-footer"><button type="button" class="btn btn-danger ml-auto" id="deleteSelected">🚮 Delete Selected</button></th>
                            </tr>
                        </thead>
                        <tbody class="wrap-table-data">
                            <?php
                                    $sql2 = "SELECT * FROM mahasiswa ORDER BY id DESC";
                                    if (isset($_GET['q']) && !empty($_GET['q'])) {
                                        $q2 = mysqli_query($koneksi, "SELECT * FROM mahasiswa WHERE nama LIKE '%".$_GET['q']."%' OR nim LIKE '%".$_GET['q']."%' ORDER BY id DESC");
                                    } else {
                                        $q2 = mysqli_query($koneksi, $sql2);
                                    }
                                    $urut = 1;
                                    while ($r2 = mysqli_fetch_array($q2)) {
                                        $id         = $r2['id'];
                                        $nim        = $r2['nim'];
                                        $nama       = $r2['nama'];
                                        $alamat     = $r2['alamat'];
                                        $fakultas   = $r2['fakultas'];

                                        switch ($fakultas) {
                                            case 'saintek':
                                                $fakultas_display = 'Sains dan Teknologi';
                                                break;
                                            case 'ekobis':
                                                $fakultas_display = 'Ekonomi dan Bisnis';
                                                break;
                                            case 'ilkes':
                                                $fakultas_display = 'Ilmu Kesehatan';
                                                break;
                                            default:
                                                $fakultas_display = $fakultas;
                                                break;
                                        }
                                    ?>
                            <tr class="atur-width-table">
                                <th scope="row"><?php echo $urut++ ?></th>
                                <td scope="row"><?php echo $nim ?></td>
                                <td scope="row"><?php echo $nama ?></td>
                                <td scope="row"><?php echo $alamat ?></td>
                                <td scope="row"><?php echo $fakultas_display ?></td>
                                <td scope="row"> 
                                    
                                    <button type="button" class="btn btn-warning" data-bs-toggle="modal"
                                        data-bs-target="#modalEditData<?php echo $id ?>">✏️ Edit</button>
                                    <a href="index.php?op=delete&id=<?php echo $id ?>"
                                        onclick="return confirm('Apakah anda yakin mau delete data?')"><button
                                            type="button" class="btn btn-danger">🗑️ Delete</button></a>
                                </td>
                                <td><input type="checkbox" name="selected_ids[]" value="<?php echo $id; ?>" class="select-checkbox atur-select-checkbox"></td>
                            </tr>
                            <?php
                                    }
                                    ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    </div>
    </div>
    <script>
    $(document).ready(function() {
        // Alihkan semua kotak centang ketika kotak centang header diklik
        $("#selectAll").change(function() {
            $(".select-checkbox").prop('checked', $(this).prop("checked"));
        });

        // Alihkan tombol "Hapus yang Dipilih" berdasarkan pilihan kotak centang
        $(".select-checkbox").change(function() {
            if ($(".select-checkbox:checked").length > 0) {
                $("#deleteSelected").removeAttr("disabled");
            } else {
                $("#deleteSelected").attr("disabled", "disabled");
            }
        });

        // Hapus acara klik tombol yang Dipilih
        $("#deleteSelected").click(function() {
            var selectedIds = [];

            // Dapatkan ID yang dipilih
            $(".select-checkbox:checked").each(function() {
                selectedIds.push($(this).val());
            });

            // Periksa apakah ada kotak centang yang dipilih
            if (selectedIds.length > 0) {
                // Minta konfirmasi
                if (confirm("Are you sure you want to delete the selected entries?")) {
                    // Redirect untuk menghapus semua entri yang dipilih
                    window.location.href = "delete_selected.php?ids=" + selectedIds.join(",");
                }
            }
        });
    });
</script>

</body>
</html>