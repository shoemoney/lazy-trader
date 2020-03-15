<?php


namespace App\Services\Exchanges\Implementations\Sockets;


use App\Models\Exchange;

abstract class BaseExchangeSocketJob
{
    public abstract function start(): void;
    public abstract function getExchange(): Exchange;
}
