<?php

namespace Modules\Authorization\Tests\Unit\Domain;

use Modules\Authorization\Domain\Exceptions\ForbiddenFieldException;
use Modules\Authorization\Domain\Services\FieldGuardService;
use Modules\Authorization\Domain\ValueObjects\FieldPermissions;
use PHPUnit\Framework\TestCase;

class FieldGuardServiceTest extends TestCase
{
    private FieldGuardService $fieldGuardService;

    public function test_does_not_throw_when_all_fields_are_writable(): void
    {
        $fp = new FieldPermissions(readable: null, writable: ['title', 'body']);
        $input = ['title' => 'Hello', 'body' => 'World'];
        $this->fieldGuardService->guard($input, $fp);
        $this->assertTrue(true);
    }

    public function test_throws_when_forbidden_field_is_in_input(): void
    {
        $this->expectException(ForbiddenFieldException::class);

        $fp = new FieldPermissions(readable: null, writable: ['title', 'body']);
        $input = ['title' => 'Hello', 'status' => 'published']; // 'status' is forbidden

        $this->fieldGuardService->guard($input, $fp);
    }

    public function test_exception_contains_forbidden_field_names(): void
    {
        $fp = new FieldPermissions(readable: null, writable: ['title']);
        $input = ['title' => 'Hi', 'status' => 'x', 'cost' => 100];
        try {
            $this->fieldGuardService->guard($input, $fp);
            $this->fail('Excepted ForbiddenFieldException was not thrown.');
        } catch (ForbiddenFieldException $e) {
            $this->assertContains('status', $e->forbiddenFields());
            $this->assertContains('cost', $e->forbiddenFields());
            $this->assertNotContains('title', $e->forbiddenFields());
        }
    }


    public function test_unrestricted_never_throws(): void
    {
        $fp = FieldPermissions::unrestricted();
        $input = ['title' => 'Hi', 'status' => 'x', 'internal_notes' => 'secret'];

        $this->fieldGuardService->guard($input, $fp);
        $this->assertTrue(true);
    }

    protected function setUp(): void
    {
        $this->fieldGuardService = new FieldGuardService();
    }
}
