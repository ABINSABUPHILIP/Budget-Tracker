<?php
// index.php
include 'database.php';

// Handle Deleting a Transaction
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
    $delete_id = $_POST['delete_id'];
    
    if (is_numeric($delete_id)) {
        // Prepare and execute delete query
        $stmt = $db->prepare("DELETE FROM transactions WHERE id = :id");
        $stmt->bindParam(':id', $delete_id, PDO::PARAM_INT);
        $stmt->execute();
    }
    
    // Redirect to the same page to avoid form resubmission
    header("Location: index.php");
    exit();
}

// Handle Adding a New Transaction
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['transaction'])) {
    $type = $_POST['type'];
    $amount = $_POST['amount'];
    $note = trim($_POST['note']) ?? '';
    $date = date('Y-m-d'); // Current date

    // Validate input
    if (($type === 'income' || $type === 'expense') && is_numeric($amount)) {
        $stmt = $db->prepare("INSERT INTO transactions (type, amount, date, note) VALUES (:type, :amount, :date, :note)");
        $stmt->bindParam(':type', $type);
        $stmt->bindParam(':amount', $amount);
        $stmt->bindParam(':date', $date);
        $stmt->bindParam(':note', $note);
        $stmt->execute();
    }

    header("Location: index.php");
    exit();
}

// Handle Updating Settings (Monthly Income and Budget)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['settings'])) {
    $monthly_income = $_POST['monthly_income'];
    $monthly_budget = $_POST['monthly_budget'];

    // Validate input
    if (is_numeric($monthly_income) && is_numeric($monthly_budget)) {
        $stmt = $db->prepare("UPDATE settings SET monthly_income = :monthly_income, monthly_budget = :monthly_budget WHERE id = 1");
        $stmt->bindParam(':monthly_income', $monthly_income);
        $stmt->bindParam(':monthly_budget', $monthly_budget);
        $stmt->execute();
    }

    header("Location: index.php");
    exit();
}

// Fetch All Transactions
$stmt = $db->query("SELECT * FROM transactions ORDER BY date DESC");
$transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch Settings
$stmt = $db->query("SELECT * FROM settings WHERE id = 1");
$settings = $stmt->fetch(PDO::FETCH_ASSOC);
$monthly_income = $settings['monthly_income'];
$monthly_budget = $settings['monthly_budget'];

// Calculate Total Expenses
$stmt = $db->query("SELECT SUM(amount) as total_expenses FROM transactions WHERE type = 'expense'");
$total_expenses = $stmt->fetch(PDO::FETCH_ASSOC)['total_expenses'] ?? 0;

// Calculate Total Income
$stmt = $db->query("SELECT SUM(amount) as total_income FROM transactions WHERE type = 'income'");
$total_income = $stmt->fetch(PDO::FETCH_ASSOC)['total_income'] ?? 0;

// Calculate Remaining Budget
$remaining_budget = $monthly_budget - $total_expenses;

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Budget Tracker</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1><i class="fas fa-wallet"></i> Budget Tracker</h1>

        <!-- Monthly Income and Budget Form -->
        <form id="settingsForm" method="POST" action="index.php">
            <h3><i class="fas fa-money-bill-alt"></i> Set Monthly Income and Budget</h3>
            <input type="number" name="monthly_income" placeholder="Monthly Income" value="<?= htmlspecialchars($monthly_income) ?>" required>
            <input type="number" name="monthly_budget" placeholder="Monthly Budget" value="<?= htmlspecialchars($monthly_budget) ?>" required>
            <button type="submit" name="settings"><i class="fas fa-save"></i> Update</button>
        </form>

        <!-- Summary Section -->
        <div class="summary">
            <h2><i class="fas fa-chart-line"></i> Summary</h2>
            <p><span>Monthly Income:</span> $<?= number_format($monthly_income, 2) ?></p>
            <p><span>Monthly Budget:</span> $<?= number_format($monthly_budget, 2) ?></p>
            <p><span>Total Income:</span> $<?= number_format($total_income, 2) ?></p>
            <p><span>Total Expenses:</span> $<?= number_format($total_expenses, 2) ?></p>
            <p><span>Remaining Budget:</span> $<?= number_format($remaining_budget, 2) ?></p>
        </div>

        <!-- Add Transaction Form -->
        <form id="transactionForm" method="POST" action="index.php">
            <h3><i class="fas fa-plus-circle"></i> Add Transaction</h3>
            <div class="form-group">
                <select name="type" required>
                    <option value="" disabled selected>Select Type</option>
                    <option value="income">Income</option>
                    <option value="expense">Expense</option>
                </select>
                <input type="number" name="amount" placeholder="Amount" required>
                <input type="text" name="note" placeholder="Note (optional)">
            </div>
            <button type="submit" name="transaction"><i class="fas fa-plus"></i> Add</button>
        </form>

        <!-- Transaction History -->
        <h2><i class="fas fa-history"></i> Transaction History</h2>
        <ul id="transactionList">
            <?php if (count($transactions) > 0): ?>
                <?php foreach ($transactions as $transaction): ?>
                    <li class="<?= htmlspecialchars($transaction['type']) ?>">
                        <strong><?= htmlspecialchars($transaction['date']) ?></strong> - 
                        <?= ucfirst(htmlspecialchars($transaction['type'])) ?>: $<?= number_format(htmlspecialchars($transaction['amount']), 2) ?>
                        <?php if (!empty($transaction['note'])): ?>
                            <br><small><strong>Note:</strong> <?= htmlspecialchars($transaction['note']) ?></small>
                        <?php endif; ?>

                        <!-- Delete Form -->
    <form method="POST" action="index.php" class="delete-form">
        <input type="hidden" name="delete_id" value="<?= htmlspecialchars($transaction['id']) ?>">
        <button type="submit" name="delete" class="delete-button"><i class="fas fa-trash-alt"></i></button>
    </form>
                    </li>
                <?php endforeach; ?>
            <?php else: ?>
                <li>No transactions found.</li>
            <?php endif; ?>
        </ul>
    </div>
</body>
</html>
