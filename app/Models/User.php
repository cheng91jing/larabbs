<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Auth;

/**
 * Class User
 * @package App\Models
 * @property \Illuminate\Notifications\DatabaseNotificationCollection $unreadNotifications 数据库通知集合
 */
class User extends Authenticatable
{
    use Notifiable{
        notify as protected laravelNotify;
    }

    public function notify($instance)
    {
        //如果通知的用户是当前用户的话就不必通知了
        if($this->id === Auth::id()){
            return;
        }
        $this->increment('notification_count');
        $this->laravelNotify($instance);
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'introduction', 'avatar',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function topics()
    {
        return $this->hasMany(Topic::class);
    }

    public function replies()
    {
        return $this->hasMany(Reply::class);
    }

    //清除未读消息标识
    public function markAsRead()
    {
        $this->notification_count = 0;
        $this->save();
        //\Illuminate\Notifications\DatabaseNotification 集合
        $this->unreadNotifications->markAsRead();
    }
}
