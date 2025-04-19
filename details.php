<?php
// اتصال به دیتابیس
include("db.php");

if (isset($_GET['id'])) {
    $system_id = intval($_GET['id']);

    $query = "SELECT * FROM payments WHERE system_id = $system_id";
    $result = mysqli_query($conn, $query);
    $payments = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $payments[] = $row;
    }
} else {
    echo "سیستم یافت نشد.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="fa">
<head>
  <meta charset="UTF-8">
  <title>جزئیات سیستم</title>
  <style>
    body {
      margin: 0;
      padding: 0;
      background: linear-gradient(120deg, #0f0c29, #302b63, #24243e);
      color: #00f7ff;
      font-family: 'Vazir', sans-serif;
      display: flex;
      flex-direction: column;
      align-items: center;
      animation: fadeIn 1s ease-in-out;
    }

    h1 {
      margin-top: 30px;
      font-size: 2rem;
      text-shadow: 0 0 10px #00f7ff;
    }

    .payment {
      background: rgba(0, 0, 0, 0.5);
      border: 1px solid #00f7ff;
      border-radius: 15px;
      padding: 20px;
      margin: 15px;
      width: 80%;
      max-width: 600px;
      box-shadow: 0 0 20px #00f7ff44;
      animation: slideUp 0.5s ease forwards;
    }

    .payment p {
      margin: 5px 0;
      font-size: 1.1rem;
    }

    .back-btn {
      margin: 30px;
      padding: 10px 20px;
      background-color: transparent;
      border: 2px solid #00f7ff;
      color: #00f7ff;
      font-size: 1rem;
      border-radius: 10px;
      cursor: pointer;
      transition: 0.3s;
    }

    .back-btn:hover {
      background-color: #00f7ff;
      color: #000;
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }

    @keyframes slideUp {
      from { transform: translateY(50px); opacity: 0; }
      to { transform: translateY(0); opacity: 1; }
    }
  </style>
</head>
<body>
  <h1>جزئیات سیستم شماره <?= htmlspecialchars($system_id) ?></h1>

  <?php if (count($payments) > 0): ?>
    <?php foreach ($payments as $payment): ?>
      <div class="payment">
        <p>🟢 زمان شروع: <?= htmlspecialchars($payment['start_time']) ?></p>
        <p>🔴 زمان پایان: <?= htmlspecialchars($payment['end_time']) ?></p>
        <p>💸 مبلغ: <?= number_format($payment['amount']) ?> تومان</p>
      </div>
    <?php endforeach; ?>
  <?php else: ?>
    <p style="margin-top: 50px;">هیچ اطلاعاتی برای این سیستم ثبت نشده است.</p>
  <?php endif; ?>

  <button class="back-btn" onclick="window.location.href='../index.php'">🔙 بازگشت به منوی اصلی</button>
</body>
</html>
