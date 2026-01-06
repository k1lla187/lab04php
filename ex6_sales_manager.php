<?php
require_once "Product.php";
function h($s)
{
    return htmlspecialchars($s);
}

$products = [];
$minPrice = 0;
$sortAmountDesc = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $raw = $_POST['data'] ?? '';
    $minPrice = floatval($_POST['minPrice'] ?? 0);
    $sortAmountDesc = isset($_POST['sortAmountDesc']);

    $records = explode(';', $raw);
    foreach ($records as $rec) {
        $fields = explode('-', $rec);
        if (count($fields) !== 4) continue;
        [$id, $name, $priceStr, $qtyStr] = $fields;
        if (!is_numeric($priceStr) || !is_numeric($qtyStr)) continue;
        $products[] = new Product($id, $name, floatval($priceStr), floatval($qtyStr));
    }

    // Filter minPrice
    $products = array_filter($products, fn($p) => $p->getPrice() >= $minPrice);

    // Sort
    if ($sortAmountDesc) {
        usort($products, fn($a, $b) => $b->amount() <=> $a->amount());
    }
}
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Mini Sales Manager</title>
</head>

<body>
    <h2>Mini Sales Manager</h2>
    <form method="post">
        <textarea name="data" rows="5" cols="50"><?= isset($_POST['data']) ? h($_POST['data']) : '' ?></textarea><br>
        Min Price: <input type="number" step="0.01" name="minPrice" value="<?= h($minPrice) ?>">
        Sort Amount giảm: <input type="checkbox" name="sortAmountDesc" <?= $sortAmountDesc ? 'checked' : '' ?>>
        <button type="submit">Process</button>
    </form>

    <?php if (!empty($products)): ?>
        <table border="1" cellpadding="5">
            <tr>
                <th>STT</th>
                <th>ID</th>
                <th>Name</th>
                <th>Price</th>
                <th>Qty</th>
                <th>Amount</th>
                <th>Status</th>
            </tr>
            <?php foreach ($products as $i => $p): ?>
                <tr>
                    <td><?= $i + 1 ?></td>
                    <td><?= h($p->getId()) ?></td>
                    <td><?= h($p->getName()) ?></td>
                    <td><?= $p->getPrice() ?></td>
                    <td><?= $p->getQty() ?></td>
                    <td><?= $p->amount() ?></td>
                    <td><?= $p->getQty() <= 0 ? "Invalid qty" : "Valid" ?></td>
                </tr>
            <?php endforeach; ?>
        </table>

        <?php
        $amounts = array_map(fn($p) => $p->amount(), $products);
        $prices = array_map(fn($p) => $p->getPrice(), $products);
        $total = array_sum($amounts);
        $maxAmount = max($amounts);
        $avgPrice = count($prices) ? array_sum($prices) / count($prices) : 0;
        $outOfStock = count(array_filter($products, fn($p) => $p->getQty() <= 0));

        echo "<p>Tổng tiền: $total</p>";
        echo "<p>Sản phẩm amount lớn nhất: $maxAmount</p>";
        echo "<p>Giá trung bình: " . number_format($avgPrice, 2) . "</p>";
        echo "<p>Số sản phẩm Invalid qty: $outOfStock</p>";
        ?>
    <?php endif; ?>
</body>

</html>