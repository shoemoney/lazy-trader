<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class BackfillMarketPrices extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Run hourly backfill
        \DB::statement('INSERT IGNORE INTO market_prices (market_id, timestamp, type, open, high, low, close, volume)
                        SELECT
                            market_prices.market_id as market_id,
                            FLOOR(market_prices.timestamp / 3600) * 3600 as timestamp,
                            \'hour\' as type,
                            (SELECT open FROM market_prices openmp WHERE openmp.timestamp = MIN(market_prices.timestamp) AND openmp.market_id = market_prices.market_id LIMIT 1) as open,
                            MAX(high) as high,
                            MIN(low) as low,
                            (SELECT close FROM market_prices closemp WHERE closemp.timestamp = MAX(market_prices.timestamp) AND closemp.market_id = market_prices.market_id LIMIT 1) as close,
                            SUM(volume) AS volume
                        FROM market_prices
                        GROUP BY market_prices.market_id, FLOOR(market_prices.timestamp / 3600) * 3600;');

        // Run daily backfill
        \DB::statement('INSERT IGNORE INTO market_prices (market_id, timestamp, type, open, high, low, close, volume)
                        SELECT
                            x.market_id as market_id,
                            CAST(UNIX_TIMESTAMP(CONVERT_TZ(CONCAT(x.year, \'-\', x.month, \'-\', x.day, \' 00:00:00 UTC\'), \'+00:00\', @@global.time_zone)) AS UNSIGNED) as timestamp,
                            \'day\' as type,
                            x.open as open,
                            x.high as high,
                            x.low as low,
                            x.close as close,
                            x.volume as volume
                        FROM (
                        SELECT
                            market_prices.market_id as market_id,
                            (SELECT open FROM market_prices openmp WHERE openmp.timestamp = MIN(market_prices.timestamp) AND openmp.market_id = market_prices.market_id LIMIT 1) as open,
                            MAX(high) as high,
                            MIN(low) as low,
                            (SELECT close FROM market_prices closemp WHERE closemp.timestamp = MAX(market_prices.timestamp) AND closemp.market_id = market_prices.market_id LIMIT 1) as close,
                            SUM(volume) AS volume,
                            YEAR(FROM_UNIXTIME(market_prices.timestamp)) as year,
                            MONTH(FROM_UNIXTIME(market_prices.timestamp)) as month,
                            DAY(FROM_UNIXTIME(market_prices.timestamp)) as day
                        FROM market_prices
                        GROUP BY
                            market_prices.market_id,
                            YEAR(FROM_UNIXTIME(market_prices.timestamp)),
                            MONTH(FROM_UNIXTIME(market_prices.timestamp)),
                            DAY(FROM_UNIXTIME(market_prices.timestamp))
                        ) x;');

        // Run monthly backfill
        \DB::statement('INSERT IGNORE INTO market_prices (market_id, timestamp, type, open, high, low, close, volume)
                        SELECT
                            x.market_id as market_id,
                            CAST(UNIX_TIMESTAMP(CONVERT_TZ(CONCAT(x.year, \'-\', x.month, \'-01 00:00:00 UTC\'), \'+00:00\', @@global.time_zone)) AS UNSIGNED) as timestamp,
                            \'month\' as type,
                            x.open as open,
                            x.high as high,
                            x.low as low,
                            x.close as close,
                            x.volume as volume
                        FROM (
                        SELECT
                            market_prices.market_id as market_id,
                            (SELECT open FROM market_prices openmp WHERE openmp.timestamp = MIN(market_prices.timestamp) AND openmp.market_id = market_prices.market_id LIMIT 1) as open,
                            MAX(high) as high,
                            MIN(low) as low,
                            (SELECT close FROM market_prices closemp WHERE closemp.timestamp = MAX(market_prices.timestamp) AND closemp.market_id = market_prices.market_id LIMIT 1) as close,
                            SUM(volume) AS volume,
                            YEAR(FROM_UNIXTIME(market_prices.timestamp)) as year,
                            MONTH(FROM_UNIXTIME(market_prices.timestamp)) as month
                        FROM market_prices
                        GROUP BY
                            market_prices.market_id,
                            YEAR(FROM_UNIXTIME(market_prices.timestamp)),
                            MONTH(FROM_UNIXTIME(market_prices.timestamp))
                        ) x;');

        // Run yearly backfill
        \DB::statement('INSERT IGNORE INTO market_prices (market_id, timestamp, type, open, high, low, close, volume)
                        SELECT
                            x.market_id as market_id,
                            CAST(UNIX_TIMESTAMP(CONVERT_TZ(CONCAT(x.year, \'-01-01 00:00:00 UTC\'), \'+00:00\', @@global.time_zone)) AS UNSIGNED) as timestamp,
                            \'year\' as type,
                            x.open as open,
                            x.high as high,
                            x.low as low,
                            x.close as close,
                            x.volume as volume
                        FROM (
                        SELECT
                            market_prices.market_id as market_id,
                            (SELECT open FROM market_prices openmp WHERE openmp.timestamp = MIN(market_prices.timestamp) AND openmp.market_id = market_prices.market_id LIMIT 1) as open,
                            MAX(high) as high,
                            MIN(low) as low,
                            (SELECT close FROM market_prices closemp WHERE closemp.timestamp = MAX(market_prices.timestamp) AND closemp.market_id = market_prices.market_id LIMIT 1) as close,
                            SUM(volume) AS volume,
                            YEAR(FROM_UNIXTIME(market_prices.timestamp)) as year
                        FROM market_prices
                        GROUP BY
                            market_prices.market_id,
                            YEAR(FROM_UNIXTIME(market_prices.timestamp))
                        ) x;');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \DB::statement('DELETE FROM market_prices WHERE type IN (\'hourly\', \'daily\', \'monthly\', \'yearly\');');
    }
}
