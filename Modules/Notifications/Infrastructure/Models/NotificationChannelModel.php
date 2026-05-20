<?php

namespace Modules\Notifications\Infrastructure\Models;

use Illuminate\Database\Eloquent\Model;

class NotificationChannelModel extends Model
{
    protected $fillable = ['name', 'display_name'];
    protected $table = 'notification_channels';
}
