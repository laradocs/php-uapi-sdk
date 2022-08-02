<?php

namespace Laradocs\Uapi\Traits;

use Laradocs\Uapi\Utils\Json;

trait HasSignature
{
    public function sign(array $params): string
    {
        return md5(
            sprintf('%s%s', $this->config->getSecretKey(), $this->comm($params))
        );
    }

    public function comm(array $params): string
    {
        $url = '';
        foreach ($params as $key => $param) {
            unset($params[$key]);
            if (is_array($param)) {
                $param = Json::encode($param);
            }
            $url .= "{$key}={$param}&";
        }
        $url = rtrim($url);

        return $url;
    }
}
