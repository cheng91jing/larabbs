<div class="panel panel-default">
    <div class="panel-body">
        <a href="{{ route('topics.create') }}" class="btn btn-success btn-block" aria-label="Left Align">
            <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> 新建帖子
        </a>
    </div>
</div>
@if(count($active_users))
    <div class="panel panel-default">
        <div class="panel-body active-users">
            <div class="text-center">活跃用户</div>
            <hr>
            @foreach($active_users as $user)
                <a class="media" href="{{ route('users.show', $user->id) }}">
                    <div class="media-left media-middle">
                        <img src="{{ $user->avatar }}" width="24px" height="24px" class="img-circle media-object">
                    </div>

                    <div class="media-body">
                        <span class="media-heading">{{ $user->name }}</span>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
@endif