<?php
$sql_total = "SELECT COUNT(*) AS total FROM product";
$stmt_total = $conn->query($sql_total);
$total = $stmt_total->fetch(PDO::FETCH_ASSOC)['total'];
$total_pages = ceil($total / $items_per_page);

for ($i = 1; $i <= $total_pages; $i++) {
    echo "<a href='?page=$i'>$i</a> ";
}

