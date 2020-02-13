<?php

namespace App\Jobs;

use App\Models\Coin;
use App\Models\CoinPair;
use App\Models\Exchange;
use App\Models\Market;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ImportPricingFromCsv implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var
     */
    private $file;

    /**
     * @var array
     */
    private $marketCache;

    /**
     * @var bool
     */
    private $deleteFile;

    /**
     * Create a new job instance.
     */
    public function __construct($file, $deleteFile = false)
    {
        $this->file = $file;
        $this->deleteFile = $deleteFile;
        $this->marketCache = [];
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $fileHandler = fopen($this->file, 'r');

        if ($fileHandler) {
            // Remove header line...
            fgets($fileHandler);

            while (($line = fgets($fileHandler)) !== false) {
                $this->processLine($line);
            }

            fclose($fileHandler);

            if($this->deleteFile) {
                unlink($this->file);
            }
        } else {
            throw new \Exception("Unable to find file");
        }

    }

    private function processLine(string $line)
    {
        $data = str_getcsv($line);

        $marketKey = $data[1].$data[2].$data[3];

        if(!isset($this->marketCache[$marketKey])) {
            $exchange = Exchange::whereName($data[1])->first();
            $quoteCoin = Coin::whereSymbol($data[2])->first();
            $baseCoin = Coin::whereSymbol($data[3])->first();
            $coinPair = CoinPair::whereQuoteCoinId($quoteCoin->id)->whereBaseCoinId($baseCoin->id)->first();

            $this->marketCache[$marketKey] = Market::whereExchangeId($exchange->id)
                ->whereCoinPairId($coinPair->id)
                ->first();
        }

        $market = $this->marketCache[$marketKey];

        $market->prices()->updateOrCreate([
            'timestamp' => $data[0]
        ], [
            'open' => $data[4],
            'high' => $data[5],
            'low' => $data[6],
            'close' => $data[7]
        ]);
    }
}
