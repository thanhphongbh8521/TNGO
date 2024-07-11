<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trạm xe</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f2f2f2;
        }
        .container {
            width: 80%;
            margin: 20px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
        }
        .station {
            border: 1px solid #dddddd;
            margin-bottom: 10px;
            padding: 10px;
            border-radius: 5px;
            cursor: pointer; 
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
        }
        .station h3 {
            margin-top: 0;
            width: 100%;
            text-align: center;
        }
        .station p {
            margin: 0;
            width: 100%;
            text-align: center;
        }
        .bike-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            margin-top: 10px;
        }
        .bike {
            width: 80px;
            height: 60px;
            background-color: #ccc;
            border-radius: 5px;
            margin: 5px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer; 
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Thông tin trạm xe đạp</h2>
    <?php
    $sql = "SELECT stations.station_id, stations.name, stations.location, COUNT(bike.bike_number) AS available_bike 
            FROM stations LEFT JOIN bike ON stations.station_id = bike.station_id AND bike.status = '0'
            GROUP BY stations.station_id";
    $result = $mysqli->query($sql);

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo "<div class='station' onclick='redirectToStationInfoPage(".$row["station_id"].")'>";
            echo "<h3>".$row["name"]."</h3><p>Vị trí: ".$row["location"]."</p>";
            echo "<p>Số lượng xe sẵn sàng: ".$row["available_bike"]."</p>";
            echo "</div>";
        }
    } else {
        echo "<p>Không có dữ liệu</p>";
    }
    ?>
</div>

<script>
    function redirectToStationInfoPage(stationId) {
        window.location.href = "index.php?quanly=thongtintram&station_id=" + stationId;
    }
</script>

</body>
</html>
