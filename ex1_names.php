<?php
function h($s)
{
    return htmlspecialchars($s);
}

// Lấy input GET
$raw = $_GET['names'] ?? '';

// Tách chuỗi thành mảng, trim và loại bỏ phần tử rỗng
$names = array_filter(array_map('trim', explode(',', $raw)));

?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Bài 1 - Danh sách tên</title>
</head>

<body>
    <h2>Chuỗi gốc:</h2>
    <p><?= h($raw) ?></p>

    <h2>Kết quả:</h2>
    <?php if (empty($names)): ?>
        <p>Chưa có dữ liệu hợp lệ</p>
    <?php else: ?>
        <p>Số lượng tên hợp lệ: <?= count($names) ?></p>
        <ol>
            <?php foreach ($names as $name): ?>
                <li><?= h($name) ?></li>
            <?php endforeach; ?>
        </ol>
    <?php endif; ?>
</body>

</html>