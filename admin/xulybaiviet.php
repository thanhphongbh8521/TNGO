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
    if(isset($_POST['thembaiviet'])){
        $tenbaiviet = $_POST['tenbaiviet'];
        $hinhanh = $_FILES['hinhanh']['name'];
        $danhmuc = $_POST['danhmuc'];
        $chitiet = $_POST['chitiet'];
        $mota = $_POST['mota'];
        $path = '../uploads/';

        $hinhanh_tmp = $_FILES['hinhanh']['tmp_name'];
        $sql_insert_ptoduct = mysqli_query($mysqli, "INSERT INTO tbl_baiviet(tenbaiviet,tomtat,noidung,danhmuctin_id,baiviet_image) values('$tenbaiviet','$mota','$chitiet','$danhmuc','$hinhanh')");
        move_uploaded_file($hinhanh_tmp,$path.$hinhanh);
    }elseif(isset($_POST['capnhatbaiviet'])){
        $id_update = $_POST['id_update'];
        $tenbaiviet = $_POST['tenbaiviet'];
        $hinhanh = $_FILES['hinhanh']['name'];
        $hinhanh_tmp = $_FILES['hinhanh']['tmp_name'];
        $danhmuc = $_POST['danhmuc'];
        $chitiet = $_POST['chitiet'];
        $mota = $_POST['mota'];
        $path = '../uploads/';
        if($hinhanh==''){
            $sql_update_image = "UPDATE tbl_baiviet SET tenbaiviet='$tenbaiviet',noidung='$chitiet',tomtat='$mota',danhmuctin_id='$danhmuc' WHERE baiviet_id='$id_update'";
        }else{
            move_uploaded_file($hinhanh_tmp,$path.$hinhanh);
            $sql_update_image = "UPDATE tbl_baiviet SET tenbaiviet='$tenbaiviet',noidung='$chitiet',tomtat='$mota',danhmuctin_id='$danhmuc',baiviet_image='$hinhanh' WHERE baiviet_id='$id_update'";
        }
        mysqli_query($mysqli,$sql_update_image);
    }
    
