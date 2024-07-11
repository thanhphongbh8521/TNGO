<?php
$mysqli = new mysqli("localhost","root","","kngt");

// Check connection
if ($mysqli -> connect_errno) {
  echo "Kết nối lỗi đến MySQL: " . $mysqli -> connect_error;
  exit();
}
?>