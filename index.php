<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "studikasus";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Mengambil data dari tabel_pendidikan
$sql = "SELECT * FROM tabel_pendidikan";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Web GIS & Tabel Fasilitas Pendidikan</title>
    
    <!-- Leaflet CSS Library -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.5.1/dist/leaflet.css">
    
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            height: 100vh;
            flex-direction: column;
        }
        header {
            background-color: #2c95bc;
            color: white;
            padding: 10px 20px;
            font-size: 18px;
        }
        .container {
            display: flex;
            flex: 1;
            flex-direction: column; 
        }
        /* Peta di atas dan lebih dominan (65%) */
        #map {
            width: 100%;
            height: 65%;
        }
        /* Tabel di bawah (35%) dengan scroll vertikal */
        #sidebar {
            width: 100%;
            height: 35%;
            padding: 15px;
            box-sizing: border-box;
            overflow-y: auto;
            background-color: #f8f9fa;
            border-top: 1px solid #ddd;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            background: white;
        }
        table, th, td {
            border: 1px solid #ccc;
        }
        th, td {
            padding: 8px;
            text-align: left;
            font-size: 13px;
        }
        th {
            background-color: #f2f2f2;
        }
        .text-center {
            text-align: center;
        }
        a.input-link {
            color: purple;
            text-decoration: underline;
            font-weight: bold;
            display: inline-block;
            margin-bottom: 10px;
        }
        .edit-btn {
            color: blue;
            text-decoration: none;
            font-weight: bold;
        }
        .delete-btn {
            color: red;
            text-decoration: none;
            font-weight: bold;
        }
        .edit-btn:hover, .delete-btn:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>

    <header>
        <strong>Web GIS Persebaran Fasilitas Pendidikan & Peta Interaktif</strong>
    </header>

    <div class="container">
        <!-- Bagian Peta Web GIS LeafletJS di Atas -->
        <div id="map"></div>

        <!-- Bagian Tabel Atribut di Bawah -->
        <div id="sidebar">
            <?php
            // Link menuju folder input
            echo "<a href='input/index.html' class='input-link'>Input Data Baru</a>";

            $markers = [];

            if ($result->num_rows > 0) {
                echo "<table>
                <tr>
                    <th>ID</th>
                    <th>Kecamatan</th>
                    <th>Nama Objek</th>
                    <th>Jenjang Pendidikan</th>
                    <th>Jalur Pendidikan</th>
                    <th>Longitude</th>
                    <th>Latitude</th>
                    <th class='text-center'>Aksi</th>
                </tr>";
                
                while($row = $result->fetch_assoc()) {
                    $id_data = $row["ID"]; // Kolom primary key database
                    echo "<tr>";
                    echo "<td>" . $row["ID"] . "</td>";
                    echo "<td>" . $row["Kecamatan"] . "</td>";
                    echo "<td>" . $row["Nama Objek"] . "</td>";
                    echo "<td>" . $row["Jenjang Pendidikan"] . "</td>";
                    echo "<td>" . $row["Jalur Pendidikan"] . "</td>";
                    echo "<td>" . $row["Longitude"] . "</td>";
                    echo "<td>" . $row["Latitude"] . "</td>";
                    echo "<td class='text-center'>
                            <a href='edit/index.php?id=$id_data' class='edit-btn'>Edit</a> | 
                            <a href='delete.php?id=$id_data' class='delete-btn' onclick='return confirm(\"Yakin ingin menghapus data ini?\")'>Hapus</a>
                            </td>";
                    echo "</tr>"; 

                    // Simpan data ke dalam array untuk ditarik ke peta JavaScript
                    $markers[] = $row;
                }
                
                echo "</table>";
            } else {
                echo "0 results";
            }
            ?>
        </div>
    </div>

    <!-- Leaflet JavaScript Library -->
    <script src="https://unpkg.com/leaflet@1.5.1/dist/leaflet.js"></script>

    <script>
        /* Initial Map (Berpusat di wilayah Bantul/Yogyakarta) */
        var map = L.map('map').setView([-7.9500, 110.3000], 11);

        /* Tile Basemap 1: Esri Imagery */
        var basemap1 = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
            attribution: 'Tiles &copy; Esri'
        });

        /* Tile Basemap 2: Google Street */
        var basemap2 = L.tileLayer('https://{s}.google.com/vt/lyrs=m&x={x}&y={y}&z={z}', {
            maxZoom: 20,
            subdomains: ['mt0', 'mt1', 'mt2', 'mt3'],
            attribution: 'Map data &copy; Google'
        });

        // Menambahkan basemap default ke peta
        basemap1.addTo(map);

        // Layer Group untuk menampung marker dari database tabel_pendidikan
        var markerLayer = L.layerGroup();

        <?php 
        // Looping data dari PHP ke JavaScript Leaflet sesuai kolom tabel_pendidikan
        foreach ($markers as $m) {
            $lat = $m['Latitude'];
            $lng = $m['Longitude'];
            $nama_objek = addslashes($m['Nama Objek']);
            $kecamatan = addslashes($m['Kecamatan']);
            $jenjang = addslashes($m['Jenjang Pendidikan']);
            
            echo "var marker = L.marker([$lat, $lng]);";
            echo "marker.bindPopup('<b>$nama_objek</b><br>Kecamatan: $kecamatan<br>Jenjang: $jenjang');";
            echo "markerLayer.addLayer(marker);";
        }
        ?>

        markerLayer.addTo(map);

        /* Control Layer */
        var baseMaps = {
            "Esri Imagery": basemap1,
            "Google Street": basemap2
        };

        var overlayMaps = {
            "Titik Fasilitas Pendidikan": markerLayer
        };

        L.control.layers(baseMaps, overlayMaps, { collapsed: false }).addTo(map);

        /* Scale Bar */
        L.control.scale({
            maxWidth: 150,
            position: 'bottomright'
        }).addTo(map);
    </script>
</body>
</html>

<?php
$conn->close();
?>