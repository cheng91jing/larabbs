<?php

namespace App\Models;

/**
 * Class Reply
 * @package App\Models
 * @property Topic $topic
 */
class Reply extends Model
{
    protected $fillable = ['content'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function topic()
    {
        return $this->belongsTo(Topic::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
