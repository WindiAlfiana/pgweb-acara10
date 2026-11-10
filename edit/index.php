<!DOCTYPE html>
<html>
<body>
<h2>Form Edit</h2>
<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "tabel php";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$id = $_GET['id'];
$sql = "SELECT * FROM tabel_penduduk WHERE id = $id";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<form action='edit.php' onsubmit='return validateForm()' method='post'>";
    while($row = $result->fetch_assoc()) {
        echo "<input type='hidden' name='id' value='".$row['id']."'>";
        echo "<label for='kecamatan'>Kecamatan:</label><br>";
        echo "<input type='text' id='kec' name='kecamatan' value='".$row['Kecamatan']."'>";
        echo "<br><label for='longitude'>Longitude:</label><br>";
        echo "<input type='text' id='long' name='longitude' value='".$row['Longitude']."'>";
        echo "<br><label for='latitude'>Latitude:</label><br>";
        echo "<input type='text' id='lat' name='latitude' value='".$row['Latitude']."'>";
        echo "<br><label for='luas'>Luas:</label><br>";
        echo "<input type='text' id='luas' name='luas' value='".$row['Luas']."'>";
        echo "<br><label for='jumlah_penduduk'>Jumlah Penduduk:</label><br>";
        echo "<input type='text' id='jml_pddk' name='jumlah_penduduk' value='".$row['Jumlah Penduduk']."'>";
        echo "<br><br>";
    }
    echo "<input type='submit' value='Submit'>";
    echo "</form>";
}
?>

<p id="informasi" style="color: red;"></p>

<script>
    function validateForm() {
        let luas = document.getElementById("luas").value;
        let text="";
        if (isNaN(luas) || luas < 1) {
            text = "Data luas harus angka dan tidak boleh bernilai negatif";
            event.preventDefault();
        }
        document.getElementById("informasi").innerHTML = text;
    }
</script>
</body>
</html>