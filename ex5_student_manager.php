<?php
require_once "Student.php";
function h($s)
{
    return htmlspecialchars($s);
}

$students = [];
$threshold = 0;
$sortDesc = false;
$errorMsg = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $raw = $_POST['data'] ?? '';
    $threshold = floatval($_POST['threshold'] ?? 0);
    $sortDesc = isset($_POST['sortDesc']);

    $records = explode(';', $raw);
    foreach ($records as $rec) {
        $fields = explode('-', $rec);
        if (count($fields) !== 3) continue;
        [$id, $name, $gpaStr] = $fields;
        if (!is_numeric($gpaStr)) continue;
        $students[] = new Student($id, $name, floatval($gpaStr));
    }

    if (empty($students)) $errorMsg = "Không có dữ liệu hợp lệ.";

    // Filter theo threshold
    $students = array_filter($students, fn($s) => $s->getGpa() >= $threshold);

    // Sort nếu checkbox checked
    if ($sortDesc) {
        usort($students, fn($a, $b) => $b->getGpa() <=> $a->getGpa());
    }
}
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Bài 5 - Student Manager</title>
</head>

<body>
    <h2>Student Manager</h2>
    <form method="post">
        <textarea name="data" rows="5" cols="50"><?= isset($_POST['data']) ? h($_POST['data']) : '' ?></textarea><br>
        Threshold GPA: <input type="number" step="0.1" name="threshold" value="<?= h($threshold) ?>">
        Sort GPA giảm: <input type="checkbox" name="sortDesc" <?= $sortDesc ? 'checked' : '' ?>>
        <button type="submit">Parse & Show</button>
    </form>

    <?php if ($errorMsg): ?>
        <p style="color:red"><?= h($errorMsg) ?></p>
    <?php elseif (!empty($students)): ?>
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
        $gpas = array_map(fn($s) => $s->getGpa(), $students);
        echo "<p>GPA trung bình: " . number_format(array_sum($gpas) / count($gpas), 2) . "</p>";
        echo "<p>GPA max: " . max($gpas) . ", min: " . min($gpas) . "</p>";

        // Thống kê rank
        $stats = ["Giỏi" => 0, "Khá" => 0, "Trung bình" => 0];
        foreach ($students as $s) $stats[$s->rank()]++;
        echo "<p>Thống kê: ";
        foreach ($stats as $r => $n) echo "$r: $n; ";
        echo "</p>";
        ?>
    <?php endif; ?>
</body>

</html>