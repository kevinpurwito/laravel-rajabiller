<?php

namespace Kevinpurwito\LaravelRajabiller;

use GuzzleHttp\Client;
use Kevinpurwito\LaravelRajabiller\Models\RbItem;
use Kevinpurwito\LaravelRajabiller\Models\RbOrder;
use Psr\Http\Message\ResponseInterface;

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
            $response = $this->item($item->code);
            $content = json_decode($response->getBody()->getContents());
            $status = $content->STATUS ?? '';

            if ($status !== '00') {
                continue;
            }

            // $item->name = $content->PRODUK; // use the name from seeders instead
            $item->price = $content->HARGA;
            $item->admin_fee = $content->ADMIN;
            $item->commission = $content->KOMISI;
            $item->status = ($content->STATUS_PRODUK == 'AKTIF');
            $item->save();
        }
    }

    public function populateItemsH2H()
    {
        $items = RbItem::all();

        foreach ($items as $item) {
            $code = $item->code . 'H';
            $response = $this->item($code);
            $content = json_decode($response->getBody()->getContents());
            $status = $content->STATUS ?? '';

            if ($status !== '00') {
                continue;
            }

            $item->code = $code;
            $item->price = $content->HARGA;
            $item->admin_fee = $content->ADMIN;
            $item->commission = $content->KOMISI;
            $item->status = ($content->STATUS_PRODUK == 'AKTIF');
            $item->save();
        }
    }

    public function getBalance(): int
    {
        $response = $this->balance();

        return json_decode($response->getBody()->getContents())->SALDO ?? 0;
    }

    public function balance(): ResponseInterface
    {
        $params = [
            'uid' => $this->uid, 'pin' => $this->pin,
            'method' => 'rajabiller.balance',
        ];

        return $this->client->request('POST', $this->url, ['json' => $params]);
    }

    /**
     * @param string|null $date | Y-m-d, ex: 2021-08-01
     * @param RbOrder|null $order
     * @return ResponseInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function orders($date = null, RbOrder $order = null): ResponseInterface
    {
        // Max Range only 1 day
        $date = $date ?? date('Y-m-d');
        $startDate = date_format(date_create_from_format('Y-m-d', $date), 'Ymd') . '000000';
        $endDate = date_format(date_create_from_format('Y-m-d', $date), 'Ymd') . '235959';

        $transId = $order->ref_2 ?? '';
        $customerId = $order->customer_id_1 ?? '';
        $itemId = $order->item_code ?? '';

        $params = [
            'uid' => $this->uid, 'pin' => $this->pin,
            'method' => 'rajabiller.datatransaksi',
            'tgl1' => $startDate,
            'tgl2' => $endDate,
            'id_transaksi' => $transId,
            'id_produk' => $itemId,
            'idpel' => $customerId,
            'limit' => '',
        ];

        return $this->client->request('POST', $this->url, ['json' => $params]);
    }

    public function groupItems(string $groupCode): ResponseInterface
    {
        $params = [
            'uid' => $this->uid, 'pin' => $this->pin,
            'method' => 'rajabiller.harga',
            'produk' => $groupCode,
        ];

        return $this->client->request('POST', $this->url, ['json' => $params]);
    }

    public function item(string $code): ResponseInterface
    {
        $params = [
            'uid' => $this->uid, 'pin' => $this->pin,
            'method' => 'rajabiller.info_produk',
            'kode_produk' => $code,
        ];

        return $this->client->request('POST', $this->url, ['json' => $params]);
    }

    /**
     * @param string $refId | Reference or Transaction Id
     * @param string $itemCode
     * @param string $customerId
     * for Mobile Legend, use Mobile Legend Id instead
     * for E-Toll, use E-Toll Number instead
     * for Other Items, use Customer's Phone Number
     * @param string $method | 'pulsa' or 'game'
     */
    public function purchase(string $refId, string $itemCode, string $customerId, string $method = 'pulsa'): ResponseInterface
    {
//        if (! in_array($method, ['pulsa', 'game'])) {
//            return (object)['sucess' => false, 'message' => 'Invalid method $method'];
//        }

        $params = [
            'uid' => $this->uid, 'pin' => $this->pin,
            'method' => 'rajabiller.' . $method,
            'kode_produk' => $itemCode,
            'no_hp' => $customerId,
            'ref1' => $refId,
        ];

        return $this->client->request('POST', $this->url, ['json' => $params]);
    }

    /**
     * @param string $refId
     * @param string $itemCode
     * @param string $customerId
     * @param string $areaCode | required for $itemCode = TELEPON, otherwise not needed
     */
    public function inquiry(string $refId, string $itemCode, string $customerId, string $areaCode = ''): ResponseInterface
    {
        $params = [
            'uid' => $this->uid, 'pin' => $this->pin,
            'method' => 'rajabiller.inq',
            'kode_produk' => $itemCode,
            'idpel1' => $customerId,
            'idpel2' => '',
            'idpel3' => '',
            'ref1' => $refId,
        ];

        if ($itemCode == 'TELEPON' && $areaCode) {
            $params['idpel1'] = $areaCode;
            $params['idpel2'] = $customerId;
        }

        return $this->client->request('POST', $this->url, ['json' => $params]);
    }

    public function pay(string $refId, string $itemCode, string $customerId, string $method = 'paydetail', string $areaCode = ''): ResponseInterface
    {
//        if (! in_array($method, ['paydetail', 'pay'])) {
//            return (object)['sucess' => false, 'message' => 'Invalid method $method'];
//        }

        $response = $this->inquiry($refId, $itemCode, $customerId, $areaCode);
        $content = json_decode($response->getBody()->getContents());
        $status = $content->STATUS ?? '';

        if (! $status == '00') {
            return $response;
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

        if ($itemCode == 'TELEPON' && $areaCode) {
            $params['idpel1'] = $areaCode;
            $params['idpel2'] = $customerId;
        }

        return $this->client->request('POST', $this->url, ['json' => $params]);
    }

    public function bpjsInquiry(string $refId, string $itemCode, string $customerId, string $period): ResponseInterface
    {
        $params = [
            'uid' => $this->uid, 'pin' => $this->pin,
            'method' => 'rajabiller.bpjsinq',
            'kode_produk' => $itemCode,
            'periode' => $period,
            'idpel1' => $customerId,
            'ref1' => $refId,
        ];

        return $this->client->request('POST', $this->url, ['json' => $params]);
    }

    public function bpjsPay(string $refId, string $itemCode, string $customerId, string $period): ResponseInterface
    {
        $response = $this->bpjsInquiry($refId, $itemCode, $customerId, $period);
        $content = json_decode($response->getBody()->getContents());
        $status = $content->STATUS ?? '';

        if (! $status == '00') {
            return $response;
        }

        $params = [
            'uid' => $this->uid, 'pin' => $this->pin,
            'method' => 'rajabiller.bpjspay',
            'kode_produk' => $itemCode,
            'idpel1' => $customerId,
            'periode' => $period,
            'ref1' => '',
            'ref2' => $content->REF2,
            'nominal' => $content->NOMINAL,
        ];

        return $this->client->request('POST', $this->url, ['json' => $params]);
    }
}
