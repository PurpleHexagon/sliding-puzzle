<?php
use Symfony\Component\Cache\Adapter\RedisAdapter;

// DIC configuration

$container = $app->getContainer();

// view renderer
$container['renderer'] = function ($c) {
    $settings = $c->get('settings')['renderer'];
    return new Slim\Views\PhpRenderer($settings['template_path']);
};

// monolog
$container['logger'] = function ($c) {
    $settings = $c->get('settings')['logger'];
    $logger = new Monolog\Logger($settings['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], $settings['level']));
    return $logger;
};

$container['session'] = function ($c) {
  return new \SlimSession\Helper;
};

$container['cache'] = function ($c) {
    $cache = new RedisAdapter(RedisAdapter::createConnection('redis://localhost'));
    return $cache;
};

$container['jwt'] = function ($c) {
    $jwtService = new \PurpleHexagon\Services\Auth\JwtService($c->get('settings')['jwtSigningKey']);
    return $jwtService;
};
