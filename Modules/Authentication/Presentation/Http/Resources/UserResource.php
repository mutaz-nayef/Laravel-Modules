<?php

namespace Modules\Authentication\Presentation\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => (string) $this->id,
            'name' => $this->name,
            'email' => (string) $this->email,
            $this->mergeWhen($request->routeIs('users.*'), [
                'emailVerifiedAt' => $this->email_verified_at ?? null,
                'createdAt' => $this->created_at ?? null,
                'updatedAt' => $this->updated_at ?? null,
            ]),
            /*  $this->whenLoaded('permissions'),
              'relationships' => [
                  'permissions' => [
                      $this->permissions
                  ],
              ],*/
        ];
    }
}
