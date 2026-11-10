<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "tabel php";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$kecamatan       = $_POST['kecamatan'] ?? '';
$longitude       = $_POST['longitude'] ?? '';
$latitude        = $_POST['latitude'] ?? '';
$luas            = $_POST['luas'] ?? '';
$jumlah_penduduk = $_POST['jumlah_penduduk'] ?? '';

if (empty($kecamatan)) {
    die("Error: Nama kecamatan tidak boleh kosong!");
}

if (!is_numeric($jumlah_penduduk) || $jumlah_penduduk < 0) {
    die("Error: Jumlah penduduk harus berupa angka yang valid!");
}

if (!is_numeric($longitude) || !is_numeric($latitude)) {
    die("Error: Koordinat longitude dan latitude harus berupa angka!");
}

$sql = "INSERT INTO tabel_penduduk (Kecamatan, Longitude, Latitude, Luas, `Jumlah Penduduk`) 
        VALUES ('$kecamatan', '$longitude', '$latitude', '$luas', '$jumlah_penduduk')";

if ($conn->query($sql) === TRUE) {
    // Kembali ke halaman index.php utama di luar folder input
    header("Location: ../index.php");
    exit();
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>