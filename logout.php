<?php
session_start();
session_unset();      // Καθαρίζουμε όλες τις session μεταβλητές
session_destroy();    // Καταστρέφουμε το session
header("Location: login.php");
exit;
?>
