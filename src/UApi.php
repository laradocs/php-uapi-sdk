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

    /**
     * @var Config
     */
    protected Config $config;

    /**
     * @var string
     */
    protected $baseUri = 'http://uapis.ten.sdream.top/apis/api';

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    public function client(): Client
    {
        return new Client([
            'base_uri' => $this->baseUri,
            'timeout' => 1.5,
        ]);
    }

    /**
     * 发送短信
     *
     * @param  string  $mobile
     * @param  string  $content
     * @return array
     * @throws MissingArgumentException
     * @throws HttpException
     */
    public function sms(array $params): array
    {
        $this->checkRequireParameters(['mobile', 'content'], $params);
        try {
            $json = $this->client()
                ->post('sms', [
                    RequestOptions::JSON => [
                        'biz_content' => [
                            'mobile' => $params['mobile'],
                            'content' => $params['content'],
                        ],
                        'agent_id' => $this->config->getAgentId(),
                        'sign' => $this->sign($params),
                    ]
                ])->getBody()
                ->getContents();

            return $this->body($json);
        } catch (Exception $e) {
            throw new HttpException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * 实名认证
     *
     * @param  string  $cardno  身份证号码
     * @param  string  $name  姓名
     * @return array
     * @throws MissingArgumentException
     * @throws HttpException
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
                    ]
                )->getBody()
                ->getContents();

            return $this->body($json);
        } catch (Exception $e) {
            throw new HttpException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * 联行号查询
     *
     * @param  string  $card  银行卡号
     * @param  string  $province  省份
     * @param  string  $city  城市名
     * @param  string  $key  关键字(非必填)
     * @return array
     * @throws MissingArgumentException
     * @throws HttpException
     */
    public function bankaps(array $params): array
    {
        $this->checkRequireParameters(['province', 'city', 'card', 'key'], $params);
        try {
            $json = $this->client()
                ->post('bankaps', [
                        RequestOptions::JSON => [
                            'biz_content' => [
                                'card' => $params['card'],
                                'province' => $params['province'],
                                'city' => $params['city'],
                                'key' => $params['key'],
                            ],
                            'agent_id' => $this->config->getAgentId(),
                            'sign' => $this->sign($params),
                        ]
                    ]
                )->getBody()
                ->getContents();

            return $this->body($json);
        } catch (Exception $e) {
            throw new HttpException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * 银行卡三要素认证
     *
     * @param  string  $accountNo  银行卡号
     * @param  string  $idCard  身份证号
     * @param  string  $name  姓名
     * @return array
     * @throws MissingArgumentException
     * @throws HttpException
     */
    public function bank3Check(array $params): array
    {
        $this->checkRequireParameters(['accountNo', 'idCard', 'name'], $params);
        try {
            $json = $this->client()
                ->post('bank3Check', [
                    RequestOptions::JSON => [
                        'biz_content' => [
                            'accountNo' => $params['accountNo'],
                            'idCard' => $params['idCard'],
                            'name' => $params['name'],
                        ],
                        'agent_id' => $this->config->getAgentId(),
                        'sign' => $this->sign($params),
                    ]
                ])->getBody()
                ->getContents();

            return $this->body($json);
        } catch (Exception $e) {
            throw new HttpException($e->getMessage(), $e->getCode(), $e);
        }
    }


    /**
     * 物流查询
     *
     * @param  string  $no  快递单号
     * @param  string  $type  快递公司代码
     * @return array
     * @throws MissingArgumentException
     * @throws HttpException
     */
    public function express(array $params): array
    {
        $this->checkRequireParameters(['no', 'type'], $params);
        try {
            $json = $this->client()
                ->post('express', [
                    RequestOptions::JSON => [
                        'biz_content' => [
                            'no' => $params['no'],
                            'type' => $params['type'],
                        ],
                        'agent_id' => $this->config->getAgentId(),
                        'sign' => $this->sign($params),
                    ]
                ])->getBody()
                ->getContents();

            return $this->body($json);
        } catch (Exception $e) {
            throw new HttpException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * 银行卡信息查询
     *
     * @param  string  $bankcard
     * @return array
     * @throws MissingArgumentException
     * @throws HttpException
     */
    public function queryBankInfo(array $params): array
    {
        try {
            $json = $this->client()
                ->post('queryBankInfo', [
                    RequestOptions::JSON => [
                        'biz_content' => [
                            'bankcard' => $params['bankcard'] ?? '',
                        ],
                        'agent_id' => $this->config->getAgentId(),
                        'sign' => $this->sign($params),
                    ]
                ])->getBody()
                ->getContents();
            return $this->body($json);
        } catch (Exception $e) {
            throw new HttpException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * 获取快递公司信息
     *
     * @param  string  $type  快递公司代码（不传为获取快递公司列表）
     * @return array
     * @throws MissingArgumentException
     * @throws HttpException
     */
    public function getExpressList(array $params): array
    {
        $this->checkRequireParameters(['type'], $params);
        try {
            $json = $this->client()
                ->post('getExpressList', [
                    RequestOptions::JSON => [
                        'biz_content' => [
                            'type' => $params['type'],
                        ],
                        'agent_id' => $this->config->getAgentId(),
                        'sign' => $this->sign($params),
                    ]
                ])->getBody()
                ->getContents();
            return $this->body($json);
        } catch (Exception $e) {
            throw new HttpException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * 银行卡四要素认证
     *
     * @param  string  $accountNo  银行卡号
     * @param  string  $idCard  身份证号
     * @param  string  $name  姓名
     * @param  string  $mobile  手机号码
     * @return array
     * @throws MissingArgumentException
     * @throws HttpException
     */
    public function bank4Check(array $params): array
    {
        $this->checkRequireParameters(['accountNo', 'idCard', 'name', 'mobile'], $params);
        try {
            $json = $this->client()
                ->post('bank4Check', [
                    RequestOptions::JSON => [
                        'biz_content' => [
                            'accountNo' => $params['accountNo'],
                            'idCard' => $params['idCard'],
                            'name' => $params['name'],
                            'mobile' => $params['mobile'],
                        ],
                        'agent_id' => $this->config->getAgentId(),
                        'sign' => $this->sign($params),
                    ]
                ])->getBody()
                ->getContents();
            return $this->body($json);
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

    /**
     * 返回响应体
     *
     * @param  string  $json
     * @return array
     */
    public function body(string $json): array
    {
        $data = Json::decode($json);

        return $data['code']
            ? $data['data'] ?? []
            : throw new Exception($data['msg'], $data['code']);
    }
}
