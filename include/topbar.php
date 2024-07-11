<?php
    // session_destroy();
    // unset('dangnhap');
    if(isset($_POST['dangnhap_home'])){
        $taikhoan = $_POST['email_login'];
        $matkhau = md5($_POST['password_login']);
        if($taikhoan=='' || $matkhau ==''){
            echo '<script>alert("Vui lòng không để trống")</script>';
        }else{
            $sql_select_admin = mysqli_query($mysqli,"SELECT * FROM tbl_thanhvien WHERE email ='$taikhoan' AND password = '$matkhau' LIMIT 1");
            $count = mysqli_num_rows($sql_select_admin);
            $row_dangnhap = mysqli_fetch_array($sql_select_admin);
            if($count>0){
                $_SESSION['dangnhap_home'] = $row_dangnhap['name'];
                $_SESSION['thanhvien_id'] = $row_dangnhap['thanhvien_id'];
				
                header('Location: index.php');
            }else{
                echo '<script>alert("Tài khoản hoặc mật khẩu không đúng")</script>';
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
	}elseif(isset($_GET['dangxuat'])){
		$id = $_GET['dangxuat'];
		if($id==1){
		unset($_SESSION['dangnhap_home']);
		}
	}
?>
<!-- top-header -->
    <div class="agile-main-top">
		<div class="container-fluid">
			<div class="row main-top-w3l py-2">
				<div class="col-lg-4 header-most-top">
					<!-- logo -->
					<!-- <div class="col-md-3 logo_agile"> -->
						<h1 class="text-center">
							<a href="index.php" >
								<img src="images/tngo.jpg">
							</a>
						</h1>
					<!-- </div> -->

				</div>
				<div class="col-lg-8 header-right mt-lg-0 mt-2">
					<!-- header lists -->
					<ul class="text-right">
						<li></li>
						<?php
						if(isset($_SESSION['dangnhap_home'])){
						?>
							<li class="btn-welcome text-center text-white">
								<a href="index.php?quanly=traxe&thanhvien_id=<?php echo $_SESSION['thanhvien_id'] ?>" class="text-white">
									<i class=""></i>Xem xe đang thuê của: <?php echo $_SESSION['dangnhap_home'] ?></a>
							</li>
							<li class="btn-welcome text-center text-white">
								<a href="index.php?quanly=dangbai" class="text-white">
									<i class="fas light fa-upload"></i> Đăng tin tức </a>
							</li>
							<li class="btn-welcome text-center text-white">
								<a href="index.php?dangxuat=1" class="text-white">
									<i class="fas fa-sign-in-alt mr-2"></i> Đăng xuất </a>
							</li>
						<?php
						}else{
						?>
							<li class="btn-welcome text-center ">
								<a href="welcome.php" class="text-white">
									<i class="fas fa-sign-in-alt mr-2"></i>Đăng nhập</a>
							</li>
							<li class="btn-welcome text-center text-white">
								<a href="register.php" class="text-white">
									<i class="fas fa-sign-out-alt mr-2"></i>Đăng ký</a>
							</li>
						<?php
						}
						?>
						
					</ul>
					<!-- //header lists -->
				</div>
			</div>
		</div>
	</div>



    
	<!-- //top-header -->
    <!-- header-bottom-->
    <div class="header-bot">
		<div class="container">
			<div class="row header-bot_inner_wthreeinfo_header_mid">
				
			</div>
		</div>
	</div>
	<!-- shop locator (popup) -->
	<!-- //header-bottom -->
	<!-- navigation -->