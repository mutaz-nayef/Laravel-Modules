<?php

namespace Modules\Notifications\Infrastructure\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Authentication\Infrastructure\Models\UserModel;

class NotificationModel extends Model
{

    protected $fillable = ['user_id', 'data', 'read_at'];

    protected $table = 'notifications';

    public function user(): BelongsTo
    {
        return $this->belongsTo(UserModel::class, 'user_id');
    }

//    public function notificationTypes(): BelongsTo
//    {
//        return $this->belongsTo(NotificationTypesModel::class);
//    }

}
