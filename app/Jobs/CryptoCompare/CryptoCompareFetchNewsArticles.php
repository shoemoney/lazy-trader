<?php

namespace App\Jobs\CryptoCompare;

use App\Integrations\CryptoCompareApi;
use App\Models\NewsArticle;
use App\Models\NewsSource;
use App\Models\Tag;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CryptoCompareFetchNewsArticles implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var bool
     */
    private $latest;

    /**
     * @var string
     */
    private $source;

    public function __construct($latest = true, $source = null)
    {
        $this->latest = $latest;
        $this->source = $source;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $api = app()->make(CryptoCompareApi::class);

        $lTs = null;

        if(!$this->latest && NewsArticle::count() > 0) {
            $lTs = NewsArticle::orderBy('published_on', 'desc')->limit(1)->first()->published_on->timestamp;
        }

        $data = $api->latestNews($lTs, $this->source);

        if (!$data->Data) {
            return;
        }

        foreach($data->Data as $article) {
            // Does Article source exist?
            $source = NewsSource::updateOrCreate([
                'internal_name' => $article->source
            ], [
                'name' => $article->source_info->name,
                'language' => $article->source_info->lang,
                'image_url' => $article->source_info->img,
            ]);

            $newsArticle = NewsArticle::updateOrCreate([
                'source_id' => $source->id,
                'title' => $article->title
            ], [
                'body' => $article->body,
                'url' => $article->url,
                'image_url' => $article->imageurl,
                'published_on' => Carbon::parse($article->published_on),
            ]);

            $tags = [];
            // Link tags
            foreach(explode('|', $article->tags) as $tag) {
                $tags[] = Tag::updateOrCreate(['tag' => \Str::slug($tag)])->id;
            }

            $newsArticle->tags()->sync($tags);
        }
    }
}
