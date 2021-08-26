<?php

namespace Kevinpurwito\LaravelRajabiller\Tests\Unit;

use GuzzleHttp\Psr7\Response;
use Kevinpurwito\LaravelRajabiller\RajabillerFacade as Rajabiller;
use Kevinpurwito\LaravelRajabiller\Tests\TestCase;

class RajabillerTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    /** @test */
    public function it_has_credentials()
    {
        config([
            'kp_rajabiller.env' => 'dev',

            'kp_rajabiller.uid' => 'mock_uid',

            'kp_rajabiller.pin' => 'mock_pin',
        ]);

        $this->assertTrue(config('kp_rajabiller.env') == 'dev');
        $this->assertTrue(config('kp_rajabiller.uid') == 'mock_uid');
        $this->assertTrue(config('kp_rajabiller.pin') == 'mock_pin');
    }

    // on `prod` env, these tests can only be done from whitelisted IP addresses

    /** @test */
    public function it_has_balance()
    {
        $this->markTestIncomplete('This test has passed, skipping to conserve Rajabiller balance.');

        $balance = Rajabiller::getBalance();
        $this->assertTrue(is_numeric($balance));
    }

    /** @test */
    public function it_can_purchase()
    {
        $this->markTestIncomplete('This test has passed, skipping to conserve Rajabiller balance.');

        $refId = date('ymd') . '_pcs_' . '01';
        $itemCode = env('KP_RB_TEST_PURCHASE_CODE');
        $customerId = env('KP_RB_TEST_PURCHASE_ID');

        /** @var Response $response */
        $response = Rajabiller::purchase($refId, $itemCode, $customerId);
        $this->assertTrue($response->getStatusCode() == 200);

        $content = json_decode($response->getBody()->getContents());
        var_dump($content);
        $this->assertTrue($content->STATUS == '00');
        $this->assertTrue($content->KET == 'APPROVE');
    }

    /** @test */
    public function it_can_inquiry()
    {
        $this->markTestIncomplete('This test has passed, skipping to conserve Rajabiller balance.');

        $refId = date('ymd') . '_inq_' . '01';
        $itemCode = env('KP_RB_TEST_INQUIRY_CODE');
        $customerId = env('KP_RB_TEST_INQUIRY_ID');

        /** @var Response $response */
        $response = Rajabiller::inquiry($refId, $itemCode, $customerId);
        $this->assertTrue($response->getStatusCode() == 200);

        $content = json_decode($response->getBody()->getContents());
        $this->assertTrue($content->STATUS == '00');
        $this->assertTrue($content->KET == 'APPROVE');
    }

    /** @test */
    public function it_can_pay()
    {
        $this->markTestIncomplete('This test has passed, skipping to conserve Rajabiller balance.');

        $refId = date('ymd') . '_inq_' . '01';
        $itemCode = env('KP_RB_TEST_INQUIRY_CODE');
        $customerId = env('KP_RB_TEST_INQUIRY_ID');

        /** @var Response $response */
        $response = Rajabiller::pay($refId, $itemCode, $customerId);
        $this->assertTrue($response->getStatusCode() == 200);

        $content = json_decode($response->getBody()->getContents());
        $this->assertTrue($content->STATUS == '00');
        $this->assertTrue($content->KET == 'APPROVE');
    }

    /** @test */
    public function it_can_bpjs_inquiry()
    {
        $this->markTestIncomplete('This test has passed, skipping to conserve Rajabiller balance.');

        $refId = date('ymd') . '_bpjs_' . '01';
        $itemCode = env('KP_RB_TEST_BPJS_CODE');
        $customerId = env('KP_RB_TEST_BPJS_ID');
        $period = env('KP_RB_TEST_BPJS_PERIOD');

        /** @var Response $response */
        $response = Rajabiller::bpjsInquiry($refId, $itemCode, $customerId, $period);
        $this->assertTrue($response->getStatusCode() == 200);

        $content = json_decode($response->getBody()->getContents());
        $this->assertTrue($content->STATUS == '00');
        $this->assertTrue($content->KET == 'APPROVE');
    }

    /** @test */
    public function it_can_bpjs_pay()
    {
        $this->markTestIncomplete('This test has passed, skipping to conserve Rajabiller balance.');

        $refId = date('ymd') . '_bpjs_' . '01';
        $itemCode = env('KP_RB_TEST_BPJS_CODE');
        $customerId = env('KP_RB_TEST_BPJS_ID');
        $period = env('KP_RB_TEST_BPJS_PERIOD');

        /** @var Response $response */
        $response = Rajabiller::bpjsPay($refId, $itemCode, $customerId, $period);
        $this->assertTrue($response->getStatusCode() == 200);

        $content = json_decode($response->getBody()->getContents());
        $this->assertTrue($content->STATUS == '00');
        $this->assertTrue($content->KET == 'APPROVE');
    }
}
