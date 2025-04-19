<!DOCTYPE html>
<html lang="fa">
<head>
  <meta charset="UTF-8">
  <title>مدیریت سیستم‌ها</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body class="dark-theme">
  <div class="container">
    <h1>مدیریت سیستم‌ها</h1>
    <form id="systemForm">
      <input type="text" id="name" placeholder="نام سیستم" required>
      <input type="date" id="last_service_date" required>
      <input type="number" id="cost_per_second" placeholder="هزینه در ثانیه" required>
      <button type="submit">افزودن سیستم</button>
    </form>

    <div id="systemList"></div>
    <button onclick="window.location='index.php'">بازگشت</button>
  </div>

  <script src="js/manage.js"></script>
</body>
</html>

