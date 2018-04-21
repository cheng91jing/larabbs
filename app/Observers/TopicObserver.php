<?php

namespace App\Observers;

use App\Handlers\SlugTranslateHandler;
use App\Models\Topic;

// creating, created, updating, updated, saving,
// saved,  deleting, deleted, restoring, restored

class TopicObserver
{
    public function saving(Topic $topic)
    {
        //由于 simditor js 插件会将html标签转义，所以，内容里面的标签不会被过滤
        $topic->body = clean($topic->body, 'user_topic_body');
        //话题摘要
        $topic->excerpt = make_excerpt($topic->body);
        //如 slug 无内容，则使用翻译对 title 进行翻译
        if (!$topic->slug) {
            $topic->slug = app(SlugTranslateHandler::class)->translate($topic->title);
        }
    }
}