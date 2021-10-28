<?php

namespace Kevinpurwito\LaravelRajabiller\Models;

use Illuminate\Database\Eloquent\Model;
use Kevinpurwito\LaravelRajabiller\Constants\RbConstant;
use Kevinpurwito\LaravelRajabiller\Constants\RbMethod;
use Kevinpurwito\LaravelRajabiller\Constants\RbSubtype;
use Kevinpurwito\LaravelRajabiller\Constants\RbType;
use Kevinpurwito\LaravelRajabiller\Facades\Rajabiller;
use Kevinpurwito\LaravelRajabiller\Relationships\BelongsToRbGroup;
use Kevinpurwito\LaravelRajabiller\Relationships\HasManyRbOrders;

class RbItem extends Model
{
    use BelongsToRbGroup;
    use HasManyRbOrders;

    protected $table = 'rb_items';

    protected $fillable = [
        'rb_group_id',
        'is_active',
        'code',
        'name',
        'type',
        'subtype',
        'group_name',
        'denominator',
        'price',
        'fee',
        'commission',
        'popular',
        'ordinal',
    ];

    public function process(array $params)
    {
        $rbItem = $this;
        $response = null;

        // required params
        $refId = $params['refId'];
        $itemCode = $params['itemCode'];
        $customerId = $params['customerId'];

        // optional params, depending on the transaction
        $nominal = $params['nominal'] ?? 0;
        $period = $params['period'] ?? '';

        if ($rbItem->type == RbType::TOP_UP) {
            $response = match ($rbItem->subtype) {
                RbSubtype::PULSA, RbSubtype::PAKET_DATA => Rajabiller::purchase($refId, $itemCode, $customerId, RbMethod::PULSA),
                RbSubtype::PLN_TOKEN => Rajabiller::buy($refId, $itemCode, $customerId, RbMethod::BUY, $nominal),
                default => Rajabiller::purchase($refId, $itemCode, $customerId, RbMethod::GAME),
            };
        }

        if ($rbItem->type == RbType::BILLS) {
            if ($rbItem->group_name == RbConstant::BPJS) {
                $response = Rajabiller::bpjsPay($refId, $itemCode, $customerId, $period);
            } else if ($rbItem->subType == RbSubtype::KARTU_KREDIT) {
                $response = Rajabiller::ccPay($refId, $itemCode, $customerId, $nominal);
            } else if (in_array($rbItem->subtype, RbSubtype::payMethod())) {
                $response = Rajabiller::pay($refId, $itemCode, $customerId, RbMethod::PAY);
            } else {
                $response = Rajabiller::pay($refId, $itemCode, $customerId, RbMethod::PAY_DETAIL);
            }
        }

        return $response;
    }
}
