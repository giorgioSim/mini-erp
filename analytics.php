<?php
session_start();
require_once 'includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

$stmt = $pdo->prepare("
    SELECT 
        DATE_FORMAT(date, '%m-%Y') AS month_year,
        SUM(CASE WHEN type = 'income' THEN amount ELSE 0 END) AS total_income,
        SUM(CASE WHEN type = 'expense' THEN amount ELSE 0 END) AS total_expense
    FROM transactions
    WHERE user_id = ?
    GROUP BY month_year
    ORDER BY STR_TO_DATE(CONCAT('01-', month_year), '%d-%m-%Y')
");
$stmt->execute([$user_id]);
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);


$labels = [];
$incomes = [];
$expenses = [];

foreach ($data as $row) {
    $labels[] = $row['month_year'];
    $incomes[] = $row['total_income'];
    $expenses[] = $row['total_expense'];
}

include 'includes/header.php';
?>

<div class="bg-white rounded-xl shadow p-6 mt-6">
  <h2 class="text-lg font-semibold mb-4">Έσοδα & Έξοδα ανά Μήνα (€)</h2>
  <canvas id="transactionsChart" height="100"></canvas>
</div>


<script>
const ctx = document.getElementById('financeChart').getContext('2d');
const financeChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: <?= json_encode($labels) ?>,
        datasets: [
            {
                label: 'Έσοδα (€)',
                backgroundColor: 'rgba(34, 197, 94, 0.7)',
                data: <?= json_encode($incomes) ?>
            },
            {
                label: 'Έξοδα (€)',
                backgroundColor: 'rgba(239, 68, 68, 0.7)',
                data: <?= json_encode($expenses) ?>
            }
        ]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'top'
            }
        },
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});
</script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>




<?php include 'includes/footer.php'; ?>
