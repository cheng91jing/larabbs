<?php

namespace App\Http\Controllers;

use App\Models\Reply;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\ReplyRequest;
use Auth;

class RepliesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

	public function store(ReplyRequest $request)
	{
		$reply = new Reply();
        $reply->content = $request->input('content');
        $reply->user_id = Auth::id();
        $reply->topic_id = $request->input('topic_id');
        $reply->save();
		return redirect()->to($reply->topic->showLink())->with('success', '回复成功！');
	}

	public function destroy(Reply $reply)
	{
		$this->authorize('destroy', $reply);
		$reply->delete();

		return redirect()->route('replies.index')->with('success', '删除成功！');
	}
}