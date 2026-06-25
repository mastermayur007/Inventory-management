<?php

require "Database.php";
require "Faker.php";
require_once "DepartmentSeeder.php";

$db = new Database();

$pdo = $db->connection();
$departmentSeeder = new DepartmentSeeder($pdo);

$departmentSeeder->run();
echo PHP_EOL;

echo "====================================".PHP_EOL;
echo " Enterprise ITAM Seeder ".PHP_EOL;
echo "====================================".PHP_EOL;

echo PHP_EOL;

echo "Database Connected Successfully.".PHP_EOL;

echo PHP_EOL;