<?php
    session_start();
    include('db/connect.php');
?>
<?php
    // session_destroy();
    // unset('dangnhap');
    if(isset($_POST['dangnhap_home'])){
        $taikhoan = $_POST['email_login'];
        $matkhau = md5($_POST['password_login']);
        if($taikhoan=='' || $matkhau ==''){
            echo '<p style="color:white; background-color:red;">Vui lòng điền đủ tài khoản và mật khẩu</p>';
        }else{
            $sql_select_admin = mysqli_query($mysqli,"SELECT * FROM tbl_thanhvien WHERE email ='$taikhoan' AND password = '$matkhau' LIMIT 1");
            $count = mysqli_num_rows($sql_select_admin);
            $row_dangnhap = mysqli_fetch_array($sql_select_admin);
            if($count>0){
                $_SESSION['dangnhap_home'] = $row_dangnhap['name'];
                $_SESSION['thanhvien_id'] = $row_dangnhap['thanhvien_id'];
				
                header('Location: index.php');
            }else{
                echo'<p style="color:white; background-color:red;">Tài khoản hoặc mật khẩu không đúng</p>';
            }
        }
    }elseif(isset($_POST['dangky'])){
		$name = $_POST['name'];
		$phone = $_POST['phone'];
		$email = $_POST['email'];
		$password = md5($_POST['password']);
		$note = $_POST['note'];
		$address = $_POST['address'];
		$sql_thanhvien = mysqli_query($mysqli,"INSERT INTO tbl_thanhvien(name,phone,email,address,note,password) values ('$name','$phone','$email','$address','$note','$password')");
		$sql_select_thanhvien = mysqli_query($mysqli,"SELECT * FROM tbl_thanhvien ORDER BY thanhvien_id DESC LIMIT 1");
		$row_thanhvien = mysqli_fetch_array($sql_select_thanhvien);
		$_SESSION['dangnhap_home'] = $name;
        $_SESSION['thanhvien_id'] = $row_thanhvien['thanhvien_id'];

		header('Location:index.php');
	}
?>

<!DOCTYPE HTML>
<html>
<head>
<title></title>
<script src="js/jquery.min.js"></script>
<!-- Custom Theme files -->
<link href="css/welcome.css" rel="stylesheet" type="text/css" media="all"/>
<!-- for-mobile-apps -->
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> 
<meta name="keywords" content="Classy Login form Responsive, Login form web template, Sign up Web Templates, Flat Web Templates, Login signup Responsive web template, Smartphone Compatible web template, free webdesigns for Nokia, Samsung, LG, SonyEricsson, Motorola web design" />
<!-- //for-mobile-apps -->
<!--Google Fonts-->
<link href='//fonts.googleapis.com/css?family=Roboto+Condensed:400,700' rel='stylesheet' type='text/css'>
<style>
    .custom-button {
        background-color: #4CAF50;
        border: none;
        color: white;
        padding: 15px 32px;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        font-size: 16px;
        margin: 4px 2px;
        cursor: pointer;
        border-radius: 8px;
    }
    .header-bottom {
    border: 1px solid white;
    background-color: rgba(0, 0, 0, 0.7); 
    }
</style>
</head>
<body>
<!--header start here-->
<div class="header">
    <div class="header-main">
        <div class="header-bottom">
        <h1>Đăng nhập</h1>
            <div class="header-right w3agile">
                <div class="header-left-bottom agileinfo">
                    <form action="#" method="POST">
                    <input type="text" name="email_login" placeholder="Email"/>
                    <input type="password" name="password_login" placeholder="Password" />
                    <input type="submit" class="" name="dangnhap_home" value="Đăng nhập"> 
                    </form>
                    <div style="margin-top: 10px; text-align: center;">
                        <button class="custom-button" onclick="window.location.href='index.php'">Trang chủ</button>
                        <button class="custom-button" onclick="window.location.href='register.php'">Đăng ký</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
