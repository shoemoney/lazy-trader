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

class CryptoCompareFetchNewsSources implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $api = app()->make(CryptoCompareApi::class);

        $data = $api->newsSources();

        foreach($data as $source) {
            NewsSource::updateOrCreate([
                'internal_name' => $source->key
            ], [
                'name' => $source->name,
                'language' => $source->lang,
                'image_url' => $source->img,
            ]);
        }
    }
}
