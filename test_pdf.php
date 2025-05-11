<?php
require 'dompdf/autoload.inc.php';
require_once 'includes/db.php';

use Dompdf\Dompdf;
use Dompdf\Options;

// Ρυθμίσεις για ελληνικά
$options = new Options();
$options->set('defaultFont', 'DejaVu Sans');
$dompdf = new Dompdf($options);

// Φέρνουμε τα δεδομένα από τη βάση
$stmt = $pdo->query("SELECT * FROM transactions ORDER BY date DESC");
$transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Δημιουργούμε HTML περιεχόμενο για το PDF
$html = '
<h2 style="text-align: center;">Αναφορά Εσόδων/Εξόδων</h2>
<table width="100%" border="1" cellspacing="0" cellpadding="5">
    <thead>
        <tr style="background-color: #f0f0f0;">
            <th>Ημερομηνία</th>
            <th>Τύπος</th>
            <th>Περιγραφή</th>
            <th>Ποσό (€)</th>
        </tr>
    </thead>
    <tbody>
';

$totalIncome = 0;
$totalExpense = 0;

foreach ($transactions as $t) {
    $date = date('d-m-Y', strtotime($t['date']));
    $type = $t['type'] == 'income' ? 'Έσοδο' : 'Έξοδο';
    $amount = number_format($t['amount'], 2, ',', '.');

    if ($t['type'] == 'income') {
        $totalIncome += $t['amount'];
    } else {
        $totalExpense += $t['amount'];
    }

    $html .= "
        <tr>
            <td>$date</td>
            <td>$type</td>
            <td>{$t['description']}</td>
            <td style='text-align: right;'>€ $amount</td>
        </tr>
    ";
}

$balance = $totalIncome - $totalExpense;

$html .= '</tbody></table><br><br>';
$html .= '<h4>Σύνολο Εσόδων: € ' . number_format($totalIncome, 2, ',', '.') . '</h4>';
$html .= '<h4>Σύνολο Εξόδων: € ' . number_format($totalExpense, 2, ',', '.') . '</h4>';
$html .= '<h3>Υπόλοιπο: € ' . number_format($balance, 2, ',', '.') . '</h3>';

// Δημιουργία PDF
$dompdf->loadHtml($html, 'UTF-8');
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream("report.pdf", ["Attachment" => false]);
exit;
?>
