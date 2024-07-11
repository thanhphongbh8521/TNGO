  
    <div class="navbar-inner">
		<div class="container">
			<nav class="navbar navbar-expand-lg navbar-light bg-light">
				<!-- <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
				    aria-expanded="false" aria-label="Toggle navigation">
					<span class="navbar-toggler-icon"></span>
				</button> -->
				<div class="collapse navbar-collapse" id="navbarSupportedContent">
					<ul class="navbar-nav ml-auto text-center mr-xl-5">
						<li class="nav-item ">
							<a class="nav-link" href="index.php">Trang chủ
								<span class="sr-only">(current)</span>
							</a>
						</li>
						<li class="nav-item  ">
							<a class="nav-link" href="index.php#price-title">Bảng giá dịch vụ
								<span class="sr-only">(current)</span>
							</a>
						</li>
						<li class="nav-item">
							<?php	
							if(isset($_SESSION['dangnhap_home'])) {
								?>
								<a class="nav-link" href="rent_bike.php">Thuê xe
									<span class="sr-only">(current)</span>
								</a>
								<?php
							} else {
								?>
								<a class="nav-link" href="#" onclick="alert('Đăng nhập để thực hiện chức năng này'); location.href='welcome.php';">Thuê xe
									<span class="sr-only">(current)</span>
								</a>
								<?php
							}
							?>
						</li>
						<li class="nav-item  ">
							<a class="nav-link" href="New/Map.html">Danh sách trạm
								<span class="sr-only">(current)</span>
							</a>
						</li>
						<li class="nav-item  ">
							<a class="nav-link" href="index.php#contant-title">Liên hệ
								<span class="sr-only">(current)</span>
							</a>
						</li>
						</li>
						<li class="nav-item dropdown mr-lg-2 mb-lg-0 mb-2">
							<?php
							$sql_danhmuctin = mysqli_query($mysqli,"SELECT  * FROM tbl_danhmuctin ORDER BY danhmuctin_id ASC");
							?>
							<a class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								Tin tức
							</a>
							<div class="dropdown-menu">
								<?php
								while($row_danhmuctin = mysqli_fetch_array($sql_danhmuctin)){
								?>
								<a class="dropdown-item" href="?quanly=tintuc&id_tin=<?php echo $row_danhmuctin['danhmuctin_id']?> "><?php echo $row_danhmuctin['tendanhmuc'] ?></a>
								<?php
								}
								?>
								
							</div>
						</li>
					</ul>
				</div>
			</nav>
		</div>
	</div>
	<!-- //navigation -->
