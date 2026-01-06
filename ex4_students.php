<?php
require_once "Student.php";

function h($s)
{
    return htmlspecialchars($s);
}

// Tạo danh sách sinh viên
$students = [
    new Student("SV001", "An", 3.5),
    new Student("SV002", "Binh", 2.6),
    new Student("SV003", "Chi", 3.8),
    new Student("SV004", "Dung", 2.4),
    new Student("SV005", "Em", 3.0),
];

// Render bảng
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Bài 4 - Student</title>
</head>

<body>
    <h2>Danh sách sinh viên</h2>
    <table border="1" cellpadding="5">
        <tr>
            <th>STT</th>
            <th>ID</th>
            <th>Name</th>
            <th>GPA</th>
            <th>Rank</th>
        </tr>
        <?php foreach ($students as $i => $s): ?>
            <tr>
                <td><?= $i + 1 ?></td>
                <td><?= h($s->getId()) ?></td>
                <td><?= h($s->getName()) ?></td>
                <td><?= number_format($s->getGpa(), 2) ?></td>
                <td><?= h($s->rank()) ?></td>
            </tr>
        <?php endforeach; ?>
    </table>

    <?php
    // GPA trung bình
    $avgGpa = array_sum(array_map(fn($s) => $s->getGpa(), $students)) / count($students);
    echo "<p>GPA trung bình lớp: " . number_format($avgGpa, 2) . "</p>";

    // Thống kê theo rank
    $stats = ["Giỏi" => 0, "Khá" => 0, "Trung bình" => 0];
    foreach ($students as $s) {
        $stats[$s->rank()]++;
    }
    echo "<p>Thống kê theo xếp loại: ";
    foreach ($stats as $rank => $num) {
        echo "$rank: $num; ";
    }
    echo "</p>";
    ?>
</body>

</html>