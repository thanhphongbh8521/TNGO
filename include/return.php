<?php
date_default_timezone_set('Asia/Ho_Chi_Minh');

$currentDate = date("d-m-Y");
$currentDateTime = date("H:i:s");

$message = '';

if (isset($_GET['thanhvien_id'])) {
    $thanhvienId = $_GET['thanhvien_id'];

    $sql_rental = "SELECT rental.*, bike.bike_number FROM rental 
                   INNER JOIN bike ON rental.bike_id = bike.id
                   WHERE rental.thanhvien_id = $thanhvienId";
    $result_rental = $mysqli->query($sql_rental);
    if ($result_rental->num_rows > 0) {
        $rentals = $result_rental->fetch_all(MYSQLI_ASSOC);
    } else {
        $message = "Hiện tại không có xe nào được thuê";
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {

    $rentalId = $_POST['rental_id'];

    $sql_update_bike = "UPDATE bike SET status = '0' WHERE id = (SELECT bike_id FROM rental WHERE id = $rentalId)";
    $result_update_bike = $mysqli->query($sql_update_bike);

    if ($result_update_bike) {
        $sql_delete_rental = "DELETE FROM rental WHERE id = $rentalId";
        $result_delete_rental = $mysqli->query($sql_delete_rental);

        if ($result_delete_rental) {
            echo "<script>alert('Trả xe thành công'); window.location.href = 'rent_bike.php' </script>";
        } else {
            echo "Có lỗi xảy ra khi xóa dữ liệu!";
        }
    } else {
        echo "Có lỗi xảy ra khi cập nhật trạng thái của xe!";
    }
}

?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trả sớm</title>
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

        .close-btn {
            background-color: #f44336;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            border-radius: 5px;
            font-size: 16px;
        }

        .close-btn:hover {
            background-color: #d32f2f;
        }
    </style>
</head>
<body>

<?php if ($message): ?>
    <div class="popup" id="popup">
        <div class="popup-content">
            <h2><?php echo $message; ?></h2>
            <button class="close-btn" onclick="closePopup()">Đóng</button>
        </div>
    </div>
<?php else: ?>
    <div class="popup" id="popup">
        <div class="popup-content">
            <h2>Quản lý xe thuê</h2>
            <?php foreach ($rentals as $rental): ?>
                <table>
                    <tr>
                        <td><strong>Số hóa đơn:</strong></td>
                        <td><?php echo $rental['id']; ?></td>
                    </tr>
                    <tr>
                        <td><strong>Tên trạm:</strong></td>
                        <td><?php echo $rental['station_name']; ?></td>
                    </tr>
                    <tr>
                        <td><strong>Tên người thuê:</strong></td>
                        <td><?php echo $_SESSION['dangnhap_home']; ?></td>
                    </tr>
                    <tr>
                        <td><strong>Số xe đạp:</strong></td>
                        <td><?php echo $rental['bike_number']; ?></td>
                    </tr>
                    <tr>
                        <td><strong>Ngày thuê:</strong></td>
                        <td><?php echo $currentDate; ?></td>
                    </tr>
                </table>

                <form id="rental-form" method="post" action="">
                    <input type="hidden" name="rental_id" value="<?php echo $rental['id']; ?>">
                    <button type="submit" id="" name="submit">Trả xe sớm</button>
                </form>
                <hr>
            <?php endforeach; ?>
            <button class="close-btn" onclick="closePopup()">Đóng</button>
        </div>
    </div>
<?php endif; ?>

<script>
    function closePopup() {
        document.getElementById('popup').style.display = 'none';
    }
</script>

</body>
</html>
