<?php
$scores = [8.5, 7.0, 9.25, 6.5, 8.0, 5.75];

// Điểm trung bình
$avg = array_sum($scores) / count($scores);

// Lọc điểm >= 8.0
$highScores = array_filter($scores, fn($s) => $s >= 8.0);

// Max và Min
$max = max($scores);
$min = min($scores);

// Sắp xếp
$asc = $scores;
sort($asc);

$desc = $scores;
rsort($desc);
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Bài 2 - Mảng điểm</title>
</head>

<body>
    <h2>Danh sách điểm:</h2>
    <p><?= implode(', ', $scores) ?></p>

    <p>Điểm trung bình: <?= number_format($avg, 2) ?></p>
    <p>Số lượng điểm >= 8.0: <?= count($highScores) ?> (<?= implode(', ', $highScores) ?>)</p>
    <p>Điểm cao nhất: <?= $max ?>, thấp nhất: <?= $min ?></p>
    <p>Sắp xếp tăng dần: <?= implode(', ', $asc) ?></p>
    <p>Sắp xếp giảm dần: <?= implode(', ', $desc) ?></p>
</body>

</html>