<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "studikasus";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$id = $_GET['id'] ?? '';
$result = $conn->query("SELECT * FROM tabel_pendidikan WHERE ID = $id");
$row = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Form Edit Fasilitas Pendidikan</title>
</head>
<body>
    <h2>Form Edit Fasilitas Pendidikan</h2>
    <!-- Mengarah ke file edit.php di folder yang sama -->
    <form action="edit.php" method="post">
        <!-- ID dikirim secara tersembunyi (hidden) untuk acuan WHERE ID -->
        <input type="hidden" name="id" value="<?php echo $row['ID']; ?>">

        <label for="kecamatan">Kecamatan:</label><br>
        <input type="text" name="kecamatan" value="<?php echo $row['Kecamatan']; ?>"><br>

        <label for="nama_objek">Nama Objek:</label><br>
        <input type="text" name="nama_objek" value="<?php echo $row['Nama Objek']; ?>"><br>

        <label for="jenjang_pendidikan">Jenjang Pendidikan:</label><br>
        <input type="text" name="jenjang_pendidikan" value="<?php echo $row['Jenjang Pendidikan']; ?>"><br>

        <label for="jalur_pendidikan">Jalur Pendidikan:</label><br>
        <input type="text" name="jalur_pendidikan" value="<?php echo $row['Jalur Pendidikan']; ?>"><br>

        <label for="longitude">Longitude:</label><br>
        <input type="text" name="longitude" value="<?php echo $row['Longitude']; ?>"><br>

        <label for="latitude">Latitude:</label><br>
        <input type="text" name="latitude" value="<?php echo $row['Latitude']; ?>"><br><br>

        <input type="submit" value="Update Data">
    </form>
</body>
</html>
<?php $conn->close(); ?>