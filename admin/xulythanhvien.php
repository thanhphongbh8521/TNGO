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
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thành viên</title>
    <link href="../css/bootstrap.css" rel="stylesheet" type="text/css" media="all" />

</head>
<body>
<p>Xin chào: <?php echo $_SESSION['dangnhap'] ?> <a href="?login=dangxuat">Đăng xuất </a></p>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="xulydanhmucbaiviet.php">Danh mục bài viết</a>
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
            <div class="col-md-12">
                <h4>Thành viên</h4>
                <?php
                    $sql_select = mysqli_query($mysqli,"SELECT * FROM tbl_thanhvien");
                ?>
                <table class="table table-bordered">
                    <tr>
                        <th>Thứ tự</th>
                        <th>Tên khách hàng</th>
                        <th>Số điện thoại</th>
                        <th>Địa chỉ</th>
                        <th>Email</th>
                    </tr>
                    <?php
                        $i =0;
                        while($row_thanhvien = mysqli_fetch_array($sql_select)){
                            $i++;
                    ?>
                    <tr>
                        <td><?php echo $i ?></td>
                        <td><?php echo $row_thanhvien['name'] ?></td>
                        <td><?php echo $row_thanhvien['phone'] ?></td>
                        <td><?php echo $row_thanhvien['address'] ?></td>
                        <td><?php echo $row_thanhvien['email'] ?></td>
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