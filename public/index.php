<?php

use SecretServer\Api\v1\Application;
use Dotenv\Dotenv;

require __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv::createImmutable(__DIR__, '../.env');

$dotenv->load();

Application::run();
