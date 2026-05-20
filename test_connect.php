<?php
require_once __DIR__ . '/config/db.php';
try {
    $db = get_db();
    echo "CONNECTED\n";
} catch (Throwable $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
?>