<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Binance;

class BinanceController extends Controller
{
    protected $api;

    public function __construct()
    {
        // $api_key = '373184fIE6zG6vA1IujAHdhUNgG4fZy8IxezsuBWAto8stTixGVU9a2DcFyQvkiT';
        $api_key = config('binance.api_key');
        // $api_secret = 'vQdPeXICp6KXZOXi3PRhA8VrceatEHkQPHkMLbtLxfacJtF134s4yciLKBPnL91H';
        $api_secret = config('binance.api_secret');
        
        $this->api = new Binance\API($api_key, $api_secret);
    }

    public function index()
    {
        return $this->api->prices();
        // $api = new Binance\RateLimiter($api);

        // return 'oi';
    }

}
