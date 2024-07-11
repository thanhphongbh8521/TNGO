<?php
    session_start();
    include('../db/connect.php');
?>
<?php
    // session_destroy();
    // unset('dangnhap');
    if(isset($_POST['dangnhap'])){
        $taikhoan = $_POST['taikhoan'];
        $matkhau = md5($_POST['matkhau']);
        if($taikhoan=='' || $matkhau ==''){
            echo '<p>Hãy nhập đủ tài khoản và mật khẩu</p>';
        }else{
            $sql_select_admin = mysqli_query($mysqli,"SELECT * FROM tbl_admin WHERE email ='$taikhoan' AND password = '$matkhau' LIMIT 1");
            $count = mysqli_num_rows($sql_select_admin);
            $row_dangnhap = mysqli_fetch_array($sql_select_admin);
            if($count>0){
                $_SESSION['dangnhap'] = $row_dangnhap['admin_name'];
                $_SESSION['admin_id'] = $row_dangnhap['admin_id'];
                header('Location: dashboard.php');
            }else{
                echo "<script>alert('Tài khoản hoặc mật khẩu không đúng') </script>";
            }
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Đăng nhập Admin</title>
    <!-- <link href="../css/bootstrap.css" rel="stylesheet" type="text/css" media="all" /> -->
    <link href="../css/login.css" rel="stylesheet" type="text/css" media="all" />
</head>
<body>
    
        <div class="login-box">
            <h2>Đăng nhập Admin</h2>
            <form action="" method="POST">
                <div class="user-box">
                <input type="text" name="taikhoan"  required="">
                <label>Tài khoản</label>
                </div>
                <div class="user-box">
                <input type="password" name="matkhau"  required="">
                <label>Mật khẩu</label>
                </div>
                <a href="#">
                <span></span>
                <span></span>
                <span></span>
                <span></span>
                <input type="submit" class="btn mt-4" name="dangnhap" value="LOGIN">
                </a>
            </form>
        </div>
</body>
</html>