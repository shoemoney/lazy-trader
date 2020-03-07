<?php

namespace App\Jobs\CryptoCompare;

use App\Models\CoinPair;
use App\Models\Exchange;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CryptoCompareFetchPricing implements ShouldQueue
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
     * @var bool
     */
    private $historical;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(
        Exchange $exchange = null,
        CoinPair $pair = null,
        $activeOnly = false,
        $historical = false
    )
    {
        $this->exchange = $exchange;
        $this->pair = $pair;
        $this->activeOnly = $activeOnly;
        $this->historical = $historical;
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
            \Log::info('Spawning market ' . ($this->historical ? 'historical' : 'current') . ' pricing data for ' . $market->name);
            dispatch(new CryptoCompareFetchPricingForMarket($market, $this->historical));
        }
    }
}
