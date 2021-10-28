<?php

namespace Kevinpurwito\LaravelRajabiller\Constants;

use Kevinpurwito\PhpConstant\PhpConstant;

class RbSubtype extends PhpConstant
{
    public const TV_KABEL = 'TV KABEL';
    public const KARTU_KREDIT = 'KARTU KREDIT';
    public const ASURANSI = 'ASURANSI';
    public const TIKET = 'TIKET';
    public const MULTI_FINANCE = 'MULTI FINANCE';
    public const PDAM = 'PDAM';
    public const GAS_ALAM = 'GAS ALAM';
    public const HP_PASCABAYAR = 'HP PASCABAYAR';
    public const PLN = 'PLN';
    public const TELKOM = 'TELKOM';
    public const PAJAK = 'PAJAK';
    public const ZAKAT = 'ZAKAT';
    public const BIOSKOP = 'BIOSKOP';
    public const E_MONEY = 'E-MONEY';
    public const E_TOLL = 'E-TOLL';
    public const GAME = 'GAME';
    public const PAKET_DATA = 'PAKET DATA';
    public const PLN_TOKEN = 'PLN TOKEN';
    public const PULSA = 'PULSA';
    public const BELANJA = 'BELANJA';

    public static function payMethod(): array
    {
        return [
            self::TV_KABEL,
            self::HP_PASCABAYAR,
            self::ASURANSI,
        ];
    }
}
