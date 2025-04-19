<!DOCTYPE html>
<html lang="fa">
<head>
  <meta charset="UTF-8">
  <title>مدیریت گیم‌نت</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body class="dark-theme">
  <div class="container">
    <h1>لیست سیستم‌ها</h1>
    <button onclick="window.location='manage.php'">مدیریت سیستم‌ها</button>
    <div id="systems"></div>
  </div>

<?php
$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'game_net'; // اسم دیتابیس شما

$conn = mysqli_connect($host, $user, $pass, $dbname);
if (!$conn) {
    die("خطا در اتصال به پایگاه داده: " . mysqli_connect_error());
}

$result = mysqli_query($conn, "SELECT * FROM systems");
?>


<script src="js/main.js"></script>
</body>
</html>
