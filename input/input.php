<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "studikasus";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Menangkap data dari form input pendidikan
$kecamatan          = $_POST['kecamatan'] ?? '';
$nama_objek         = $_POST['nama_objek'] ?? '';
$jenjang_pendidikan = $_POST['jenjang_pendidikan'] ?? '';
$jalur_pendidikan   = $_POST['jalur_pendidikan'] ?? '';
$longitude          = $_POST['longitude'] ?? '';
$latitude           = $_POST['latitude'] ?? '';

// Validasi data tidak boleh kosong
if (empty($kecamatan) || empty($nama_objek)) {
    die("Error: Kecamatan dan Nama Objek tidak boleh kosong!");
}

// Validasi koordinat harus berupa angka
if (!is_numeric($longitude) || !is_numeric($latitude)) {
    die("Error: Koordinat longitude dan latitude harus berupa angka!");
}

// Query SQL Insert dengan menyesuaikan nama kolom berhuruf kapital di database
$sql = "INSERT INTO tabel_pendidikan (`Kecamatan`, `Nama Objek`, `Jenjang Pendidikan`, `Jalur Pendidikan`, `Longitude`, `Latitude`) 
        VALUES ('$kecamatan', '$nama_objek', '$jenjang_pendidikan', '$jalur_pendidikan', '$longitude', '$latitude')";

if ($conn->query($sql) === TRUE) {
    // Kembali ke halaman index.php utama
    header("Location: ../index.php");
    exit();
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>