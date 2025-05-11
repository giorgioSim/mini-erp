<?php
session_start();
require_once 'includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

$stmt = $pdo->prepare("SELECT * FROM transactions WHERE user_id = ? ORDER BY date DESC");
$stmt->execute([$user_id]);
$transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=transactions.csv');

$output = fopen('php://output', 'w');

// Γράφουμε την πρώτη γραμμή (τίτλους στηλών)
fputcsv($output, ['ID', 'Τύπος', 'Ποσό (€)', 'Περιγραφή', 'Ημερομηνία']);

foreach ($transactions as $row) {
    fputcsv($output, [
        $row['id'],
        $row['type'] === 'income' ? 'Έσοδο' : 'Έξοδο',
        number_format($row['amount'], 2),
        $row['description'],
        date('d-m-Y', strtotime($row['date']))
    ]);
}
fclose($output);
exit;
?>
