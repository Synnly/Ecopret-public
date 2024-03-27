<?php

declare(strict_types=1);

namespace App\Services\Mercure;

use Lcobucci\JWT\Token;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key;
use Lcobucci\JWT\Token\Builder;
use Lcobucci\JWT\Encoding\JoseEncoder;
use Lcobucci\JWT\Encoding\ChainedFormatter;
use phpDocumentor\Reflection\Types\This;

class JwtProvider
{
    private string $key;

    public function __construct(string $key)
    {
        $this->key = $key;
    }

    public function __invoke(): string
    {
        $algorithm    = new Sha256();

        return (new Builder(new JoseEncoder(), ChainedFormatter::default()))
        ->withClaim('mercure', ['publish' => ['*']])
            ->getToken($algorithm, Key\InMemory::plainText($this->key))
            ->toString();
    }
}
