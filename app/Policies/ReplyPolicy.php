<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Reply;

class ReplyPolicy extends Policy
{
    public function update(User $user, Reply $reply)
    {
        // return $reply->user_id == $user->id;
        return true;
    }

    public function destroy(User $user, Reply $reply)
    {
        // 此处 $reply->topic->user_id 涉及到关联查询，为防止查询 N + 1 问题，需要在对$reply进行预载入 topic 或者从外部将 topic 模型传入
        return $user->id === $reply->user_id || $user->id === $reply->topic->user_id;
    }
}
