<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use Dotenv\Dotenv;

// Caminho até a raiz do projeto (onde está o .env)
$path = dirname(__DIR__, 2);

// Carrega o .env apenas se ainda não foi carregado
if (!isset($_ENV['APP_ENV'])) {
    $dotenv = Dotenv::createImmutable($path);
    $dotenv->load();
}