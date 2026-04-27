<?php


namespace Modules\Authentication\Tests\Unit;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Modules\Authentication\Infrastructure\Services\Security\LaravelPasswordHasher;

 class PasswordHasherTest extends BaseTestCase
{
        public function testPasswordHasherReturnString()
        {
            $hasher = new LaravelPasswordHasher();

            $hash = $hasher->make('123456');
            $this->assertIsString($hash);
            $this->assertNotEmpty($hash);
        }
        public function testPasswordHasherWorkSuccessfully()
        {
         $hasher = new LaravelPasswordHasher();
         $hash = $hasher->make('123456');
         $this->assertTrue($hasher->check('123456', $hash));
        }

     public function testPasswordHasherWrongPassword()
     {
         $hasher = new LaravelPasswordHasher();
         $hash = $hasher->make('123456');
         $this->assertFalse($hasher->check('wrong-password', $hash));
     }

     public function testSamePasswordCreateDifferentHashes()
     {
         $hasher = new LaravelPasswordHasher();

         $hash1 = $hasher->make('123456');
         $hash2 = $hasher->make('123456');

         $this->assertNotEquals($hash1, $hash2);
     }
}
