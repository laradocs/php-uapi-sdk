# UApi SDK

[![Total Downloads](https://poser.pugx.org/laradocs/uapi/d/total.svg)](https://packagist.org/packages/laradocs/uapi)
[![Latest Stable Version](https://poser.pugx.org/laradocs/uapi/v/stable.svg)](https://packagist.org/packages/laradocs/uapi)
[![Latest Unstable Version](https://poser.pugx.org/laradocs/uapi/v/unstable.svg)](https://packagist.org/packages/laradocs/uapi)
[![License](https://poser.pugx.org/laradocs/uapi/license.svg)](https://packagist.org/packages/laradocs/uapi)
[![Test](https://github.com/laradocs/php-uapi-sdk/actions/workflows/test.yml/badge.svg)](https://github.com/laradocs/php-uapi-sdk/actions/workflows/test.yml)



## PHP 版本

PHP 需要 8.0 或以上版本

## 安装

```bash
composer require laradocs/uapi
```

## 用法

```php
use Laradocs\Uapi\UApi;
use Laradocs\Uapi\Config;

$uapi = new UApi(new Config('agentId', 'secretKey', 'https://www.example.com'));
```

参数说明

> * agentId - 商户 ID
> * secretKey - 商户密钥

### 发送短信

```php
$response = $uapi->sms([
    'mobile' => '13888888888',
    'content' => '测试短信内容',
]);
```

#### 参数说明

> * mobile - 手机号码
> * content - 短信内容

返回示例

```json
{}
```

### 实名认证

```php
$response = $uapi->idcard([
    'cardno' => '37285200010189382',
    'name' => 'Tall Libra',
]);
```

#### 参数说明

> * cardno - 身份证号码
> * name - 姓名

返回示例

```json
{
  "sex": "男",
  "address": "江西省-九江市-湖口县",
  "birthday": "2000-10-18"
}
```

### 联行号查询

```php
$response = $uapi->bankaps([
    'card' => '764722938483920007',
    'province' => '江西省',
    'city' => '南昌市',
    'key' => '',
]);
```

#### 参数说明

> * card - 银行卡号
> * province - 省份
> * city - 城市
> * key - 关键字(可为空)

返回示例

```json
{
  "addr": "南昌市叠山路119号天河大厦",
  "bank": "兴业银行",
  "city": "南昌市",
  "province": "江西省",
  "bankCode": "1234567890",
  "lName": "兴业银行南昌分行",
  "tel": "0791-86887081"
}
```

### 银行卡三要素认证

```php
$response = $uapi->bank3Check([
    'accountNo' => '764722938483920007',
    'idCard' => '37285200010189382',
    'name' => 'Tall Libra',
]);
```

#### 参数说明

> * accountNo - 银行卡号
> * idCard - 身份证号
> * name - 姓名

返回示例

```json
{
  "status": "01",
  "msg": "验证通过",
  "idCard": "37285200010189382",
  "accountNo": "764722938483920007",
  "bank": "兴业银行",
  "cardName": "兴业卡(银联标准卡)",
  "cardType": "借记卡",
  "name": "Tall Libra",
  "sex": "男",
  "area": "湖北省黄冈市蕲春县",
  "province": "湖北省",
  "city": "黄冈市",
  "prefecture": "蕲春县",
  "birthday": "1970-01-01",
  "addrCode": "0000000",
  "lastCode": "9"
}
```

### 物流查询

```php
$response = $uapi->express([
    'no' => '75141039665226',
    'type' => 'zto',
]);
```

#### 参数说明

> * no - 快递单号
> * type - 快递公司代码

返回示例

```json
{
  "status": 1,
  "data": {
    "number": "75141039665226",
    "type": "zto",
    "list": [
      {
        "time": "2019-04-15 08:40:50",
        "status": "【南昌市】  已签收, 签收人凭取货码签收, 如有疑问请电联: 15070082552 / 4006406999, 您的快递已经妥投。风里来雨里去, 只为客官您满意。上有老下有小, 赏个好评好不好？【请在评价快递员处帮忙点亮五颗星星哦~】"
      }
    ],
    "deliverystatus": "3",
    "issign": "1",
    "expName": "中通快递",
    "expSite": "www.zto.com",
    "expPhone": "95311",
    "logo": "http://img3.fegine.com/express/zto.jpg",
    "courier": "",
    "courierPhone": ""
  }
}
```

### 银行卡信息查询

```php
$response = $uapi->queryBankInfo([
    'bankcard' => '37285200010189382',
]);
```

#### 参数说明

> * bankcard - 银行卡号

返回示例

```json
 {
  "bankname": "兴业银行",
  "banknum": "665226",
  "cardprefixnum": "372852",
  "cardname": "兴业卡(银联标准卡)",
  "cardtype": "银联借记卡",
  "cardprefixlength": 6,
  "isLuhn": true,
  "iscreditcard": 1,
  "cardlength": 18,
  "bankurl": "http://www.cib.com.cn/",
  "enbankname": "Industrial Bank",
  "abbreviation": "CIB",
  "bankimage": "http://auth.apis.la/bank/11_CIB.png",
  "servicephone": "95561",
  "province": "江西省",
  "city": "南昌市"
}
```

### 获取快递公司信息

```php
$response = $uapi->getExpressList([
    'type' => '',
]);
```

#### 参数说明

> * type - 快递公司代码(可为空)

返回示例

```json
{
  "ZTO": "中通快递",
  "YTO": "圆通快递",
  ....
}
```

### 银行卡四要素认证

```php
$response = $uapi->bank4Check([
    'accountNo' => '37285200010189382',
    'idCard' => '37285200010189382',
    'name' => 'Tall Libra',
    'mobile' => '13888888888',
]);
```

#### 参数说明

> * accountNo - 银行卡号
> * idCard - 身份证号
> * name - 姓名
> * mobile - 手机号码

返回示例

```json
{
  "status": "01",
  "msg": "验证通过",
  "idCard": "37285200010189382",
  "accountNo": "764722938483920007",
  "bank": "兴业银行",
  "cardName": "兴业卡(银联标准卡)",
  "cardType": "借记卡",
  "name": "Tall Libra",
  "sex": "男",
  "area": "湖北省黄冈市蕲春县",
  "province": "湖北省",
  "city": "黄冈市",
  "prefecture": "蕲春县",
  "birthday": "1970-01-01",
  "addrCode": "0000000",
  "lastCode": "9"
}
```
