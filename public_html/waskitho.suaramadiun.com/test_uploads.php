<?php
$fcpath = __DIR__ . '/';
echo "FCPATH: " . $fcpath . "<br>";
$tmpDir = $fcpath . 'uploads/tmp/';
echo "uploads/tmp/ exists: " . (is_dir($tmpDir) ? 'Yes' : 'No') . "<br>";
if (!is_dir($tmpDir)) {
    echo "Attempting to create uploads/tmp/: " . (mkdir($tmpDir, 0775, true) ? 'Success' : 'Failed') . "<br>";
}
echo "uploads/tmp/ writable: " . (is_writable($tmpDir) ? 'Yes' : 'No') . "<br>";

$imgDir = $fcpath . 'uploads/images/';
echo "uploads/images/ exists: " . (is_dir($imgDir) ? 'Yes' : 'No') . "<br>";
if (!is_dir($imgDir)) {
    echo "Attempting to create uploads/images/: " . (mkdir($imgDir, 0775, true) ? 'Success' : 'Failed') . "<br>";
}
echo "uploads/images/ writable: " . (is_writable($imgDir) ? 'Yes' : 'No') . "<br>";

$mthDir = $fcpath . 'uploads/images/' . date('Ym') . '/';
echo "uploads/images/" . date('Ym') . "/ exists: " . (is_dir($mthDir) ? 'Yes' : 'No') . "<br>";
if (!is_dir($mthDir)) {
    echo "Attempting to create " . $mthDir . ": " . (mkdir($mthDir, 0775, true) ? 'Success' : 'Failed') . "<br>";
}
echo "uploads/images/" . date('Ym') . "/ writable: " . (is_writable($mthDir) ? 'Yes' : 'No') . "<br>";
