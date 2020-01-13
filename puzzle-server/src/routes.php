<?php
use PurpleHexagon\Services\Puzzle\PuzzleEngine;

// Routes
//
$app->get('/start-puzzle', function ($request, $response, $args) {
    $cache = $this->get('cache');

    /** @var \PurpleHexagon\Services\Auth\JwtService $jwtService */
    $jwtService = $this->get('jwt');
    $token = $jwtService->mintToken();

    $puzzleCacheItem = $cache->getItem('puzzle_' . $token);
    $exists = false;
    if ($exists === false) {
        $puzzle = new PuzzleEngine(9);
        $puzzleCacheItem->set($puzzle);
        $cache->save($puzzleCacheItem);
    }

    $now = new \DateTimeImmutable();
    // Return the tiles to display the initial mixed up puzzle blocks on load
    return json_encode([
        'tiles' => $puzzle->getTiles(),
        'started' => $now->format(DATE_RFC3339_EXTENDED),
        'token' => $token
    ]);
});

$app->post('/move', function ($request, $response, $args) {
    $cache = $this->get('cache');
    $body = (string) $request->getBody();
    $parsedBody = json_decode($body, true);
    $puzzleCacheItem = $cache->getItem('puzzle_' . $parsedBody['token']);
    $exists = $puzzleCacheItem->isHit();

    if ($exists === false) {
        return json_encode([]);
    }

    $puzzle = $puzzleCacheItem->get();

    if ($puzzle === null) {
        return json_encode(['test']);
    }

    $move = $parsedBody['moveArray'];
    $log = $this->get('logger');
    $isSolved = $puzzle->move(...$move);
    $cache->save($puzzleCacheItem);

    return json_encode(['isSolved' => $isSolved, 'tiles' => $puzzle->getTiles()]);
});

$app->get('/end-puzzle', function ($request, $response, $args) {

});

$app->map(['GET', 'POST', 'PUT', 'DELETE', 'PATCH'], '/{routes:.+}', function($req, $res) {
    $handler = $this->notFoundHandler; // handle using the default Slim page not found handler
    return $handler($req, $res);
});