<?php

namespace Kevinpurwito\LaravelRajabiller\Constants;

use Kevinpurwito\PhpConstant\PhpConstant;

class RbMethod extends PhpConstant
{
    public const IP = 'cekip';
    public const BALANCE = 'balance';

    public const PRICE = 'harga';
    public const PRODUCT_INFO = 'info_produk';
    public const PRODUCT_GROUP = 'group_produk';

    public const TRANSACTION = 'datatransaksi';
    public const STATUS = 'cekstatus';

    public const CU = 'cu';
    public const CU_DETAIL = 'cudetail';

    public const PULSA = 'pulsa';
    public const GAME = 'game';
    public const BUY = 'beli';

    public const INQ = 'inq';
    public const PAY = 'pay';
    public const PAY_DETAIL = 'paydetail';

    public const BPJS_INQ = 'bpjsinq';
    public const BPJS_PAY = 'bpjspay';

    public const CC_INQ = 'inqkk';
    public const CC_PAY = 'paykk';

    public const TRANSFER_INQ = 'transferinq';
    public const TRANSFER_PAY = 'transferpay';

    public static function purchases(): array
    {
        return [
            self::PULSA,
            self::GAME,
        ];
    }

    public static function payments(): array
    {
        return [
            self::PAY,
            self::PAY_DETAIL,
        ];
    }
}
