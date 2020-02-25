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
     * @var null
     */
    private $startTimestamp;

    /**
     * @var null
     */
    private $endTimestamp;

    /**
     * @var bool
     */
    private $checkMarketReport;

    /**
     * Create a new job instance.
     */
    public function __construct(Market $market, $startTimestamp = null, $endTimestamp = null, $checkMarketReport = true)
    {
        $this->market = $market;
        $this->startTimestamp = $startTimestamp;
        $this->endTimestamp = $endTimestamp;
        $this->checkMarketReport = $checkMarketReport;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->market->prices()->count() === 0)
            return;

        $missing = [];
        $group = [];
        $lastKnownValue = null;
        $lastReportedTimestamp = null;

        $query = $this->market->prices()
            ->select(['timestamp'])
            ->orderBy('timestamp', 'asc');

        if ($this->checkMarketReport && $this->market->missing_data_report_timestamp) {
            $query = $query->where('timestamp', '>=', $this->market->missing_data_report_timestamp);
        }

        if ($this->startTimestamp) {
            $query = $query->where('timestamp', '>=', $this->startTimestamp);
        }

        if ($this->endTimestamp) {
            $query = $query->where('timestamp', '<=', $this->endTimestamp);
        }

        $query->chunk(5000, function ($data) use (&$missing, &$group, &$lastKnownValue, &$lastReportedTimestamp) {
            if (count($group) > 0 && $group[count($group) - 1] + 60 === $data[0]->timestamp) {
                $missing = [$group[0], $group[count($group) - 1]];
                $group = [];
            }

            for ($i = 0; $i < count($data); $i++) {
                $timestamp = $data[$i]->timestamp;
                $lastReportedTimestamp = $timestamp;

                for ($x = isset($lastKnownValue) ? $lastKnownValue : $timestamp + 60; isset($data[$i + 1]) && $x < $data[$i + 1]->timestamp; $x += 60) {
                    $group[] = $x;
                }

                if (count($group) > 0) {
                    $missing[] = [$group[0], $group[count($group) - 1]];
                    $group = [];
                }
            }

            if (count($group) > 0) {
                $lastKnownValue = $group[0];
            } else {
                $lastKnownValue = null;
            }
        });

        if(!$this->startTimestamp && !$this->endTimestamp && $lastReportedTimestamp) {
            // Are we missing data up to 3 mins ago?
            $lastTimestamp = $lastReportedTimestamp + 60;
            $currentTimestamp = now()->timestamp;
            $regressionTimestamp = $currentTimestamp - ($currentTimestamp % 60) - (60 * 3);

            if ($lastTimestamp < $regressionTimestamp) {
                $missing[] = [$lastTimestamp, $regressionTimestamp];
            }
        }

        // Remove all existing data gaps.
        $deleteQuery = $this->market->priceGaps();

        if ($this->startTimestamp) {
            $deleteQuery = $deleteQuery->where('gap_timestamp_start', '>=', $this->startTimestamp);
        }
        if ($this->endTimestamp) {
            $deleteQuery = $deleteQuery->where('gap_timestamp_end', '<=', $this->endTimestamp);
        }

        $deleteQuery->delete();

        // Add new market_price_gaps
        foreach ($missing as $m) {
            $this->market->priceGaps()->create([
                'gap_timestamp_start' => $m[0],
                'gap_timestamp_end' => $m[1]
            ]);
        }

        // Set a timestamp for the end of the report.
        if ($this->checkMarketReport && $lastReportedTimestamp) {
            $this->market->missing_data_report_timestamp = $lastReportedTimestamp;
            $this->market->save();
        }

    }
}
