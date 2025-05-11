<?php
require_once __DIR__ . '/dompdf/vendor/autoload.php'; // Σωστό path για composer
require_once 'includes/db.php';

use Dompdf\Dompdf;
use Dompdf\Options;

// Dompdf ρυθμίσεις
$options = new Options();
$options->set('defaultFont', 'DejaVu Sans'); // Για υποστήριξη ελληνικών
$dompdf = new Dompdf($options);

// Φέρε τα δεδομένα
$stmt = $pdo->query("SELECT * FROM transactions ORDER BY date DESC");
$transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);

// HTML για το PDF
$html = '
<html>
<head>
  <meta charset="utf-8">
  <style>
    body { font-family: DejaVu Sans, sans-serif; padding: 20px; }
    h2 { text-align: center; color: #1e3a8a; margin-bottom: 30px; }
    table {
      width: 100%;
      border-collapse: collapse;
      font-size: 12px;
    }
    th, td {
      border: 1px solid #ddd;
      padding: 10px;
      text-align: center;
    }
    th {
      background-color: #f3f4f6;
      color: #111827;
    }
    tr:nth-child(even) {
      background-color: #f9fafb;
    }
    .income { color: #059669; }
    .expense { color: #dc2626; }
  </style>
</head>
<body>
  <h2>Οικονομική Αναφορά</h2>
  <table>
    <thead>
      <tr>
        <th>Ημερομηνία</th>
        <th>Περιγραφή</th>
        <th>Ποσό (€)</th>
        <th>Κατηγορία</th>
      </tr>
    </thead>
    <tbody>';

foreach ($transactions as $row) {
    $class = $row['type'] === 'income' ? 'income' : 'expense';
    $amount = number_format($row['amount'], 2, ',', '.');
    $date = date('d-m-Y', strtotime($row['date']));
    $typeLabel = $row['type'] === 'income' ? 'Έσοδο' : 'Έξοδο';

    $html .= "<tr>
                <td>{$date}</td>
                <td>{$row['description']}</td>
                <td class='{$class}'>{$amount} €</td>
                <td>{$typeLabel}</td>
              </tr>";
}

$html .= '
    </tbody>
  </table>
</body>
</html>';

// Γεννάμε το PDF
$dompdf->loadHtml($html, 'UTF-8');
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream('report.pdf', ['Attachment' => true]); // true = force download
exit;
?>
