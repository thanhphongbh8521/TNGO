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
    if(isset($_POST['themdanhmuc'])){
        $tendanhmuc = $_POST['danhmuc'];
        $sql_insert = mysqli_query($mysqli, "INSERT INTO tbl_danhmuctin(tendanhmuc) values('$tendanhmuc')");

    }elseif(isset($_POST['capnhatdanhmuc'])){
        $id_post = $_POST['id_danhmuc'];
        $tendanhmuc = $_POST['danhmuc'];
        $sql_update = mysqli_query($mysqli, "UPDATE tbl_danhmuctin SET tendanhmuc='$tendanhmuc' WHERE danhmuctin_id ='$id_post' ");
        // header('Location:xulydanhmucbaiviet.php');
    }
    if(isset($_GET['xoa'])){
        $id= $_GET['xoa'];
        $sql_xoa = mysqli_query($mysqli, "DELETE FROM tbl_danhmuctin WHERE danhmuctin_id = '$id'");
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh mục</title>
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
            <?php
            if(isset($_GET['quanly'])=='capnhat'){
            $id_capnhat = $_GET['id'];
            $sql_capnhat = mysqli_query($mysqli, "SELECT * FROM tbl_danhmuctin WHERE danhmuctin_id ='$id_capnhat'");
            $row_capnhat = mysqli_fetch_array($sql_capnhat);
            ?>
                <div class="col-md-4">
                    <h4>Cập nhật danh mục</h4>
                    <label>Tên danh mục</label>
                    <form action=""method ="POST">
                        <input type="text" class="form-control" name="danhmuc" value="<?php echo $row_capnhat['tendanhmuc'] ?>"><br>
                        <input type="hidden" class="form-control" name="id_danhmuc" value="<?php echo $row_capnhat['danhmuctin_id'] ?>">
                        <input type="submit" name="capnhatdanhmuc" value="Cập nhật danh mục" class="btn btn-default">
                    </form>
                </div>
            <?php
            }else{
            ?>
                <div class="col-md-4">
                    <h4>Thêm danh mục</h4>
                    <label>Tên danh mục</label>
                    <form action=""method ="POST">
                        <input type="text" class="form-control" name="danhmuc" placeholder="Tên danh mục"><br>
                        <input type="submit" name="themdanhmuc" value="Thêm danh mục" class="btn btn-default">
                    </form>
                </div>
                <?php
                }
                ?>

            <div class="col-md-8">
                <h4>Liệt kê danh mục</h4>
                <?php
                    $sql_select = mysqli_query($mysqli,"SELECT * FROM tbl_danhmuctin ORDER BY danhmuctin_id DESC");
                ?>
                <table class="table table-bordered">
                    <tr>
                        <th>Thứ tự</th>
                        <th>Tên danh mục</th>
                        <th>Quản lý</th>
                    </tr>
                    <?php
                        $i =0;
                        while($row_category = mysqli_fetch_array($sql_select)){
                            $i++;
                    ?>
                    <tr>
                        <td><?php echo $i ?></td>
                        <td><?php echo $row_category['tendanhmuc'] ?></td>
                        <td><a href="?xoa=<?php echo $row_category['danhmuctin_id'] ?>">Xóa</a> || <a href="?quanly=capnhat&id=<?php echo $row_category['danhmuctin_id'] ?>">Cập nhật</a></td>
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