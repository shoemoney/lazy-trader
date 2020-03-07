<?php

namespace App\Jobs\MarketVolumeGap;

use App\Models\Market;
use App\Services\Exchanges\ExchangeFactory;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class MarketVolumeGapRecovery implements ShouldQueue
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

            $prices = $this->market->prices()->whereNull('volume')->get();

            $timestamps = $prices->map(function ($value) {
                return $value->timestamp;
            });

            $gaps = [];

            $groupBy = 100;
            foreach($timestamps as $timestamp) {
                $group = intval(floor($timestamp / ($groupBy * 60))) * $groupBy * 60;

                if(!isset($gaps[$group])) {
                    $gaps[$group] = [];
                }

                $gaps[$group][] = $timestamp;
            }

            $gaps = collect($gaps)->map(function($value) {
                return ['start' => min($value), 'end' => max($value)];
            });

            foreach($gaps as $gap) {
                $data = $exchangeApi->minuteOhlcv($this->market, $gap['start'], $gap['end'] + 60);

                \Log::info($this->market->name . ' attempting recovery from ' . $gap['start'] . ' to ' . $gap['end'] . ' found ' . count($data) . ' rows');

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
        } catch (\Exception $e) {
            \Log::info('Unable to process market volume recovery');
            \Log::error($e);
        }
    }
}
