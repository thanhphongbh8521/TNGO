<?php
	
    if(isset($_POST['thembinhluan'])){
        $binhluan = $_POST['content'];
		$baiviet = $_POST['baiviet'];
		$thanhvien = $_POST['thanhvien'];
        $sql_insert_comment = mysqli_query($mysqli, "INSERT INTO tbl_binhluan(content, baiviet_id, thanhvien_id ) values('$binhluan','$baiviet','$thanhvien')");
    }elseif(isset($_GET['dangxuat'])){
		$id = $_GET['dangxuat'];
		if($id==1){
		unset($_SESSION['dangnhap_home']);
		}
	}   
?>
<?php
	if(isset($_GET['id_tin'])){
		$id_baiviet = $_GET['id_tin'];
	}else{
		$id_baiviet ='';
	}
?>
<!-- page -->
<div class="services-breadcrumb">
		<div class="agile_inner_breadcrumb">
			<div class="container">
				<ul class="w3_short">
					<li>
						<a href="index.php">Trang chủ </a>
						<i>|</i>
					</li>
					<?php
					$sql_tenbaiviet = mysqli_query($mysqli,"SELECT * FROM tbl_baiviet WHERE baiviet_id='$id_baiviet'");
					$row_bai =mysqli_fetch_array($sql_tenbaiviet);
					?>
					<li><?php echo $row_bai['tenbaiviet'] ?></li>
				</ul>
			</div>
		</div>
	</div>
	<!-- //page -->

	<!-- about -->
	<div class="welcome py-sm-5 py-4">
		<div class="container py-xl-4 py-lg-2">
			<!-- tittle heading -->
			<?php
				$sql_tenbaiviet1 = mysqli_query($mysqli,"SELECT * FROM tbl_baiviet WHERE baiviet_id='$id_baiviet'");
                $row_bai1 =mysqli_fetch_array($sql_tenbaiviet1);
                ?>
			<h3 class="tittle-w3l text-center mb-lg-5 mb-sm-4 mb-3"><?php echo $row_bai1['tenbaiviet'] ?></h3>
			<!-- //tittle heading -->
			<?php
				$sql_baiviet = mysqli_query($mysqli, "SELECT * FROM tbl_baiviet WHERE tbl_baiviet.baiviet_id ='$id_baiviet'");
				$row_baiviet = mysqli_fetch_array($sql_baiviet)
			?>
			<div class="row">
				<div class="col-lg-12 welcome-left">
					<h5><a href="index.php?quanly=chitiettin&id=<?php echo $row_baiviet['baiviet_id'] ?>"><?php echo $row_baiviet['tenbaiviet'] ?></a></h5>
					<h4 class="my-sm-3 my-2"><?php echo $row_baiviet['tomtat'] ?></h4>
                    <p><?php echo $row_baiviet['noidung'] ?></p>
				</div> 
			</div><br>	
		</div>
	</div>
	<!-- //about -->
	<?php
		$sql_binhluan = mysqli_query($mysqli,"SELECT * FROM tbl_baiviet WHERE baiviet_id='$id_baiviet'");
		$row_binhluan = mysqli_fetch_array($sql_binhluan);
	?>


	<div class="row">
		<div class="col-3"></div>
		<div class="col-6">
			<form action="" method='POST'>
				<div class="comment">
					<input type="text" name="content" required placeholder="Để lại bình luận tại đây">
					<input type="hidden" name="baiviet">
					<input type="hidden" name="thanhvien">
					<button name="thembinhluan" type="submit">Gửi</button>
				</div>
			</form>
		</div>	
	</div>