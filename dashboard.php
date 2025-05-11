<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
include 'includes/header.php';
?>

<div class="max-w-2xl mx-auto mt-10 space-y-6">
    <h2 class="text-3xl font-bold">👋 Καλώς ήρθες, <?= htmlspecialchars($_SESSION['user_name']) ?>!</h2>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <a href="add_transaction.php" class="bg-green-600 hover:bg-green-700 text-white p-4 rounded shadow text-center">
            ➕ Προσθήκη Συναλλαγής
        </a>
        <a href="transactions.php" class="bg-blue-600 hover:bg-blue-700 text-white p-4 rounded shadow text-center">
            📄 Προβολή Συναλλαγών
        </a>
        <a href="reports.php" class="bg-purple-600 hover:bg-purple-700 text-white p-4 rounded shadow text-center">
            📊 Αναφορές
        </a>
        <a href="export_csv.php" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
            Εξαγωγή σε CSV
        </a>
        <a href="analytics.php" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
            Αναλυτικά Γράφημα
        </a>
        <a href="export_pdf.php" target="_blank" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded transition">
    Εξαγωγή σε PDF
</a>

        <a href="backup_db.php" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded">Backup Βάσης</a>



    </div>
</div>

<?php include 'includes/footer.php'; ?>
