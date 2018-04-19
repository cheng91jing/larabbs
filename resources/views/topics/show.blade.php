@extends('layouts.app')
@section('title', $topic->title)
@section('description', $topic->excerpt)
@section('content')

    <div class="container">
        <div class="col-md-3 col-lg-3 hidden-sm hidden-xs author-info">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="text-center">
                        作者：{{ $topic->user->name }}
                    </div>
                    <hr>
                    <div class="media">
                        <div align="center">
                            <a href="{{ route('users.show', [$topic->user_id]) }}">
                                <img src="{{ $topic->user->avatar }}" width="300px" height="300px" class="thumbnail img-responsive" alt="">
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-9 col-lg-9 col-sm-12 col-xs-12 topic-content">
            <div class="panel panel-default">
                <div class="panel-body">
                    <h1 class="text-center">
                        {{ $topic->title }}
                    </h1>
                    <div class="article-meta text-center">
                        {{ $topic->created_at->diffForHumans() }}
                        ⋅
                        <span class="glyphicon glyphicon-comment" aria-hidden="true"></span>
                        {{ $topic->reply_count }}
                    </div>
                    <div class="topic-body">
                        {!! $topic->body !!}
                    </div>
                    <div class="operate">
                        <hr>
                        <a href="{{ route('topics.edit', [$topic->id]) }}" class="btn btn-default btn-xs" role="button">
                            <i class="glyphicon glyphicon-edit"></i> 编辑
                        </a>
                        <a href="#" class="btn btn-default btn-xs" role="button">
                            <i class="glyphicon glyphicon-trash"></i> 删除
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
