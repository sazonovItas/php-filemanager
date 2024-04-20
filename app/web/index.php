<?php
declare(strict_types=1);

use Pecee\SimpleRouter\SimpleRouter as Router;

// load packages
require_once __DIR__ . '/../vendor/autoload.php';

// init routes and repositories
require_once __DIR__ . '/../config/repo.php';
require_once __DIR__ . '/../config/routes.php';

Router::start();
