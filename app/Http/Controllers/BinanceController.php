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
        // $api = new Binance\RateLimiter($api);
    }

    public function index()
    {
        return $this->api->prices();
    }

    public function conversor()
    {
        # Market
        $symbol = "BTCUSDC";
        # Quantity available in btc wallet
        $quantity = $this->getBtcWallet();
        // $quantity = 0.001;
        // dd($quantity);
        # Price of $market
        $price = $this->api->price($symbol);

        if (($quantity * $price) > 0) {
            # Route to order
            $this->api->marketSell("ETHBTC", $quantity, $price, "LIMIT");
            # Route to test order
            // $order = $this->api->sellTest("BTCUSDC", $quantity, $price, "LIMIT");
            // dd($order);
        }
    }

    public function getUsdcWallet()
    {
        $accounts = $this->api->account()["balances"];

        for ($i=0; $i < sizeof($accounts); $i++) {
            if ($accounts[$i]["asset"] == "USDC") {
                return $accounts[$i]["free"];
            }
        }
    }

    public function getBtcWallet()
    {
        $accounts = $this->api->account()["balances"];

        for ($i=0; $i < sizeof($accounts); $i++) {
            if ($accounts[$i]["asset"] == "BTC") {
                return $accounts[$i]["free"];
            }
        }
    }

    public function withdraw30()
    {
        $this->api->withdraw();

        return '<>';
    }

    public function withdraw70()
    {
        return '<>';
    }

    public function split($value30, $value70)
    {
        # Manda pra w30
        $response30 = $this->withdraw30($value30);
        Log::info($response30);

        # Manda pra w70
        $response70 = $this->withdraw70($value70);
        Log::info($response70);
    }

    public function splitValues($order_id = 'none')
    {
        # Pegar o valor da carteira USDC
        $wallet = $this->getUsdcWallet();
        $size = $wallet["available"];

        # Padroniza o size para a quantidade de casas decimais suportadas pelas operações do coinbase
        $size = $this->getValueSixDecimal($size);

        # Dividir em 30%
        $value30 = 0.3 * $size;
        $value30 = $this->getValueSixDecimal($value30);

        # Dividir em 70%
        $value70 = 0.7 * $size;
        $value70 = $this->getValueSixDecimal($value70);

        if ($value30 > 0 && $value70 > 0) {
            # Withdraw para duas carteiras
            $this->split($value30, $value70);
        }
    }

    private function getValueSixDecimal($value): float
    {
        return intval(strval($value * 1000000)) / 1000000;
    }

}
