<?php
// check_columns.php
include 'database.php';

$stmt = $db->query("PRAGMA table_info(transactions)");
$columns = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "<h2>Columns in 'transactions' Table:</h2><ul>";
foreach ($columns as $column) {
    echo "<li>" . htmlspecialchars($column['name']) . " (" . htmlspecialchars($column['type']) . ")</li>";
}
echo "</ul>";
?>
