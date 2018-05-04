<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\TopicRequest;
use App\Models\Topic;
use App\Transformers\TopicTransformer;
use Illuminate\Http\Request;

class TopicsController extends Controller
{
    public function store(TopicRequest $request)
    {
        $topics = new Topic();
        $topics->fill($request->all());
        $topics->user_id = $this->user()->id;
        $topics->save();

        return $this->response->item($topics, new TopicTransformer())->setStatusCode(201);
    }
}
