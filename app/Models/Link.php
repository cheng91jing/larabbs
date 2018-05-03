<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Cache;

/**
 * App\Models\Link
 *
 * @property int $id
 * @property string $title 资源的描述
 * @property string $link 资源的链接
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Link whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Link whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Link whereLink($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Link whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Link whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Link extends Model
{
    protected $fillable = ['title', 'link'];

    public $cache_key = 'bbs_links';
    protected $cache_expire_in_minutes = 1440;

    public function getAllCached()
    {
        return Cache::remember($this->cache_key, $this->cache_expire_in_minutes, function (){
            return $this->all();
        });
    }
}
