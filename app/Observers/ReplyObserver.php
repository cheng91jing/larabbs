<?php

namespace App\Observers;

use App\Models\Reply;
use App\Notifications\TopicReplied;

// creating, created, updating, updated, saving,
// saved,  deleting, deleted, restoring, restored

class ReplyObserver
{
    public function creating(Reply $reply)
    {
        $reply->content = clean($reply->content, 'user_topic_body');
    }

    public function updating(Reply $reply)
    {
        //
    }

    public function created(Reply $reply)
    {
        $topic = $reply->topic;
        $topic->increment('reply_count', 1);
        //回复成功后，需要通知话题作者, 使用作者模型调用通知类
        if($reply->user_id !== $topic->user_id)
            $topic->user->notify(new TopicReplied($reply));
    }

    public function deleted(Reply $reply)
    {
        $reply->topic->decrement('reply_count');
    }
}