?>
<?php
 if(isset($_GET['xoa'])){
    $id= $_GET['xoa'];
    $sql_xoa = mysqli_query($mysqli, "DELETE FROM tbl_baiviet WHERE baiviet_id='$id'");
    // $path_delete='../upload/';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bài viết</title>
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
            <?php
                if(isset($_GET['quanly'])=='capnhat'){
                $id_capnhat = $_GET['capnhat_id'];
                $sql_capnhat = mysqli_query($mysqli, "SELECT * FROM tbl_baiviet WHERE baiviet_id='$id_capnhat'");
                $row_capnhat = mysqli_fetch_array($sql_capnhat);
                $id_category_1= $row_capnhat['danhmuctin_id'];   
            ?>
            <div class="col-md-4">
                    <h4>Cập nhật bài viết</h4>
                    
                    <form action=""method ="POST" enctype="multipart/form-data">
                        <label>Tên bài viết</label>
                        <input type="text" class="form-control" name="tenbaiviet" value="<?php echo $row_capnhat['tenbaiviet'] ?>"><br>
                        <input type="hidden" class="form-control" name="id_update" value="<?php echo $row_capnhat['baiviet_id'] ?>">
                        <label>Hình ảnh</label>
                        <input type="file" class="form-control" name="hinhanh"><br>
                        <img src="../uploads/<?php echo $row_capnhat['baiviet_image'] ?>" height="80" width="80" ><br>
                        <label>Mô tả</label>
                        <textarea class="form-control" rows="10" name="mota"><?php echo $row_capnhat['tomtat'] ?>"</textarea><br>
                        <label>Chi tiết</label>
                        <textarea class="form-control" rows="10" name="chitiet"><?php echo $row_capnhat['noidung'] ?></textarea><br>
                        <label>Danh mục</label>
                        <?php
                            $sql_danhmuc = mysqli_query($mysqli,"SELECT * FROM tbl_danhmuctin ORDER BY danhmuctin_id DESC");
                        ?>
                        <select name="danhmuc" class="form-control" >
                            <option value="">CHỌN DANH MỤC</option>
                            <?php
                                while($row_danhmuc = mysqli_fetch_array($sql_danhmuc)){
                                    if($id_category_1==$row_danhmuc['danhmuctin_id']){
                            ?>
                            <option selected value="<?php echo $row_danhmuc['danhmuctin_id'] ?>"><?php echo $row_danhmuc['tendanhmuc'] ?></option>
                            <?php
                                }else{
                            ?>
                            <option value="<?php echo $row_danhmuc['danhmuctin_id'] ?>"><?php echo $row_danhmuc['tendanhmuc'] ?></option>
                            <?php
                                }
                            }
                            ?>
                        </select><br>
                        <input type="submit" name="capnhatbaiviet" value="Cập nhật bài viết" class="btn btn-default">
                    </form>
                </div>
            <?php
            }else{
            ?>
                <div class="col-md-4">
                    <h4>Thêm bài viết</h4>
                    
                    <form action=""method ="POST" enctype="multipart/form-data">
                        <label>Tên sản phẩm</label>
                        <input type="text" class="form-control" name="tenbaiviet" placeholder="Tên bài viết"><br>
                        <label>Hình ảnh</label>
                        <input type="file" class="form-control" name="hinhanh"><br>
                        <label>Mô tả</label>
                        <textarea class="form-control" name="mota" ></textarea><br>
                        <label>Chi tiết</label>
                        <textarea class="form-control" name="chitiet" ></textarea><br>
                        <label>Danh mục</label>
                        <?php
                            $sql_danhmuc = mysqli_query($mysqli,"SELECT * FROM tbl_danhmuctin ORDER BY danhmuctin_id DESC");
                        ?>
                        <select name="danhmuc" class="form-control" >
                            <option value="">--------Chọn danh mục--------</option>
                            <?php
                                while($row_danhmuc = mysqli_fetch_array($sql_danhmuc)){
                            ?>
                            <option value="<?php echo $row_danhmuc['danhmuctin_id'] ?>"><?php echo $row_danhmuc['tendanhmuc'] ?></option>
                            <?php
                            }
                            ?>
                        </select><br>
                        <input type="submit" name="thembaiviet" value="Thêm bài viết" class="btn btn-default">
                    </form>
                </div>
            <?php
            }
            ?>

            <div class="col-md-8">
                <h4>Liệt kê bài viết</h4>
                <?php
                    $sql_select_bv = mysqli_query($mysqli,"SELECT * FROM tbl_baiviet, tbl_danhmuctin WHERE tbl_baiviet.danhmuctin_id=tbl_danhmuctin.danhmuctin_id ORDER BY tbl_baiviet.baiviet_id DESC");
                ?>
                <table class="table table-bordered">
                    <tr>
                        <th>Thứ tự</th>
                        <th>Tên sản phẩm</th>
                        <th>Hình ảnh</th>
                        <th>Danh mục</th>
                        <th>Quản lý</th>
                    </tr>
                    <?php
                        $i =0;
                        while($row_bv = mysqli_fetch_array($sql_select_bv)){
                        $i++;
                    ?>
                    <tr>
                        <td><?php echo $i ?></td>
                        <td><?php echo $row_bv['tenbaiviet'] ?></td>
                        <td><img src="../uploads/<?php echo $row_bv['baiviet_image'] ?>" height="100" width="80" ></td>
                        <td><?php echo $row_bv['tendanhmuc'] ?></td>
                        <td><a href="?xoa=<?php echo $row_bv['baiviet_id'] ?>">Xóa</a> || 
                            <a href="xulybaiviet.php?quanly=capnhat&capnhat_id=<?php echo $row_bv['baiviet_id'] ?>">Cập nhật</a></td>
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