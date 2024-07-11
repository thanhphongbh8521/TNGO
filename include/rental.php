<?php
if (isset($_GET['id'])) {
    $bikeId = $_GET['id'];
    $sql_bike = "SELECT bike_number FROM bike WHERE id = $bikeId";
    $result_bike = $mysqli->query($sql_bike);
    if ($result_bike->num_rows > 0) {
        $row_bike = $result_bike->fetch_assoc();
        $bikeNumber = $row_bike['bike_number'];
    } else {
        $bikeNumber = "Không tìm thấy thông tin";
    }

    $sql_station = "SELECT name FROM stations WHERE station_id = (SELECT station_id FROM bike WHERE id = $bikeId)";
    $result_station = $mysqli->query($sql_station);
    if ($result_station->num_rows > 0) {
        $row_station = $result_station->fetch_assoc();
        $stationName = $row_station['name'];
    } else {
        $stationName = "Không tìm thấy thông tin";
    }
} else {
    $bikeNumber = "Không có ID xe đạp";
    $renterName = "Không có tên người thuê";
    $stationName = "Không có tên trạm";
}

date_default_timezone_set('Asia/Ho_Chi_Minh');

$currentDate = date("d-m-Y");

$currentDateTime = date("d-m-Y H:i:s");

if(isset($_POST['rent-button'])) {

    $rentalDuration = $_POST['rental-duration'];
    $roundedMinutes = ceil($rentalDuration / 30) * 30;
    $rentalPrice = $roundedMinutes * (5000 / 30);
    $thanhvienId = $_SESSION['thanhvien_id'];
    $renterName = $_SESSION['dangnhap_home'];
    $rentalDate = date("Y-m-d H:i:s");
    $returnTime = date("Y-m-d H:i:s", strtotime("+$roundedMinutes minutes"));

    $sql_insert_rental = "INSERT INTO rental (bike_id, thanhvien_id, name, station_name, rental_date, return_time, rental_duration, rental_price) 
                            VALUES ('$bikeId', '$thanhvienId', '$renterName', '$stationName', '$rentalDate', '$returnTime', '$roundedMinutes', '$rentalPrice')";
    $result_insert_rental = $mysqli->query($sql_insert_rental);

    $sql_update_bike = "UPDATE bike SET status = 1 WHERE id = $bikeId";
    $result_update_bike = $mysqli->query($sql_update_bike);

    echo "<script>alert('Thuê xe thành công'); window.location.href = 'index.php?quanly=traxe&thanhvien_id=" . $_SESSION['thanhvien_id'] . "';</script>";
} 

?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thuê Xe</title>
    <style>
        .popup {
            display: block;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.5); 
        }

        .popup-content {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: #fefefe;
            padding: 20px;
            border: 1px solid #888;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
    </style>
</head>
<body>

<div class="popup" id="popup">
    <div class="popup-content">
        <h2>Thuê Xe</h2>
        <table>
            <tr>
                <td><strong>Tên trạm:</strong></td>
                <td><?php echo $stationName; ?></td>
            </tr>
            <tr>
                <td><strong>Tên người thuê:</strong></td>
                <td><?php echo $_SESSION['dangnhap_home']; ?></td>
            </tr>
            <tr>
                <td><strong>ID Thành viên:</strong></td>
                <td><?php echo $_SESSION['thanhvien_id']; ?></td>
            </tr>
            <tr>
                <td><strong>Số xe đạp:</strong></td>
                <td><?php echo $bikeNumber; ?></td>
            </tr>
            <tr>
                <td><strong>Ngày thuê:</strong></td>
                <td><?php echo $currentDate; ?></td>
            </tr>
            <tr>
                <td><strong>Thời gian trả:</strong></td>
                <td id="return-time"></td>
            </tr>
        </table>

        <form id="rental-form" method="post">
            <label for="rental-duration">Số phút muốn thuê:</label>
            <input type="number" id="rental-duration" name="rental-duration" min="30" placeholder="Tg thuê ít nhất 30 phút" required>
            <p>Giá thuê: 5000VNĐ/30 phút</p>
            <p id="rental-price">Số tiền phải trả:  VNĐ</p>
            <button type="submit" id="rent-button" name="rent-button">Bắt đầu</button>
        </form>
    </div>
    
</div>

<script>
    // Lấy trường input và phần hiển thị giá tiền và thời gian trả
    var rentalDurationInput = document.getElementById('rental-duration');
    var rentalPriceDisplay = document.getElementById('rental-price');
    var returnTimeDisplay = document.getElementById('return-time');

   
    // Sự kiện khi trường input thay đổi
    rentalDurationInput.addEventListener('input', function() {
        // Lấy giá trị nhập vào
        var rentalDuration = parseInt(rentalDurationInput.value);

        // Kiểm tra nếu giá trị nhập vào là số hợp lệ
        if (!isNaN(rentalDuration)) {
            // Tính toán số tiền phải trả
            var rentalPrice = rentalDuration * (5000 / 30); // Giá thuê: 5000VNĐ/30 phút

            // Hiển thị số tiền phải trả
            rentalPriceDisplay.textContent = 'Số tiền phải trả: ' + rentalPrice.toLocaleString() + ' VNĐ';

            // Tính toán và hiển thị thời gian trả
            var returnTime = new Date();
            returnTime.setMinutes(returnTime.getMinutes() + rentalDuration); // Thêm số phút muốn thuê
            returnTimeDisplay.textContent = returnTime.toLocaleString();
        }
    });
</script>


</body>
</html>