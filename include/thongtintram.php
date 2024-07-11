<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bike Info</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f2f2f2;
            font-size: 14px; 
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
        p {
            text-align: center;
        }
        .bike-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: left;
            margin-bottom: 20px;
        }
        .bike {
            width: 24%;
            border: 1px solid #ddd;
            margin-left: 5px;
            padding: 10px;
            border-radius: 5px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); 
            margin-bottom: 20px;
            background-color: #9eff9d; 
            cursor: pointer; 
        }
        .bike p:nth-child(2) {
            display: none;
        }
        .bike-rented {
            background-color: #ff9d9d; 
        }
        
    </style>
</head>
<body>

<div class="container">
    <?php
        if (isset($_GET['station_id'])) {
            $station_id = $_GET['station_id'];

            $sql_station = "SELECT name FROM stations WHERE station_id = $station_id";
            $result_station = $mysqli->query($sql_station);

            if ($result_station->num_rows > 0) {
                $row_station = $result_station->fetch_assoc();
                echo "<h2>Thông tin xe đạp của ".$row_station['name']."</h2><br> "; 
            } 

            $sql_bike = "SELECT * FROM bike WHERE station_id = $station_id";
            $result_bike = $mysqli->query($sql_bike);

            if ($result_bike->num_rows > 0) {
                echo "<div class='bike-container'>";
                $count = 0; 
                while ($row = $result_bike->fetch_assoc()) {
                    $bg_color = ($row['status'] == '1') ? 'bike-rented' : '';
                    $bg_color = ($row['status'] == '0') ? 'bike-ready' : $bg_color; 
                    echo "<div class='bike $bg_color' onclick='checkAndDisplayModal(\"".$row['id']."\", \"".$row['status']."\", \"$station_id\")'><h5 style='margin: 0; text-align: center;'>Số xe đạp: ".$row['bike_number']."</h5><p style='display: none;'>ID xe đạp: ".$row['id']."</p><p>ID trạm: ".$row['station_id']."</p><p>Trạng thái: ";
                    echo ($row['status'] == '1') ? 'Đang được thuê' : 'Sẵn sàng';
                    echo "</p></div>";
                    $count++;
                    if ($count == 4) {
                        echo "</div><div class='bike-container'>";
                        $count = 0;
                    }
                }
                echo "</div>";
            } else {
                echo "<p>Không tìm thấy thông tin cho trạm xe đạp có ID: $station_id</p>";
            }
        } else {
            echo "<p>Không đủ thông tin để hiển thị.</p>";
        }
    ?>


</div>
<script>
    function checkAndDisplayModal(id, status) {
        if (status === '1') {
            alert("Xe đạp này đang được thuê. Vui lòng chọn xe khác.");
        } else {
            window.location.href = "index.php?quanly=thuexe&id=" + encodeURIComponent(id);
        }
    }
</script>


</body>
</html>
