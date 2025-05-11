<!DOCTYPE html>
<html lang="el">
<head>
    <meta charset="UTF-8">
    <title>Mini ERP</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 text-gray-800">
    <header class="bg-gray-900 text-white p-4 mb-6">
        <div class="max-w-6xl mx-auto flex justify-between items-center">
            <h1 class="text-xl font-bold">Mini ERP</h1>
            <?php if (isset($_SESSION['user_id'])): ?>
                <nav class="space-x-4">
                    <a href="dashboard.php" class="hover:underline">🏠 Dashboard</a>
                    <a href="add_transaction.php" class="hover:underline">➕ Νέα Συναλλαγή</a>
                    <a href="transactions.php" class="hover:underline">📄 Προβολή</a>
                    <a href="reports.php" class="hover:underline">📊 Αναφορές</a>
                    <a href="logout.php" class="text-red-400 hover:underline">Αποσύνδεση</a>
                </nav>
            <?php endif; ?>
        </div>
    </header>

    <script src="assets/js/menu.js" defer></script>

