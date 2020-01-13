<?php
declare(strict_types=1);
namespace PurpleHexagon\Services\Auth;

use \Firebase\JWT\JWT;
use Ramsey\Uuid\Uuid;

/**
 * Class JwtService
 * @package PurpleHexagon\Services\Auth
 */
class JwtService
{
    /**
     * TODO: This needs to be moved to config and changed to a secure string with high entropy
     */
    const KEY = '689hjfkdsf893yb4k5j435843jngfdhg8e7tjtreog8er';

    /**
     * @return string
     */
    public function mintToken()
    {
        $uuid = Uuid::uuid4();
        $payload = [
            'uuid' => $uuid
        ];

        return JWT::encode($payload, self::KEY);
    }
}
