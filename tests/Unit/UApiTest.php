<?php

namespace Unit;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use Laradocs\Uapi\Config;
use Laradocs\Uapi\Exceptions\HttpException;
use Laradocs\Uapi\UApi;
use PHPUnit\Framework\TestCase;
use Mockery;
use Mockery\Matcher\AnyArgs;
use Exception;

class UApiTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
    }

    public function testSmsWithSignException(): void
    {
        $client = Mockery::mock(Client::class);
        $client->allows()
            ->post(new AnyArgs())
            ->andThrow(new Exception('未添加短信签名', 0));

        $uapi = Mockery::mock(UApi::class, [new Config('xxx', 'xxx')])->makePartial();
        $uapi->shouldReceive('client')->andReturn($client);

        $this->expectException(HttpException::class);
        $this->expectExceptionMessage('未添加短信签名');

        $uapi->sms([
            'mobile' => '13888888888',
            'content' => '测试短信内容',
        ]);
    }

    public function testSms(): void
    {
        $uapi = Mockery::mock(UApi::class, [new Config('xxx', 'xxx')])->makePartial();
        $uapi->shouldReceive('client')->andReturn($this->client());
        $data = $uapi->sms([
            'mobile' => '13888888888',
            'content' => '测试短信内容',
        ]);

        $this->assertEmpty($data);
    }

    public function testIdcard(): void
    {
        $uapi = Mockery::mock(UApi::class, [new Config('xxx', 'xxx')])->makePartial();
        $uapi->shouldReceive('client')->andReturn($this->client());
        $data = $uapi->idcard([
            'cardno' => 'xxx',
            'name' => 'xxx',
        ]);

        $this->assertNotEmpty($data);
        $this->assertSame('1993-01-16', $data['birthday']);
    }

    public function testBankaps(): void
    {
        $uapi = Mockery::mock(UApi::class, [new Config('xxx', 'xxx')])->makePartial();
        $uapi->shouldReceive('client')->andReturn($this->client());
        $data = $uapi->bankaps([
            'card' => 'xxx',
            'province' => 'xxx',
            'city' => 'xxx',
            'key' => ''
        ]);

        $this->assertNotEmpty($data);
        $this->assertSame('309421001012', $data['bankCode']);
    }

    public function testBank3Check(): void
    {
        $uapi = Mockery::mock(UApi::class, [new Config('xxx', 'xxx')])->makePartial();
        $uapi->shouldReceive('client')->andReturn($this->client());
        $data = $uapi->bank3Check([
            'accountNo' => 'xxx',
            'idCard' => 'xxx',
            'name' => 'xxx',
        ]);

        $this->assertNotEmpty($data);
        $this->assertSame('01', $data['status']);
    }

    public function testExpress(): void
    {
        $uapi = Mockery::mock(UApi::class, [new Config('xxx', 'xxx')])->makePartial();
        $uapi->shouldReceive('client')->andReturn($this->client());
        $data = $uapi->express([
            'no' => '75141039665226',
            'type' => 'zto',
        ]);

        $this->assertNotEmpty($data);
        $this->assertSame(1, $data['status']);
    }

    public function testQueryBankInfo(): void
    {
        $uapi = Mockery::mock(UApi::class, [new Config('xxx', 'xxx')])->makePartial();
        $uapi->shouldReceive('client')->andReturn($this->client());
        $data = $uapi->queryBankInfo([
            'bankcard' => 'xxx',
        ]);

        $this->assertNotEmpty($data);
        $this->assertSame('123456', $data['cardprefixnum']);
    }

    public function testGgetExpressList(): void
    {
        $uapi = Mockery::mock(UApi::class, [new Config('xxx', 'xxx')])->makePartial();
        $uapi->shouldReceive('client')->andReturn($this->client());
        $data = $uapi->getExpressList([
            'type' => 'ZTO'
        ]);

        $this->assertNotEmpty($data);
        $this->assertSame('中通快递', $data['ZTO']);
    }

    public function testBank4Check(): void
    {
        $uapi = Mockery::mock(UApi::class, [new Config('xxx', 'xxx')])->makePartial();
        $uapi->shouldReceive('client')->andReturn($this->client());
        $data = $uapi->bank4Check([
            'accountNo' => 'xxx',
            'idCard' => 'xxx',
            'name' => 'xxx',
            'mobile' => 'xxx',
        ]);

        $this->assertNotEmpty($data);
        $this->assertSame('01', $data['status']);
    }

    protected function client()
    {
        $client = Mockery::mock(Client::class);
        $client->shouldReceive('post')->withAnyArgs()->andReturnUsing(function ($url) {
            if (str_contains($url, 'sms')) {
                $json = file_get_contents(__DIR__.'/../json/sms.json');
            }
            if (str_contains($url, 'idcard')) {
                $json = file_get_contents(__DIR__.'/../json/idcard.json');
            }
            if (str_contains($url, 'bankaps')) {
                $json = file_get_contents(__DIR__.'/../json/bankaps.json');
            }
            if (str_contains($url, 'bank3Check')) {
                $json = file_get_contents(__DIR__.'/../json/bank3Check.json');
            }
            if (str_contains($url, 'express')) {
                $json = file_get_contents(__DIR__.'/../json/express.json');
            }
            if (str_contains($url, 'queryBankInfo')) {
                $json = file_get_contents(__DIR__.'/../json/queryBankInfo.json');
            }
            if (str_contains($url, 'getExpressList')) {
                $json = file_get_contents(__DIR__.'/../json/getExpressList.json');
            }
            if (str_contains($url, 'bank4Check')) {
                $json = file_get_contents(__DIR__.'/../json/bank4Check.json');
            }

            return new Response(body: $json);
        });

        return $client;
    }
}