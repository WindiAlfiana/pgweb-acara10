<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "tabel php";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM tabel_penduduk";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Web GIS & Tabel Data Penduduk</title>
    
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
            /* Mengubah arah susunan dari samping ke bawah (vertikal) */
            flex-direction: column; 
            height: calc(100vh - 50px);
        }
        /* Peta berada di atas dengan ukuran lebih dominan (65%) */
        #map {
            width: 100%;
            height: 65%;
        }
        /* Sidebar/Tabel berada di bawah dengan ukuran 35% */
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
        <strong>Web GIS Persebaran Penduduk & Peta Interaktif</strong>
    </header>

    <div class="container">
        <!-- Bagian Peta Web GIS LeafletJS -->
        <div id="map"></div>

        <!-- Bagian Sidebar / Tabel Penduduk -->
        <div id="sidebar">
            <?php
            echo "<a href='input/index.html' class='input-link'>Input</a>";

            $markers = [];

            if ($result->num_rows > 0) {
                echo "<table border='1px'>
                <tr>
                    <th>ID</th>
                    <th>Kecamatan</th>
                    <th>Longitude</th>
                    <th>Latitude</th>
                    <th>Luas</th>
                    <th>Jumlah Penduduk</th>
                    <th class='text-center'>Aksi</th>
                </tr>";
                
                while($row = $result->fetch_assoc()) {
                    $id_data = $row["id"];
                    echo "<tr>";
                    echo "<td>" . $row["id"] . "</td>";
                    echo "<td>" . $row["Kecamatan"] . "</td>";
                    echo "<td>" . $row["Longitude"] . "</td>";
                    echo "<td>" . $row["Latitude"] . "</td>";
                    echo "<td>" . $row["Luas"] . "</td>";
                    echo "<td>" . $row["Jumlah Penduduk"] . "</td>";
                    echo "<td class='text-center'>
                            <a href='edit/index.php?id=$id_data' class='edit-btn'>Edit</a> | 
                            <a href='delete.php?id=$id_data' class='delete-btn' onclick='return confirm(\"Yakin ingin menghapus data ini?\")'>Delete</a>
                        </td>";
                    echo "</tr>"; 

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
        /* Initial Map */
        var map = L.map('map').setView([-7.7500, 110.3000], 11);

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

        // Layer Group untuk menampung marker dari database tabel_penduduk
        var markerLayer = L.layerGroup();

        <?php 
        foreach ($markers as $m) {
            $lat = $m['Latitude'];
            $lng = $m['Longitude'];
            $nama = $m['Kecamatan'];
            $luas = $m['Luas'];
            $penduduk = $m['Jumlah Penduduk'];
            
            echo "var marker = L.marker([$lat, $lng]);";
            echo "marker.bindPopup('<b>Kecamatan: $nama</b><br>Luas: $luas km²<br>Jumlah Penduduk: $penduduk');";
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
            "Titik Kecamatan": markerLayer
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