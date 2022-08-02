<?php

namespace Unit;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use Laradocs\Uapi\Config;
use Laradocs\Uapi\Exceptions\HttpException;
use Laradocs\Uapi\Exceptions\MissingArgumentException;
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

        $uapi = Mockery::mock(UApi::class.'[client]', [new Config('123', '123')]);
        $uapi->shouldReceive('client')->andReturn($client);

        $this->expectException(HttpException::class);
        $this->expectExceptionMessage('未添加短信签名');

        $uapi->sms([
            'mobile' => '13888888888',
            'content' => '测试短信内容',
        ]);
    }

    public function testSmsWithParamsException(): void
    {
        $this->expectException(MissingArgumentException::class);
        $this->expectExceptionMessage('The mobile parameter is required');

        $uapi = new UApi(new Config('123', '123'));
        $uapi->sms([
            'xxx' => 'xxx',
        ]);
    }

    public function testSms(): void
    {
        $uapi = Mockery::mock(UApi::class.'[client]', [new Config('mockery-agentId', 'mockery-secretKey')]);
        $uapi->shouldReceive('client')->andReturn($this->client());
        $data = $uapi->sms([
            'mobile' => '13888888888',
            'content' => '测试短信内容',
        ]);

        $this->assertEmpty($data);
    }

    protected function client()
    {
        $client = Mockery::mock(Client::class);
        $client->shouldReceive('post')->withAnyArgs()->andReturnUsing(function ($url) {
            if (str_contains($url, 'sms')) {
                $json = file_get_contents(__DIR__.'/../json/sms.json');
            }

            return new Response(body: $json);
        });

        return $client;
    }
}