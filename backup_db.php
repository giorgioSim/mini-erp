<?php

$dbhost = 'localhost';
$dbuser = 'root';
$dbpass = '';
$dbname = 'mini_erp';

$backup_file = $dbname . '_' . date("Y-m-d-H-i-s") . '.sql';

$command = "C:\\xampp\\mysql\\bin\\mysqldump --user=$dbuser --password=$dbpass --host=$dbhost $dbname > $backup_file";
system($command);

header('Content-Description: File Transfer');
header('Content-Type: application/octet-stream');
header("Content-Disposition: attachment; filename=\"$backup_file\"");
readfile($backup_file);
unlink($backup_file);
