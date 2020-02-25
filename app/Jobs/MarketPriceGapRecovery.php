<?php

namespace App\Jobs;

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
                $data = $exchangeApi->minuteOhlc($this->market, $gap->gap_timestamp_start, $gap->gap_timestamp_end + 60);

                \Log::info($this->market->name . ' attempting recovery from ' . $gap->gap_timestamp_start . ' to ' . $gap->gap_timestamp_end . ' found ' . count($data) . ' rows');

                foreach ($data as $ohlc) {
                    try {
                        $this->market->prices()->updateOrCreate([
                            'timestamp' => $ohlc->getTimestamp()
                        ], [
                            'open' => $ohlc->getOpen(),
                            'high' => $ohlc->getHigh(),
                            'low' => $ohlc->getLow(),
                            'close' => $ohlc->getClose()
                        ]);
                    } catch (QueryException $exception) {
                        if (!\Str::contains($exception->getMessage(), 'Duplicate entry')) {
                            throw $exception;
                        }

                    }
                }

                $gap->delete();

                // Re-run our analysis to verify we have good data.
                dispatch(new MarketPriceGapAnalysis($this->market, $gap->gap_timestamp_start, $gap->gap_timestamp_end + 60, false));
            }
        } catch (\Exception $e) {
            \Log::info('Unable to process for Gemini');
        }
    }
}
