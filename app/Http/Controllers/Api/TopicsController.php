<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\TopicRequest;
use App\Models\Topic;
use App\Models\User;
use App\Transformers\TopicTransformer;
use Illuminate\Http\Request;

class TopicsController extends Controller
{
    public function index(Request $request)
    {
        $query = Topic::query();
//        $query = (new Topic())->query();
        if($categoryId = $request->category_id){
            $query->where('category_id' , $categoryId);
        }

        //为了说明 N+1 不使用 withOrder
        switch ($request->order){
            case 'recent':
                $query->recent();
                break;
            default:
                $query->recentReplied();
        }
        $topics = $query->paginate(20);

        return $this->response->paginator($topics, new TopicTransformer());
    }

    public function userIndex(Request $request, User $user)
    {
        $topics = $user->topics()->recent()->paginate(20);

        return $this->response->paginator($topics, new TopicTransformer());
    }

    public function store(TopicRequest $request)
    {
        $topics = new Topic();
        $topics->fill($request->all());
        $topics->user_id = $this->user()->id;
        $topics->save();

        return $this->response->item($topics, new TopicTransformer())->setStatusCode(201);
    }

    public function update(TopicRequest $request, Topic $topic)
    {
        $this->authorize('update', $topic);

        $topic->update($request->all());
        return $this->response->item($topic, new TopicTransformer());
    }

    public function destroy(Topic $topic)
    {
        $this->authorize('update', $topic);

        $topic->delete();
        return $this->response->noContent();
    }
}
