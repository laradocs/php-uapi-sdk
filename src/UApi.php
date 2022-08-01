<?php

namespace Laradocs\Uapi;

use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use Laradocs\Uapi\Exceptions\HttpException;
use Laradocs\Uapi\Exceptions\MissingArgumentException;
use Laradocs\Uapi\Traits\HasSignature;
use Exception;
use Laradocs\Uapi\Utils\Json;

class UApi
{
    use HasSignature;

    protected Config $config;

    protected $baseUri = 'http://uapis.ten.sdream.top/apis/api';

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    protected function client(): Client
    {
        return new Client([
            'base_uri' => $this->baseUri,
            'timeout' => 1.5,
        ]);
    }

    /**
     * 实名认证
     *
     * @param  string  $cardno  身份证号码
     * @param  string  $name  姓名
     * @return array
     */
    public function idcard(array $params): array
    {
        $this->checkRequireParameters(['cardno', 'name'], $params);
        try {
            $json = $this->client()
                ->post('idcard', [
                    RequestOptions::JSON => [
                        'biz_content' => [
                            'cardno' => $params['cardno'],
                            'name' => $params['name'],
                        ],
                        'agent_id' => $this->config->getAgentId(),
                        'sign' => $this->sign($params),
                    ]
                ])->getBody()
                ->getContents();
            $data = Json::decode($json);

            return $data['code'] ? $data['data'] : throw new Exception($data['msg'], $data['code']);
        } catch (Exception $e) {
            throw new HttpException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * 验证参数
     *
     * @param  array  $required
     * @param  array  $params
     * @return void
     * @throws MissingArgumentException
     */
    protected function checkRequireParameters(array $required, array $params): void
    {
        foreach ($required as $req) {
            if (! isset($params[$req])) {
                throw new MissingArgumentException(
                    sprintf('The %s parameter is required.', $req)
                );
            }
        }
    }
}
