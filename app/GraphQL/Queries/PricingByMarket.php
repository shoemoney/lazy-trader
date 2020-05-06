<?php

namespace App\GraphQL\Queries;

use App\Models\MarketPrice;
use Carbon\Carbon;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class PricingByMarket
{
    /**
     * Return a value for the field.
     *
     * @param null $rootValue Usually contains the result returned from the parent field. In this case, it is always `null`.
     * @param mixed[] $args The arguments that were passed into the field.
     * @param \Nuwave\Lighthouse\Support\Contracts\GraphQLContext $context Arbitrary data that is shared between all fields of a single query.
     * @param \GraphQL\Type\Definition\ResolveInfo $resolveInfo Information about the query itself, such as the execution state, the field name, path to the field from the root, and more.
     * @return mixed
     */
    public function __invoke($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $query = MarketPrice::whereMarketId($args['market']);

        if (isset($args['type']) && $args['type'] !== 'minute') {
            if ($args['type'] === 'hourly') {
                $time = 60 * 60;
            } else if ($args['type'] === 'daily') {
                $time = 60 * 60 * 24;
            } else if ($args['type'] === 'weekly') {
                $time = 60 * 60 * 24 * 7;
            } else if ($args['type'] === 'monthly') {
                $time = 60 * 60 * 24 * 30;
            } else if ($args['type'] === 'yearly') {
                $time = 60 * 60 * 24 * 365;
            }

            $query->groupBy([\DB::raw('FLOOR(market_prices.timestamp / ' . $time . ') * ' . $time), 'market_id']);
            $query->select([
                \DB::raw('FLOOR(market_prices.timestamp / ' . $time . ') * ' . $time . ' as ts'),
                \DB::raw('(SELECT open FROM market_prices openmp WHERE openmp.timestamp = MIN(market_prices.timestamp) AND openmp.market_id = market_prices.market_id LIMIT 1) as open'),
                \DB::raw('MAX(market_prices.high) as high'),
                \DB::raw('MIN(market_prices.low) as low'),
                \DB::raw('(SELECT close FROM market_prices closemp WHERE closemp.timestamp = MAX(market_prices.timestamp) AND closemp.market_id = market_prices.market_id LIMIT 1) as close'),
                \DB::raw('IFNULL(SUM(market_prices.volume), 0) as volume')
            ]);
        } else {
            $query->select([
                \DB::raw('timestamp as ts'),
                'open',
                'high',
                'low',
                'close'
            ]);
        }

        if (isset($args['range'])) {
            $minTime = Carbon::now()->subDay();
            if (strpos($args['range'], 'min')) {
                $minTime = Carbon::now()->subMinutes(str_replace('min', '', $args['range']));
            } else if (strpos($args['range'], 'd')) {
                $minTime = Carbon::now()->subDays(str_replace('d', '', $args['range']));
            } else if (strpos($args['range'], 'm')) {
                $minTime = Carbon::now()->subMonths(str_replace('m', '', $args['range']));
            } else if (strpos($args['range'], 'y')) {
                $minTime = Carbon::now()->subYears(str_replace('y', '', $args['range']));
            }

            $query->where('timestamp', '>=', $minTime->getTimestamp());
        } else {
            if (isset($args['minTimestamp'])) {
                $query->where('timestamp', '>=', $args['minTimestamp']);
            }

            if (isset($args['maxTimestamp'])) {
                $query->where('timestamp', '<=', $args['maxTimestamp']);
            }
        }

        \Log::info($query->toSql());

        return $query->orderBy('ts', 'asc')->get()->toArray();

    }
}
