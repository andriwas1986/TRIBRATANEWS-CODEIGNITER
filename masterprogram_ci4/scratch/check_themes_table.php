<?php
require_once 'app/Config/Database.php';
$db = \Config\Database::connect();
$query = $db->query("SHOW COLUMNS FROM themes");
foreach ($query->getResult() as $row) {
    echo $row->Field . " (" . $row->Type . ")\n";
}
