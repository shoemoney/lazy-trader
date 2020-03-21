<?php

namespace App\Jobs\Pricing;

use App\Models\Market;
use App\Services\Coins\CoinMarketCapService;
use App\Services\Coins\CoinPriceService;
use App\Services\Exchanges\ExchangeFactory;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class FetchCurrentPricing implements ShouldQueue
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
        ini_set('memory_limit', '4G');

        try {

            $exchangeApi = ExchangeFactory::create($this->market->exchange->internal_name);
            $maxRecords = $exchangeApi->minuteOhlcvLimit();
            $endTime = intval(time() / 60) * 60;
            $startTime = $endTime - ($maxRecords * 60);

            $data = $exchangeApi->minuteOhlcv($this->market, $startTime, $endTime);
            \Log::info($this->market->name . ' attempting to fetch current pricing from ' . $startTime . ' to ' . $endTime . ' found ' . count($data) . ' rows');

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

            if ($this->market->coinPair->quote->is_fiat_currency) {
                CoinMarketCapService::marketCap($this->market->coinPair->base);
                CoinPriceService::aggregatePrice($this->market->coinPair->base);
            }

            unset($data);

        } catch (\Exception $e) {
            \Log::error('Failed to fetch current pricing for ' . $this->market->name);
            \Log::error($e);
        }

    }
}
