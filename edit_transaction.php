<?php
session_start();
require 'includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

if (!isset($_GET['id'])) {
    echo "Μη έγκυρο αίτημα.";
    exit;
}

$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM transactions WHERE id = ? AND user_id = ?");
$stmt->execute([$id, $_SESSION['user_id']]);
$transaction = $stmt->fetch();

if (!$transaction) {
    echo "Δεν βρέθηκε η συναλλαγή.";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $type = $_POST['type'];
    $amount = $_POST['amount'];
    $description = $_POST['description'];
    $date = date('Y-m-d', strtotime($_POST['date']));

    $update = $pdo->prepare("UPDATE transactions SET type = ?, amount = ?, description = ?, date = ? WHERE id = ? AND user_id = ?");
    $update->execute([$type, $amount, $description, $date, $id, $_SESSION['user_id']]);

    header("Location: transactions.php");
    exit;
}
?>

<?php include 'includes/header.php'; ?>

<div class="max-w-xl mx-auto mt-10">
    <h2 class="text-2xl font-bold mb-4">✏️ Επεξεργασία Συναλλαγής</h2>
    <form method="post" class="space-y-4">
        <select name="type" required class="w-full p-2 border rounded">
            <option value="income" <?= $transaction['type'] === 'income' ? 'selected' : '' ?>>Έσοδο</option>
            <option value="expense" <?= $transaction['type'] === 'expense' ? 'selected' : '' ?>>Έξοδο</option>
        </select>
        <input type="number" name="amount" step="0.01" value="<?= $transaction['amount'] ?>" required class="w-full p-2 border rounded">
        <input type="text" name="description" value="<?= htmlspecialchars($transaction['description']) ?>" class="w-full p-2 border rounded">
        <input type="date" name="date" value="<?= $transaction['date'] ?>" class="w-full p-2 border rounded">
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Αποθήκευση</button>
    </form>
</div>

<?php include 'includes/footer.php'; ?>
