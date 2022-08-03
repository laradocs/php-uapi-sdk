<?php

namespace Laradocs\Uapi;

class Config
{
    /**
     * 商户号
     *
     * @var string
     */
    protected string $agentId;

    /**
     * 商户秘钥
     *
     * @var string
     */
    protected string $secretKey;

    /**
     * 请求的 URL
     *
     * @var string
     */
    protected string $baseUri;

    public function __construct(string $agentId, string $secretKey, string $baseUri)
    {
        $this->agentId = $agentId;
        $this->secretKey = $secretKey;
        $this->baseUri = $baseUri;
    }

    public function getAgentId(): string
    {
        return $this->agentId;
    }

    public function getSecretKey(): string
    {
        return $this->secretKey;
    }

    public function getBaseUri(): string
    {
        return $this->baseUri;
    }
}
