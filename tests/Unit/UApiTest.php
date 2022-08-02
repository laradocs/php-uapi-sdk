<?php

namespace Unit;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use Laradocs\Uapi\Config;
use Laradocs\Uapi\Exceptions\HttpException;
use Laradocs\Uapi\UApi;
use PHPUnit\Framework\TestCase;
use Mockery;

class UApiTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
    }

    public function testSmsWithSignException(): void
    {
        $this->expectException(HttpException::class);
        $this->expectExceptionMessage('未添加短信签名');

        $client = Mockery::mock(UApi::class . '[client]', [new Config('mockery-agentId', 'mockery-secretKey')]);
        $client->shouldReceive('client')->andReturn($this->client());
        $client->sms([
            'mobile' => '13888888888',
            'content' => 'mockery-content',
        ]);
    }

    protected function client()
    {
        $client = Mockery::mock(Client::class);
        $client->shouldReceive('post')->withAnyArgs()->andReturnUsing(function ($url) {
            if (str_contains($url, 'sms')) {
                $json = file_get_contents(__DIR__ . '/../json/sms.json');
            }

            return new Response(body: $json);
        });

        return $client;
    }
}