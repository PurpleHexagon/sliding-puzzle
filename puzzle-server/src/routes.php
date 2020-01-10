<?php
use PurpleHexagon\Puzzle\PuzzleEngine;
use Ramsey\Uuid\Uuid;
// Routes
//
$app->get('/start-puzzle', function ($request, $response, $args) {
    $exists = $this->session->exists('puzzle');
    if ($exists === false) {
        $puzzle = new PuzzleEngine(9);
        $this->session->set('puzzle', $puzzle);
    }
    // $isSolved = false;
    //
    // while ($puzzle->getIsSolved() === false) {
    //   $from = readline("from: ");
    //   $to = readline("to: ");
    //   $isSolved = $puzzle->move($from, $to);
    // }

    // Render index view
    return json_encode(['test']);
});

$app->post('/move', function ($request, $response, $args) {
    $uuid = Uuid::uuid4();

    $cache = $this->get('cache');
    $numProducts = $cache->getItem('stats.num_products');

    $puzzle = $this->session->get('puzzle');

    if ($puzzle === null) {
        return json_encode(['test']);
    }

    $body = (string) $request->getBody();
    $parsedBody = json_decode($body, true);
    $move = $parsedBody['moveArray'];
    $isSolved = $puzzle->move(...$move);

    return json_encode(['isSolved' => $isSolved]);
});

$app->get('/end-puzzle', function ($request, $response, $args) {

});

$app->map(['GET', 'POST', 'PUT', 'DELETE', 'PATCH'], '/{routes:.+}', function($req, $res) {
    $handler = $this->notFoundHandler; // handle using the default Slim page not found handler
    return $handler($req, $res);
});