<?php
    include('db/connect.php');

    if(isset($_POST['thembaiviet'])){
        $tenbaiviet = $_POST['tenbaiviet'];
        $hinhanh = $_FILES['hinhanh']['name'];
        $danhmuc = $_POST['danhmuc'];
        $chitiet = $_POST['chitiet'];
        $mota = $_POST['mota'];
        $path = 'uploads/';
        $thanhvien_id = $_SESSION['thanhvien_id']; // Giả sử 'thanhvien_id' được lưu trong session khi người dùng đăng nhập

        $hinhanh_tmp = $_FILES['hinhanh']['tmp_name'];
        $sql_insert_product = mysqli_query($mysqli, "INSERT INTO tbl_baiviet(tenbaiviet, tomtat, noidung, danhmuctin_id, baiviet_image, thanhvien_id) VALUES('$tenbaiviet', '$mota', '$chitiet', '$danhmuc', '$hinhanh', '$thanhvien_id')");
        move_uploaded_file($hinhanh_tmp, $path.$hinhanh);
    } elseif (isset($_GET['dangxuat'])) {
        $id = $_GET['dangxuat'];
        if($id == 1){
            unset($_SESSION['dangnhap_home']);
        }
    }
?>
<?php
if(isset($_GET['xoa'])){
    $id = $_GET['xoa'];
    $sql_delete = mysqli_query($mysqli, "DELETE FROM tbl_baiviet WHERE baiviet_id='$id'");
}
?>

<div class="container">
    <div class="row">
        <div class="col-md-4"><br>
            <h4>Thêm bài viết</h4>
            <form action="" method="POST" enctype="multipart/form-data">
                <label>Tên bài viết</label>
                <input type="text" class="form-control" name="tenbaiviet" placeholder="Tên bài viết"><br>
                <label>Hình ảnh</label>
                <input type="file" class="form-control" name="hinhanh"><br>
                <label>Mô tả</label>
                <textarea class="form-control" name="mota"></textarea><br>
                <label>Chi tiết</label>
                <textarea class="form-control" name="chitiet"></textarea><br>
                <label>Danh mục</label>
                <?php
                    $sql_danhmuc = mysqli_query($mysqli, "SELECT * FROM tbl_danhmuctin ORDER BY danhmuctin_id ASC");
                ?>
                <select name="danhmuc" class="form-control">
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
       
        <div class="col-md-8"><br>
            <h4>Liệt kê bài viết</h4>
            <?php
                $thanhvien_id = $_SESSION['thanhvien_id']; // Lấy thanhvien_id từ session
                $sql_select_bv = mysqli_query($mysqli, "SELECT * FROM tbl_baiviet, tbl_danhmuctin WHERE tbl_baiviet.danhmuctin_id=tbl_danhmuctin.danhmuctin_id AND tbl_baiviet.thanhvien_id='$thanhvien_id' ORDER BY tbl_baiviet.baiviet_id DESC");
            ?>
            <table class="table table-bordered">
                <tr>
                    <th>Thứ tự</th>
                    <th>Tên bài viết</th>
                    <th>Hình ảnh</th>
                    <th>Danh mục</th>
                    <th>Quản lý</th>
                </tr>
                <?php
                    $i = 0;
                    while($row_bv = mysqli_fetch_array($sql_select_bv)){
                        $i++;
                ?>
                <tr>
                    <td><?php echo $i ?></td>
                    <td><?php echo $row_bv['tenbaiviet'] ?></td>
                    <td><img src="uploads/<?php echo $row_bv['baiviet_image'] ?>" height="100" width="80"></td>
                    <td><?php echo $row_bv['tendanhmuc'] ?></td>
                    <td><a href="index.php?quanly=dangbai&xoa=<?php echo $row_bv['baiviet_id'] ?>">Xóa</a></td>
                </tr>
                <?php
                    }
                ?>
            </table>
        </div>
    </div>
</div>
