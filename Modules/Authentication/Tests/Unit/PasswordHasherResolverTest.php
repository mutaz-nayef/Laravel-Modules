<?php


namespace Modules\Authentication\Tests\Unit;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Modules\Authentication\Infrastructure\Services\Security\LaravelPasswordHasher;
use Modules\Authentication\Infrastructure\Services\Security\PasswordHasherResolver;

class PasswordHasherResolverTest extends BaseTestCase
{
    private PasswordHasherResolver $resolver;
    protected function setUp(): void
    {
        parent::setUp();
        $this->resolver = new PasswordHasherResolver();
    }

     public function testItReturnCorrectHasherClass()
     {
         config([
             'auth-module.password_hash_driver' => 'bcrypt',
             'auth-module.password_hashers' => [
                'bcrypt' => \Modules\Authentication\Infrastructure\Services\Security\LaravelPasswordHasher::class,
                'md5' => \Modules\Authentication\Infrastructure\Services\Security\MD5PasswordHasher::class,
            ],
         ]);
         $result = $this->resolver->resolve();

         $this->assertEquals('Modules\Authentication\Infrastructure\Services\Security\LaravelPasswordHasher', $result);
     }

    public function testItReturnExceptionNotCorrectHasherClass()
    {

        $hash_driver = 'invalid';
        $this->expectException(\Exception::class);

        config([
            'auth-module.password_hash_driver' => $hash_driver,
            'auth-module.password_hashers' => [
                'bcrypt' => \Modules\Authentication\Infrastructure\Services\Security\LaravelPasswordHasher::class,
                'md5' => \Modules\Authentication\Infrastructure\Services\Security\MD5PasswordHasher::class,
            ],
        ]);
         $this->resolver->resolve();
    }
}
