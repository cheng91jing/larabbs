<?php

namespace App\Http\Controllers;

use App\Handlers\ImageUploadHandler;
use App\Models\Category;
use App\Models\Link;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\TopicRequest;
use Auth;

class TopicsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['index', 'show']]);
    }

	public function index(Request $request, User $user)
	{
		$topics = Topic::withOrder($request->query('order'))->paginate(20);
		$active_users = $user->getActiveUsers();
		$links = (new Link())->getAllCached();
		return view('topics.index', compact('topics', 'active_users', 'links'));
	}

    public function show(Request $request, Topic $topic)
    {
        //URL 矫正
        if(! empty($topic->slug) && $topic->slug != $request->slug){
            return redirect($topic->showLink(), 301);
        }

        return view('topics.show', compact('topic'));
    }

	public function create(Topic $topic)
	{
	    $categories = Category::all();
		return view('topics.create_and_edit', compact('topic', 'categories'));
	}

	public function store(TopicRequest $request)
	{
		$topic = (new Topic)->fill($request->all());
		$topic->user_id = Auth::id();
        $topic->save();
		return redirect()->to($topic->showLink())->with('success', '成功创建话题');
//		return redirect()->route('topics.show', $topic->id)->with('success', '成功创建话题');
	}

	public function edit(Topic $topic)
	{
        $this->authorize('update', $topic);
        $categories = Category::all();
		return view('topics.create_and_edit', compact('topic', 'categories'));
	}

	public function update(TopicRequest $request, Topic $topic)
	{
		$this->authorize('update', $topic);
		$topic->update($request->all());

//		return redirect()->route('topics.show', $topic->id)->with('success', '更新成功');
		return redirect()->to($topic->showLink())->with('success', '更新成功');
	}

	public function destroy(Topic $topic)
	{
		$this->authorize('destroy', $topic);
		$topic->delete();

		return redirect()->route('topics.index')->with('success', '成功删除');
	}

    public function uploadImage(Request $request, ImageUploadHandler $imageUploadHandler)
    {
        //初始化数据 默认失败
        $data = [
            'success' => false,
            'msg' => '上传失败',
            'file_path' => ''
        ];
        if($request->file('upload_file')){
            $result = $imageUploadHandler->savePublic($request->file('upload_file'), 'topics', Auth::id(), 1024);
            if($result){
                $data['file_path'] = $result['path'];
                $data['msg']       = "上传成功!";
                $data['success']   = true;
            }
        }
        return $data;
	}
}