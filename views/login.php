<?php

require_once '../config/config.php';
require_once '../classes/Database.php';

$database = new Database();
$connection = $database->connect();

echo "Prisijungta prie duomenu bazes";