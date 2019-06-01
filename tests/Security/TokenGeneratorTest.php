<?php

namespace App\Tests\Security;

use PHPUnit\Framework\TestCase;
use App\Security\TokenGenerator;

class TokenGeneratorTest extends TestCase
{
    public function testTokenGeneration()
    {
        $tokLen = 10;
        $tokenGen = new TokenGenerator();
        $token = $tokenGen->getRandomSecureToken($tokLen);
        $len = strlen($token);
        $this->assertEquals($tokLen, $len);
        $this->assertTrue(ctype_alnum($token))
    }
}
