<?php

namespace App\Tools;

use DateTime;
use function round;

class Bitcoins {

    public static function rates() {
        $data = file_get_contents("https://www.mercadobitcoin.net/api/BTC/ticker/");
        return json_decode($data)->ticker;
    }

    public static function real_btc($real) {
        return round($real / self::rates()->last,8);
    }

    public static function real_btc_sell($real) {
        return round($real / self::rates()->sell,8);
    }

    public static function btc_real($btc) {
        return round(self::rates()->last * $btc,8);
    }

    public static function variation () {
        // retorna a variação em porcentagem conforme o maior valor e menor valor do bitcoin
        return number_format((( Bitcoins::rates()->high / Bitcoins::rates()->low ) - 1 ) * 100,1);
    }

    public static function trades() {
        $date = new DateTime(date("Y-m-d"));
        $data = file_get_contents("https://www.mercadobitcoin.net/api/BTC/trades/".$date->getTimestamp());
        return json_decode($data);
    }
}
