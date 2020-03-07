<?php namespace App\Services\Coins;

use App\Models\Coin;
use App\Models\MarketPrice;
use App\Models\NewsArticle;

class CoinRankingService
{
    public function rank()
    {
        $coins = Coin::whereIsFiatCurrency(false)->whereId(3358)->get();
        $coinScores = [];
        foreach ($coins as $coin) {
            $coinScores[$coin->id] = $this->score($coin);
        }
    }

    // TODO: cache each section
    private function score(Coin $coin)
    {
        // Volume traded in USD

        $startTimestamp = time() - (60 * 60 * 24);

        $volumeData = MarketPrice::where('timestamp', '>', $startTimestamp)
            ->whereIn('market_id', $usdMarkets)
            ->select(\DB::raw('SUM(volume) as volume'), 'market_id')
            ->groupBy('market_id')
            ->get();

//        dd($volumeData->toArray());

        // Number of related news articles

        $tags = $coin->tags()->get();
        $articles = NewsArticle::with(['tags' => function($query) use ($tags) {
            return $query->whereIn('tags.id', $tags->pluck('id'));
        }])
            ->get();

        dd($articles->filter(function($article) {
            return count($article->tags) > 0;
        })->pluck('id'));

        // CryptoCompare rank, updated daily

        return 0;
    }

}
