<?php
// Include your database connection code here
$host = "localhost";
$user = "root";
$pass = "";
$db = "mahasiswauam";

$koneksi = mysqli_connect($host, $user, $pass, $db);
if (!$koneksi) {
    die("Tidak bisa terkoneksi ke database");
}

if (isset($_GET['ids']) && !empty($_GET['ids'])) {
    $selectedIds = explode(",", $_GET['ids']);
    
    // Validate and sanitize the IDs to prevent SQL injection
    $selectedIds = array_map('intval', $selectedIds);
    $selectedIds = implode(",", $selectedIds);

    $sql = "DELETE FROM mahasiswa WHERE id IN ($selectedIds)";
    $q = mysqli_query($koneksi, $sql);

    if ($q) {
        $sukses = "Selected entries have been deleted successfully.";
    } else {
        $error = "Failed to delete selected entries.";
    }
} else {
    $error = "No selected entries to delete.";
}

// Redirect back to the main page
header("Location: index.php?error=$error&sukses=$sukses");
exit();
?>
