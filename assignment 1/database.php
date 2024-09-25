<?php
// database.php
try {
    // Connect to SQLite database
    $db = new PDO('sqlite:transactions.db');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Create the transactions table if it doesn't exist
    $db->exec("CREATE TABLE IF NOT EXISTS transactions (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        type TEXT NOT NULL,
        amount REAL NOT NULL,
        date TEXT NOT NULL
    )");

    // Create the settings table if it doesn't exist
    $db->exec("CREATE TABLE IF NOT EXISTS settings (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        monthly_income REAL NOT NULL DEFAULT 0,
        monthly_budget REAL NOT NULL DEFAULT 0
    )");

    // Initialize settings with default values if not set
    $stmt = $db->query("SELECT COUNT(*) as count FROM settings");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($result['count'] == 0) {
        $db->exec("INSERT INTO settings (monthly_income, monthly_budget) VALUES (0, 0)");
    }

    // Check if the 'note' column exists in the transactions table
    $stmt = $db->query("PRAGMA table_info(transactions)");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $noteExists = false;
    foreach ($columns as $column) {
        if (strtolower($column['name']) === 'note') {
            $noteExists = true;
            break;
        }
    }

    // If 'note' column does not exist, add it
    if (!$noteExists) {
        $db->exec("ALTER TABLE transactions ADD COLUMN note TEXT");
    }

} catch (PDOException $e) {
    echo "Database Error: " . $e->getMessage();
    exit();
}
?>
