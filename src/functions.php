<?php
declare(strict_types=1);

use NiceYu\UniqueCode\UniqueCode;

if (!function_exists('unique_encode')) {
    function unique_encode(int $id): ?string
    {
        return UniqueCode::encode($id);
    }
}

if (!function_exists('unique_decode')) {
    function unique_decode(string $code): int
    {
        return UniqueCode::decode($code);
    }
}