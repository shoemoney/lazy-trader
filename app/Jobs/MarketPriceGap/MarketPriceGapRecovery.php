<?php

namespace App\Jobs\MarketPriceGap;

use App\Models\Market;
use App\Services\Exchanges\ExchangeFactory;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class MarketPriceGapRecovery implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var Market
     */
    private $market;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Market $market)
    {
        $this->market = $market;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $exchangeApi = ExchangeFactory::create($this->market->exchange->internal_name);

            foreach ($this->market->priceGaps as $gap) {

                $totalMinutes = ($gap->gap_timestamp_end / 60) - ($gap->gap_timestamp_start / 60);

                for($i = 0; $i < $totalMinutes; $i += 500) {

                    $data = $exchangeApi->minuteOhlcv($this->market, $gap->gap_timestamp_start + ($i * 60), $gap->gap_timestamp_start + ($i * 60) + (500 * 60));

                    \Log::info($this->market->name . ' attempting recovery from ' . ($gap->gap_timestamp_start + ($i * 60)) . ' to ' . ($gap->gap_timestamp_start + ($i * 60) + (500 * 60)) . ' found ' . count($data) . ' rows');

                    foreach ($data as $ohlcv) {
                        try {
                            $this->market->prices()->updateOrCreate([
                                'timestamp' => $ohlcv->getTimestamp()
                            ], [
                                'open' => $ohlcv->getOpen(),
                                'high' => $ohlcv->getHigh(),
                                'low' => $ohlcv->getLow(),
                                'close' => $ohlcv->getClose(),
                                'volume' => $ohlcv->getVolume()
                            ]);
                        } catch (QueryException $exception) {
                            if (!\Str::contains($exception->getMessage(), 'Duplicate entry')) {
                                throw $exception;
                            }

                        }
                    }

                }
                $gap->delete();

                // Re-run our analysis to verify we have good data.
                dispatch(new MarketVolumeGapAnalysis($this->market, $gap->gap_timestamp_start, $gap->gap_timestamp_end + 60, false));
            }
        } catch (\Exception $e) {
            \Log::info('Unable to process market recovery');
            \Log::error($e);
        }
    }
}
