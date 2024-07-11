<?php
    include('../db/connect.php');

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

    if(isset($_POST['themtram'])){
        $name = $_POST['name'];
        $location = $_POST['location'];
        $sql_check_station = mysqli_query($mysqli, "SELECT * FROM stations WHERE name='$name'");
        if(mysqli_num_rows($sql_check_station) > 0) {
            echo "<script>alert('Tên trạm đã tồn tại. Vui lòng chọn tên khác!');</script>";
        } else {
            $sql_insert_station = mysqli_query($mysqli, "INSERT INTO stations(name, location) VALUES ('$name', '$location')");
        }
    } elseif(isset($_POST['capnhattram'])){
        $id_update = $_POST['id_update'];
        $name = $_POST['name'];
        $location = $_POST['location'];
        $sql_check_station = mysqli_query($mysqli, "SELECT * FROM stations WHERE name='$name' AND station_id != '$id_update'");
        if(mysqli_num_rows($sql_check_station) > 0) {
            
        } else {
            $sql_update_station = "UPDATE stations SET name='$name', location='$location' WHERE station_id='$id_update'";
            mysqli_query($mysqli, $sql_update_station);
        }
    }

    if(isset($_GET['xoa'])){
        $id= $_GET['xoa'];
        $sql_xoa = mysqli_query($mysqli, "DELETE FROM stations WHERE station_id='$id'");
        header('Location: danhmuctramxe.php'); 
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
            <div class="col-md-4">
                <h4>Thêm Trạm Xe</h4>
                
                <form action="" method ="POST">
                    <label>Tên trạm xe</label>
                    <input type="text" class="form-control" name="name" placeholder="Tên trạm xe" required><br>
                    <label>Vị trí</label>
                    <input type="text" class="form-control" name="location" placeholder="Vị trí" required><br>
                    <input type="submit" name="themtram" value="Thêm Trạm Xe" class="btn btn-default">
                </form>
            </div>
            <?php
                if(isset($_GET['quanly'])=='capnhat'){
                    $id_capnhat = $_GET['capnhat_id'];
                    $sql_capnhat = mysqli_query($mysqli, "SELECT * FROM stations WHERE station_id='$id_capnhat'");
                    $row_capnhat = mysqli_fetch_array($sql_capnhat);
            ?>
            <div class="col-md-4">
                <h4>Cập nhật Trạm Xe</h4>
                
                <form action="" method="POST">
                    <input type="hidden" class="form-control" name="id_update" value="<?php echo $row_capnhat['station_id'] ?>">
                    <label>Tên trạm xe</label>
                    <input type="text" class="form-control" name="name" value="<?php echo $row_capnhat['name'] ?>"><br>
                    <label>Vị trí</label>
                    <input type="text" class="form-control" name="location" value="<?php echo $row_capnhat['location'] ?>"><br>
                    <input type="submit" name="capnhattram" value="Cập nhật Trạm Xe" class="btn btn-default">
                </form>
            </div>
            <?php
                }
            ?>
            <div class="col-md-12">
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
                        <td>
                            <a href="?xoa=<?php echo $row_station['station_id'] ?>" onclick="return confirm('Bạn có chắc chắn muốn xóa?')">Xóa</a> || 
                            <a href="danhmuctramxe.php?quanly=capnhat&capnhat_id=<?php echo $row_station['station_id'] ?>">Cập nhật</a>
                        </td>
                    </tr>
                    <?php
                        }
                    ?>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
