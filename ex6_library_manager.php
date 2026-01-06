<?php
require_once "Book.php";

function h($s)
{
    return htmlspecialchars($s);
}

$books = [];
$q = "";
$sortQtyDesc = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $raw = $_POST['data'] ?? '';
    $q = trim($_POST['q'] ?? '');
    $sortQtyDesc = isset($_POST['sortQtyDesc']);

    $records = explode(';', $raw);

    foreach ($records as $rec) {
        $fields = explode('-', $rec);

        if (count($fields) !== 3) continue;

        [$id, $title, $qtyStr] = $fields;

        if (!is_numeric($qtyStr)) continue;

        $books[] = new Book(
            trim($id),
            trim($title),
            intval($qtyStr)
        );
    }

    if ($q !== '') {
        $books = array_filter($books, function ($b) use ($q) {
            return stripos($b->getTitle(), $q) !== false;
        });
    }

    if ($sortQtyDesc) {
        usort($books, fn($a, $b) => $b->getQty() <=> $a->getQty());
    }
}
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Library Manager</title>
</head>

<body>

    <h2>📚 Library Manager</h2>

    <form method="post">
        <textarea name="data" rows="5" cols="60"
            placeholder="B001-Intro to PHP-2;B002-Web Programming-5;B003-Database Basics-1"><?= isset($_POST['data']) ? h($_POST['data']) : '' ?></textarea>
        <br><br>

        Tìm theo Title:
        <input type="text" name="q" value="<?= h($q) ?>">

        Sort Qty giảm dần:
        <input type="checkbox" name="sortQtyDesc" <?= $sortQtyDesc ? 'checked' : '' ?>>

        <button type="submit">Process</button>
    </form>

    <?php if (!empty($books)): ?>
        <hr>

        <table border="1" cellpadding="6">
            <tr>
                <th>STT</th>
                <th>BookID</th>
                <th>Title</th>
                <th>Qty</th>
                <th>Status</th>
            </tr>

            <?php foreach ($books as $i => $b): ?>
                <tr>
                    <td><?= $i + 1 ?></td>
                    <td><?= h($b->getId()) ?></td>
                    <td><?= h($b->getTitle()) ?></td>
                    <td><?= $b->getQty() ?></td>
                    <td><?= $b->getStatus() ?></td>
                </tr>
            <?php endforeach; ?>
        </table>

        <?php
        $totalBooks = count($books);
        $totalQty = array_sum(array_map(fn($b) => $b->getQty(), $books));

        $maxQtyBook = $books[0];
        foreach ($books as $b) {
            if ($b->getQty() > $maxQtyBook->getQty()) {
                $maxQtyBook = $b;
            }
        }

        $outOfStock = count(array_filter($books, fn($b) => $b->getQty() == 0));
        ?>

        <h3>📊 Thống kê</h3>
        <ul>
            <li>Tổng đầu sách: <?= $totalBooks ?></li>
            <li>Tổng số quyển: <?= $totalQty ?></li>
            <li>Sách qty lớn nhất: <?= h($maxQtyBook->getTitle()) ?> (<?= $maxQtyBook->getQty() ?>)</li>
            <li>Số sách Out of stock: <?= $outOfStock ?></li>
        </ul>

    <?php elseif ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
        <p style="color:red">❌ Không có dữ liệu hợp lệ</p>
    <?php endif; ?>

</body>

</html>