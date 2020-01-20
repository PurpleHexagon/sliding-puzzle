<?php
$localSettings = require_once 'local.settings.php';

$settings = [
    'settings' => [
        "determineRouteBeforeAppMiddleware" => false,
        'displayErrorDetails' => false, // set to false in production
        'addContentLengthHeader' => false, // Allow the web server to send the content-length header

        // Renderer settings
        'renderer' => [
            'template_path' => __DIR__ . '/../templates/',
        ],

        // Monolog settings
        'logger' => [
            'name' => 'slim-app',
            'path' => __DIR__ . '/../logs/app.log',
            'level' => \Monolog\Logger::DEBUG,
        ],
        'jwtSigningKey' => ''
    ],
];

return Laminas\Stdlib\ArrayUtils::merge($settings, $localSettings);
