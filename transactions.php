<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
require 'includes/config.php';
include 'includes/header.php';

$user_id = $_SESSION['user_id'];
$month = $_GET['month'] ?? date('m');
$year = $_GET['year'] ?? date('Y');

$stmt = $pdo->prepare("SELECT * FROM transactions WHERE user_id = ? AND MONTH(date) = ? AND YEAR(date) = ? ORDER BY date DESC");
$stmt->execute([$user_id, $month, $year]);
$transactions = $stmt->fetchAll();

$income = 0;
$expense = 0;
foreach ($transactions as $t) {
    if ($t['type'] === 'income') $income += $t['amount'];
    else $expense += $t['amount'];
}
$balance = $income - $expense;
?>

<div class="max-w-4xl mx-auto mt-10">
    <h2 class="text-2xl font-bold mb-4">Οι Συναλλαγές μου</h2>

    <form method="get" class="mb-4 flex gap-2">
        <select name="month" class="border p-2 rounded">
            <?php for ($m = 1; $m <= 12; $m++): ?>
                <option value="<?= str_pad($m, 2, '0', STR_PAD_LEFT) ?>" <?= $m == $month ? 'selected' : '' ?>>
                    <?= str_pad($m, 2, '0', STR_PAD_LEFT) ?>
                </option>
            <?php endfor; ?>
        </select>
        <select name="year" class="border p-2 rounded">
            <?php for ($y = 2023; $y <= date('Y'); $y++): ?>
                <option value="<?= $y ?>" <?= $y == $year ? 'selected' : '' ?>><?= $y ?></option>
            <?php endfor; ?>
        </select>
        <button class="bg-gray-800 text-white px-3 rounded">Φίλτρο</button>
    </form>

    <div class="grid grid-cols-3 gap-4 mb-6">
        <div class="bg-green-100 text-green-700 p-4 rounded">Έσοδα: €<?= number_format($income, 2, ',', '.') ?></div>
        <div class="bg-red-100 text-red-700 p-4 rounded">Έξοδα: €<?= number_format($expense, 2, ',', '.') ?></div>
        <div class="bg-blue-100 text-blue-700 p-4 rounded">Υπόλοιπο: €<?= number_format($balance, 2, ',', '.') ?></div>
    </div>

    <table class="w-full border-collapse">
        <thead>
            <tr class="bg-gray-200">
                <th class="border p-2">Ημερομηνία</th>
                <th class="border p-2">Τύπος</th>
                <th class="border p-2">Τίτλος</th>
                <th class="border p-2">Ποσό (€)</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($transactions as $t): ?>
                <tr class="border-b">
                    <td class="p-2"><?= (new DateTime($t['date']))->format('d-m-Y') ?></td>
                    <td class="p-2"><?= $t['type'] === 'income' ? 'Έσοδο' : 'Έξοδο' ?></td>
                    <td class="p-2"><?= htmlspecialchars($t['title']) ?></td>
                    <td class="p-2 text-right"><?= number_format($t['amount'], 2, ',', '.') ?></td>
                    <td class="p-2 text-right">
                     <a href="edit_transaction.php?id=<?= $row['id'] ?>" class="text-blue-600 hover:underline">Επεξεργασία</a>
    |
                    <a href="delete_transaction.php?id=<?= $row['id'] ?>" class="text-red-600 hover:underline" onclick="return confirm('Σίγουρα;')">Διαγραφή</a>
</td>

                  </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include 'includes/footer.php'; ?>
