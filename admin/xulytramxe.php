<?php
    include('../db/connect.php');
?>
<?php
    session_start();
    if(!isset($_SESSION['dangnhap'])){
        header('Location:index.php');
    }
    if(isset($_GET['login'])){
        $dangxuat = $_GET['login'];
    }else{
        $dangxuat = '';
    }
    if($dangxuat == 'dangxuat'){
        session_destroy();
        header('Location:index.php');
    }
?>
<?php
    if(isset($_POST['themxe'])){
        $station_id = $_POST['station_id'];
        $bike_number = $_POST['bike_number'];

        $sql_check_bike = mysqli_query($mysqli, "SELECT * FROM bike WHERE station_id='$station_id' AND bike_number='$bike_number'");
        if(mysqli_num_rows($sql_check_bike) > 0){
            echo "<script>alert('Số xe đã tồn tại trong trạm. Vui lòng chọn số xe khác.');</script>";
        } else {
            $sql_insert_bike = mysqli_query($mysqli, "INSERT INTO bike(station_id, bike_number, status) VALUES ('$station_id', '$bike_number', '0')");

            if($sql_insert_bike) {
                updateBikeCount($mysqli, $station_id);
            } else {
                echo "<script>alert('Có lỗi xảy ra khi thêm xe.');</script>";
            }
        }
    }
    if(isset($_POST['xoaxe'])){
        $bike_number = $_POST['bike_number'];
        $station_id = $_POST['station_id'];

        $sql_delete_bike = mysqli_query($mysqli, "DELETE FROM bike WHERE bike_number='$bike_number'");

        if($sql_delete_bike) {
            updateBikeCount($mysqli, $station_id);
        } else {
            echo "<script>alert('Có lỗi xảy ra khi xóa xe.');</script>";
        }
    }

    function updateBikeCount($mysqli, $station_id) {
        $sql_count_bikes = mysqli_query($mysqli, "SELECT COUNT(*) AS count FROM bike WHERE station_id = '$station_id'");
        $row_count = mysqli_fetch_assoc($sql_count_bikes);
        $new_bike_count = $row_count['count'];

        $sql_update_station = mysqli_query($mysqli, "UPDATE stations SET bike_count = '$new_bike_count' WHERE station_id = '$station_id'");
        
        if(!$sql_update_station) {
            echo "<script>alert('Có lỗi xảy ra khi cập nhật số lượng xe của trạm.');</script>";
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Trạm Xe</title>
    <link href="../css/bootstrap.css" rel="stylesheet" type="text/css" media="all" />
</head>
<body>
    <p>Xin chào: <?php echo $_SESSION['dangnhap'] ?> <a href="?login=dangxuat">Đăng xuất </a></p>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="xulydanhmucbaiviet.php">Danh mục bài viết
                            <i class="fas fa-solid fa-list"></i></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="xulybaiviet.php">Bài viết</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="xulythanhvien.php">Thành viên</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="danhmuctramxe.php">Danh mục trạm xe</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="xulytramxe.php">Xử lý trạm xe</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav><br>
    <div class="container">
        <div class="row">
            <div class="col-md-10">
                <h4>Danh sách Trạm Xe</h4>
                <?php
                    $sql_select_station = mysqli_query($mysqli, "SELECT * FROM stations");
                ?>
                <table class="table table-bordered">
                    <tr>
                        <th>Thứ tự</th>
                        <th>Tên trạm xe</th>
                        <th>Vị trí</th>
                        <th>Số lượng xe</th>
                        <th>Quản lý</th>
                        <th hidden></th>
                    </tr>
                    <?php
                        $i = 0;
                        while($row_station = mysqli_fetch_array($sql_select_station)){
                            $i++;
                    ?>
                    <tr>
                        <td><?php echo $i ?></td>
                        <td><?php echo $row_station['name'] ?></td>
                        <td><?php echo $row_station['location'] ?></td>
                        <td><?php echo $row_station['bike_count'] ?></td>
                        <td><a href="?xem=<?php echo $row_station['station_id'] ?>">Xem</a>
                    </tr>
                    <?php
                        }
                    ?>
                </table>
            </div>

            <?php
            if(isset($_GET['xem'])){
                $station_id = $_GET['xem'];
                $sql_select_bikes = mysqli_query($mysqli, "SELECT * FROM bike WHERE station_id='$station_id'");
            ?>
            <div class="col-md-9">
                <h4>Thông tin chi tiết Trạm Xe</h4>
                <table class="table table-bordered">
                    <tr>
                        <th>Thứ tự</th>
                        <th>Số xe</th>
                        <th>Trạng thái</th>
                        <th>Quản lý</th>
                    </tr>
                    <?php
                        $i = 0;
                        while($row_bike = mysqli_fetch_array($sql_select_bikes)){
                            $i++;
                    ?>
                    <tr>
                        <td><?php echo $i ?></td>
                        <td><?php echo $row_bike['bike_number'] ?></td>
                        <td>
                            <?php
                                if ($row_bike['status'] == 0) {
                                    echo "Sẵn sàng";
                                } elseif ($row_bike['status'] == 1) {
                                    echo "Đang được thuê";
                                }
                            ?>
                        </td>
                        <td>
                            <form action="" method="POST">
                                <input type="hidden" name="station_id" value="<?php echo $station_id; ?>">
                                <input type="hidden" name="bike_number" value="<?php echo $row_bike['bike_number']; ?>">
                                <input type="submit" name="xoaxe" value="Xóa" class="btn btn-danger">
                            </form>
                        </td>
                    </tr>
                    <?php
                        }
                    ?>
                </table>
            </div>

            <div class="col-md-3">
                <h4>Thêm Xe</h4>
                <form action="" method="POST">
                    <input type="hidden" name="station_id" value="<?php echo $station_id; ?>">
                    <label>Số xe</label>
                    <input type="text" class="form-control" name="bike_number" required><br>
                    <input type="submit" name="themxe" value="Thêm Xe" class="btn btn-default">
                </form>
            </div>
            <?php
                }
            ?>

        </div>
    </div>
</body>
</html>
