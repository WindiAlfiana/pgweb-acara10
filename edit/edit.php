<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "studikasus";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$id                 = $_POST['id'];
$kecamatan          = $_POST['kecamatan'];
$nama_objek         = $_POST['nama_objek'];
$jenjang_pendidikan = $_POST['jenjang_pendidikan'];
$jalur_pendidikan   = $_POST['jalur_pendidikan'];
$longitude          = $_POST['longitude'];
$latitude           = $_POST['latitude'];

// Query Update database
$sql = "UPDATE tabel_pendidikan 
        SET `Kecamatan` = '$kecamatan', 
            `Nama Objek` = '$nama_objek', 
            `Jenjang Pendidikan` = '$jenjang_pendidikan', 
            `Jalur Pendidikan` = '$jalur_pendidikan', 
            `Longitude` = '$longitude', 
            `Latitude` = '$latitude' 
        WHERE `ID` = $id";

if ($conn->query($sql) === TRUE) {
    // Kembali ke index.php utama di luar folder
    header("Location: ../index.php");
    exit();
} else {
    echo "Error updating record: " . $conn->error;
}

$conn->close();
?>