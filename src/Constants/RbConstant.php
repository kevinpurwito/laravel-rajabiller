<?php

namespace Kevinpurwito\LaravelRajabiller\Constants;

use Kevinpurwito\PhpConstant\PhpConstant;

class RbConstant extends PhpConstant
{
    public const BPJS = 'BPJS';
    public const PHONE = 'TELEPON';
    public const PLN = 'PLNPRAH';
    public const TRANSFER = 'BLTRFAG';

    public const SUCCESS_CODE = '00';
    public const ACTIVE = 'AKTIF';

    public static function nominal(): array
    {
        return [
            20000, 50000, 100000, 200000, 500000, 1000000, 5000000, 10000000, 50000000,
        ];
    }
}
