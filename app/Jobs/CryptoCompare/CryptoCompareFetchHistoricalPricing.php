<?php

namespace App\Jobs\CryptoCompare;

use App\Models\CoinPair;
use App\Models\Exchange;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CryptoCompareFetchHistoricalPricing implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var Exchange
     */
    private $exchange;

    /**
     * @var CoinPair
     */
    private $pair;

    /**
     * @var bool
     */
    private $activeOnly;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(
        Exchange $exchange = null,
        CoinPair $pair = null,
        $activeOnly = false
    )
    {
        $this->exchange = $exchange;
        $this->pair = $pair;
        $this->activeOnly = $activeOnly;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        if (isset($this->exchange)) {
            $this->fetchForExchange($this->exchange);
        } else {
            foreach (Exchange::all() as $exchange) {
                $this->fetchForExchange($exchange);
            }
        }
    }

    private function fetchForExchange(Exchange $exchange)
    {
        $marketQuery = $exchange->markets();
        if (isset($this->pair)) {
            $marketQuery = $marketQuery->whereCoinPairId($this->pair->id);
        }

        if (!$this->activeOnly) {
            $marketQuery = $marketQuery->whereActive(true);
        }

        $markets = $marketQuery->get();

        foreach ($markets as $market) {
            \Log::info('Spawning market historical data for ' . $market->name);
            dispatch(new CryptoCompareFetchHistoricalPricingForMarket($market));
        }
    }
}
