<?php

namespace Modules\Notifications\Infrastructure\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Authentication\Infrastructure\Models\UserModel;

class NotificationTypesModel extends Model
{
    protected $fillable = ['name', 'group'];

    protected $table = 'notification_types';

    public function notifications(): HasMany
    {
        return $this->hasMany(NotificationModel::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(UserModel::class);
    }

}
