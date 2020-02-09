<?php

namespace App\Console\Commands;

use App\Models\Exchange;
use Illuminate\Console\Command;

class ActivateMarkets extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lazy-trader:activate-markets {--exchanges}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';


    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // TODO: Select Coin -> Coin Pair -> Multiple Choice with List of Markets
        if($this->hasOption('exchanges')) {
            $this->processExchanges();
        } else {
            $this->error('Please use a flag: exchanges.');
        }
    }

    private function processExchanges()
    {
        // Prompt user with all exchanges.
        $exchanges = Exchange::all();

        while(true) {
            $input = $this->anticipate('What exchange would you like to activate? (use q to quit)', $exchanges->map(function ($x) {
                return $x->name;
            }));

            if($input === 'q')
                break;

            $exchange = Exchange::whereName($input)->first();

            if(!$exchange) {
                $this->error('Unable to find provided exchange.');
                continue;
            }

            $exchange->markets()->update(['active' => true]);

            $this->info($exchange->markets()->count() . ' markets have been activated.');
        }
    }
}
