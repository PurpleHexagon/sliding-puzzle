<?php
// Application middleware

// e.g: $app->add(new \Slim\Csrf\Guard);

// Remove session for now as not currently using
//$app->add(new \Slim\Middleware\Session([
//  'autorefresh' => false,
//  'lifetime' => '1 hour'
//]));

$app->options('/{routes:.+}', function ($request, $response, $args) {
    return $response;
});


$app->add(function ($req, $res, $next) {
    $response = $next($req, $res);
    return $response
            ->withHeader('Access-Control-Allow-Origin', '*')
            ->withHeader('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With')
            ->withHeader('Access-Control-Allow-Methods', 'GET, POST, OPTIONS');
});
