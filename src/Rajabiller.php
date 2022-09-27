<?php

namespace Kevinpurwito\LaravelRajabiller;

use GuzzleHttp\Client;
use Kevinpurwito\LaravelRajabiller\Constants\RbConstant;
use Kevinpurwito\LaravelRajabiller\Constants\RbMethod;
use Kevinpurwito\LaravelRajabiller\Models\RbItem;
use Kevinpurwito\LaravelRajabiller\Models\RbOrder;

class Rajabiller
{
    protected string $url = 'https://rajabiller.fastpay.co.id/transaksi/json_devel.php';
    protected string $env = 'dev';
    protected string $uid = '';
    protected string $pin = '';
    protected Client $client;

    public function __construct(string $env, string $uid, string $pin, string $url = '')
    {
        $this->client = new Client();
        $this->uid = $uid;
        $this->pin = $pin;
        $this->env = $env;

        if ($this->env == 'prod') {
            $this->url = 'https://rajabiller.fastpay.co.id/transaksi/json.php';
        }

        if ($url !== '') {
            $this->url = $url;
        }
    }

    public function populateItems()
    {
        $items = RbItem::all();

        foreach ($items as $item) {
            $content = $this->item($item->code);
            $status = $content->STATUS ?? '';

            $code = $content->KODE_PRODUK ?? '';
            if ($code !== $item->code) {
                continue;
            }

            if ($status !== RbConstant::SUCCESS_CODE) {
                continue;
            }

            // $item->name = $content->PRODUK; // use the name from seeders instead
            $item->price = (int)$content->HARGA;
            $item->fee = (int)$content->ADMIN;
            $item->commission = (int)$content->KOMISI;
            $item->is_active = ($content->STATUS_PRODUK == RbConstant::ACTIVE);
            $item->save();
        }
    }

    public function populateItemsH2H()
    {
        $items = RbItem::all();

        foreach ($items as $item) {
            $code = $item->code . 'H';
            $content = $this->item($code);
            $status = $content->STATUS ?? '';

            $itemCode = $content->KODE_PRODUK ?? '';
            if ($itemCode !== $code) {
                continue;
            }

            if ($status !== RbConstant::SUCCESS_CODE) {
                continue;
            }

            $duplicate = RbItem::whereCode($code)->first();
            if ($duplicate && $duplicate->id !== $item->id) {
                continue;
            }

            $item->code = $code;
            $item->price = (int)$content->HARGA;
            $item->fee = (int)$content->ADMIN;
            $item->commission = (int)$content->KOMISI;
            $item->is_active = ($content->STATUS_PRODUK == RbConstant::ACTIVE);
            $item->save();
        }
    }

    public function getBalance(): int
    {
        return $this->balance()->SALDO ?? 0;
    }

    public function balance(): object
    {
        $params = [
            'uid' => $this->uid, 'pin' => $this->pin,
            'method' => 'rajabiller.' . RbMethod::BALANCE,
        ];

        $response = $this->client->request('POST', $this->url, ['json' => $params]);

        return json_decode($response->getBody()->getContents());
    }

    public function orders(?string $date = null, ?RbOrder $order = null): object
    {
        // Max Range only 1 day
        $date = $date ?? date('Y-m-d'); // Y-m-d, ex: 2021-08-01
        $startDate = date_format(date_create_from_format('Y-m-d', $date), 'Ymd') . '000000';
        $endDate = date_format(date_create_from_format('Y-m-d', $date), 'Ymd') . '235959';

        $transId = $order->ref_2 ?? '';
        $customerId = $order->customer_id_1 ?? '';
        $itemId = $order->item_code ?? '';

        $params = [
            'uid' => $this->uid, 'pin' => $this->pin,
            'method' => 'rajabiller.' . RbMethod::TRANSACTION,
            'tgl1' => $startDate,
            'tgl2' => $endDate,
            'id_transaksi' => $transId,
            'id_produk' => $itemId,
            'idpel' => $customerId,
            'limit' => '',
        ];

        $response = $this->client->request('POST', $this->url, ['json' => $params]);

        return json_decode($response->getBody()->getContents());
    }

    public function groupItems(string $groupCode): object
    {
        $params = [
            'uid' => $this->uid, 'pin' => $this->pin,
            'method' => 'rajabiller.' . RbMethod::PRICE,
            'produk' => $groupCode,
        ];

        $response = $this->client->request('POST', $this->url, ['json' => $params]);

        return json_decode($response->getBody()->getContents());
    }

