<?php
// Application middleware

// e.g: $app->add(new \Slim\Csrf\Guard);
$app->add(new \Slim\Middleware\Session([
  'autorefresh' => false,
  'lifetime' => '1 hour'
]));
// 
// $app->add(new Tuupola\Middleware\Cors([
//     "origin" => ["*"],
//     "methods" => ["GET", "POST", "PUT", "PATCH", "DELETE"],
//     "headers.allow" => [],
//     "headers.expose" => [],
//     "credentials" => false,
//     "cache" => 0,
//     "error" => function ($request, $response, $arguments) {
//         $data["status"] = "error";
//         $data["message"] = $arguments["message"];
//         return $response
//             ->withHeader("Content-Type", "application/json")
//             ->write(json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
//     }
// ]));

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


// $app->add(function($request, $response, $next) {
//     $route = $request->getAttribute("route");
//
//     $methods = [];
//
//     if (!empty($route)) {
//         $pattern = $route->getPattern();
//
//         foreach ($this->router->getRoutes() as $route) {
//             if ($pattern === $route->getPattern()) {
//                 $methods = array_merge_recursive($methods, $route->getMethods());
//             }
//         }
//         //Methods holds all of the HTTP Verbs that a particular route handles.
//     } else {
//         $methods[] = $request->getMethod();
//     }
//
//     $response = $next($request, $response);
//
//
//     return $response->withHeader("Access-Control-Allow-Methods", implode(",", $methods));
// });
