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
     * @var string
     */
    protected $signingKey;

    /**
     * JwtService constructor.
     * @param string $signingKey
     */
    public function __construct(string $signingKey)
    {
        $this->signingKey = $signingKey;
    }

    /**
     * @param UuidInterface $uuid
     * @return string
     */
    public function mintToken(UuidInterface $uuid)
    {
        $payload = [
            'uuid' => $uuid
        ];

        return JWT::encode($payload, $this->signingKey);
    }

    /**
     * @param string $token
     * @return object
     */
    public function decodeToken(string $token)
    {
        return JWT::decode($token, $this->signingKey, ['HS256']);
    }
}
