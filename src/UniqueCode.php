<?php
declare(strict_types=1);

namespace NiceYu\UniqueCode;

class UniqueCode
{
    /**
     * Seed number
     * @var int
     */
    private static int $seedNumber = 0;

    /**
     * 26 letters + 10 digits - 4 confusing characters - 2 delimiter characters = 30 characters
     * 30 characters to the power of 6 = 729 million
     * Remove confusing characters (0 O 1 I)
     * Keep 2 delimiter characters (Y Z)
     * @var array|string[]
     */
    private static array $dictionaries = [
        '2', '3', '4', '5', '6', '7', '8', '9',
        'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H',
        'J', 'K', 'L', 'M', 'N', 'P', 'Q', 'R',
        'S', 'T', 'U', 'V', 'W', 'X'
    ];

    /**
     * Delimiter characters
     * @var array
     */
    private static array $complement = ['Y', 'Z'];

    /**
     * Length of the unique code
     * @var int
     */
    private static int $max = 6;

    /**
     * Shuffle dictionaries based on seed count
     * @return void
     */
    private static function shuffleWithSeed(): void
    {
        $seed = self::$seedNumber;
        if ($seed === 0) {
            $hash = md5(php_uname() . PHP_BINDIR . PHP_SAPI . PHP_BINARY . PHP_VERSION);
            $seed = hexdec(substr($hash, 0, 8));
        }

        /** Initialize seed number */
        mt_srand($seed);

        $dictionariesCount = count(self::$dictionaries);
        for ($i = $dictionariesCount - 1; $i > 0; $i--) {
            $j = mt_rand(0, $i);

            /** Swap */
            [self::$dictionaries[$i], self::$dictionaries[$j]] = [self::$dictionaries[$j], self::$dictionaries[$i]];
        }

        /** Reset to default random seed */
        mt_srand();
    }

    /**
     * Generate a unique code
     * @param int $id
     * @return string|null
     */
    public static function encode(int $id): ?string
    {
        /** Shuffle the dictionaries based on seed */
        self::shuffleWithSeed();

        $code = '';
        $char = self::$dictionaries;
        $charCount = count($char);

        /** Exceeding the maximum number that can be generated */
        if ($id >= pow($charCount, self::$max)) {
            return null;
        }

        while ($id > 0) {
            $index = $id % $charCount;
            $code .= $char[$index];
            $id = intdiv($id, $charCount);
        }

        $codeLength = strlen($code);
        if ($codeLength < self::$max) {
            /** Fill delimiter character */
            $complementCount = count(self::$complement);
            $index = mt_rand(0, $complementCount - 1);
            $code .= self::$complement[$index];

            /** Fill in random letters */
            for ($i = 0; $i < self::$max - ($codeLength + 1); $i++) {
                $index = mt_rand(0, $charCount - 1);
                $code .= $char[$index];
            }
        }
        return $code;
    }

    /**
     * Decode the unique code
     * @param string $code
     * @return int
     */
    public static function decode(string $code): int
    {
        $charMap = array_flip(self::$dictionaries);

        $codeLength = strlen($code);
        $complementCount = count(self::$complement);

        for ($i = 0; $i < $complementCount; $i++) {
            $item = strpos($code, self::$complement[$i]);
            if ($item !== false) {
                $codeLength = $item;
                break;
            }
        }

        $charCount = count($charMap);
        $encoded = 0;
        for ($i = 0; $i < $codeLength; $i++) {
            $index = $charMap[$code[$i]];
            $encoded += pow($charCount, $i) * $index;
        }

        return $encoded;
    }

    public static function getSeedNumber(): int
    {
        return self::$seedNumber;
    }

    public static function getDictionaries(): array
    {
        return self::$dictionaries;
    }

    public static function getComplement(): array
    {
        return self::$complement;
    }

    public static function getMax(): int
    {
        return self::$max;
    }

    public static function setSeedNumber(int $seedNumber): self
    {
        self::$seedNumber = $seedNumber;
        return new static();
    }

    public static function setDictionaries(array $dictionaries): self
    {
        self::$dictionaries = $dictionaries;
        return new static();
    }

    public static function setComplement(array $complement): self
    {
        self::$complement = $complement;
        return new static();
    }

    public static function setMax(int $max): self
    {
        self::$max = $max;
        return new static();
    }
}
