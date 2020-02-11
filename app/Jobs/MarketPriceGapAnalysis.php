<?php

namespace App\Jobs;

use App\Models\Market;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class MarketPriceGapAnalysis implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var Market
     */
    private $market;

    /**
     * Create a new job instance.
     */
    public function __construct(Market $market)
    {
        $this->market = $market;

        ini_set('memory_limit', '4G');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $timestamps = $this->market->prices()
            ->select(['timestamp'])
            ->orderBy('timestamp', 'asc')
            ->get()
            ->pluck('timestamp');

        $missing = [];
        $group = [];

        for ($i = 0; $i < count($timestamps) - 1; $i++) {
            for ($x = $timestamps[$i] + 60; $x < $timestamps[$i + 1]; $x += 60) {
                $group[] = $x;
            }

            if (count($group) > 0) {
                $missing[] = [$group[0], $group[count($group) - 1]];
                $group = [];
            }
        }

        // TODO: Are we missing data up to 3 mins ago?
        $lastTimestamp = $this->market->prices()->selectRaw('MAX(timestamp) as timestamp')->first()->timestamp + 60;
        $currentTimestamp = now()->timestamp;
        $regressionTimestamp = $currentTimestamp - ($currentTimestamp % 60) - (60 * 3);

        if($lastTimestamp < $regressionTimestamp) {
            $missing[] = [$lastTimestamp, $regressionTimestamp];
        }

        // Remove all existing data gaps.
        $this->market->priceGaps()->delete();

        // Add new market_price_gaps
        foreach ($missing as $m) {
            $this->market->priceGaps()->create([
                'gap_timestamp_start' => $m[0],
                'gap_timestamp_end' => $m[1]
            ]);
        }
    }
}
