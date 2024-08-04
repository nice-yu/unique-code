# Unique Code Library

[简体中文](README.zh-CN.md) | [English](README.md)

## Unit Tests

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

## Default 6 Digits

1. In general, 6 digits are sufficient for large-scale projects.
2. If needed, you can modify it to 7 digits for cases like order calculations.

| Digits | Maximum Value     | Calculation   |
|--------|-------------------|---------------|
| 5      | 24,300,000        | 30^5          |
| 6      | 729,000,000       | 30^6          |
| 7      | 21,870,000,000    | 30^7          |

## Installation

### System Requirements
- PHP >= 7.4

### Install Composer

If you haven't installed Composer yet, install it first:
```sh
curl -sS https://getcomposer.org/installer | php
mv composer.phar /usr/local/bin/composer
```

### Install Library

```sh
composer require nice-yu/unique-code
```

## Parameter Description

| Parameter Name | Default Value | Description                                                                                 |
|----------------|---------------|---------------------------------------------------------------------------------------------|
| seed_number    | MD5           | The seed number for shuffling the dictionary, default is to use system info hashed with MD5 |
| dictionaries   | 0-9, A-Z      | Dictionary info, default removed characters 0, O, 1, I, and Y, Z as separators              |
| complement     | Y-Z           | Separator characters                                                                        |
| max            | 6             | Number of digits to generate                                                                |

## Default Parameters

- You can set parameters in a fluent manner

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

## Get Parameters

```php
use NiceYu\UniqueCode\UniqueCode;

UniqueCode::getSeedNumber();
UniqueCode::getDictionaries();
UniqueCode::getComplement();
UniqueCode::getMax();
```

## Generate Unique Code

- Note the parameters set, if the maximum value is exceeded, it will return `null`
- If used across multiple servers, ensure the `seed_number` value is consistent to avoid duplicate unique codes

```php
use NiceYu\UniqueCode\UniqueCode;

$id = 123456789;
$code = UniqueCode::encode($id); // Generate
$decodedId = UniqueCode::decode($code); // Reverse
```

## Function Usage

- If you prefer not to use the class, you can use the functions directly, but mind the parameters

```php
$id = 12345;
$code = unique_encode($id); // Generate
$decodedId = unique_decode($code); // Reverse
```

## Error Handling

- If encoding fails (e.g., exceeding the maximum value), the `encode` method will return `null`. Ensure to check for this when using.
- In distributed systems, ensure all servers use the same `seed_number` to avoid encoding conflicts.

## License

This project is licensed under the [MIT License](LICENSE).