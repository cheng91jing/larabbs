<?php

namespace App\Models;

class Topic extends Model
{
    protected $fillable = [
        'title', 'body', 'category_id', 'excerpt', 'slug',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param $order string
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithOrder($query, $order)
    {
        switch ($order){
            case 'recent':
                $query->recent();
                break;
            default:
                $query->recentReplied();
        }
        //预防查询 N + 1
        return $query->with(['category', 'user']);
    }

    /**
     * 最新回复话题排序
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeRecentReplied($query)
    {
        //当话题有新回复时，我们将更新话题的 reply_count 属性
        return $query->orderBy('updated_at', 'desc');
    }

    /**
     * 最新话题排序
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeRecent($query)
    {
        return $query->orderBy('created_at', 'desc');
    }
}
