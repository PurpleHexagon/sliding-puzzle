<?php
declare(strict_types=1);
namespace PurpleHexagon\Services\Auth;

use \Firebase\JWT\JWT;
use Ramsey\Uuid\UuidInterface;

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
     * @param UuidInterface $uuid
     * @return string
     */
    public function mintToken(UuidInterface $uuid)
    {
        $payload = [
            'uuid' => $uuid
        ];

        return JWT::encode($payload, self::KEY);
    }

    /**
     * @param string $token
     * @return object
     */
    public function decodeToken(string $token)
    {
        return JWT::decode($token, self::KEY, ['HS256']);
    }
}
