<?php
$logDir = __DIR__ . '/../../kopetcodeigniter/writable/logs/';
$files = glob($logDir . '*.log');
if (empty($files)) {
    echo "No log files found.";
    exit;
}
rsort($files);
$latestLog = $files[0];
echo "<h1>Latest Log: " . basename($latestLog) . "</h1>";
echo "<pre>";
echo htmlspecialchars(file_get_contents($latestLog));
echo "</pre>";
