<?php declare(strict_types=1);
namespace Orion\Core\Service;

use ReallySimpleJWT\Exception\ValidateException;
use ReallySimpleJWT\Token;

/**
 * Class TokenService
 *
 * @package App\Service
 */
class TokenService
{
    /**
     * Creates the JWT - Token and returns it
     *
     * @param mixed $id
     * @return string
     * @throws ValidateException
     */
    public function execute(mixed $id): string
    {
        $payload = [
            'iat' => time(),
            'uid' => $id,
            'exp' => time() + $_ENV['TOKEN_DURATION'],
            'iss' => $_ENV['TOKEN_ISSUER']
        ];

        return Token::customPayload($payload, $_ENV['TOKEN_SECRET']);
    }
}
