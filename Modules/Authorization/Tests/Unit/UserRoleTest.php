<?php
//
//namespace Modules\Authorization\Tests\Unit;
//
//use Illuminate\Foundation\Testing\RefreshDatabase;
//use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
//use Mockery;
//use Modules\Authentication\Domain\ValueObjects\UserId;
//use Modules\Authorization\Domain\Entities\RoleModel;
//use Modules\Authorization\Domain\Entities\UserRole;
//use Modules\Authorization\Domain\Repositories\RoleRepositoryInterface;
//use Modules\Authorization\Domain\Repositories\UserRoleRepositoryInterface;
//use Modules\Authorization\Infrastructure\Services\UserRoleService;
//use Modules\Shared\Domain\ValueObjects\Uuid;
//
//class UserRoleTest extends BaseTestCase
//{
//    use RefreshDatabase;
//
//    private UserRoleService $service;
//    private $roleRepository;
//    private $userRoleRepository;
//    private UserId $userId;
//    private Uuid $uuid;
//    private RoleModel $role;
//    private UserRole $userRole;
//    private string $authCode = 'user';
//
//    protected function setUp(): void
//    {
//        parent::setUp();
//
//        $this->roleRepository = Mockery::mock(RoleRepositoryInterface::class);
//        $this->userRoleRepository = Mockery::mock(UserRoleRepositoryInterface::class);
//
//        $this->userId = new UserId('1');
//        $this->uuid = new Uuid('550e8400-e29b-41d4-a716-446655440000');
//
//        $this->role = new RoleModel($this->uuid, $this->authCode, 'User', [], new \DateTimeImmutable());
//        $this->userRole = new UserRole(1, $this->userId, $this->role->id, new \DateTimeImmutable());
//
//        $this->service = new UserRoleService($this->roleRepository, $this->userRoleRepository);
//    }
////
////    public function testAssignRoleSuccessfully(): void
////    {
////
////        $this->roleRepository
////            ->shouldReceive('findByAuthCode')
////            ->once()
////            ->with($this->authCode)
////            ->andReturn($this->role);
////
////        $this->userRoleRepository
////            ->shouldReceive('exists')
////            ->once()
////            ->andReturn(false);
////
////        $this->userRoleRepository
////            ->shouldReceive('create')
////            ->once()
////            ->andReturn($this->userRole);
////
////        $this->service->assignRole($this->userId, $this->authCode);
////    }
////
////    public function testAssignRoleAlreadyAssigned(): void
////    {
////        $this->expectException(UserAlreadyHasRoleException::class);
////        $this->roleRepository
////            ->shouldReceive('findByAuthCode')
////            ->once()
////            ->with($this->authCode)
////            ->andReturn($this->role);
////
////        $this->userRoleRepository
////            ->shouldReceive('exists')
////            ->once()
////            ->andReturn(true);
////
////        $this->userRoleRepository
////            ->shouldNotReceive('create');
////
////        $this->service->assignRole($this->userId, $this->authCode);
////    }
////
////    public function testAssignRoleNotFound(): void
////    {
////        $this->expectException(RoleNotFoundException::class);
////
////        $this->roleRepository
////            ->shouldReceive('findByAuthCode')
////            ->once()
////            ->with($this->authCode)
////            ->andReturnNull();
////
////        $this->service->assignRole($this->userId, $this->authCode);
////    }
////
////    public function testSyncRolesAssignsAllRoles(): void
////    {
////        $this->roleRepository
////            ->shouldReceive('findByAuthCode')
////            ->twice()
////            ->andReturn($this->role);
////
////        $this->userRoleRepository
////            ->shouldReceive('exists')
////            ->twice()
////            ->andReturn(false);
////
////        $this->userRoleRepository
////            ->shouldReceive('create')
////            ->twice();
////
////        $this->service->syncRoles($this->userId, ['admin', 'user']);
////    }
////
////    /**
////     * @throws InvalidArgumentException
////     */
////    public function testRemoveRoleSuccessfully(): void
////    {
////        $user = User::factory()->create();
////
////        $role = \Modules\Authorization\Infrastructure\Models\RoleModel::create([
////            'id' => $this->uuid,
////            'name' => 'Admin',
////            'auth_code' => 'admin'
////        ]);
////        $user->roles()->attach($role);
////        $userId = new UserId($user->id);
////
////        $this->userRoleRepository
////            ->shouldReceive('delete')
////            ->once()
////            ->andReturn(true);
////
////        $this->service->removeRole($userId, $this->uuid);
////    }
////
////
////    public function testUserHasRole(): void
////    {
////        $user = User::factory()->create();
////
////        $role = \Modules\Authorization\Infrastructure\Models\RoleModel::create([
////            'id' => $this->uuid,
////            'name' => 'Admin',
////            'auth_code' => 'admin'
////        ]);
////        $user->roles()->attach($role);
////        $userId = new UserId($user->id);
////
////        $this->userRoleRepository
////            ->shouldReceive('exists')
////            ->once()
////            ->andReturn(true);
////
////        $this->service->hasRole($userId, $this->uuid);
////    }
////
////    #TODO
////    public function testUserHasAnyRole(): void
////    {
////        $user = User::factory()->create();
////
////        $uuid = new Uuid('550e8412-e29b-41d4-a716-446655440000');
////
////        $admin = \Modules\Authorization\Infrastructure\Models\RoleModel::create([
////            'id' => $uuid,
////            'name' => 'Admin',
////            'auth_code' => 'admin'
////        ]);
////        $userRole = \Modules\Authorization\Infrastructure\Models\RoleModel::create([
////            'id' => $this->uuid,
////            'name' => 'User',
////            'auth_code' => 'user'
////        ]);
////        $user->roles()->attach([$admin->id, $userRole->id]);
////
////        $userId = new UserId($user->id);
////
////        $this->userRoleRepository
////            ->shouldReceive('exists')
////            ->once()
////            ->andReturn(true);
////
////        $this->service->hasAnyRole($userId, ['test', 'fake']);
////    }
//
//
//}
