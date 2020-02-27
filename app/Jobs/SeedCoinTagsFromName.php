<?php

namespace App\Jobs;

use App\Models\Coin;
use App\Models\Tag;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SeedCoinTagsFromName implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $coins = Coin::all();

        foreach($coins as $coin) {
            // Create a coin tag from name & symbol
            \Log::info($coin->name);
            \Log::info(Tag::updateOrCreate(['tag' => \Str::slug($coin->symbol)])->id);

            $coin->tags()->sync([
                Tag::updateOrCreate(['tag' => \Str::slug($coin->symbol)])->id,
                Tag::updateOrCreate(['tag' => \Str::slug($coin->name)])->id,
                Tag::updateOrCreate(['tag' => \Str::slug($coin->proof_type)])->id
            ]);

            $coin->save();

        }
    }
}
