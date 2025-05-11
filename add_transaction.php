<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
require 'includes/config.php';
include 'includes/header.php';

$user_id = $_SESSION['user_id'];
$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $type = $_POST['type'];
    $title = trim($_POST['title']);
    $amount = floatval($_POST['amount']);
    $date = DateTime::createFromFormat('d-m-Y', $_POST['date']);

    if (!$date) {
        $errors[] = "Μη έγκυρη μορφή ημερομηνίας!";
    }

    if ($type && $title && $amount > 0 && $date) {
        $formattedDate = $date->format('Y-m-d');
        $stmt = $pdo->prepare("INSERT INTO transactions (user_id, type, title, amount, date) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$user_id, $type, $title, $amount, $formattedDate]);
        $success = "Η καταχώρηση έγινε με επιτυχία!";
    } else {
        $errors[] = "Συμπλήρωσε όλα τα πεδία σωστά!";
    }
}
?>

<div class="max-w-xl mx-auto bg-white p-6 mt-10 rounded shadow">
    <h2 class="text-2xl font-bold mb-4">Καταχώρηση Συναλλαγής</h2>

    <?php if ($errors): ?>
        <div class="bg-red-100 p-3 rounded mb-4 text-red-700">
            <?= implode("<br>", $errors) ?>
        </div>
    <?php elseif ($success): ?>
        <div class="bg-green-100 p-3 rounded mb-4 text-green-700">
            <?= $success ?>
        </div>
    <?php endif; ?>

    <form method="post">
        <label class="block mb-2 font-semibold">Τύπος</label>
        <select name="type" class="w-full border p-2 rounded mb-4">
            <option value="income">Έσοδο</option>
            <option value="expense">Έξοδο</option>
        </select>

        <label class="block mb-2 font-semibold">Τίτλος</label>
        <input type="text" name="title" class="w-full border p-2 rounded mb-4" required>

        <label class="block mb-2 font-semibold">Ποσό (€)</label>
        <input type="number" step="0.01" name="amount" class="w-full border p-2 rounded mb-4" required>

        <label class="block mb-2 font-semibold">Ημερομηνία</label>
        <input type="text" name="date" placeholder="π.χ. 09-05-2025" class="w-full border p-2 rounded mb-4" required>

        <button class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Καταχώρηση</button>
    </form>
</div>

<?php include 'includes/footer.php'; ?>
