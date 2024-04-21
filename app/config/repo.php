<?php
declare(strict_types=1);

use app\domain\repo\FileRepository;
use app\domain\repo\TokenRepository;
use \app\domain\repo\UserRepository;

$db_info = [
    'host' => getenv('DB_HOST'),
    'port' => getenv('DB_PORT'),
    'name' => getenv('DB_NAME'),
    'user' => getenv('DB_USER'),
    'pass' => getenv('DB_USER_PASS')
];

try {
    $db = new PDO('mysql:host=' . $db_info['host'] . ';port=' . $db_info['port'] . ';dbname=' . $db_info['name'], $db_info['user'], $db_info['pass']);
    UserRepository::$db = $db;
    TokenRepository::$db = $db;
    FileRepository::$prefixPath = getenv("DATA_PREFIX_PATH");
} catch (Exception $e) {
    throw new RuntimeException('Could not connect to database: ' . $e->getMessage());
}
