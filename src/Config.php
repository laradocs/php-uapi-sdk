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

    public function __construct(string $agentId, string $secretKey)
    {
        $this->agentId = $agentId;
        $this->secretKey = $secretKey;
    }

    public function getAgentId(): string
    {
        return $this->agentId;
    }

    public function getSecretKey(): string
    {
        return $this->secretKey;
    }
}
