<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Topic;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Category $category
     * @param Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category, Request $request)
    {
        //读取ID相关联的话题，按每页20条分页
        $topics = Topic::withOrder($request->query('order'))
                    ->where('category_id', $category->id)
                    ->paginate(20);
        return view('topics.index', compact('topics', 'category'));
    }
}
