<?php

namespace Modules\Authorization\Application\Actions;

use Modules\Authorization\Application\DTOs\Input\FieldAccessInputDto;
use Modules\Authorization\Application\DTOs\Output\FieldAccessOutputDto;
use Modules\Authorization\Domain\Contracts\PolicyEngineInterface;

class ResolveFieldAccessAction
{
    public function __construct(
        private readonly PolicyEngineInterface $policyEngine,
    ) {
    }


    public function execute(FieldAccessInputDto $input): FieldAccessOutputDto
    {
        $fieldPermissions = $this->policyEngine->resolveFieldPermissions($input->userId, $input->permissionName);

        return new FieldAccessOutputDto(
            readableFields: $fieldPermissions->readableFields(),
            writableFields: $fieldPermissions->writableFields(),
        );

    }

}