    public function item(string $code): object
    {
        $params = [
            'uid' => $this->uid, 'pin' => $this->pin,
            'method' => 'rajabiller.' . RbMethod::PRODUCT_INFO,
            'kode_produk' => $code,
        ];

        $response = $this->client->request('POST', $this->url, ['json' => $params]);

        return json_decode($response->getBody()->getContents());
    }

    /**
     * @param string $refId | Reference or Transaction Id
     * @param string $itemCode
     * @param string $customerId
     * for Mobile Legend, use Mobile Legend Id instead
     * for E-Toll, use E-Toll Number instead
     * for Other Items, use Customer's Phone Number
     * @param string $method | 'pulsa' or 'game'
     * @return object
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function purchase(string $refId, string $itemCode, string $customerId, string $method = RbMethod::PULSA): object
    {
        if (! in_array($method, RbMethod::purchases())) {
            return (object)['success' => false, 'message' => 'Invalid method: ' . $method];
        }

        $params = [
            'uid' => $this->uid, 'pin' => $this->pin,
            'method' => 'rajabiller.' . $method,
            'kode_produk' => $itemCode,
            'no_hp' => $customerId,
            'ref1' => $refId,
        ];

        $response = $this->client->request('POST', $this->url, ['json' => $params]);

        return json_decode($response->getBody()->getContents());
    }

    public function inquiry(string $refId, string $itemCode, string $customerId, string $areaCode = ''): object
    {
        $params = [
            'uid' => $this->uid, 'pin' => $this->pin,
            'method' => 'rajabiller.' . RbMethod::INQ,
            'kode_produk' => $itemCode,
            'idpel1' => $customerId,
            'idpel2' => '',
            'idpel3' => '',
            'ref1' => $refId,
        ];

        if ($itemCode == RbConstant::PHONE && $areaCode) {
            $params['idpel1'] = $areaCode;
            $params['idpel2'] = $customerId;
        }

        $response = $this->client->request('POST', $this->url, ['json' => $params]);

        return json_decode($response->getBody()->getContents());
    }

    public function pay(string $refId, string $itemCode, string $customerId, string $method = RbMethod::PAY_DETAIL, string $areaCode = ''): object
    {
        if (! in_array($method, RbMethod::payments())) {
            return (object)['success' => false, 'message' => 'Invalid method: ' . $method];
        }

        $content = $this->inquiry($refId, $itemCode, $customerId, $areaCode);
        $status = $content->STATUS ?? '';

        if (! $status == RbConstant::SUCCESS_CODE) {
            return $content;
        }

        $params = [
            'uid' => $this->uid, 'pin' => $this->pin,
            'method' => 'rajabiller.' . $method,
            'kode_produk' => $itemCode,
            'idpel1' => $customerId,
            'idpel2' => '',
            'idpel3' => '',
            'ref1' => '',
            'ref2' => $content->REF2,
            'ref3' => '',
            'nominal' => $content->NOMINAL,
        ];

        if ($itemCode == RbConstant::PHONE && $areaCode) {
            $params['idpel1'] = $areaCode;
            $params['idpel2'] = $customerId;
        }

        $response = $this->client->request('POST', $this->url, ['json' => $params]);

        return json_decode($response->getBody()->getContents());
    }

    public function buy(string $refId, string $itemCode, string $customerId, string|int $nominal): object
    {
        if (! in_array($itemCode, [RbConstant::PLN])) {
            return (object)['success' => false, 'message' => 'Invalid itemCode: ' . $itemCode];
        }

        if (! in_array($nominal, RbConstant::nominal())) {
            return (object)['success' => false, 'message' => 'Invalid nominal: ' . $nominal];
        }

        $params = [
            'uid' => $this->uid, 'pin' => $this->pin,
            'method' => 'rajabiller.' . RbMethod::BUY,
            'kode_produk' => $itemCode,
            'idpel' => $customerId,
            'ref1' => $refId,
            'nominal' => intval($nominal),
        ];

        $response = $this->client->request('POST', $this->url, ['json' => $params]);

        return json_decode($response->getBody()->getContents());
    }

    public function bpjsInquiry(string $refId, string $itemCode, string $customerId, string $period): object
    {
        $params = [
            'uid' => $this->uid, 'pin' => $this->pin,
            'method' => 'rajabiller.' . RbMethod::BPJS_INQ,
            'kode_produk' => $itemCode,
            'periode' => $period,
            'idpel1' => $customerId,
            'ref1' => $refId,
        ];

        $response = $this->client->request('POST', $this->url, ['json' => $params]);

        return json_decode($response->getBody()->getContents());
    }

    public function bpjsPay(string $refId, string $itemCode, string $customerId, string $period): object
    {
        $content = $this->bpjsInquiry($refId, $itemCode, $customerId, $period);
        $status = $content->STATUS ?? '';

        if (! $status == RbConstant::SUCCESS_CODE) {
            return $content;
        }

        $params = [
            'uid' => $this->uid, 'pin' => $this->pin,
            'method' => 'rajabiller.' . RbMethod::BPJS_PAY,
            'kode_produk' => $itemCode,
            'idpel1' => $customerId,
            'periode' => $period,
            'ref1' => '',
            'ref2' => $content->REF2,
            'nominal' => $content->NOMINAL,
        ];

        $response = $this->client->request('POST', $this->url, ['json' => $params]);

        return json_decode($response->getBody()->getContents());
    }

    public function ccInquiry(string $refId, string $itemCode, string $customerId, int $nominal): object
    {
        $params = [
            'uid' => $this->uid, 'pin' => $this->pin,
            'method' => 'rajabiller.' . RbMethod::CC_INQ,
            'kode_produk' => $itemCode,
            'idpel1' => $customerId,
            'idpel2' => '',
            'idpel3' => '',
            'ref1' => $refId,
            'nominal' => $nominal,
        ];

        $response = $this->client->request('POST', $this->url, ['json' => $params]);

        return json_decode($response->getBody()->getContents());
    }

    public function ccPay(string $refId, string $itemCode, string $customerId, int $nominal): object
    {
        $content = $this->ccInquiry($refId, $itemCode, $customerId, $nominal);
        $status = $content->STATUS ?? '';

        if (! $status == RbConstant::SUCCESS_CODE) {
            return $content;
        }

        $params = [
            'uid' => $this->uid, 'pin' => $this->pin,
            'method' => 'rajabiller.' . RbMethod::CC_PAY,
            'kode_produk' => $itemCode,
            'idpel1' => $customerId,
            'idpel2' => '',
            'idpel3' => '',
            'ref1' => '',
            'ref2' => $content->REF2,
            'ref3' => '',
            'nominal' => $nominal,
        ];

        $response = $this->client->request('POST', $this->url, ['json' => $params]);

        return json_decode($response->getBody()->getContents());
    }

    public function transferInquiry(string $refId, string $itemCode, string $customerId, int $nominal, string $bankCode, string $phoneNo): object
    {
        if (! in_array($itemCode, [RbConstant::TRANSFER])) {
            return (object)['success' => false, 'message' => 'Invalid itemCode: ' . $itemCode];
        }

        $params = [
            'uid' => $this->uid, 'pin' => $this->pin,
            'method' => 'rajabiller.' . RbMethod::TRANSFER_INQ,
            'kode_produk' => $itemCode,
            'idpel1' => $customerId,
            'idpel2' => '',
            'idpel3' => '',
            'ref1' => $refId,
            'nominal' => $nominal,
            'kodebank' => $bankCode,
            'nomorhp' => $phoneNo,
        ];

        $response = $this->client->request('POST', $this->url, ['json' => $params]);

        return json_decode($response->getBody()->getContents());
    }

    public function transferPay(string $refId, string $itemCode, string $customerId, int $nominal, string $bankCode, string $phoneNo): object
    {
        $content = $this->transferInquiry($refId, $itemCode, $customerId, $nominal, $bankCode, $phoneNo);
        $status = $content->STATUS ?? '';

        if (! $status == RbConstant::SUCCESS_CODE) {
            return $content;
        }

        $params = [
            'uid' => $this->uid, 'pin' => $this->pin,
            'method' => 'rajabiller.' . RbMethod::TRANSFER_PAY,
            'kode_produk' => $itemCode,
            'idpel1' => $customerId,
            'idpel2' => '',
            'idpel3' => '',
            'ref1' => '',
            'ref2' => $content->REF2,
            'ref3' => '',
            'nominal' => $nominal,
            'kodebank' => $bankCode,
            'nomorhp' => $phoneNo,
        ];

        $response = $this->client->request('POST', $this->url, ['json' => $params]);

        return json_decode($response->getBody()->getContents());
    }
}
