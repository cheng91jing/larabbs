<?php

namespace App\Jobs;

use App\Handlers\SlugTranslateHandler;
use App\Models\Topic;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class TranslateSlug implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $topic;

    /**
     * Create a new job instance.
     *
     * @param \App\Models\Topic $topic
     */
    public function __construct(Topic $topic)
    {
        $this->topic = $topic;
    }

    /**
     * Execute the job.
     *
     * @param \App\Handlers\SlugTranslateHandler $slugTranslateHandler
     *
     * 还有一点需要注意，我们将会在模型监控器中分发任务，
     * 任务中要避免使用 Eloquent 模型接口调用，如：create(), update(), save() 等操作。
     * 否则会陷入调用死循环 —— 模型监控器分发任务，任务触发模型监控器，模型监控器再次分发任务，任务再次触发模型监控器.... 死循环。
     * 在这种情况下，使用 DB 类直接对数据库进行操作即可。
     *
     * @return void
     */
    public function handle(SlugTranslateHandler $slugTranslateHandler)
    {
        //请求百度API进行接口翻译
        $slug = $slugTranslateHandler->translate($this->topic->title);
        //为了避免模型监控器死循环调用，我们使用 DB 类直接对数据库进行操作
        \DB::table('topics')->where('id', $this->topic->id)->update(['slug' => $slug]);
    }
}
