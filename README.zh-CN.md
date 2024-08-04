# Unique Code Library

[简体中文](README.zh-CN.md) | [English](README.md)

## 单元测试

```sh
PHPUnit 9.6.0 by Sebastian Bergmann and contributors.

Runtime:       PHP 7.4
Configuration: /var/www/packages/unique-code/phpunit.xml

Functions (NiceYu\Tests\Functions)
 ✔ Unique encode [1.34 ms]
 ✔ Unique decode [17.30 ms]

Unique Code (NiceYu\Tests\UniqueCode)
 ✔ Getters [0.17 ms]
 ✔ Encode decode consistency [0.21 ms]
 ✔ Encode with max length [3.46 ms]
 ✔ Encode exceeding max [1.02 ms]
 ✔ Invalid decode [0.13 ms]
 ✔ Set seed number [0.15 ms]
 ✔ Set dictionaries [1.03 ms]
 ✔ Set complement [0.13 ms]
 ✔ Set max [0.12 ms]

Time: 00:00.040, Memory: 8.00 MB

OK (11 tests, 20 assertions)
```

## 默认 6位

1. 一般来说 6位数 已经足够大型项目使用
2. 如果你有需要, 可以修改为 7位数 比如用于订单的计算, 也是可以的

| 位数 | 最大值            | 计算方法   |
|----|----------------|--------|
| 5  | 24,300,000     | 30^5次方 |
| 6  | 729,000,000    | 30^6次方 |
| 7  | 21,870,000,000 | 30^7次方 |

## 安装

### 系统要求
- PHP >= 7.4

### 安装 Composer

如果你还没有安装 Composer，请先安装：
```sh
curl -sS https://getcomposer.org/installer | php
mv composer.phar /usr/local/bin/composer
```

### 安装 Library

```sh
composer require nice-yu/unique-code
```

## 参数说明

| 参数名          | 默认值      | 说明                               |
|--------------|----------|----------------------------------|
| seed_number  | MD5      | 打乱字典时的种子数, 默认使用系统信息进行MD5取值       |
| dictionaries | 0-9, A-Z | 字典信息, 默认删除了 0 O 1 I 和作为分割字符的 Y Z |
| complement   | Y-Z      | 分割字符                             |
| max          | 6        | 生成位数                             |

## 默认参数

- 设置时可以采用连贯方式设置

```php
use NiceYu\UniqueCode\UniqueCode;

UniqueCode::setSeedNumber(0)
    ->setDictionaries([
        '2', '3', '4', '5', '6', '7', '8', '9',
        'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H',
        'J', 'K', 'L', 'M', 'N', 'P', 'Q', 'R',
        'S', 'T', 'U', 'V', 'W', 'X'
    ])
    ->setComplement(['Y', 'Z'])
    ->setMax(6);
```

## 获取参数

```php
use NiceYu\UniqueCode\UniqueCode;

UniqueCode::getSeedNumber();
UniqueCode::getDictionaries();
UniqueCode::getComplement();
UniqueCode::getMax();
```

## 获取唯一编码

- 注意设定的参数, 如果超出最大值, 会返回 `null`
- 同时如果多台服务器使用时, 需要保证 seed_number 的值一致, 否则生成的唯一编码会重复

```php
use NiceYu\UniqueCode\UniqueCode;

$id = 123456789;
$code = UniqueCode::encode($id); // 生成
$decodedId = UniqueCode::decode($code); // 反转
```

## 函数使用

- 如果不想使用类, 可以直接使用函数, 需要注意参数

```php
$id = 12345;
$code = unique_encode($id); // 生成
$decodedId = unique_decode($code); // 反转
```

## 错误处理

- 如果编码生成失败（如超出最大值），`encode` 方法将返回 `null`，请确保在使用时进行检查。
- 在分布式系统中，请确保所有服务器使用相同的 `seed_number` 以避免编码冲突。


## 许可证

此项目使用 [MIT 许可证](LICENSE)。