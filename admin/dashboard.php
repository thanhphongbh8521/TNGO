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
    <title>Welcome Admin</title>
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
                            <i class="fas fa-solid fa-list"></i>
                        </a>
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
    </nav>
</body>
</html>