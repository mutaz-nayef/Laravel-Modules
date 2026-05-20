<?php

namespace Modules\Notifications\Infrastructure\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Authentication\Infrastructure\Models\UserModel;

class NotificationPreferencesModel extends Model
{

    protected $table = 'notification_preferences';

    protected $casts = [
        'channels' => 'array'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(UserModel::class);
    }
}
