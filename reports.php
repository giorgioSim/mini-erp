<?php
session_start();
require 'includes/db.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$stmt = $pdo->prepare("SELECT type, SUM(amount) as total FROM transactions WHERE user_id = ? GROUP BY type");
$stmt->execute([$_SESSION['user_id']]);
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

$income = 0;
$expense = 0;

foreach ($data as $d) {
    if ($d['type'] === 'income') $income = $d['total'];
    if ($d['type'] === 'expense') $expense = $d['total'];
}
?>

<?php include 'includes/header.php'; ?>

<div class="max-w-2xl mx-auto mt-10">
    <h2 class="text-2xl font-bold mb-4">📊 Αναφορές Εσόδων/Εξόδων</h2>
    <canvas id="reportChart" height="100"></canvas>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('reportChart').getContext('2d');
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Έσοδα (€)', 'Έξοδα (€)'],
            datasets: [{
                data: [<?= $income ?>, <?= $expense ?>],
                backgroundColor: ['#22c55e', '#ef4444']
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
</script>

<?php include 'includes/footer.php'; ?>
