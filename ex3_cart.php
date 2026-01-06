<?php
function h($s)
{
    return htmlspecialchars($s);
}

$products = [
    ['name' => 'Makarov PM', 'price' => 50, 'qty' => 10],
    ['name' => 'Glock 19X', 'price' => 500, 'qty' => 1],
    ['name' => 'Tokarev TT-33', 'price' => 90, 'qty' => 4],
];

// Thêm cột amount
$products = array_map(fn($p) => array_merge($p, ['amount' => $p['price'] * $p['qty']]), $products);

// Tổng tiền
$total = array_reduce($products, fn($carry, $p) => $carry + $p['amount'], 0);

// Tìm sản phẩm amount lớn nhất
$maxProduct = $products[0];
foreach ($products as $p) {
    if ($p['amount'] > $maxProduct['amount']) $maxProduct = $p;
}

// Sắp xếp theo price giảm dần
$sorted = $products;
usort($sorted, fn($a, $b) => $b['price'] <=> $a['price']);
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Bài 3 - Giỏ hàng</title>
</head>

<body>
    <h2>Danh sách sản phẩm</h2>
    <table border="1" cellpadding="5">
        <tr>
            <th>STT</th>
            <th>Name</th>
            <th>Price</th>
            <th>Qty</th>
            <th>Amount</th>
        </tr>
        <?php foreach ($products as $i => $p): ?>
            <tr>
                <td><?= $i + 1 ?></td>
                <td><?= h($p['name']) ?></td>
                <td><?= $p['price'] ?></td>
                <td><?= $p['qty'] ?></td>
                <td><?= $p['amount'] ?></td>
            </tr>
        <?php endforeach; ?>
        <tr>
            <td colspan="4"><strong>Tổng tiền</strong></td>
            <td><?= $total ?></td>
        </tr>
    </table>

    <h3>Sản phẩm amount lớn nhất: <?= h($maxProduct['name']) ?> (<?= $maxProduct['amount'] ?>)</h3>

    <h3>Danh sách sau sort theo price giảm dần:</h3>
    <ul>
        <?php foreach ($sorted as $p): ?>
            <li><?= h($p['name']) ?> - Price: <?= $p['price'] ?></li>
        <?php endforeach; ?>
    </ul>
</body>

</html>